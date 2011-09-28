#include "handleonefileaction.h"
//
/*  Save file as handleonefileaction.cpp  */
/*  incomming class name HandleOneFileAction */
//


using namespace std;


void LoadPageExtend::Setting( const QString file_full_path , QSqlDatabase db , int id )
{
	currentID = id;
	dbconnection = db;
	fi = QFileInfo(file_full_path);
	setTerminationEnabled(true);
}

void LoadPageExtend::run()
{
	////qDebug() << "#### start id   " << currentID;
	QString stream,titlerx,mime,sqlinsert;
	bool insertdb = true;
	int fsize;
	//////int dbid = currentID + 1000;
	
	 if (fi.isFile() && fi.isReadable() ) {
		  const QString ext = fi.suffix().toLower();
		  fsize = fi.size();
		  emit dir_indexing(fi.absolutePath());
		  /* search file name if exist and same size */
		  const QString tsize = QString("%1").arg(fsize);
		  QString sqlsearch = QString("SELECT name FROM fileindex where fullpath='%1' and size='%2' limit 1 ").arg(fi.absoluteFilePath(),tsize);
		  QSqlQuery query(dbconnection);
		        if (query.exec(sqlsearch)) {
					    if (query.first()) {
							QString FILENAMER = query.value(0).toString();
							//////cout << "file exist (not register) name ->" << qPrintable(FILENAMER) << endl;
							insertdb = false;
						}  
		         }
		  
		  if (insertdb) {
		  
						  if (ext.contains("html") || ext.contains("htm")) {
									mime = QString("html");
									stream = StreamFromFile(fi.absoluteFilePath());
									if (!stream.isEmpty()) {
										 ////QTextDocument *docc = new QTextDocument(); 
										 /* QTextDocument is not fast to extract text from html file !!! */
									   titlerx = GrepTitle(stream.trimmed());
									   stream = catTextFromHtml(stream.trimmed());
									   if ( titlerx == _DEAFAULT_TITLE_) {
										   insertdb = false;
									   }
									   
									   stream.prepend(QString(" %1 /").arg(titlerx));  /* space to query */
									   stream.prepend(QString(" %1 /").arg(fi.fileName()) );
									}
									QStringList X;  ///  "fullpath varchar(220), name varchar(220) , title varchar(240), txtgrep TEXT , size int )");
												X.clear();
												X.append(QString("(SELECT max(id) FROM fileindex)+1"));
												X.append(QString("'%1'").arg(squote(fi.absoluteFilePath())));
												X.append(QString("'%1'").arg(squote(fi.fileName())));
												X.append(QString("'%1'").arg(squote(titlerx)));
												X.append(QString("'%1'").arg(squote(stream)));
												X.append(QString("'%1'").arg(fsize));
									
									sqlinsert = QString("INSERT INTO fileindex VALUES (%1);").arg(X.join(","));
									
						  }  else if (ext.contains("pdf")) {
							  /////////  pdftotext -htmlmeta -enc UTF-8 aa.pdf sake.html
							  stream = gethtmlFrompdf(fi.absoluteFilePath());
							  titlerx = QString("PDF: %1").arg(GrepTitle(stream.trimmed()));
							  
							  stream = catTextFromHtml(stream.trimmed());
							  if ( stream == _NULL_) {
								  insertdb = false;
							  }
							  
							  
								  QString rec = QString("%1 - %2").arg(fi.fileName()).arg( stream.left(15) );
								  titlerx.append(rec);
							  
							  
							  
							  stream.prepend(QString(" %1 /").arg(titlerx));
							  stream.prepend(QString(" %1 /").arg(fi.fileName()) );
												QStringList X;
												X.clear();
												X.append(QString("(SELECT max(id) FROM fileindex)+1"));
												X.append(QString("'%1'").arg(squote(fi.absoluteFilePath())));
												X.append(QString("'%1'").arg(squote(fi.fileName())));
												X.append(QString("'%1'").arg(squote(titlerx)));
												X.append(QString("'%1'").arg(squote(stream)));
												X.append(QString("'%1'").arg(fsize));
									
									sqlinsert = QString("INSERT INTO fileindex VALUES (%1);").arg(X.join(","));
							  
						  }
		  
		  
			  /* item insert on db */
			 if  (!sqlinsert.isEmpty()) {
				   if (insertdb) {
					   QSqlQuery insertquery(dbconnection);
					   if (insertquery.exec(sqlinsert)) {
					   /////qDebug() << "#### executedQuery ->   " << insertquery.executedQuery();
					   } else {
						   emit error_indexing(QString("SQL insert error!"));
						   qDebug() << "#### fatal error ->   " << insertquery.executedQuery();
					   }
			       }
				 
			 }
			  
		  }
		  
	  }
    
    
    //////qDebug() << "#### fi.absolutePath()  " << fi.absolutePath();
    /////qDebug() << "#### fi.absoluteFilePath()->   " << fi.absoluteFilePath();
    //////qDebug() << "#### fi body ->   " << stream;
    //////cout << currentID << ") Read end from file ->" << qPrintable(fi.fileName()) << endl;
    emit ready(currentID);
    exit();
}



