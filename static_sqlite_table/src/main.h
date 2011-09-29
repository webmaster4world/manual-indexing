#ifndef MAIN_H
#define MAIN_H


#define _PROGRAM_NAME "Sqlite3 Bridge to qt4"
#define _PROGRAM_SHORT_NAME "sqlite_bridge"
#define _SQLITEVERSION "3"
#define _PROGRAM_VERSION "QT4.1.2 version 0.1 beta"
#define _PROGRAM_TITLE  _PROGRAM_NAME" "_PROGRAM_VERSION


#define _NULL_ \
             QString("")    


/* dna on remote server! */
#define _USER_AGENT_CURL_APPS "Mozilla/5.0 (Windows; U; Windows NT 5.1; it-CH; rv:1.7.12) Gecko/20050919 Firefox/1.0.7"

/*  Debug option to show __LINE__ __FILE__ or put on a file */
#define STRINGIFY(x) #x
#define TOSTRING(x) STRINGIFY(x)
#define AT __FILE__":"TOSTRING(__LINE__)" "

#ifndef WORK_CACHEDIR
#define WORK_CACHEDIR \
              QString( "%1/.%2/" ).arg( QDir::homePath() , _PROGRAM_SHORT_NAME )
#endif


#define XML_SIGNATURE_ROOT \
              QString( "%1 sqliteversion %2" ).arg( _PROGRAM_TITLE , _SQLITEVERSION )


#ifndef STATIC_DB_FILE
#define STATIC_DB_FILE \
              QString( "%1STATIC_DB_FILE.db" ).arg( WORK_CACHEDIR )
#endif

#ifndef STATIC_TMP_SQL_DUMP
#define STATIC_TMP_SQL_DUMP \
              QString( "%1_tmp_db.sql" ).arg( WORK_CACHEDIR )
#endif

#ifndef SQLITE_CLASS_LOG
#define SQLITE_CLASS_LOG \
              QString( "%1_running_sqlclass.log" ).arg( WORK_CACHEDIR )
#endif

#ifndef QUERY_RUNNING_LOG
#define QUERY_RUNNING_LOG \
              QString( "%1_running_query.log" ).arg( WORK_CACHEDIR )
#endif

#ifndef BAD_QUERY_RUNNING_LOG
#define BAD_QUERY_RUNNING_LOG \
              QString( "%1_running_bad_query.log" ).arg( WORK_CACHEDIR )
#endif

#ifndef LOCALE_DOWNLOAD
#define LOCALE_DOWNLOAD \
              QString( "%1_locale_yes" ).arg( WORK_CACHEDIR )
#endif


#ifndef ROW_DB_CACHE
#define ROW_DB_CACHE \
              QString( "%1_db_row.dat" ).arg( WORK_CACHEDIR )
#endif


#if defined( Q_WS_X11 )
#include "osnix.h"
#endif
#if defined( Q_WS_MACX )
#include "osmac.h"
#endif
#if defined(Q_WS_WIN)
#include "oswin32.h"
#endif



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
#if defined(HAVE_CONFIG_H)
#include <config.h>
#endif
#if defined(HAVE_SYS_TIMEB_H) || defined(WIN32)
#include <sys/timeb.h>
#endif
#ifdef HAVE_SYS_TYPES_H
#include <sys/types.h> // needed too in Windows
#endif
#ifdef HAVE_SYS_TIME_H
#include <sys/time.h>
#endif




/*  plug-in load */
#include <QTimer>
#include <QVector>
#include <QByteArray>
#include <QLocale>
#include <QTranslator>
#include <QDir>
#include <QDateTime>
#include <QMessageBox>
#include <QApplication>
#include <QStyle>
#include <QPointer>
#include <QDir>
#include <QDateTime>
#include <QFile>
#include <QTextStream>
#include <QRegExp>
#include <QIODevice>
#include <QFileInfo>
#include <QMap>
#include <QChar>
#include <QString>
#include <QtCore/QVariant>
#include <QtGui/QAction>
#include <QtGui/QApplication>
#include <QtGui/QButtonGroup>
#include <QtGui/QMainWindow>
#include <QtGui/QMenuBar>
#include <QtGui/QPushButton>
#include <QtGui/QStatusBar>
#include <QtGui/QWidget>
#include <QStatusBar>
#include <QtCore/QVariant>
#include <QtGui/QAction>
#include <QtGui/QApplication>
#include <QtGui/QButtonGroup>
#include <QtGui/QLabel>
#include <QtGui/QMainWindow>
#include <QtGui/QMenu>
#include <QtGui/QMenuBar>
#include <QtGui/QStatusBar>
#include <QtGui/QWidget>
#include <QStatusBar>
#include <QVector>
#include <QAction>
#include <QApplication>
#include <QClipboard>
#include <QColorDialog>
#include <QComboBox>
#include <QFile>
#include <QFileDialog>
#include <QFileInfo>
#include <QFontDatabase>
#include <QMenu>
#include <QMenuBar>
#include <QPrintDialog>
#include <QPrinter>
#include <QTextCodec>
#include <QTextEdit>
#include <QToolBar>
#include <QTextCursor>
#include <QTextList>
#include <QtDebug>
#include <QCloseEvent>
#include <QMessageBox>
#include <QString>
#include <QObject>
#include <QLayout>
#include <QFileInfo>
#include <QDir>
#include <QListWidget>
#include <QString>
#include <QStringList>
#include <QSettings>
#include <QFileInfo>

#endif

