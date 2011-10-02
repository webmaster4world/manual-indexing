/***********************************************************************
 *
 * Copyright (C) 2011 Peter Hohl <pehohlva@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 ***********************************************************************/



#include "richtext.h"
/* if only text or xml */
#include "XML_Editor.h"
/* remote item if need */
#include "getitem.h"
#include <QTextEdit>
#include <QtCore>
#include <QDebug>
#include <QObject>

#include <QMessageBox>


/* rtf read document */
#include "rtf_reader.h"


DocPage::DocPage() {
	
	sizebyte = 0;
    Title= QString("NULL_DOCUMENT");
    mimetipe = _NULL_;
    filefull= _NULL_; 
    doc = _NULL_;
}

DocPage& DocPage::operator=( const DocPage& d ) 
{
	sizebyte = d.sizebyte;
    Title = d.Title;
    mimetipe = d.mimetipe;
    filefull = d.filefull; 
    doc = d.doc;
    ///return this;
}


using namespace std;



void DocLoader::Setting( QObject *parent , DocPage x  )
{
	receiver = parent;
	Udoc= x;
	setTerminationEnabled(true);
}

void DocLoader::run()
{
	QFileInfo fi(Udoc.filefull);
	QString inside = QString("Unable to read file %1").arg(fi.absoluteFilePath());
	const QString FNAME = fi.fileName().toLower();
	
	QFile file(fi.absoluteFilePath()); 
	cout << "fi.absoluteFilePath()->" <<  qPrintable(fi.absoluteFilePath()) <<  endl;
        if (!file.open(QIODevice::ReadOnly | QIODevice::Text)) {
			emit setText(inside);
	        exit();
	        
		}
    QString allchunk = _NULL_;
    int i=0;
    while (!file.atEnd()) {
        allchunk.append(file.readLine());
        ////i++;
        ////QString send = QString("Read line-> %1").arg(i);
        /////cout << "Job->" <<  qPrintable(send) <<  endl;
    }
    file.close();
    if ( FNAME.endsWith(".html",Qt::CaseInsensitive) ||
         FNAME.endsWith(".htm",Qt::CaseInsensitive) ) {
		emit setText(allchunk);
	} else {
		emit setText(allchunk);
	}
	exit();
}





QPointer<RichTextIstance> RichTextIstance::_self = 0L;


RichTextIstance* RichTextIstance::self()
{
	if ( !_self )
	_self = new RichTextIstance(186458);
	return _self;
}

/* file to open  and place to send result */
void RichTextIstance::load( const QString fi , QObject *sender ) {
	
	///////Q_UNUSED(sender);
	QTextEdit *textedit = qobject_cast<QTextEdit *>(sender);
	textedit->setAutoFormatting(QTextEdit::AutoNone);
	
	QTextDocument *document = textedit->document();
	textedit->blockSignals(true);
	document->blockSignals(true);
	/* set a new */
	////QDomDocument *doc = new QDomDocument(QString("Laoding text...."));
	////textedit->setDocument(doc); /* free Highlighter */
	cout << "Init RichTextIstance start ok.. load ->" <<  qPrintable(fi) <<  endl;
	QFileInfo info(fi);
	////bool takefile = true;
	RICHMIME mime = Rmime(info.absoluteFilePath());
    QString ext = info.suffix().toLower();
            ext.prepend(QString("."));
    cout << "suffix ->" <<  qPrintable(ext) <<  endl;
    cout << "associate mime ->" <<  mime <<  endl;
    
    /* typedef enum
{  
  Rhtml = 100,
  Rrtf = 200,
  Rxml = 300,
  Rxslfo = 400,
  Rtext = 500,
  Roasi = 600,
  Runknow = 1000
} RICHMIME; */

	DocPage current;
    current.filefull = info.absoluteFilePath();
    current.mimetipe = ext;
    current.sizebyte  = info.size();
    
    if (mime == Rrtf ) {
		    cout << "Swap RTF" <<  endl;
		    textedit->setAcceptRichText ( true );
           /* only rtf document !!! */
	             QFile file(info.absoluteFilePath());
				if (file.open(QIODevice::ReadOnly)) {
					RTF::Reader reader;
					reader.read(&file, document);
					file.close();
					if (reader.hasError()) {
						QMessageBox::warning(textedit, tr("Sorry"), reader.errorString());
					}
					
				}
    
     } else if ( mime == Rtext ) {
		           cout << "Swap Plain text" <<  endl;
		           textedit->setAcceptRichText ( false );
		           textedit->setLineWrapMode ( QTextEdit::NoWrap );
					 /* swap edit modus */
				   XmlHighlighter *highlight = new XmlHighlighter(textedit->document());
				   Q_UNUSED(highlight);
				   /////connect(textedit->verticalScrollBar(), SIGNAL(valueChanged(int)),textedit, SLOT(update()));
                   /////connect(textedit, SIGNAL(textChanged()),textedit SLOT(update()));
                   /* swap edit modus */
				   /* start a new QThread to not frozen gui only text QTextDocument not like QThread */
				   DocLoader *initdoc = new DocLoader();
				   initdoc->Setting(sender,current);
				   connect(initdoc, SIGNAL(setText(QString)),textedit, SLOT(setText(QString)));
				   ////////connect(initdoc, SIGNAL(setHtml(QString)),textedit, SLOT(setHtml(QString)));
				   initdoc->start(QThread::LowPriority);
	   } else if ( mime == Rxml ) {
					 /* swap edit modus */
				   textedit->setAcceptRichText ( false );
				   textedit->setLineWrapMode ( QTextEdit::NoWrap );
					 cout << "Swap XML" <<  endl;
				   XmlHighlighter *highlight = new XmlHighlighter(textedit->document());
				   ////connect(textedit->verticalScrollBar(), SIGNAL(valueChanged(int)),textedit, SLOT(update()));
                   ////connect(textedit, SIGNAL(textChanged()),textedit SLOT(update()));
                   /* swap edit modus */
				   /* start a new QThread to not frozen gui only text QTextDocument not like QThread */
				   DocLoader *initdoc = new DocLoader();
				   initdoc->Setting(sender,current);
				   connect(initdoc, SIGNAL(setText(QString)),textedit, SLOT(setText(QString)));
				   ////////connect(initdoc, SIGNAL(setHtml(QString)),textedit, SLOT(setHtml(QString)));
				   initdoc->start(QThread::LowPriority);
				   Q_UNUSED(highlight);
	   }  else if ( mime == Roasi ) {
					 /* swap edit modus */
				 
				   cout << "Swap oasi" <<  endl;
				   ////DocLoader *initdoc = new DocLoader();
				   /////initdoc->Setting(sender,current);
				   /////connect(initdoc, SIGNAL(setText(QString)),textedit, SLOT(setText(QString)));
				   ////////connect(initdoc, SIGNAL(setHtml(QString)),textedit, SLOT(setHtml(QString)));
				   /////initdoc->start(QThread::LowPriority);
				   ////////Q_UNUSED(highlight);
	   }
		   
    
    
    /* free all connection */
    document->setUndoRedoEnabled(false);
	document->setModified(true);
	document->blockSignals(false);
	textedit->blockSignals(false);
	
}


RichTextIstance::RichTextIstance( int mi )
	: cicle(mi)
{
	
	Load_Connector();
}


void RichTextIstance::Load_Connector()
{
   /* dummy template function cp paste  */

}



