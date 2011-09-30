#include "interface.h"
#include "index_dir_doc.h"
#include <QCloseEvent>
#include <QFileDialog>
#include <QStackedWidget>
#include <QDockWidget>
#include <QWebFrame>
#include <QDesktopServices>
#include <QNetworkRequest>
#include <QNetworkReply>


using namespace std;



QPointer<Comand_W> Comand_W::_self = 0L;

Comand_W* Comand_W::self( QWidget* parent )
{
	if ( !_self )
	_self = new Comand_W( parent );
	return _self;
}


Comand_W::Comand_W( QWidget* parent )
	: QWidget( parent )
{
	setupUi( this );
}








QPointer<Interface> Interface::_istance = 0L;
Interface* Interface::self( QWidget* parent  )
{
	if ( !_istance )
	_istance = new Interface( parent );
	return _istance;
}
Interface::Interface( QWidget* parent )
	: QMainWindow( parent )
{
	comand = Comand_W::self();
	comand->progressread->setValue(0);
	Onsearch = false;
	
	CurrentSearchWord = _NULL_;
	Currentanchor = _NULL_;
	CurrentUri = _NULL_;
	
	setupUi( this );
	docBrowser->page()->setLinkDelegationPolicy(QWebPage::DelegateAllLinks);  /* delegate link request */
	Load_Connector();
	dock = new QDockWidget(tr("Search Manual"),this);
    dock->setAllowedAreas(Qt::LeftDockWidgetArea | Qt::RightDockWidgetArea);
    dock->setWidget(comand);
    addDockWidget(Qt::LeftDockWidgetArea, dock);
	menuIndex_action->addAction(dock->toggleViewAction());
	rundir = new Index_Dir_Doc();
	int record2 = rundir->CountItem(QString("manual"));
	if (record2 > 0) {
		UseVirtualTable = true;
	} else {
		UseVirtualTable = false;
	}
	
	docBrowser->page()->setForwardUnsupportedContent(true);

	connect(actionSetting, SIGNAL(triggered()), this, SLOT(configure_dir()));
	connect(actionVirtual, SIGNAL(triggered()), this, SLOT(make_virtual()));
	connect(actionTruncatedb, SIGNAL(triggered()), this, SLOT(truncate_table()));
	
	
	comand->groupBox_2->hide ();  //// comand->groupBox_2->setVisible ( true );
	connect(actionIndexing_now, SIGNAL(triggered()), this, SLOT(index_dir()));
	connect(comand->Wordfind, SIGNAL(textEdited(QString)), this, SLOT(active_search(QString)));
	connect(comand->listResult, SIGNAL(itemClicked(QListWidgetItem *)), this, SLOT(list_select(QListWidgetItem *)));
	connect(docBrowser, SIGNAL(loadFinished(bool)), this, SLOT(manual_load(bool)));
	///////connect(docBrowser->page(), SIGNAL(downloadRequested(const QNetworkRequest&)), this, SLOT(downloadRequested(const QNetworkRequest&)));
	connect(docBrowser->page(),SIGNAL(downloadRequested(QNetworkRequest)),this, SLOT(downloadRequested(QNetworkRequest)));
	connect(docBrowser->page(),SIGNAL(linkClicked(QUrl)),this, SLOT(url_Controller(QUrl)));
	connect(docBrowser->page(),SIGNAL(linkHovered(QString,QString,QString)),this, SLOT(linkactive(QString,QString,QString)));
	connect(docBrowser->page(),SIGNAL(unsupportedContent(QNetworkReply *)),this, SLOT(tryhandle(QNetworkReply *)));
	connect(docBrowser->page(),SIGNAL(loadFinished(bool)),this, SLOT(loadFinished(bool)));
	
	
	
	Box_att.clear();
	Box_att.insert(0,QStringList() << "http://www.google.com/" <<  "Online: Google main index");
	Box_att.insert(11,QStringList() << "http://www.google.com/codesearch" <<  "Online: code search");
	
	Box_att.insert(1,QStringList() << "http://www.php.net/manual/en/" <<  "Online: PHP Manual online");
	Box_att.insert(2,QStringList() << "http://dev.mysql.com/doc/" <<  "Online: Mysql online manual");
	Box_att.insert(3,QStringList() << "http://docs.python.org/" <<  "Online: Python online manual");
	Box_att.insert(4,QStringList() << "http://doc.qt.nokia.com/" <<  "Online: QT4 online manual");
	Box_att.insert(5,QStringList() << "http://www.w3.org/" <<  "Online: W3C specification");
	Box_att.insert(6,QStringList() << "http://aktuell.de.selfhtml.org/" <<  "Online: Selfhtml german");
	Box_att.insert(7,QStringList() << "http://docs.wxwidgets.org/stable/" <<  "Online: wxWidgets 2.8.12");
	Box_att.insert(8,QStringList() << "http://download.oracle.com/javase/6/docs/" <<  "Online: Java doc");
	Box_att.insert(9,QStringList() << "http://www.zvon.org/xxl/xslfoReference/Output/index.html" <<  "Online: XSL FO reference");
	Box_att.insert(10,QStringList() << "http://xmlgraphics.apache.org/fop/quickstartguide.html" <<  "Online: Apache fop reference");
	
	Logvisible(QString("Inizialise."));
	
	
}

