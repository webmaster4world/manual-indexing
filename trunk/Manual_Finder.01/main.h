#ifndef MAINSETTING_H
#define MAINSETTING_H

#include <QFile>
#include <QtCore> 
#include <iostream>
#include <sstream>
#include <string>
#include <QDataStream>
#include <QMessageBox>
#include <QSqlDatabase>
#include <QSqlError>
#include <QSqlQuery>
#include <QSqlQueryModel>
#include <QSqlRecord>
#include <QNetworkInterface>

#define _PROGRAM_NAME_ "Indexing Manual pdf & html page  by  Peter Hohl"
#define _PROGRAM_NAME_DOMAINE_ "qmake.net"
#define _ORGANIZATION_NAME_ "Vivi e lascia vivere"


#include "index_dir_doc.h"
#define APPNAME "rundir"
#define SNORFMAX 10000
#define NULLO "error"
#define _INDEXCACHE_ \
             QString("%1/.rundir_CACHE").arg(QDir::homePath())    /* cache to write tmp data and sql file */
#define DBfileSQLITE  \
             QString("%1/database.db").arg(_INDEXCACHE_) 
#define GREPDIR  \
             QString("%1/doc").arg(QDir::homePath()) 
#define _PDFCACHE_ \
             QString("%1/tmp").arg(_INDEXCACHE_)
             
#define _DEAFAULT_TITLE_ \
             QString("NO TITLE FOUND")

#define _NULL_ \
             QString("")          
             
using namespace std;

typedef QMap<int, QStringList> TableUri;



/* if external network is up? */
inline bool NetworkEnable() {

    bool validip = false;
    QList<QHostAddress> addrlist = QNetworkInterface::allAddresses();
    int o = -1;
    foreach(QHostAddress addr, addrlist){
    o++;
    QNetworkInterface hop = QNetworkInterface::interfaceFromIndex(o);
    QString mach = hop.hardwareAddress(); 
    QString name = hop.name();
    QString oneip = addr.toString();
    //////cout << APPNAME << " network ips-> " <<   qPrintable(oneip)  << "......." << endl;
    /////cout << APPNAME << " network name-> " <<   qPrintable(name)  << "......." << endl;
    
        if (oneip.contains("192.")) {
          return true;
        }
        if (oneip.contains("10.")) {
          return true;
        }
        if (name.contains("wlan")) {
          return true;
        }
    }
return validip;
}


inline QString NetworkItem() {
	
	QStringList msg;
	            msg.append(QString("<h2>NetworkInterface now.</h2>"));
	QList<QHostAddress> addrlist = QNetworkInterface::allAddresses();
    int o = -1;
    foreach(QHostAddress addr, addrlist){
    o++;
    QNetworkInterface hop = QNetworkInterface::interfaceFromIndex(o);
    QString mach = hop.hardwareAddress(); 
    QString name = hop.name();
    QString oneip = addr.toString();
    QString line = QString("<p>Name: %2 <br> Mac: %1  <br>  IP:%3</p>").arg(mach,name,oneip);
       msg.append(line);
    }
	return msg.join(QString("\n"));
}






inline bool hardcopy(const QString filesource , const QString filedest )
{
	QFile dest(filedest); 
	QFile suor(filesource); 
	        if (dest.exists()) {
				dest.remove();
			}
	        if (suor.exists()) {
				return suor.copy(filedest);
			}
	return false;
}

inline QString execResult(const QString cmd)
{
     QString inside = "";
     QProcess p;
     p.setReadChannelMode(QProcess::MergedChannels);
     p.start(cmd,QIODevice::ReadOnly);
            if (!p.waitForFinished(1000)) {
				return inside;
			} else {
				QByteArray xx = p.readAllStandardOutput();
				inside = QString::fromUtf8(xx.data());
				return inside.trimmed();
			}
   
    return inside.trimmed();
}


