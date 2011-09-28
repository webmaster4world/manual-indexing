#ifndef INTERFACE_H
#define INTERFACE_H
//
#include "interface_start.h"
#include "main.h"
#include <QMainWindow>
#include <QPointer>
#include <QtDebug>
#include <QDebug> 
#include <QApplication>
#include <QProgressDialog>
#include <QtCore/QVariant>
#include <QtGui/QAction>
#include <QtGui/QApplication>
#include <QtGui/QButtonGroup>
#include <QtGui/QHeaderView>
#include <QtGui/QMainWindow>
#include <QtGui/QMenu>
#include <QtGui/QMenuBar>
#include <QtGui/QStatusBar>
#include <QtGui/QWidget>
#include <QtWebKit/QWebView>
#include <QListWidgetItem>
#include <QPointer>



class Comand_W : public QWidget, public Ui::Comand_W
{
     Q_OBJECT
//
public:	 
  static Comand_W* self( QWidget* = 0 );
protected:	 
private:
 Comand_W( QWidget* = 0 );
 static QPointer<Comand_W> _self;
signals:
public slots:

};




//
class Interface : public QMainWindow, public Form::Interface
{
     Q_OBJECT
//
public:	 
    
    static Interface* self( QWidget* = 0  );

protected:	 
    ///////void closeEvent( QCloseEvent* );
    explicit Interface( QWidget* = 0 );
private:
 static QPointer<Interface> _istance;
 Index_Dir_Doc *rundir;
 QProgressDialog *pd;
 void Load_Connector();
 Comand_W *comand;
 bool Onsearch;
 QDockWidget *dock;
 bool UseVirtualTable;
 QString CurrentSearchWord;
 QString CurrentUri;
 QString Currentanchor;
 TableUri Box_att;
signals:
  /* emiter */
   
public slots:
   void configure_dir();
   void checkvirtual();
   void make_virtual();
   void index_dir();
   void indexing_abort();
   void active_search(QString);
   void list_select( QListWidgetItem * uri );
   void Logvisible( const QString txt );
   void url_Controller(const QUrl &u);
   void linkactive( const QString a ,const QString b ,const QString c );
   void downloadRequested( const QNetworkRequest &e );
   void manual_load( bool e  );  /* if page end load on browser */
   void tryhandle( QNetworkReply * e );
   void loadFinished( bool e );
   void truncate_table();
   void Load_404();
};


//
#endif // INTERFACE_H