void Interface::loadFinished( bool e )
{
	QWebFrame *frame = docBrowser->page()->currentFrame();
	if (Currentanchor !=_NULL_ && e) {
		frame->scrollToAnchor(Currentanchor);
		Currentanchor = _NULL_;
	}
	
}

/* incomming request url to handle redirect or waht else */
void Interface::url_Controller(const QUrl &u) 
{
	Currentanchor = _NULL_;
	CurrentUri = _NULL_;
	QWebFrame *frame = docBrowser->page()->currentFrame();
	const QString url_request = u.toString(QUrl::None);
	Logvisible(QString("Url request:%1").arg(url_request));
	const QString url_now = frame->url().toString(QUrl::None);
	const QString she = u.scheme();
	/* handle first only fragment if having! */
	if (u.hasFragment()) {
		Currentanchor = u.fragment();
		int anchorsize = Currentanchor.size() + 1; /* strip #*/  
		QString urlrewrite = url_request.left(url_request.size() - anchorsize);
		if (url_request.contains(url_now)) {
		frame->scrollToAnchor(Currentanchor);
		Currentanchor = _NULL_;
	    } else {
		docBrowser->setUrl(QUrl(urlrewrite));  /* at load end go anchor !!!! */
		}
		return;
	}
	
	QFileInfo fi(u.toLocalFile());
	/* local file handle */
	if (fi.isFile() && fi.isReadable() ) {
		   /* if html or pdf ?? */
		    const QString ext = fi.suffix().toLower();
		    if (ext.contains("html") || ext.contains("htm")) {
				docBrowser->setUrl(u);
			}  else if (ext.contains("pdf")) {
				 /* load default pdf display acro or */
				 QDesktopServices::openUrl(QUrl::fromLocalFile(fi.absoluteFilePath()));
			}
     } else if (u.isValid()) {
		 
		 docBrowser->setUrl(u);
		 
		 
	 } else if (!u.isValid()) {
		qWarning("url not valid report!");
		Load_404();
		 
	 }
}



void Interface::downloadRequested( const QNetworkRequest &e ) 
{
	
	qWarning("Download Request");
	QString defaultFileName = QFileInfo(e.url().toString()).fileName();
    /////QString defaultFileName = QFileInfo(e.url().toString()).fileName();
    cout << APPNAME << " downloadRequested " <<   qPrintable(defaultFileName)  << "......." << endl;
    Load_404();
	
}
  
void Interface::manual_load( bool e  ) 
{
	
	if (CurrentSearchWord != _NULL_ && e) {
	docBrowser->findText(CurrentSearchWord,QWebPage::HighlightAllOccurrences);
    }
}

void Interface::list_select( QListWidgetItem * uri ) {
	
	////cout << APPNAME << " QListWidgetItem click " <<   qPrintable(uri->text())  << "......." << endl;
	dock->setVisible(true);
	QString file = uri->data(11).toString();
	QFileInfo fi(file);
	QUrl ruri(file);
	/* local file handle */
	if (fi.isFile() && fi.isReadable() ) {
		   /* if html or pdf ?? */
		    const QString ext = fi.suffix().toLower();
		    if (ext.contains("html") || ext.contains("htm")) {
				docBrowser->setUrl(QUrl(file));
			}  else if (ext.contains("pdf")) {
				 /* load default pdf display acro or */
				 QDesktopServices::openUrl(QUrl::fromLocalFile(file));
			}
     } else if (ruri.isValid()) {
		 docBrowser->setUrl(ruri);
	 }
	 
	 Logvisible(QString("Request uri send:%1").arg(file));
	
}

