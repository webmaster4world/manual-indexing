#include "index_dir_doc.h"
#include "main.h"
#include "handleonefileaction.h"
using namespace std;

void CreateVirtualtable::Setting( QSqlDatabase db , int summe )
{
	dbconnection = db;
	totalnormal = summe;
	setTerminationEnabled(true);
}

int CreateVirtualtable::CountRow( const QString table)
{
	QSqlQuery query(dbconnection);
	int count = 0;
	QString rowsquery = QString("select count(title) as tot FROM %1").arg(table);
	if (query.exec(rowsquery)) {
		if (query.first()) {
			return query.value(0).toInt();
		}
	}
	return count;
}


void CreateVirtualtable::run()
{
    QSqlQuery query(dbconnection);
	int record2 = CountRow(QString("manual"));
	if (record2 > 0) {
			QString steep0 = QString("DROP TABLE manual");
			if (query.exec(steep0)) {
				
			}
    }
    int xlimit = totalnormal; ////// CountRow(QString("fileindex"));
	QString steep1 = QString("CREATE VIRTUAL TABLE manual USING fts3(title,body,uri)");
	QString steep2 = QString("SELECT title,txtgrep,fullpath FROM fileindex");
	
	QSqlQuery qui(dbconnection);
	int count = 0;
	        if (query.exec(steep1)) {
				  if (query.exec(steep2)) {
					    while (query.next()) {
							     count++;
							     qui.prepare("INSERT INTO manual (title, body, uri) "
                                          "VALUES (:title, :body, :uri)");
								 qui.bindValue(0,query.value(0).toString());
								 qui.bindValue(1,query.value(1).toString());
								 qui.bindValue(2,query.value(2).toString());
								 qui.exec();
								 
								 
								    qreal cento = 100.0;
									qreal percentuale = count*cento/xlimit;
									int pint = percentuale;
									cout <<  "###### virtual writlen %" << percentuale << "\r";
									emit virtualstatus(pint);
								 
						}
				  }
			}
	
	
	emit virtualready();
	exit();
}





/* ################### class init #################################################################################### */

Index_Dir_Doc::Index_Dir_Doc( QObject* parent )
	: QObject( parent )
{
	extension = QString(".");
	recursive_dir = true;
	dirandfile.clear();
	continueread = true;
	setObjectName(QString("Index_Dir_Doc"));
	Setdir(QDir::homePath());
	if (!init_db()) {
		Fireall();
	}
}

bool Index_Dir_Doc::init_db() {
	Setdir(QDir::homePath());
	bool first = false;
	QDir dirapp = QDir();
	dirandfile.clear();
	
	   if (!QFile::exists(DBfileSQLITE)) {
		   if (!dirapp.mkpath (_INDEXCACHE_)) {
		   cout << "Unable to create a cache dir!" <<  endl;
		   /////emit read_error(QString("Unable to create a cache dir!:%1").arg(_INDEXCACHE_));
		   Fireall();
		   return false;
	       }
		  first = true;
	   }
	    
	db = QSqlDatabase::addDatabase("QSQLITE");
    db.setDatabaseName(DBfileSQLITE);
    db.setConnectOptions("QSQLITE_BUSY_TIMEOUT");
    if (!db.open()) {
		cout << "Unable to connect on sqlite db ->" << qPrintable(DBfileSQLITE) << endl;
		Fireall();
		return false;
	}
	
    if (first) {
		/* populate db */
		QSqlQuery query;
        query.exec("create table fileindex (id INTEGER PRIMARY KEY, "
                   "fullpath varchar(220), name varchar(220) , title varchar(240), txtgrep TEXT , size int )");
		
	}
	
	return db.open();
}



void Index_Dir_Doc::Setdir(const QString d) {
	readpath = d;
}

bool Index_Dir_Doc::onwork() {
	return db.open();
}

void Index_Dir_Doc::abort() {
	continueread = false;
}

void Index_Dir_Doc::initread() {
	
	continueread = true;
	Load_Connector();
}


void Index_Dir_Doc::CatEnvoirment()
{
	/* test function */
	const QString name = QString("fhfdhdfhdhindex.html");
	int fsize = 9453;
	/* search file name if exist and same size */
	     const QString size = QString("%1").arg(fsize);
		  QString sqlsearch = QString("SELECT fullpath FROM fileindex where name='%1' and size='%2' limit 1 ").arg(name,size);
		  QSqlQuery query(db);
		        if (query.exec(sqlsearch)) {
					    if (query.first()) {
							QString filepat = query.value(0).toString();
							cout << "file ->" << qPrintable(squote(filepat)) << endl;
						}  
		         }
	Fireall();
}

int Index_Dir_Doc::CountItem( const QString table)
{
	QSqlQuery query(db);
	int count = 0;
	QString rowsquery = QString("select count(title) as tot FROM %1").arg(table);
	if (query.exec(rowsquery)) {
		if (query.first()) {
			return query.value(0).toInt();
		}
	}
	return count;
}

