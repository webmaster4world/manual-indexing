#include <QApplication>
#include <QWidget>
#include "sqltable.h"

#include "main.h"
#include "_tools.h"
#include "sqltable.h"

int main(int argc, char *argv[]) {
    
    QApplication a( argc, argv );
    
    QDir dir(WORK_CACHEDIR);
    if ( dir.mkpath(WORK_CACHEDIR) ) { } else {
    QMessageBox::warning( 0, "File error!", "Not possibel to create a work dir on: "+WORK_CACHEDIR);
	return 0;
    }       
    
    
	GuiMain::self()->show();
    a.connect( &a, SIGNAL( lastWindowClosed() ), &a, SLOT( quit() ) );
    return a.exec();
};



