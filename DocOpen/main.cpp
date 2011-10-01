/***********************************************************************
 *
 * Copyright (C) 2011 Peter Hohl <pehohlva@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 ***********************************************************************/


#include <QApplication>
#include <QtGui>

#include "richtext.h"

#if defined _COMPOSE_STATIC_
#include <QtPlugin>
#if defined _USE_qjpeg
Q_IMPORT_PLUGIN(qjpeg)
#endif
#if defined _USE_qmng
Q_IMPORT_PLUGIN(qmng)
#endif
#if defined _USE_qgif
Q_IMPORT_PLUGIN(qgif)
#endif
#if defined _USE_qtiff
Q_IMPORT_PLUGIN(qtiff)
#endif
#endif


int main( int argc, char ** argv )
{
    QApplication a( argc, argv );
    a.setOrganizationName("Oasis Test Reader");
    a.setOrganizationDomain("QTuser");
    a.setApplicationName("Mini Office");
    #if QT_VERSION >= 0x040500
    qDebug() << "### QT_VERSION main  -> " << QT_VERSION;
    qDebug() << "### QT_VERSION_STR main -> " << QT_VERSION_STR;
    #endif
    QTextEdit mw;
    mw.resize( 700, 450 );
    RichTextIstance::self()->load(QString("hello.rtf"),&mw);
    
    
    mw.show();
    QObject::connect(&a, SIGNAL(lastWindowClosed()), &a, SLOT(quit()));
    return a.exec();
}
