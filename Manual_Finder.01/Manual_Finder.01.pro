######################################################################
# Automatically generated by qmake (2.01a) mer set 28 07:51:21 2011
###################################################################### debug console

TEMPLATE = app
TARGET = _ManualGrep
DEPENDPATH += . handler  ui
INCLUDEPATH += . handler


CONFIG +=  qt release
CONFIG   += qt warn_on 
QT           += sql webkit network
MOC_DIR	= build/_moc
RCC_DIR	= build/_rcc
OBJECTS_DIR = build/_obj

UI_DIR	= ui


DESTDIR	+= ./

# Input
HEADERS += interface.h \
           interface_start.h \
           main.h \
           handler/handleonefileaction.h \
           handler/index_dir_doc.h
FORMS += ui/form.ui
SOURCES += interface.cpp \
           main.cpp \
           handler/handleonefileaction.cpp \
           handler/index_dir_doc.cpp
