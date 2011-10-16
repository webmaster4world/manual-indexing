#include <QtGui>
#include <QApplication>
#include "xmlhighlighter.h"


int main(int argc, char *argv[])
{
    QApplication a(argc, argv);
    a.setOrganizationName("Svizzerotto");
    a.setOrganizationDomain("paner.com");
    a.setApplicationName("Xml format");
    
    qDebug() << "### main in.."; 
    
    XMLTextEdit w;
    w.show();
    a.connect(&a, SIGNAL(lastWindowClosed()), &a, SLOT(quit()));
    return a.exec();
}
