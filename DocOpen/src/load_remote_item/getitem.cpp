#include "getitem.h"
/* Load image from remote page linked on document  */

LoadGetImage::LoadGetImage( const QString uniqueID , QUrl url_send  )
		: QHttp(url_send.host(),QHttp::ConnectionModeHttp ,80)
{
	url = url_send;
	cid = uniqueID;
	setHost(url_send.host() , 80);
}

void LoadGetImage::Start()
{
	const QString METHOD =  "GET";
	const QString agent = QString("Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)");
	header.setRequest(METHOD,url.path(),1,1);
	header.setValue("Accept-Charset","ISO-8859-1,utf-8;q=0.7,*;q=0.7");
	header.setValue("Host",url.host());
	header.setValue("User-Agent",agent);
	connect(this, SIGNAL(done(bool)), this , SLOT(ImageReady(bool)));
	Http_id = request(header,0,0);
}

void LoadGetImage::ImageReady( bool error )
{
	if (!error) {
		resultimage.loadFromData(readAll());
		if (!resultimage.isNull()) {
			emit service_ready(cid);
		} else {
			emit service_error(cid);
		}
	} 
}

/* Thread to get item from document */


void ImgTheard::Setting( QObject *parent , const QString  id , QUrl url_send )
{
	receiver = parent;
	cid = id;
	url = url_send;
	setTerminationEnabled(true);
}

void ImgTheard::run()
{
	Rhttp = new LoadGetImage(cid,url);
	connect(Rhttp, SIGNAL(service_ready(QString)), receiver , SLOT(in_image(QString)));
	connect(Rhttp, SIGNAL(service_error(QString)), receiver , SLOT(in_error(QString)));
	Rhttp->Start();
	exec();
}



