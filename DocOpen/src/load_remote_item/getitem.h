#ifndef GETITEMSINGLE_H
#define GETITEMSINGLE_H

#include <stdio.h>
#include <iostream>
#include <QtCore>
#include <QDebug>
#include <QHttpRequestHeader>
#include <QDomDocument>
#include <QXmlStreamReader>
#include <QHttp>
#include <QImage>
#include <QThread>

////////


/* port 80 mode get image */
class LoadGetImage : public QHttp
{
    Q_OBJECT
//
public: 
     LoadGetImage( const QString uniqueID , QUrl url_send );
     void Start();
     inline int Htpp_id() { return Http_id; } 
     inline QImage pics() { return resultimage; } 
     QString cid;
     int Http_id;
    QHttpRequestHeader header;
    QUrl url;
    QImage resultimage;
    signals:
      void service_ready(QString);
      void service_error(QString);
    public slots:
     void ImageReady( bool error );
};




class ImgTheard : public QThread
{
    Q_OBJECT
     
public:
  void Setting( QObject *parent , const QString id , QUrl url_send ); 
protected:
  void run();
  signals:
private:
    QString cid;
    QUrl url;
    LoadGetImage *Rhttp;
    QObject* receiver;
};






















#endif
