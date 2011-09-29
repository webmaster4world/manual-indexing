#include "sqlitedb.h"
#include "sqlite_c.h"
#include <stdlib.h>
#include "sqlite3.h"
#include "sqliteInt.h"

/* Part of this code is original from http://sqlitebrowser.sourceforge.net/ */
/*  porting to qt4  PPK-Webprogramm www.ciz.ch */

void DBBrowserTable::addField(int order, const QString& wfield,const QString& wtype)
{
    fldmap[order] = DBBrowserField(wfield,wtype);
}


Sqlitedb::Sqlitedb()
{
  DEBUG_WORKING = false;
  is_open = false;
  dirty = false;
  lastErrorMessage = QString("init_class");
  SqlLog(lastErrorMessage);
}

Sqlitedb::~Sqlitedb()
{
  lastErrorMessage = QString("destructor_class");
  SqlLog(lastErrorMessage);
}

void Sqlitedb::updateSchema( )
{
  // qDebug ("Getting list of tables");
	sqlite3_stmt *vm;
	const char *tail;
	/*int ncol;*/
	QStringList r;
	int err=0;
	int idxnum =0;
	int tabnum = 0;
	
	idxmap.clear();
	tbmap.clear();
	
	lastErrorMessage = QString("no error");
	QString statement = "SELECT name, sql "
		"FROM sqlite_master "
		"WHERE type='table' "
		"ORDER BY name;";
	
	err=sqlite3_prepare(_db, statement.toUtf8().constData(),-1,
											&vm, &tail);
	if (err == SQLITE_OK){
        SqlLog(QString( "%1 from updateSchema 1" ).arg( statement ));
		while ( sqlite3_step(vm) == SQLITE_ROW ){
						QString  val1, val2;
						val1 = QString((const char *) sqlite3_column_text(vm, 0));
						val2 = QString((const char *) sqlite3_column_text(vm, 1));
						tbmap[tabnum] = DBBrowserTable(val1, val2);
						tabnum++;
		}
		sqlite3_finalize(vm);
	}else{
		qDebug ("could not get list of tables: %d, %s",err,sqlite3_errmsg(_db));
	}
	
	//now get the field list for each table in tbmap
	tableMap::Iterator it;
	for ( it = tbmap.begin(); it != tbmap.end(); ++it ) {
		statement = "PRAGMA TABLE_INFO(";
		statement.append( QString(it.value().getname()) );
		statement.append(");");
		SqlLog(QString( "%1 from updateSchema 2" ).arg( statement ));
		err=sqlite3_prepare(_db,statement.toUtf8().constData(),-1,
												&vm, &tail);
		if (err == SQLITE_OK){
			it.value(). fldmap.clear();
			int e = 0;
			while ( sqlite3_step(vm) == SQLITE_ROW ){
				if (sqlite3_column_count(vm)==6) {
					QString  val1, val2;
					int ispk= 0;
					val1 = QString((const char *) sqlite3_column_text(vm, 1));
					val2 = QString((const char *) sqlite3_column_text(vm, 2));
					ispk = sqlite3_column_int(vm, 5);
					if (ispk==1){
						val2.append(QString(" PRIMARY KEY"));
					}
					it.value().addField(e,val1,val2);
					e++;
				}
      }
      sqlite3_finalize(vm);
		} else{
			lastErrorMessage = QString ("could not get types");
		}
	}
	statement = "SELECT name, sql "
		"FROM sqlite_master "
		"WHERE type='index' "
		"ORDER BY name;";
  /*"ORDER BY name;"*/
	//finally get indices
	err=sqlite3_prepare(_db,statement.toUtf8().constData(),-1,
											&vm, &tail);
	SqlLog(QString( "%1 from updateSchema 3" ).arg( statement ));
	if (err == SQLITE_OK){
		while ( sqlite3_step(vm) == SQLITE_ROW ){
			QString  val1, val2;
			val1 = QString((const char *) sqlite3_column_text(vm, 0));
			val2 = QString((const char *) sqlite3_column_text(vm, 1));
      idxmap[idxnum] = DBBrowserIndex(val1,val2);
      idxnum ++;
		}
		sqlite3_finalize(vm);
	}else{
		lastErrorMessage = QString ("could not get list of indices");
	}
}