void Index_Dir_Doc::Load_Connector()
{
	
    /////cout << APPNAME << " Index_Dir_Doc  start...." <<   qPrintable(extension)  << "......." << endl;
    IndexDir(readpath);
    int xsize = dirandfile.size();
    /////cout << APPNAME << " found...." <<   xsize  << " total file." << endl;
    if (xsize > 0) {
		OpenId(0);
	} else {
		Fireall();
	}
	 
}

Index_Dir_Doc::~Index_Dir_Doc() {
	
	  
	
	 db.close();
	 const QString _database = db.connectionName();
	 /////cout << APPNAME << " end class e close db ->" <<   qPrintable(_database)  << "......." << endl;
	 db.removeDatabase(_database);
	 QSqlDatabase::removeDatabase(_database);
	 if (db.open()) {
		 delete &db;
	 }
	 
	 
	  
	
}

void Index_Dir_Doc::Fireall()
{
	 dirandfile.clear();
	 emit done();  
}

void Index_Dir_Doc::OpenId(int x)
{
	if (!continueread) {
		Fireall();
		return;
	}
	
	int xsize = dirandfile.size();
	const int xlimit = xsize - 1;
	if ( x > xlimit ) {
		Fireall();
		return;
	}
	    //////qDebug() << "#### Index_Dir_Doc::OpenId " << x;
        QFileInfo fi = QFileInfo(dirandfile[x]);
        if (fi.isFile() && fi.isReadable() ) {
		LoadPageExtend *setPage = new LoadPageExtend();
        setPage->Setting(fi.absoluteFilePath(),db,x); ///// Setting( const QString file_full_path , QSqlDatabase db , int id ); 
		setPage->start(QThread::LowPriority);
		connect(setPage, SIGNAL(ready(int)),this, SLOT(gonext(int)));
		//////connect(setPage, SIGNAL(dir_indexing(QString)),this, SLOT(by_path(QString)));
		        qreal cento = 100.0;
                qreal percentuale = x*cento/xlimit;
                int pint = percentuale;
		        cout <<  "###" << qPrintable(fi.fileName()) <<  "### Read %" << percentuale << "\r";
		        emit status(pint);
		        emit read_path(fi.absolutePath());
	    } else {
			/////cout << "Error --------  unable to read file->" <<   qPrintable(fi.fileName()) << endl;
			emit read_error(QString("Unable to read file:%1").arg(fi.absoluteFilePath()));
			Fireall();
		}
}


void Index_Dir_Doc::gonext( int  index ) {
	
	int xsize = dirandfile.size();
	const int xlimit = xsize - 1;
	int nextfile = index + 1;
	if ( nextfile > xlimit ) {
		Fireall();
		return;
	} else {
		OpenId(nextfile);
	}
}

bool Index_Dir_Doc::permission()  {
	int xsize = dirandfile.size();
	if (xsize > SNORFMAX) {
		return false;
	} else {
		return true;
	}
	return false;
}


void Index_Dir_Doc::IndexDir(const QString d)
{
   if (!permission()) {
	   return;
   }
   QDir dir(d);
   if (dir.exists())
   {
      const QFileInfoList list = dir.entryInfoList();
      QFileInfo fi;
      for (int l = 0; l < list.size(); l++)
      {
         fi = list.at(l);
         if (fi.isDir() && fi.fileName() != "." && fi.fileName() != "..") {
            if (recursive_dir) {
            IndexDir(fi.absoluteFilePath());
            }
             
         } else if (fi.isFile() && !fi.fileName().contains(APPNAME) ) {
                 const QString ext = fi.suffix().toLower();
                 if (ext.contains("pdf") || 
						 ext.contains("html") ||
						 ext.contains("htm")) {
					    
                   //////int xsize = dirandfile.size();
                   if (permission()) {
					   //////") read...." <<   qPrintable(fi.fileName()) 
                   ////////cout << xsize << ")" << qPrintable(ext)  << endl;
                   dirandfile.append(fi.absoluteFilePath());
			       }
			   }
         } 
        
      }
   }
}











/* ################### struct  #################################################################################### */

DocPage& DocPage::operator=( const DocPage& d )
{
    Title = d.Title;
    pagein = d.pagein;
    mimetipe = d.mimetipe;
    sizebyte = d.sizebyte;
    keyword = d.keyword;
    return *this;
    /*
     *    uint sizebyte;
		  QString Title;
		  QString mimetipe;
		  QByteArray pagein; 
		  QStringList keyword;*/
}


DocPage::DocPage() 
{
    Title = QString("No title found on page!");
    pagein = QByteArray(NULLO);
    mimetipe = QString("ERROR=%1 ").arg(NULLO);
    sizebyte = 0;
    keyword = QStringList();
}








