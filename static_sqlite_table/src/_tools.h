#ifndef _TOOLS_H
#define _TOOLS_H
#include "main.h"


#include <iostream>
#include <errno.h>
//#include <dirent.h>
#include <stdio.h>
#include <stdlib.h>
#include <ctype.h>
#include <string>
#include <stdio.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <iostream>
#include <fstream>
#include <time.h>

using namespace std;
QString XML_utf8( QString t );
QString traduce( QString t );
int get_nummer(QString incomming);
bool is_numeric(QString incomming);
QString  UnixTime2UserTime(int utimenr);
int  getUnixTime(int d , int m , int j);
QString qt2latin1( QString item );
QString file_get_clean_line(QString fullFileName);
bool file_put_contents(QString fullFileName,QString xml);
QString file_get_line(QString fullFileName,int linenr);
char* file_get_contents_char(char *filename);
bool file_put_contents_char( char *filename , char *xml );
bool is_file(QString fullFileName);
bool qt_unlink(QString fullFileName);
double getExactTime();
bool file_put_contents_append(QString fullFileName,QString xml);
QString qt_unixtime(QString line);
char* double2char( double i_int );
char* int2char( int i_int );

QString chars2qt( char* xml );
char* qtchars( QString xml );
const char* qtchar( QString xml );
char* append( char *one , char *dwo );
QString UsersLocalCodec( QString xml );
#endif

/*
file_put_contents("conf.dat", QString( "%1/db_setting/" ).arg( QDir::homePath() ) );
*/