bool Sqlitedb::browseTable( const QString & tablename )
{
    QStringList testFields = getTableFields( tablename );
    
    if (testFields.count()>0) {//table exists
 getTableRecords( tablename );
 browseFields = testFields;
 hasValidBrowseSet = true;
 curBrowseTableName = tablename;
    } else {
 hasValidBrowseSet = false;
 curBrowseTableName = QString(" ");
 browseFields.clear();
 browseRecs.clear();
 idmap.clear();
    }
    return hasValidBrowseSet;
}


int Sqlitedb::getRecordCount()
{
    return browseRecs.count();
}

void Sqlitedb::getTableRecords( const QString & tablename )
{
   sqlite3_stmt *vm;
   const char *tail;
   
   int ncol;
   QStringList r;
  // char *errmsg;
   int err=0;
  // int tabnum = 0; 
   browseRecs.clear();
   idmap.clear();
   lastErrorMessage = QString("no error");
   
 QString statement = "SELECT rowid, *  FROM ";
 statement.append( tablename );
 statement.append(" ORDER BY rowid; ");
 //qDebug(statement);
 SqlLog(QString( "%1 from getTableRecords 1" ).arg( statement ));
 err=sqlite3_prepare(_db,statement.toUtf8().constData() ,-1,&vm, &tail);
 if (err == SQLITE_OK){
     int rownum = 0;
   while ( sqlite3_step(vm) == SQLITE_ROW ){
       r.clear();
       ncol = sqlite3_data_count(vm);
       for (int e=0; e<ncol; e++){
     QString rv(QString::fromUtf8((const char *) sqlite3_column_text(vm, e)));
     r << rv;
        if (e==0){
          idmap.insert(rv.toInt(),rownum);
          rownum++;
        }
       }
       browseRecs.append(r);
   }

          sqlite3_finalize(vm);
        }else{
          lastErrorMessage = QString ("could not get fields");
        }
}




QStringList Sqlitedb::getTableNames()
{
    tableMap::Iterator it;
    tableMap tmap = tbmap;
    QStringList res;

    for ( it = tmap.begin(); it != tmap.end(); ++it ) {
    res.append( it.value().getname() );
    }
    
   return res;
}

QStringList Sqlitedb::getIndexNames()
{
    indexMap::Iterator it;
    indexMap tmap = idxmap;
    QStringList res;

    for ( it = tmap.begin(); it != tmap.end(); ++it ) {
    res.append( it.value().getname() );
    }
    
   return res;
}

QStringList Sqlitedb::getTableFields(const QString & tablename)
{
    tableMap::Iterator it;
    tableMap tmap = tbmap;
    QStringList res;

    for ( it = tmap.begin(); it != tmap.end(); ++it ) {
         if (tablename.compare(it.value().getname())==0 ){
         fieldMap::Iterator fit;
         fieldMap fmap = it.value().fldmap;

             for ( fit = fmap.begin(); fit != fmap.end(); ++fit ) {
            res.append( fit.value().getname() );
             }
        }
    }
return res;
}


QStringList Sqlitedb::getTableTypes(const QString & tablename)
{
    tableMap::Iterator it;
    tableMap tmap = tbmap;
    QStringList res;

        for ( it = tmap.begin(); it != tmap.end(); ++it ) {
     if (tablename.compare(it.value().getname())==0 ){
     fieldMap::Iterator fit;
     fieldMap fmap = it.value().fldmap;

     for ( fit = fmap.begin(); fit != fmap.end(); ++fit ) {
   res.append( fit.value().gettype() );
     }
 }
 }
    return res;
}



