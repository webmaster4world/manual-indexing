#ifndef HANDLEONEFILEACTION_H
#define HANDLEONEFILEACTION_H
#include "main.h"
#include "index_dir_doc.h"

class DocPage
{
public:
  DocPage();
  DocPage& operator=( const DocPage& d );
  operator QVariant() const
  {
    return QVariant::fromValue(*this);
  }
          uint sizebyte;
		  QString Title;
		  QString Name;
		  QString mimetipe;
		  QString pagein; 
		  QStringList keyword;
  
};


Q_DECLARE_METATYPE(DocPage);


inline QDebug operator<<(QDebug debug, DocPage& udoc)
{
    debug.nospace() << "DocPage(Enable."
    << udoc.Title << ",Title(),"
    << udoc.mimetipe << ",mimetipe(),"
    << udoc.sizebyte << ",sizebyte() )";
    return debug.space();
}





class LoadPageExtend : public QThread
{
    Q_OBJECT
     
public:
  void Setting( const QString file_full_path , QSqlDatabase db , int id ); 
 
protected:
  void run();
  signals:
    void ready(int);  ////  emit ready(currentID);
    void dir_indexing(QString);
    void error_indexing(QString);
private:
    int currentID;
    QFileInfo fi;  /* the file to handle */
    QSqlDatabase dbconnection;  /* connection to write data */
    ///////QObject* receiver;  /* QObject sender and reciver from data  */
};


//
#endif // HANDLEONEFILEACTION_H