extern inline QString squote( QString t )
{
 QString text = t;
 text.replace("\t", " ");
 text.replace("\n", " ");
 text.replace("\r", " ");
 text.replace('"', "`");
 text.replace("'", "`");
 return text.trimmed();
} 

inline QString catTextFromHtml( QString body )
{
	body.replace("&nbsp;"," ");
	body.replace("<br>"," ##l45##");
    body.replace("</br>"," ##l45##");
    body.replace("</p>"," ##l45##");
    body.replace("</td>"," ##l45##");
	body.remove(QRegExp("<[^>]*>"));
	return body.trimmed();
}


inline QString catTextFromHtml_old( QString body )
{
    body.replace("&nbsp;"," ");
    body.replace(QRegExp("<script(.)[^>]",Qt::CaseInsensitive), "?###cdatastart###? script-tag-attribute ");
    body.replace(QRegExp("<style(.)[^>]",Qt::CaseInsensitive), "?###cdatastart###? style-tag-attribute ");
    body.replace("//<![CDATA[","?###cdatastart###? cdata ");
    body.replace("//]]>","?###cdataend###?");
    body.replace("<!--","?###cdatastart###? comment ");
    body.replace("<noscript>","?###cdatastart###? nosript-tag ");
    body.replace("</noscript>","?###cdataend###?");
    body.replace("<NOSCRIPT>","?###cdatastart###? nosript-tag");
    body.replace("</NOSCRIPT>","?###cdataend###?");
    body.replace("<SCRIPT>","?###cdatastart###? script-tag");
    body.replace("</SCRIPT>","?###cdataend###?");
    body.replace("<script>","?###cdatastart###? script-tag");
    body.replace("</script>","?###cdataend###?");
    body.replace("<style>","?###cdatastart###? style-tag");
    body.replace("</style>","?###cdataend###?");
    body.replace("<STYLE>","?###cdatastart###? style-tag");
    body.replace("</STYLE>","?###cdataend###?");
    body.replace("-->","?###cdataend###?");
    body.replace("<!---","?###cdatastart###? comment ");
    body.replace("--->","?###cdataend###?");
    /* body.replace(QRegExp("<p[^>]*>([^<]*)</p>",Qt::CaseInsensitive), "\\1");*/ 
    body.replace(QRegExp("<li[^>]*>([^<]*)</li>",Qt::CaseInsensitive), "\\1 ##l45##");
    body.replace(QRegExp("<li[^>]*>([^<]*)",Qt::CaseInsensitive), "\\1 ##l45##");
    body.replace("<br>"," ##l45##");
    body.replace("</br>"," ##l45##");
    body.replace("</p>"," ##l45##");
    body.replace("</td>"," ##l45##");
    body.remove(QRegExp("<head>(.*)</head>",Qt::CaseInsensitive));
    body.remove(QRegExp("?###cdatastart###?(.)[^?]*?###cdataend###?",Qt::CaseInsensitive));
    body.remove(QRegExp("<script(.)[^>]*</script>",Qt::CaseInsensitive));
    body.remove(QRegExp("?###cdatastart###?(.)[^?]*?###cdataend###?",Qt::CaseInsensitive));
    body.remove(QRegExp("<form(.)[^>]*</form>",Qt::CaseInsensitive));
    body.remove(QRegExp("<FORM(.)[^>]*</FORM>",Qt::CaseInsensitive));
    body.remove(QRegExp("<script(.)[^>]*</script>",Qt::CaseInsensitive));
    body.remove(QRegExp("<SCRIPT(.)[^>]*</SCRIPT>",Qt::CaseInsensitive));
    body.remove(QRegExp("<style(.)[^>]*</style>",Qt::CaseInsensitive));
    body.remove(QRegExp("<STYLE(.)[^>]*</STYLE>",Qt::CaseInsensitive));
    body.remove(QRegExp("<(.)[^>]*>"));
    
 return body.trimmed();
}