bool Sqlitedb::compact()
{
  char *errmsg;
  bool ok=false;
    
  if (!isOpen()) return false;

  if (_db){
      save();
      SqlLog(QString( "from VACUUM; 1" ));
    if (SQLITE_OK==sqlite3_exec(_db,"VACUUM;",
                               NULL,NULL,&errmsg)){
     ok=true;
     setDirty(false);
 }
    }

  if (!ok){
    lastErrorMessage = QString(errmsg);
    return false;
  }else{
    return true;
  }
}







void Sqlitedb::close ()
{
    if (_db)
    {
 if (getDirty())
 {
     QString msg = traduce("Do you want to save the changes made to the database file ");
     msg.append(curDBFilename);
     msg.append(" ?");
     if (QMessageBox::question( 0, _PROGRAM_NAME ,msg, QMessageBox::Yes, QMessageBox::No)==QMessageBox::Yes)
     {
     save();
     }
 }
 sqlite3_close(_db);
    }
   _db = 0;
}


bool Sqlitedb::executeSQL ( const QString & statement)
{
  char *errmsg;
  bool ok=false;
    
  if (!isOpen()) return false;

  if (_db){
      SqlLog(QString( "%1 from executeSQL 1" ).arg( statement ));
      setDirty(true);
    if (SQLITE_OK==sqlite3_exec(_db,statement.toUtf8().constData(),
                               NULL,NULL,&errmsg)){
     ok=true;
 }
    }

  if (!ok){
    lastErrorMessage = QString(errmsg);
    return false;
  }else{
    return true;
  }
}






bool Sqlitedb::create ( const QString & db)
{
  bool ok=false;
    
  if (isOpen()) close();
  
  lastErrorMessage = QString("no error");

    if( sqlite3_open( db.toUtf8().constData() , &_db) != SQLITE_OK ){
        lastErrorMessage = QString::fromUtf8(sqlite3_errmsg(_db));
        sqlite3_close(_db);
        _db = 0;
        return false;
      }

    if (_db){
    if (SQLITE_OK==sqlite3_exec(_db,"PRAGMA empty_result_callbacks = ON;",
                               NULL,NULL,NULL)){
    if (SQLITE_OK==sqlite3_exec(_db,"PRAGMA show_datatypes = ON;",
                               NULL,NULL,NULL)){
       ok=true;
        setDirty(false);
    }
    curDBFilename = db;
    }
    
   idxmap.clear();
   tbmap.clear();
   idmap.clear();
   browseRecs.clear();
   browseFields.clear();
   hasValidBrowseSet = false;
}

  return ok;
}


bool Sqlitedb::setRestorePoint()
{
    if (!isOpen()) return false;

  if (_db){
   if ( SQLITE_OK != sqlite3_exec(_db,"BEGIN TRANSACTION RESTOREPOINT;",
           NULL,NULL,NULL) ) {
  lastErrorMessage = QString::fromUtf8(sqlite3_errmsg(_db));
  return false;
 }
    setDirty(false);
  }   
  return true;
}

bool Sqlitedb::save()
{
  if (!isOpen()) return false;

  if (_db){
   if ( SQLITE_OK != sqlite3_exec(_db,"COMMIT TRANSACTION RESTOREPOINT;",
           NULL,NULL,NULL) ) {
  lastErrorMessage = QString::fromUtf8(sqlite3_errmsg(_db));
  return false;
 }
    setDirty(false);
  }   
  return true;
}

bool Sqlitedb::revert()
{
    if (!isOpen()) return false;

  if (_db){
   if ( SQLITE_OK != sqlite3_exec(_db,"ROLLBACK TRANSACTION RESTOREPOINT;",
           NULL,NULL,NULL) ) {
  lastErrorMessage = QString::fromUtf8(sqlite3_errmsg(_db));
  return false;
 }
    setDirty(false);
  }   
  return true;
}





bool Sqlitedb::isOpen()
{
    return is_open; 
}

bool Sqlitedb::getDirty()
{
    return dirty;
}

void Sqlitedb::setDirtyDirect(bool dirtyval)
{
    dirty = dirtyval;
}

