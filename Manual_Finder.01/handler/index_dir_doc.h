#ifndef INDEX_DIR_DOC_H
#define INDEX_DIR_DOC_H
//
#include "main.h"
#include "handleonefileaction.h"
//
/*  Save file as index_dir_doc.h  */
/*  incomming class name Index_Dir_Doc */
//
//

/*
typedef struct DocPage {
          uint sizebyte;
		  QString Title;
		  QString mimetipe;
		  QByteArray pagein; 
		  QStringList keyword;
} DocPage;
 
*/
 

class CreateVirtualtable : public QThread
{
    Q_OBJECT
     
public:
  void Setting( QSqlDatabase db , int summe ); 
 
protected:
  void run();
  int CountRow( const QString table);
  signals:
    void virtualstatus(int);
    void virtualready();
    void error_indexing(QString);
private:
    int totalnormal;
    QSqlDatabase dbconnection;
};







class Index_Dir_Doc  : public QObject
{
     Q_OBJECT
//
public:	
   explicit Index_Dir_Doc( QObject* = 0 );
   void Setdir(const QString d);
   bool init_db();
   QSqlDatabase get_db() {
	   return db;
   }
   int CountItem( const QString table);
   bool onwork();
  ~Index_Dir_Doc();
//
//
protected:
   QString extensionsearch;
   bool recursive_dir;
private:
      QString extension;
      QString readpath;
      QStringList dirandfile;
      QSqlDatabase db;
      bool continueread;
 void Load_Connector();
 bool permission();
 void Fireall();  /* close all connection */
 void IndexDir(const QString d);
 void OpenId(int x);
 void CatEnvoirment();
 signals:
    void done();
    void status(int);
    void read_error(QString);
    void read_path(QString);
 public slots:
    void abort();
    void initread();
    void gonext( int  index );
    

};
//
#endif // INDEX_DIR_DOC_H

