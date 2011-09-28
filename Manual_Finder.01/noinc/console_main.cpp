

#include "main.h"


int main(int argc, char *argv[]) {
    QCoreApplication app(argc, argv); //renamed the a to app
    //////cout << APPNAME << " Welcome parti applicativo" << endl;
    Index_Dir_Doc indexdir;
    QObject::connect(&indexdir, SIGNAL( done() ), &app, SLOT( quit() ), Qt::QueuedConnection);
    QTimer::singleShot(100, &indexdir, SLOT( initread() ));
    cout << APPNAME << " say by........" << endl;
    return app.exec(); //and we run the application
}







