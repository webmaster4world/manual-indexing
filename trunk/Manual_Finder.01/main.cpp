
#include "interface.h"
#include "main.h"


#include <QApplication>
#include <QtGui>


int main(int argc, char *argv[]) {
	
	QApplication a( argc, argv );
	
				QCoreApplication::setOrganizationName(_ORGANIZATION_NAME_);
				QCoreApplication::setOrganizationDomain(_PROGRAM_NAME_DOMAINE_);
				QCoreApplication::setApplicationName(_PROGRAM_NAME_);
				
      Interface::self()->setWindowTitle( _PROGRAM_NAME_ );
	  Interface::self()->show();
      a.connect( &a, SIGNAL( lastWindowClosed() ), &a, SLOT( quit() ) );
      return a.exec();
    
}







