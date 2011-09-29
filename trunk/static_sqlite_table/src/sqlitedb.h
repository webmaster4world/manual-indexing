#ifndef SQLITEDB_H
#define SQLITEDB_H
/* Part of this code is original from http://sqlitebrowser.sourceforge.net/ */
/*  porting to qt4  PPK-Webprogramm www.ciz.ch */
#include "main.h"
#include "_tools.h"
#include <stdlib.h>
#include "sqlite3.h"
#include "sqliteInt.h"
#include <QDir>
#include <QTranslator>
#include <QMap>
#include <QList>
#include <QStringList>

typedef QMap<int, class DBBrowserField> fieldMap;
typedef QMap<int, class DBBrowserTable> tableMap;
typedef QMap<int, class DBBrowserIndex> indexMap;
typedef QMap<int, int> rowIdMap;

typedef QList<QStringList> rowList;
typedef QMap<int, QString> resultMap;


class DBBrowserField
    {    
    public:
        DBBrowserField() : name( _NULL_ ) { }
        DBBrowserField( const QString& wname,const QString& wtype )
            : name( wname), type( wtype )
        { }
        QString getname() const { return name; }
        QString gettype() const { return type; }
 private:
        QString name;
        QString type;
 };

class DBBrowserIndex
    {    
    public:
        DBBrowserIndex() : name( _NULL_ ) { }
       DBBrowserIndex( const QString& wname,const QString& wsql )
            : name( wname), sql( wsql )
        { }
        QString getname() const { return name; }
        QString getsql() const { return sql; }
private:
        QString name;
        QString sql;
 };
 
 
 class DBBrowserTable
    {    
    public:
        DBBrowserTable() : name( _NULL_ ) { }
        DBBrowserTable( const QString& wname,const QString& wsql )
            : name( wname), sql( wsql )
        { }

        void addField(int order, const QString& wfield,const QString& wtype);

        QString getname() const { return name; }
        QString getsql() const { return sql; }
        fieldMap fldmap;
private:
        QString name;
        QString sql;
 };
 

class Sqlitedb
{
public:
    Sqlitedb();
    ~Sqlitedb();
    bool open_db( const QString & db );
    bool dumptofile( QString filename );
    bool is_open;
    bool DEBUG_WORKING; 
    bool isOpen();
    bool check_file_db();
    QString lastErrorMessage;
    int  dberror;
    QString curDBFilename;
    bool SqlLog( QString line );
    QString file_get_contents( QString filename );
    bool getDirty();
    void setDirty(bool dirtyval);
    void setDirtyDirect(bool dirtyval);
    bool setRestorePoint();
    bool save();
    bool revert();
    bool create ( const QString & db);
    void close ();
    bool compact();
    bool executeSQL ( const QString & statement);
    QStringList getTableFields(const QString & tablename);
    QStringList getTableTypes(const QString & tablename);
    void getTableRecords( const QString & tablename );
    void updateSchema( );
    bool browseTable( const QString & tablename );
    QStringList getIndexNames();
    QStringList getTableNames();
    int getRecordCount();
    tableMap tbmap;
	indexMap idxmap;
	rowIdMap idmap;
    bool hasValidBrowseSet;
    rowList browseRecs;
	QStringList browseFields;
    QString curBrowseTableName;
    
private:
    sqlite3 * _db;
    bool dirty;

};    























#endif 

