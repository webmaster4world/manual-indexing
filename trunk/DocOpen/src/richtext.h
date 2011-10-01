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



#ifndef RICHTEXT_H
#define RICHTEXT_H

#include <stdio.h>
#include <iostream>
#include <QtCore>
#include <QDebug>
#include <QObject>

/* remote item if need */
#include "getitem.h"

#define MAGICNUMMERDATA 0x48611928
#define MMVERSION 2
#define _NULL_ \
             QString("")  


class DocPage
{
public:
  DocPage();
  DocPage& operator=( const DocPage& d );
  operator QVariant() const
  {
    return QVariant::fromValue(*this);
  }
          qint64 sizebyte;
          QString Title;
		  QString mimetipe;
		  QString filefull; 
		  QString doc;
};

/*
 * DocPage current;
 *         current.filefull = 
 * 
 * */

Q_DECLARE_METATYPE(DocPage);


inline QDebug operator<<(QDebug debug, DocPage& udoc)
{
    debug.nospace() << "DocPage(Enable."
    << udoc.Title << ",Title(),"
    << udoc.filefull << ",Path(),"
    << udoc.mimetipe << ",mimetipe(),"
    << udoc.sizebyte << ",sizebyte() )";
    return debug.space();
}


inline QDataStream& operator<<(QDataStream& out, const DocPage& udoc)
{
    out << udoc.Title;
    out << udoc.filefull;
    out << udoc.mimetipe;
    out << udoc.sizebyte;
    out << udoc.doc;
    return out;
}


inline QDataStream& operator>>(QDataStream& in, DocPage& udoc)
{
    in >> udoc.Title;
    in >> udoc.filefull;
    in >> udoc.mimetipe;
    in >> udoc.sizebyte;
    in >> udoc.doc;
    return in;
}

/////qRegisterMetaTypeStreamOperators<DocPage>("DocPage");



class DocLoader : public QThread
{
    Q_OBJECT
     
public:
  void Setting( QObject *parent , DocPage x ); 
protected:
  void run();
  signals:
    void setText(QString);
private:
    DocPage Udoc;
    QObject* receiver;
};








/* RichTextIstance::self()->load( const QString file ); */

/* static class to use from all to trasform doc */

class RichTextIstance : public QObject
{
     Q_OBJECT

public:	 
  static RichTextIstance* self();
  void load( const QString fi , QObject *sender ); /* file to load */

protected:	 

 
private:
    RichTextIstance(int mi);
    int cicle;
    QStringList docentry;
   static QPointer<RichTextIstance> _self;
   void Load_Connector();
   
signals:

public slots:

private slots:

};




//
#endif // RICHTEXT_H

