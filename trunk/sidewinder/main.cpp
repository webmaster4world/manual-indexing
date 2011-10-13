#include <iostream>
#include "ustring.h"
#include <sidewinder.h>
#include <excel.h>
#include "ustring.h"
#include <QApplication>
#include <QDebug> 

#include "workbook.h"
#include "sheet.h"

#define _NULL_ \
             QString("")   
             
             
/* tool  to extract data from excel new or old format */
/*  spicyitalian qt read modus 
 *  libextractor gnu lib to search engine
 *  antiword 
 *  clamwin antivirus
 *  this here is from a old koffice version
 *  unable to find one to parse very big excel up to 4MB file
 * */


using namespace std;


inline void message( const char *m )
{
    cout << m << endl;
}



int main( int argc, char *argv[])
{
  
  message("#init main" );
  const QString currentfile = QString(argv[1]);
  
  Sidewinder::Reader *reader;
  reader = Sidewinder::ReaderFactory::createReader( "application/msexcel" );

  if( !reader )
  {
    message("Internal problem: format not supported." );
    return 0;
  }
  
    if (currentfile == _NULL_) {
	message("./appname (excel_file) >> result.data" );
	message("#set on file to read... " );
    return 1;
    }
  
  
  Sidewinder::Workbook* workbook;
  workbook = reader->load(argv[1]);
  if( !workbook )
  {
    message("#Could not read from file." );
    delete reader;
    return 0;
  }
  const int sm = workbook->sheetCount();
  cout << "#sheet total ->" << sm << endl;  
  
    for( unsigned i=0; i < workbook->sheetCount(); i++ )  {
    Sidewinder::Sheet* sheet = workbook->sheet( i );
         const QString sheet_name(sheet->name().qstring());
         cout << "#sheet name ->" << qPrintable(sheet_name) << endl; 
         for( unsigned row = 0; row <= sheet->maxRow(); row++ ) {
						  for( unsigned col = 0; col <= sheet->maxColumn(); col++ )
						  {
									Sidewinder::Cell* cell = sheet->cell( col, row, false );
									if( cell )
									{
										  const QVariant data = cell->value().asVariant();
										  const QString name = cell->name().qstring();  
										  cout << qPrintable(name) << "@pair@" << qPrintable(data.toString()) << endl;
									}
						  }
          }
         
    
    
    
     }
  
  
  
  
  
  message("#end main");
  return 0;
 }
