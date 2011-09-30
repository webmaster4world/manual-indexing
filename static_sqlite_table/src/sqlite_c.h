#ifndef ADB_H
#define ADB_H

#include <stdlib.h>
#include "sqlite3.h"
#include "sqliteInt.h"
 


#define TIMELIMITQUERY 1000

#ifdef __cplusplus
extern "C" {
#endif


    
struct sqljumper {
  sqlite3 *db;            /* The database */
  int cnt;               /* Number of records displayed so far */
  int mode;              /* An output mode setting */
  int showHeader;        /* True to show column names in List or Column mode */
  QString zDestTable;      /* Name of destination table when MODE_Insert */
  QString CleanTableName;  /* Name of destination table when export dump */
  QString separator;    /* Separator character for MODE_List */
  int colWidth[100];     /* Requested width of each column when in column mode*/
  int actualWidth;  /* Actual width of each column */
  QString nullvalue;    /* The text to print when a NULL comes back from*/
  QString zDbFilename;    /* name of the database file */
  QString zrowsFilename;    /* name of file rows streams callbacks */
};




static int normalcallback( void *pArg , int argc, char **argv, char **azColName) {
     struct sqljumper *p = (struct sqljumper *)pArg;
     int i;
     struct sqljumper d3;
     d3 = *p;       
     QFile f( d3.zrowsFilename );
     QString  field , data, tmputf8;
     if ( f.open( QFile::Append | QFile::Text ) )
	 {
        QTextStream sw( &f );
        QString timing = QString( "%1" ).arg(time( NULL ));
        sw << "<table bench_unixtime=\"" << timing << "\" name=\"" << d3.zDestTable << "\" cleanname=\"" << d3.CleanTableName << "\">\n";
        sw << "<row>\n";
        for(i=0; i<argc; i++){  /* azColName[i]  */
         tmputf8 = QString( "%1" ).arg(argv[i] ? argv[i] : "NULL"); 
         data = QString( "data=\"%1\" " ).arg( XML_utf8(tmputf8) ); 
         field = QString( "field=\"%1\" " ).arg(azColName[i]);
         sw << "<rows id=\"" << i << "\" " << field << data << "/>\n ";
        }
        sw << "</row>\n";
        sw << "</table>\n";
		f.close();
	}
      return 0;
}

    
static int dumpcallback( void *pArg , int argc, char **argv, char **azColName) {
     struct sqljumper *p = (struct sqljumper *)pArg;
     int i;
     struct sqljumper d2;
     QByteArray tab;
     char *errmsg;
     QString  nextquery;
     const char* bridgestart;
     d2 = *p;        
     QFile f( d2.zrowsFilename );
     QString  field , data;
     if( argc!=3 ) {
     f.remove(d2.zrowsFilename);
     return 1;
     }
     
     
     
     if ( f.open( QFile::Append | QFile::Text ) )
	 {
        QTextStream sw( &f );
        QString timing = QString( "%1" ).arg(time( NULL ));
        sw << "<dumprow bench_unixtime=\"" << timing << "\">\n";
        for(i=0; i<argc; i++){  /* azColName[i]  */
         data = QString( "data=\"%1\" " ).arg(argv[i] ? argv[i] : "NULL"); 
         field = QString( "field=\"%1\" " ).arg(azColName[i]);
         sw << "<dumprows sql_id=\"" << i << "\" " << field << data << "/>\n ";
        }
        
        if( strcmp(argv[1],"table")==0 ){
        QString  TableNow = QString("%1").arg(argv[0]);
        TableNow.replace(QString(" "), QString("_"));
        tab = TableNow.toAscii();
        d2.zDestTable = argv[0];
        d2.CleanTableName = tab.data();
        nextquery = QString( "SELECT * FROM '%1'" ).arg(d2.zDestTable);
        sw << "<tablecut tablename=\"" << d2.zDestTable << "\"/>\n";
        sw << "<tablecut tableclean=\"" << d2.CleanTableName << "\"/>\n";
        sw << "<tablecut nextquery=\"" << nextquery << "\"/>\n";
        }
        sw << "</dumprow>\n";
		f.close();
      
        if (tab.size() > 3) {
        QByteArray bridge = nextquery.toAscii();
        bridgestart = bridge.data();
         if (SQLITE_OK==sqlite3_exec(d2.db,bridgestart,normalcallback, &d2 , &errmsg)){
          
         }
        }
        
	}
      return 0;
}


#ifdef __cplusplus
}  /* End of the 'extern "C"' block */
#endif




#endif 