inline QString GrepTitle(const QString htmlchunk ) {
	
	QString titler = _DEAFAULT_TITLE_;
	QRegExp expression( "title>(.*)</title", Qt::CaseInsensitive );
    expression.setMinimal(true);
    int iPosition = 0;
    int canna = 0;
    while( (iPosition = expression.indexIn( htmlchunk , iPosition )) != -1 ) {
        QString semi1 = expression.cap( 1 );
        titler = semi1.trimmed();
        canna++;
        iPosition += expression.matchedLength();
    }
	return titler;
}



/* read the contenet of a local file as QByteArray*/
inline QString StreamFromFile(const QString fullFileName)
{
     QString inside = "";
    QFile file(fullFileName); 
    if (file.exists()) {
                if (file.open(QFile::ReadOnly | QFile::Text)) {
                    inside = QString::fromUtf8(file.readAll());
                  file.close();
                }
    }
    return inside;
}




inline QString  BiteorMega( qint64 peso  )
{
    QString humanread;
    qreal  faktor = 1024.00;

    qreal bytesizefile = peso / faktor;
    int kilo = 0;
    
    if (bytesizefile > 0) {
        if (bytesizefile < 1) {
           kilo = 1; 
        } else {
           kilo = bytesizefile;
        }
    }
    
   if (kilo < 1025) {
   humanread = QString("Kb.%1").arg(kilo);
   return humanread;
   }
   
   qreal megad = bytesizefile / faktor;
   
   if (megad < 1025) {
   humanread = QString("MB.%1").arg(megad, 0, 'f', 2);
   return humanread;
   } else {
            qreal gigab = megad / faktor;
            humanread = QString("GB.%1").arg(gigab, 0, 'f', 2);
       
       
   }
   
return humanread;
}



/* extract html readable code to parse */
inline QString gethtmlFrompdf(const QString fullFileName)
{
     QString inside = "";
          QDir pdfdir = QDir();
		   if (!pdfdir.mkpath (_PDFCACHE_)) {
			 cout << "Unable to create dir pdf ->" << qPrintable(_PDFCACHE_) << endl;
			return inside;   
		   }
		   
		   /* is a pdf file? */
        QFileInfo fi(fullFileName);
        if (fi.isFile() && fi.isReadable() ) {
			cout << "parse pdf file ->" << qPrintable(fi.fileName()) << endl;
		} else {
			return inside;
		}
     
        QString pdfconverer,ppwd,cmread;
		#if defined Q_WS_WIN
		pdfconverer = execResult("which pdftotext");
		#endif
		#if defined Q_WS_X11
		pdfconverer = execResult("which pdftotext");
		ppwd = execResult("pwd");
		#endif
		#if defined Q_WS_MAC
		pdfconverer = execResult("which pdftotext");
		#endif
		cmread = QString("%1 -htmlmeta -enc UTF-8 aa.pdf aa.html").arg(pdfconverer);
     if  (!pdfconverer.isEmpty()) {
		 /* comand exist */
		   QString htmlF = QString("%1/aa.html").arg(_PDFCACHE_);
	       QString pdfsing = QString("%1/aa.pdf").arg(_PDFCACHE_);
	       if (hardcopy(fullFileName,pdfsing)) {
			            QProcess process;
						process.setReadChannelMode(QProcess::MergedChannels);
						process.setWorkingDirectory(_PDFCACHE_);
						process.start(cmread);
						if (!process.waitForFinished(2500)) {
							/* some error */
						return inside;
						} else {
							/* ok remove all tmp file and clean */
							QString htmlchunk = StreamFromFile(htmlF);
							/* clean dir */
							       QFile suor(htmlF); 
									if (suor.exists()) {
										suor.remove();
									}
									QFile suora(pdfsing); 
									if (suora.exists()) {
										suora.remove();
									}
							 return htmlchunk;
						}
			   
		   }

	 }   
     
     return inside;
}




//
#endif // MAINSETTING_H

