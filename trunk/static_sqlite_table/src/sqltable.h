#ifndef GUIMAIN_H
#define GUIMAIN_H

#include "main.h"
#include "_tools.h"
#include "sqlitedb.h"

#include <QWidget>
#include <QPointer>
#include <QMainWindow>
#include <QTableWidget>

class QGroupBox;
class QTextEdit;
class QComboBox;
class QTableWidget;
class QTableWidgetItem;
    
class GuiMain : public QMainWindow, public Sqlitedb
{
	Q_OBJECT
public:
	static GuiMain* self( QWidget* = 0 );
    QTextEdit *textquery1;
    QComboBox *table_list;
    QTableWidget *master_table;
    QTableWidgetItem *rowdat;
	//
protected:
	void closeEvent( QCloseEvent* );
	//
private:
	GuiMain( QWidget* = 0 );
	static QPointer<GuiMain> _self;
    QGroupBox *PaintCentral();
    QString lasttablename;
	//
public slots:
    void RepaintQuery();
    void PaintTable(QString tablename);

};

#endif // GUIMAIN_H


