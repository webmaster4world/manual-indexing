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



#ifndef RICHTEXT_H
#define RICHTEXT_H

#include <stdio.h>
#include <iostream>
#include <QtCore>
#include <QDebug>
#include <QObject>
#include <QFileInfo>

/* remote item if need */
#include "getitem.h"
/* oasi and xsl-fo doc */
#include "XSL-FO_colorname.h"

#define MAGICNUMMERDATA 0x48611928
#define MMVERSION 2
#define _NULL_ \
             QString("")  
             
#define _RTF_ \
             QString(".rtf")      
             
using namespace std;
             
typedef enum
{  
  Rhtml = 100,
  Rrtf = 200,
  Rxml = 300,
  Rxslfo = 400,
  Rtext = 500,
  Roasi = 600,
  Runknow = 1000
} RICHMIME;


static inline RICHMIME Rmime( const QString filec )
{
    QFileInfo info(filec);
    RICHMIME x = Runknow; /* default  load as text */
    const QString ext = info.suffix().toLower();
    QString FNAME = info.suffix().toLower();
            FNAME.prepend(QString("."));
   ////cout << "1 RICHMIME RICHMIME RICHMIME ->" <<  qPrintable(FNAME) <<  endl;
    //////cout << "2 RICHMIME RICHMIME RICHMIME ->" <<  qPrintable(filec) <<  endl;  
            
      if ( FNAME.contains(".txt",Qt::CaseSensitive) ||
              FNAME.contains(".h",Qt::CaseSensitive) ||
              FNAME.contains(".cc",Qt::CaseSensitive) ||
              FNAME.contains(".cpp",Qt::CaseSensitive) ||
              FNAME.contains(".ch",Qt::CaseSensitive) ) {
		x = Rtext;  /* associate to plain text */
	} else if (FNAME.contains(".html",Qt::CaseSensitive)  ||
	           FNAME.contains(".htm",Qt::CaseSensitive) || 
	           FNAME.contains(".phtml",Qt::CaseSensitive) )  {
		x = Rhtml;   /* associate to html */
	}  else if ( ext ==  "rtf" ||  ext ==  "rtfo" )  {
		x = Rrtf;   /* associate to rtf other extension from mac!!!!  */
	}  else if (FNAME.contains(".odt",Qt::CaseSensitive) || 
	            FNAME.contains(".ott",Qt::CaseSensitive) || 
	           FNAME.contains(".sxw",Qt::CaseSensitive) )  {
		x = Roasi;   /* associate to  openoffice standard  */
	}  else if (FNAME.contains(".fo",Qt::CaseSensitive) || 
	           FNAME.contains(".fop",Qt::CaseSensitive) )  {
		x = Rxslfo;   /* associate to  openoffice standard  */
	}  else if (FNAME.contains(".xml",Qt::CaseSensitive) )  {
		x = Rxml;   /* associate to  openoffice standard  */
	}
    return x;
}



class DocPage
{
public:
  DocPage();
  DocPage& operator=( const DocPage& d );
  operator QVariant() const
  {
    return QVariant::fromValue(*this);
  }
          qint64 sizebyte;
          QString Title;
		  QString mimetipe;
		  QString filefull; 
		  QString doc;
};

/*
 * DocPage current;
 *         current.filefull = 
 * 
 * */

Q_DECLARE_METATYPE(DocPage);


inline QDebug operator<<(QDebug debug, DocPage& udoc)
{
    debug.nospace() << "DocPage(Enable."
    << udoc.Title << ",Title(),"
    << udoc.filefull << ",Path(),"
    << udoc.mimetipe << ",mimetipe(),"
    << udoc.sizebyte << ",sizebyte() )";
    return debug.space();
}


inline QDataStream& operator<<(QDataStream& out, const DocPage& udoc)
{
    out << udoc.Title;
    out << udoc.filefull;
    out << udoc.mimetipe;
    out << udoc.sizebyte;
    out << udoc.doc;
    return out;
}


inline QDataStream& operator>>(QDataStream& in, DocPage& udoc)
{
    in >> udoc.Title;
    in >> udoc.filefull;
    in >> udoc.mimetipe;
    in >> udoc.sizebyte;
    in >> udoc.doc;
    return in;
}

/////qRegisterMetaTypeStreamOperators<DocPage>("DocPage");



class DocLoader : public QThread
{
    Q_OBJECT
     
public:
  void Setting( QObject *parent , DocPage x ); 
protected:
  void run();
  signals:
    void setText(QString);
    void setHtml(QString);
private:
    DocPage Udoc;
    QObject* receiver;
};








/* RichTextIstance::self()->load( const QString file ); */

/* static class to use from all to trasform doc */

class RichTextIstance : public QObject
{
     Q_OBJECT

public:	 
  static RichTextIstance* self();
  void load( const QString fi , QObject *sender ); /* file to load */

protected:	 
    TColor::XSL_FO_ColorName *Color;
 
private:
    RichTextIstance(int mi);
    int cicle;
    QStringList docentry;
   static QPointer<RichTextIstance> _self;
   void Load_Connector();
   
signals:

public slots:

private slots:

};




//
#endif // RICHTEXT_H