void Sqlitedb::setDirty(bool dirtyval)
{
    if ((dirty==false)&&(dirtyval==true))
    {
     /* setRestorePoint(); */
    }
    dirty = dirtyval;
}













/* dump sqlite3 to xml so can convert on 100* format excel csv ecc... xslt doc */
bool Sqlitedb::dumptofile( QString filename )
{
    SqlLog(" dumptofile start  ");
    bool ok=false;
    char *errmsg;
    if (is_open) {
    SqlLog("DB is open ok.....  ");
    
    struct sqljumper p;       
    p.db = _db;
    p.mode = 10;
    p.separator = "|";
    p.showHeader = 0;
    p.zDbFilename = curDBFilename;
    p.zrowsFilename = ROW_DB_CACHE;
    
        if (SQLITE_OK==sqlite3_exec(_db,
        "SELECT name, type, sql FROM sqlite_master "
        "WHERE type!='meta' AND sql NOT NULL "
        "ORDER BY substr(type,2,1), name",
         dumpcallback , &p , &errmsg)){
        ok=true;
        SqlLog(" dumptofile start dumpcallback ");
        }
        if (ok && is_file(ROW_DB_CACHE)) {
        SqlLog("Success callback .... go to file xml result .....");
        QString xmlrows = file_get_contents(ROW_DB_CACHE);
        file_put_contents(filename,"");  
        ok = qt_unlink(ROW_DB_CACHE);
        ok = file_put_contents(filename,QString( "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<root>\n%1</root>" ).arg( xmlrows ) );
        lastErrorMessage = "success";
        } else {
        ok=false; 
        lastErrorMessage = (QString::fromUtf8(sqlite3_errmsg(_db)));  
        SqlLog (QString(" Incomming Error \"%1\" end " ).arg ( lastErrorMessage ));          
        } 
        
    }  /* is open */
 return ok;
}























































/* return file contenets as qstring */
QString Sqlitedb::file_get_contents( QString filename )
{
    QString inside = "";  
    QFile file(filename); 
    if (file.exists()) {
                if (file.open(QFile::ReadOnly | QFile::Text)) {
                  inside =file.readAll();
                  file.close();
                }
    }
   return inside;
}

/* open the file or the memory db  */
bool Sqlitedb::open_db( const QString & db )
{
   bool ok=false; 
   /* open db or file */
   dberror = sqlite3_open( db.toUtf8().constData() , &_db );
      if (dberror) {
        lastErrorMessage = QString::fromUtf8(sqlite3_errmsg(_db));
        sqlite3_close(_db);
        _db = 0;
       return ok;
      }
      
      if (_db){
       if (SQLITE_OK==sqlite3_exec(_db,"PRAGMA empty_result_callbacks = ON;",NULL,NULL,NULL)) {
           if (SQLITE_OK==sqlite3_exec(_db,"PRAGMA show_datatypes = ON;",NULL,NULL,NULL)) {
            ok=true;
           }
        }
      curDBFilename = db;
       } 
  is_open = ok;       
return ok;
}

/* check the file db if is sqlite3 format....  */
bool Sqlitedb::check_file_db()
{
   bool ok=false;
   QString first_line = file_get_line(curDBFilename,1);  /* return QString from first line or "" */
   
     QFile file(curDBFilename);
         /*  check befor sqlite3 write a new file! */
         if (!file.exists()) {
         lastErrorMessage = QString( "File \"%1\" could not be read" ).arg( file.fileName() );
         return ok;   
         }
         if (first_line.size() < 1) {
         lastErrorMessage = QString("File could not be read");
         return ok;  
         }      
         if (!first_line.startsWith("SQLite format 3")) {
         lastErrorMessage = QString("File is not a SQLite 3 database");
         return ok;  
        } 
return true;
}

/* Log action controller */
bool Sqlitedb::SqlLog( QString line )
{
    return file_put_contents_append(SQLITE_CLASS_LOG,qt_unixtime(line));
}
