#include "_tools.h"

QString traduce( QString t )
{
  return t;
}


QString XML_utf8( QString t )
{
  //the following checks are necessary before exporting
  //strings to XML. see http://hdf.ncsa.uiuc.edu/HDF5/XML/xml_escape_chars.html for details
    /*
  QByteArray ba = t.toUtf8();
  QString text = ba.data();
    */
    /*  __cpu_register__  to find and count on grep */
  QString text = t;
  text.replace("&", "&amp;");   /*  qt4 toUtf8 dont replace && */
  text.replace("\"","&quot;");
  text.replace("'", "&apos;");
  text.replace("<", "&lt;");
  text.replace(">", "&gt;");
  text.replace("\n", "&#10;");
  text.replace("\r", "&#13;");
  return text;
}

bool is_numeric(QString incomming)
{
    incomming.replace(QString(" "), QString("")); /* trimm */
    QString str2 = incomming;
    bool ok; 
    str2.toFloat(&ok); 
return ok;
}

int get_nummer(QString incomming)
{   int nummer = 0;
    incomming.replace(QString(" "), QString("")); /* trimm */
    QString str2 = incomming;
    bool ok; 
    str2.toFloat(&ok); 
    if (ok) {
    return str2.toInt();
    }
return nummer; 
}


QString  UnixTime2UserTime(int utimenr)
{
//#include <clocale>
//#include <ctime>
    
         /* time_t t = time( 0 );  */
         /* QString locale_user = QLocale::system().name(); */
         setlocale( LC_TIME, "" );
         size_t const buffer_size = 1024;
         char buffer[ buffer_size ];
         char const* format = "%A %d %B %Y %H:%M";
         time_t t = utimenr;  
         strftime( buffer, buffer_size,
                 format, localtime( &t ) );
         QString hack = QString( "%1" ).arg( buffer );
         return hack;
}


/*  user input date checks */
int  getUnixTime(int d , int m , int j)   /* day / mont / year */
{ 
    if (!d > 0 && !d < 32) {
     d =1;
    }
    if (!m > 0 && !m < 13) {
     m =1;
    }  
     if (!j > 1969 && !j < 2016) {  /* grep UPDATE_APPS */
     j =1970;
    }    
    QDateTime date_unix;
    QDate date(j,m,d);
    date_unix.setDate(date);
    return date_unix.toTime_t(); 
}




QString qt2latin1( QString item )
{
   QByteArray ba = item.toLatin1();
    QString hack = ba.data();
    return hack; 
}

const char* qtchar( QString xml )
{
    QByteArray ba = xml.toAscii();
    const char* hack = ba.data();
    return hack;
}

char* qtchars( QString xml )
{
    QByteArray ba = xml.toAscii();
    char* hack = ba.data();
    return hack;
}

QString chars2qt( char* xml )
{
    return QString( "%1" ).arg( xml );
}


QString UsersLocalCodec( QString xml )
{
    QByteArray ba = xml.toAscii();
    /* QTextCodec *codec = QTextCodec::codecForName("ISO 8859-1"); */
    return ba.data();
}



bool file_put_contents_char( char *filename , char *xml )
{
ofstream out(filename);
  if(!out) {
    return false;
   }
  out << xml;
  out.close();
  return true; 
} 

char* file_get_contents_char(char *filename)
{
    long length;
    int count;
    char *buf;
    FILE *f = fopen(filename, "rt");
    if (!f) {
        buf = "";
        return buf;
    }
    length = 0xffff;
    buf = (char*) malloc(length + 1);
    count = fread(buf, 1, length + 1, f);
    fclose(f);
    if (count > length) {
         buf = "file too large (64k limit)";
        return buf;   
    }
    buf[count] = 0;
    return buf;
}



char* int2char( int i_int )
{
    char *buffer = new char[32];
    sprintf(buffer, "%d", i_int);
    return buffer;
}

char* double2char( double i_int )
{
    char *buffer = new char[32];
    sprintf(buffer, "%f", i_int );
    return buffer;
}

QString qt_unixtime(QString line)
{
    return QString( "%1 <=logline=> %2" ).arg( int2char(  (int)time( NULL ) ) , line  );
} 

char* append( char *one , char *dwo )
{
    char *buffer = new char[255];
    sprintf (buffer, "%s%s", one , dwo);
    return buffer;
}


bool file_put_contents_append(QString fullFileName,QString xml)
{
    QString data = xml+"\n";
    QFile f( fullFileName );
	if ( f.open( QFile::Append | QFile::Text ) )
	{
		QTextStream sw( &f );
		sw << data;
		f.close();
		return true;
	}
	return false;
    
}


bool is_file(QString fullFileName)
{
    QFile f( fullFileName );
	if ( f.exists() ) {
    return true;
	} else {
	return false;
    }
}

bool qt_unlink(QString fullFileName)
{
    QFile f( fullFileName );
	if ( is_file( fullFileName ) ) {
       if (f.remove()) {
        return true;
       }
	}
return false;
}

bool file_put_contents(QString fullFileName,QString xml)
{
    QString data = xml;
    QFile f( fullFileName );
	if ( f.open( QFile::WriteOnly | QFile::Text ) )
	{
		QTextStream sw( &f );
		sw << data;
		f.close();
		return true;
	}
	return false;
    
}

QString file_get_line(QString fullFileName,int linenr)
{
    QString inside ="";
    QFile file(fullFileName);
    int countnr = 0;
    if (linenr > 0) {
    if (!file.open(QFile::ReadOnly | QFile::Text)) {
    return inside;
    }
    
    QTextStream in(&file);
    
        while (!in.atEnd()) {   ////// eben nicht am ende
         ++countnr;
            if (countnr == linenr) {
                inside = in.readLine(0);
                if (inside.size() > 0) {
                return inside;  
                }
             break;
            }
        }
    file.close();
    }
return inside;
} 





double getExactTime()
{
    double ret;
#if defined (WIN32)
    struct _timeb theTime;
    _ftime(&theTime);
    ret = theTime.time + theTime.millitm/1000.0;
#elif defined (HAVE_FTIME)
    struct timeb theTime;
    ftime(&theTime);
    ret = theTime.time + theTime.millitm/1000.0;
#elif defined (HAVE_GETTIMEOFDAY)
    timeval theTime;
    gettimeofday(&theTime, NULL);
    ret = theTime.tv_sec + theTime.tv_usec/1000000.0;
#else
    ret =time( NULL );
#endif
    return ret;
}


QString file_get_clean_line(QString fullFileName)
{
    QString inside ="";
    QString alls ="";
    QFile file(fullFileName);
    int countnr = 0;
    if (!file.open(QFile::ReadOnly | QFile::Text)) {
    return alls;
    }
    
    QTextStream in(&file);
    
        while (!in.atEnd()) {
         ++countnr;
         inside = in.readLine(0);  /* line by line (); */
            
            if (inside.size() > 1 && !inside.contains("();", Qt::CaseInsensitive) && !inside.startsWith("#", Qt::CaseInsensitive) && !inside.startsWith("<?", Qt::CaseInsensitive) ) {
                /* alls = alls+"\n" + QString( "Line %1 " ).arg( int2char( countnr ) ) + inside; */
                if (alls.size() > 0) {
                alls = alls+"\n"+ inside;
                } else {
                alls = inside;
                }
            }
        }
        
file.close();
return alls.replace(QString(",);"), QString(");"));
} 








