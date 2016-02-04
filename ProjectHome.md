## Read your html file to use search term ##

Help Developer to offline work and find all reference on pdf or html file, use Qt 4.7.4 and Webkit to consult manual.


[![](http://manual-indexing.googlecode.com/svn/trunk/html/screen_1.png)](http://manual-indexing.googlecode.com/svn/trunk/)

_To read pdf file the app take pdftotext, if you having other simple way.. please mail me..
pdftotext is very fast to read long file._


```

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


```

_Apps on indexing local file:_

[![](http://manual-indexing.googlecode.com/svn/trunk/html/screen_2.png)](http://manual-indexing.googlecode.com/svn/trunk/Manual_Finder.01/)

## Other source inside ##
Excel reader & writer lib , Oasi format reader Sqlite3 sample, ODBC export tools, QT xml panel....
[SVN source link](http://manual-indexing.googlecode.com/svn/trunk/)