void Interface::make_virtual() 
{
	if (Onsearch) {
		return;
	}
	if (!rundir->onwork()) {
	 rundir = new Index_Dir_Doc();
	}
	int totnormal = rundir->CountItem(QString("fileindex"));
	if (totnormal < 5) {
		return;
	}
	
	dock->setVisible(true);
	comand->listResult->clear();
	comand->groupBox_2->setEnabled(true);
	comand->groupBox_2->setVisible ( true );
	UseVirtualTable = false;
	comand->progressread->setValue(0);
	QSqlDatabase db = rundir->get_db();
	
	    CreateVirtualtable *setvirtual = new CreateVirtualtable();
        setvirtual->Setting(db,totnormal);
		setvirtual->start(QThread::HighestPriority);
		connect(setvirtual, SIGNAL(virtualstatus(int)),comand->progressread, SLOT(setValue(int)));
	    connect(setvirtual, SIGNAL(virtualready()),this, SLOT(checkvirtual()));
}

void Interface::checkvirtual() {
	
	int record2 = rundir->CountItem(QString("manual"));
	if (record2 > 0) {
		UseVirtualTable = true;
	} else {
		UseVirtualTable = false;
	}
	  comand->groupBox_2->setEnabled(false);
      comand->groupBox_2->hide ();
      /////cout << APPNAME << " totalindext on virtual->  " <<   record2  << "......." << endl;
}

void Interface::active_search( const QString word ) {
	
	if (Onsearch) {
		return;
	}
	comand->listResult->clear();
	///////////  void QLineEdit::editingFinished ()
	Onsearch = true;
	dock->setVisible(true);
	////cout << APPNAME << " active_search init  " <<   qPrintable(word)  << "......." << endl;
	if (!rundir->onwork()) {
	 rundir = new Index_Dir_Doc();
	}
	CurrentSearchWord = word;
	QString name = QString("");
	        name.append(word);
	        name.append(QString("%"));
	        
	QString name2 = QString("");
	        name2.append(word);
	        name2.append(QString("_"));
	        
	QSqlDatabase db = rundir->get_db();
	///////int record2 = rundir->CountItem(QString("manual"));  /* count if virtualtable exist */
	/////int record = rundir->CountItem(QString("fileindex"));
	QString sqlsearch = QString("SELECT fullpath,title FROM fileindex where txtgrep LIKE '%2' or txtgrep LIKE '%1' or title LIKE '%1' or name LIKE '%1' limit 150").arg(name,name2);
	 //////////   QString steep1 = QString("CREATE VIRTUAL TABLE manual USING fts3(title,body,uri)");
	 if (UseVirtualTable) {
	 sqlsearch = QString("SELECT uri as fullpath,title FROM manual WHERE manual MATCH '%1*' limit 200").arg(word);
     }
	 
	 QSqlQuery query(db);
	 int cas = 0 - 1;
		        if (query.exec(sqlsearch)) {
					 
					  int pa = query.record().indexOf("fullpath");
					  int tit = query.record().indexOf("title");
					     while (query.next()) {
							     cas++;
                                 QString filep = query.value(pa).toString();
                                 QString title = query.value(tit).toString();
							////cout << cas <<  "file ->" << qPrintable(squote(title)) << endl;
							///////cout << "file ->" << qPrintable(squote(filep)) << endl;
							QListWidgetItem *dd = new QListWidgetItem(title);
							dd->setData(11,filep);
							comand->listResult->addItem(dd);
						}  
		         }
		         
		    if (NetworkEnable()) {
		         
					  TableUri::Iterator it;
					  for ( it = Box_att.begin(); it != Box_att.end(); ++it ) { 
						QStringList itemsetter = it.value(); 
						QString Turi = itemsetter.at(0);
						QString Tname = itemsetter.at(1);
					   ///// cout << APPNAME << " 34 Turi  " <<   qPrintable(Turi)  << "......." << endl;
						
										QListWidgetItem *dd = new QListWidgetItem(Tname);
										dd->setData(11,Turi);
										comand->listResult->addItem(dd);
					   }
		    }   
	
	
	/////cout << APPNAME << " active_search end  " <<   qPrintable(word)  << "......." << endl;
	Onsearch = false;
}

void Interface::index_dir() {
	
	dock->setVisible(true);
	/* start to read and indexing dir  comand->groupBox_2->setVisible ( true ); */
	
	
	if (!rundir->onwork()) {
	 rundir = new Index_Dir_Doc();
	}
	comand->groupBox_2->setEnabled(true);
	comand->groupBox_2->setVisible ( true );
	QSettings settings;
    QString issetting = QDir::homePath();
     if (settings.value("DirDeafault/path").toString()!=_NULL_) {
		 issetting = settings.value("DirDeafault/path").toString();
	 }
	 
	 rundir->Setdir(issetting);
     connect(comand->AbortRead, SIGNAL(clicked()), this, SLOT(indexing_abort()));
     rundir->initread();
     connect(rundir, SIGNAL(done()), this, SLOT(indexing_abort()));
     connect(rundir, SIGNAL(status(int)),comand->progressread, SLOT(setValue(int)));
     connect(rundir, SIGNAL(read_path(QString)),comand->readDir, SLOT(setText(QString)));
     connect(rundir, SIGNAL(read_error(QString)),comand->readDir, SLOT(setText(QString)));
	 cout << APPNAME << " read from dir" <<   qPrintable(issetting)  << "......." << endl;
}

