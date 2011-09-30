/********************************************************************************
** Form generated from reading UI file 'form.ui'
**
** Created: Fri Sep 30 10:43:05 2011
**      by: Qt User Interface Compiler version 4.7.2
**
** WARNING! All changes made in this file will be lost when recompiling UI file!
********************************************************************************/

#ifndef UI_FORM_H
#define UI_FORM_H

#include <QtCore/QVariant>
#include <QtGui/QAction>
#include <QtGui/QApplication>
#include <QtGui/QButtonGroup>
#include <QtGui/QGridLayout>
#include <QtGui/QGroupBox>
#include <QtGui/QHeaderView>
#include <QtGui/QLabel>
#include <QtGui/QLineEdit>
#include <QtGui/QListWidget>
#include <QtGui/QProgressBar>
#include <QtGui/QPushButton>
#include <QtGui/QSpacerItem>
#include <QtGui/QWidget>

QT_BEGIN_NAMESPACE

class Ui_Comand_W
{
public:
    QGridLayout *gridLayout_3;
    QGroupBox *groupBox;
    QGridLayout *gridLayout_2;
    QLabel *label;
    QLineEdit *Wordfind;
    QListWidget *listResult;
    QGroupBox *groupBox_2;
    QGridLayout *gridLayout;
    QProgressBar *progressread;
    QSpacerItem *horizontalSpacer;
    QPushButton *AbortRead;
    QLineEdit *readDir;

    void setupUi(QWidget *Comand_W)
    {
        if (Comand_W->objectName().isEmpty())
            Comand_W->setObjectName(QString::fromUtf8("Comand_W"));
        Comand_W->resize(345, 595);
        gridLayout_3 = new QGridLayout(Comand_W);
        gridLayout_3->setObjectName(QString::fromUtf8("gridLayout_3"));
        groupBox = new QGroupBox(Comand_W);
        groupBox->setObjectName(QString::fromUtf8("groupBox"));
        groupBox->setMaximumSize(QSize(300, 16777215));
        gridLayout_2 = new QGridLayout(groupBox);
        gridLayout_2->setObjectName(QString::fromUtf8("gridLayout_2"));
        label = new QLabel(groupBox);
        label->setObjectName(QString::fromUtf8("label"));

        gridLayout_2->addWidget(label, 0, 0, 1, 1);

        Wordfind = new QLineEdit(groupBox);
        Wordfind->setObjectName(QString::fromUtf8("Wordfind"));

        gridLayout_2->addWidget(Wordfind, 1, 0, 1, 1);

        listResult = new QListWidget(groupBox);
        listResult->setObjectName(QString::fromUtf8("listResult"));
        listResult->setMinimumSize(QSize(200, 0));
        listResult->setMaximumSize(QSize(2000, 16777215));

        gridLayout_2->addWidget(listResult, 2, 0, 1, 1);

        groupBox_2 = new QGroupBox(groupBox);
        groupBox_2->setObjectName(QString::fromUtf8("groupBox_2"));
        groupBox_2->setEnabled(false);
        groupBox_2->setFlat(false);
        gridLayout = new QGridLayout(groupBox_2);
        gridLayout->setObjectName(QString::fromUtf8("gridLayout"));
        progressread = new QProgressBar(groupBox_2);
        progressread->setObjectName(QString::fromUtf8("progressread"));
        progressread->setValue(24);

        gridLayout->addWidget(progressread, 0, 0, 1, 2);

        horizontalSpacer = new QSpacerItem(69, 20, QSizePolicy::Expanding, QSizePolicy::Minimum);

        gridLayout->addItem(horizontalSpacer, 1, 0, 1, 1);

        AbortRead = new QPushButton(groupBox_2);
        AbortRead->setObjectName(QString::fromUtf8("AbortRead"));

        gridLayout->addWidget(AbortRead, 1, 1, 1, 1);

        readDir = new QLineEdit(groupBox_2);
        readDir->setObjectName(QString::fromUtf8("readDir"));

        gridLayout->addWidget(readDir, 2, 0, 1, 2);


        gridLayout_2->addWidget(groupBox_2, 3, 0, 1, 1);


        gridLayout_3->addWidget(groupBox, 0, 0, 1, 1);


        retranslateUi(Comand_W);

        QMetaObject::connectSlotsByName(Comand_W);
    } // setupUi

    void retranslateUi(QWidget *Comand_W)
    {
        Comand_W->setWindowTitle(QApplication::translate("Comand_W", "Form", 0, QApplication::UnicodeUTF8));
        groupBox->setTitle(QApplication::translate("Comand_W", "Index manual", 0, QApplication::UnicodeUTF8));
        label->setText(QApplication::translate("Comand_W", "Search word:", 0, QApplication::UnicodeUTF8));
        groupBox_2->setTitle(QApplication::translate("Comand_W", "Progress read:", 0, QApplication::UnicodeUTF8));
        AbortRead->setText(QApplication::translate("Comand_W", "Abort-Cancel", 0, QApplication::UnicodeUTF8));
    } // retranslateUi

};

namespace Ui {
    class Comand_W: public Ui_Comand_W {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_FORM_H
