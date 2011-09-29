#include "sqltable.h"
#include <QtGui>
#include <QCloseEvent>
//
QPointer<GuiMain> GuiMain::_self = 0L;
//
GuiMain* GuiMain::self( QWidget* parent )
{
	if ( !_self )
		_self = new GuiMain( parent );
	return _self;
}
//
GuiMain::GuiMain( QWidget* parent )
	: QMainWindow( parent )
{
    
    textquery1 = new QTextEdit(this);
    textquery1->clear();
    textquery1->insertPlainText("Select DB Connect => Start Query Dump to file => Here....");
    textquery1->toPlainText();
    
    table_list = new QComboBox(this);
    
    master_table = new QTableWidget(this);
    
    QWidget *centralwidget = new QWidget(this);
    QGridLayout *grid = new QGridLayout(centralwidget);
    grid->addWidget(PaintCentral(), 1, 1);
    grid->addWidget(table_list, 2, 1);
    grid->addWidget(master_table, 3, 1);
    grid->addWidget(textquery1, 4, 1);
    setLayout(grid);
    setCentralWidget(centralwidget);
    
    if (!open_db("master.db")){
      QMessageBox::information(this, tr("Alert Error!"),tr("Unable to open a new db connection!"));  
    } else {
     QApplication::setOverrideCursor(QCursor(Qt::WaitCursor));
     updateSchema();  
     tableMap::Iterator it;
     tableMap tmap = tbmap; 
     lasttablename;
     table_list->addItem("Select a Table:");
          for ( it = tmap.begin(); it != tmap.end(); ++it ) {
                lasttablename = QString(it.value().getname());
                table_list->addItem( lasttablename );
          }
     PaintTable(lasttablename);
     connect(table_list, SIGNAL(currentIndexChanged(QString)), this , SLOT(PaintTable(QString)));   
     QApplication::restoreOverrideCursor();          
    }
    
    
	setWindowTitle(_PROGRAM_TITLE);
    resize(480, 320);
}


void GuiMain::PaintTable(QString tablename)
{
    if (!tablename.contains(":")) {
    QApplication::setOverrideCursor(QCursor(Qt::WaitCursor));
    if (isOpen() && browseTable(tablename) ) {
      master_table->clear();
      QStringList fields = browseFields;
      /*QList data_latin = browseRecs;*/
      int coolpos = 0;
      int vertikalrow = getRecordCount();
      int datanum = browseRecs.count();
      int horizontalfield = fields.count();
      if (DEBUG_WORKING) {
      QMessageBox::information(this, tr("Alert Error!"),QString( "row %1 - %3 x %2 table = %4 " ).arg( int2char( vertikalrow ) , int2char( horizontalfield ) , int2char( datanum ) , tablename   )); 
      }
      master_table->setRowCount(vertikalrow);
      master_table->setColumnCount(horizontalfield);
      master_table->setHorizontalHeaderLabels(fields);
      
     if( datanum > 0) {
            for(int i=0; i < datanum; i++) {
            QStringList linenow =  browseRecs.value(i);
             /* file_put_contents_append("sale.dat", QString( "%1 ---> %2" ).arg( int2char( linenow.size() ) , int2char( i ) ) ); */
            for (int h = 0; h < linenow.size(); ++h) { 
                coolpos++;
                if (coolpos > 0 && coolpos < horizontalfield +1) {
                QString dat = QString(linenow.at( coolpos ));
                if (DEBUG_WORKING) {
                rowdat = new QTableWidgetItem(QString( "%1-%2" ).arg( dat , int2char( coolpos  ) ) );
                } else {
                rowdat = new QTableWidgetItem(dat);   
                }
                master_table->setItem(i,coolpos - 1 ,rowdat);
                if (DEBUG_WORKING) {
                file_put_contents_append("sale.dat", QString( "dat = %1 /-/ pos = %2 " ).arg( dat , int2char( h ) )); 
                }
                }
              
            }
             coolpos=0; 
               if (DEBUG_WORKING) {
               file_put_contents_append("sale.dat","----------------------------------");
               }
            }
         
     }
        

      
     } else {
      master_table->clear();
      QMessageBox::information(this, tr("Alert Error!"),tr("Unable to open table! ")+tablename); 
     }
     QApplication::restoreOverrideCursor(); 
   } /* select table ::: */
}



void GuiMain::RepaintQuery()
{
    QApplication::setOverrideCursor(QCursor(Qt::WaitCursor));
    dumptofile("soquery.xml");
    textquery1->clear();
    textquery1->insertPlainText(file_get_contents("soquery.xml"));
    textquery1->toPlainText();
    QApplication::restoreOverrideCursor();  
}

QGroupBox *GuiMain::PaintCentral()
{
    QGroupBox *groupBox = new QGroupBox(tr("Special Port sqlite browser qt3=>qt4"));
    /* QPushButton *toolButton = new QPushButton(tr("faktura")); */
    /* QLineEdit *linetext = new QLineEdit(groupBox); */
    /* QToolButton *toolButton = new QToolButton(groupBox); */
    
    QAction *execquery = new QAction(this);
    execquery->setText(tr("Start Query Dump to file"));
    execquery->setStatusTip(tr("Start a new Query Dump and otput the result")); 
    connect(execquery, SIGNAL(activated()), this,  SLOT(RepaintQuery()));

    QAction *exitall = new QAction(this);
    exitall->setText(tr("Exit"));
    exitall->setStatusTip(tr("Exit and close")); 
    connect(exitall, SIGNAL(activated()), this,  SLOT(close()));
    
    QPushButton *toolButton = new QPushButton(tr("DB Connect"));;
    QMenu *menu = new QMenu(this);
    menu->addAction(execquery);
    menu->addAction(exitall);
    toolButton->setMenu(menu);
    QVBoxLayout *vbox = new QVBoxLayout;
    vbox->addWidget(toolButton);
    vbox->addStretch(1);
    groupBox->setLayout(vbox);
    return groupBox;
}








void GuiMain::closeEvent( QCloseEvent* e )
{
    
	e->accept();
}