void Interface::indexing_abort() {
	  /* stop it from progress dialog */
	  if (rundir->onwork()) {
	  rundir->abort();
      }
      comand->groupBox_2->setEnabled(false);
      comand->groupBox_2->hide ();
}

void Interface::configure_dir()
{
	dock->setVisible(true);
	 /* configure which dir to read */
	QSettings settings;
    QString issetting = QDir::homePath();
     if (settings.value("DirDeafault/path").toString()!=_NULL_) {
		 issetting = settings.value("DirDeafault/path").toString();
	 }
    QString rdir = QFileDialog::getExistingDirectory(this, tr("Read from Directory;"),
                                                 issetting,
                                                 QFileDialog::ShowDirsOnly
                                                 | QFileDialog::DontResolveSymlinks);
    cout << APPNAME << " read from dir" <<   qPrintable(rdir)  << "......." << endl;
     QDir dir(rdir);
     if (dir.exists())  {   
	   settings.setValue("DirDeafault/path",rdir);
	 }
	 
	  QString enddb = QString("%1/database.db").arg(QDir::currentPath());
	  hardcopy(DBfileSQLITE,enddb);
	  cout << APPNAME << " try copy database to  ->" <<   qPrintable(enddb)  << "......." << endl;
	 
	 
}


void Interface::tryhandle( QNetworkReply * e )
{
	qWarning("tryhandle_unsupported Request");
	const QString uri = e->url().toString();
    QString content(
        "<html>\n"
        "<head>\n"
        "  <title>" + uri + "</title>\n"
        "  <style type=\"text/css\">\n"
        "  body { background-color: #FFF5EE ; color: #FF0000 }\n"
        "  </style>\n"
        "</head>\n\n"
        "<body>\n"
        "<center><h1>Unable to handle uri " + uri + " redirect to your standard Browser!</h1></center>\n\n"
        "</body>\n"
        "</html>\n");
	docBrowser->setHtml(content);
	QDesktopServices::openUrl(QUrl(uri));
}


void Interface::Load_404()
{
	////  if (NetworkEnable()) {
	    QString content(
        "<html>\n"
        "<head>\n"
        "  <title>Error 404</title>\n"
        "  <style type=\"text/css\">\n"
        "  body { background-color: #FFF5EE ; color: #FF0000 }\n"
        "  </style>\n"
        "</head>\n\n"
        "<body>\n"
        "<center><h1>Network is offline.</h1><h3>You can search on local manual file.</h3>" + NetworkItem() + "</center>\n\n"
        "</body>\n"
        "</html>\n");
	     docBrowser->setHtml(content);
}



void Interface::Load_Connector()
{
    if (NetworkEnable()) {
    docBrowser->setUrl(QUrl("http://www.google.com/"));
    } else {
		Load_404();
	}
}

void Interface::linkactive( const QString a ,const QString b ,const QString c )
{
	
	Q_UNUSED(b);
	Q_UNUSED(c);
	
	if (a!=_NULL_) {
	QString message = a; ////QString("Hover action link = %1  text=%3 title=%2").arg(a,b,c);
	Logvisible(message);
    }
	
}


void Interface::Logvisible( const QString txt )
{
	 QString message = QString("      ");
	         message.prepend(txt);
     MSG->setText( message );
}

void Interface::truncate_table() {
	Onsearch = true;
	if (!rundir->onwork()) {
	 rundir = new Index_Dir_Doc();
	}
	
	  /* back up db */
	  QString tir = QDateTime::currentDateTime().toString("dd.MM.yyyy");
	  QString enddb = QString("%1/database_%2.db").arg(QDir::currentPath(),tir);
	  hardcopy(DBfileSQLITE,enddb);
	  
	  
	        QFile suor(DBfileSQLITE); 
	        if (suor.exists()) {
				suor.remove();
			}
	  
	
	  rundir->init_db();
	  Onsearch = false;
	
}









/*
void Interface::closeEvent( QCloseEvent* e )
{
	e->accept();
}
*/
