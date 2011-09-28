/********************************************************************************
** Form generated from reading UI file 'interface.ui'
**
** Created: Tue Sep 27 11:27:24 2011
**      by: Qt User Interface Compiler version 4.7.2
**
** WARNING! All changes made in this file will be lost when recompiling UI file!
********************************************************************************/

#ifndef UI_INTERFACE_H
#define UI_INTERFACE_H

#include <QtCore/QVariant>
#include <QtGui/QAction>
#include <QtGui/QApplication>
#include <QtGui/QButtonGroup>
#include <QtGui/QHeaderView>
#include <QtGui/QMainWindow>
#include <QtGui/QMenu>
#include <QtGui/QMenuBar>
#include <QtGui/QStatusBar>
#include <QtGui/QWidget>
#include <QtWebKit/QWebView>
#include <QStackedWidget>
#include <QStackedWidget>
#include "ui_form.h"

 
 
 
 
 
 
 
 
QT_BEGIN_NAMESPACE

class Ui_Interface
{
public:
    QAction *actionExit;
    QAction *actionSetting;
    QAction *actionIndexing_now;
    QAction *actionVirtual;
    QAction *actionTruncatedb;
    /////QWidget *centralwidget;
    QWebView *docBrowser;
    QMenuBar *menubar;
    QMenu *menuFile;
    QMenu *menuIndex_action;
    QStatusBar *statusbar;
    QLabel *MSG; 
    /////  MSG = new QLabel( QString::fromUtf8("  EDIT  ") );

    void setupUi(QMainWindow *Interface)
    {
        if (Interface->objectName().isEmpty())
            Interface->setObjectName(QString::fromUtf8("Interface"));
        Interface->resize(800, 600);
        QSizePolicy sizePolicy(QSizePolicy::Maximum, QSizePolicy::Maximum);
        sizePolicy.setHorizontalStretch(0);
        sizePolicy.setVerticalStretch(0);
        sizePolicy.setHeightForWidth(Interface->sizePolicy().hasHeightForWidth());
        Interface->setSizePolicy(sizePolicy);
        actionVirtual = new QAction(Interface);
        actionVirtual->setObjectName(QString::fromUtf8("actionVirtual"));
        actionExit = new QAction(Interface);
        actionExit->setObjectName(QString::fromUtf8("actionExit"));
        actionExit->setCheckable(false);
        actionExit->setSoftKeyRole(QAction::PositiveSoftKey);
        actionSetting = new QAction(Interface);
        actionSetting->setObjectName(QString::fromUtf8("actionSetting"));
        actionIndexing_now = new QAction(Interface);
        actionIndexing_now->setObjectName(QString::fromUtf8("actionIndexing_now"));

        actionTruncatedb = new QAction(Interface);
        actionTruncatedb->setObjectName(QString::fromUtf8("actionTruncatedb"));
        
        docBrowser = new QWebView(Interface);
        docBrowser->setObjectName(QString::fromUtf8("docBrowser"));
        docBrowser->setGeometry(QRect(9, 9, 800, 600));
        sizePolicy.setHeightForWidth(docBrowser->sizePolicy().hasHeightForWidth());
        docBrowser->setSizePolicy(sizePolicy);
        docBrowser->setMinimumSize(QSize(800, 600));
        docBrowser->setAcceptDrops(true);
        docBrowser->setAutoFillBackground(false);
        docBrowser->setUrl(QUrl("http://www.google.com/"));
        Interface->setCentralWidget(docBrowser);

        
        
        
        menubar = new QMenuBar(Interface);
        menubar->setObjectName(QString::fromUtf8("menubar"));
        menubar->setGeometry(QRect(0, 0, 824, 25));
        menuFile = new QMenu(menubar);
        menuFile->setObjectName(QString::fromUtf8("menuFile"));

        menuIndex_action = new QMenu(menubar);
        menuIndex_action->setObjectName(QString::fromUtf8("menuIndex_action"));
        Interface->setMenuBar(menubar);
        statusbar = new QStatusBar(Interface);
        statusbar->setObjectName(QString::fromUtf8("statusbar"));
        Interface->setStatusBar(statusbar);
        
        MSG = new QLabel( QString::fromUtf8("Message:") );
        MSG->setMinimumSize( MSG->sizeHint() );
        MSG->setAlignment( Qt::AlignCenter );
        MSG->setText( QString::fromUtf8("Message:") );
        MSG->setToolTip( QString::fromUtf8("The current working mode.") );
        statusbar->addPermanentWidget( MSG );
        
        

        menubar->addAction(menuFile->menuAction());
        menubar->addAction(menuIndex_action->menuAction());
        menuFile->addAction(actionExit);
        menuIndex_action->addAction(actionSetting);
        
        menuIndex_action->addAction(actionVirtual);
        menuIndex_action->addAction(actionIndexing_now);
        menuIndex_action->addAction(actionTruncatedb);
        
        
        retranslateUi(Interface);
        QObject::connect(actionExit, SIGNAL(triggered()), Interface, SLOT(close()));

        QMetaObject::connectSlotsByName(Interface);
    } // setupUi

    void retranslateUi(QMainWindow *Interface)
    {
        Interface->setWindowTitle(QApplication::translate("Interface", "MainWindow", 0, QApplication::UnicodeUTF8));
        actionExit->setText(QApplication::translate("Interface", "Exit", 0, QApplication::UnicodeUTF8));
        actionVirtual->setText(QApplication::translate("Interface", "Build Virtual table, fast search", 0, QApplication::UnicodeUTF8));
        actionTruncatedb->setText(QApplication::translate("Interface", "Truncate all table on Database", 0, QApplication::UnicodeUTF8));
        actionSetting->setText(QApplication::translate("Interface", "Setting indexing dir - path", 0, QApplication::UnicodeUTF8));
        actionIndexing_now->setText(QApplication::translate("Interface", "Search indexing now all file", 0, QApplication::UnicodeUTF8));
        menuFile->setTitle(QApplication::translate("Interface", "File", 0, QApplication::UnicodeUTF8));
        menuIndex_action->setTitle(QApplication::translate("Interface", "Index action", 0, QApplication::UnicodeUTF8));
    } // retranslateUi

};

namespace Form {
    class Interface: public Ui_Interface {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_INTERFACE_H
