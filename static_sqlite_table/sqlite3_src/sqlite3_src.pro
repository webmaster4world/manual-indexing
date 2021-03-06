######################################################################
# Automatically generated by qmake (2.00a) gio 20. apr 11:20:03 2006
######################################################################

TEMPLATE = lib
TARGET += 
DEPENDPATH += .
INCLUDEPATH += .

TEMPLATE	=lib
CONFIG   += qt warn_off release staticlib 
LANGUAGE	= C
#DEFINES -= UNICODE
DEFINES += NDEBUG THREAD_SAFE=1 TEMP_STORE=2

DESTDIR	= ../ALLOSLIBS/
win32:TARGET	= sqlite3
unix:TARGET	= sqlite3
mac:TARGET	= sqlite3

unix {
  UI_DIR = .ui
  MOC_DIR = .moc
  OBJECTS_DIR = .obj
}
mac {
  UI_DIR = .ui
  MOC_DIR = .moc
  OBJECTS_DIR = .obj
  DEFINES += HAVE_USLEEP=1
}

unix {
  UI_DIR = .ui
  MOC_DIR = .moc
  OBJECTS_DIR = .obj
}


# Input
HEADERS += btree.h \
           config.h \
           hash.h \
           keywordhash.h \
           opcodes.h \
           os.h \
           os_common.h \
           os_test.h \
           os_unix.h \
           os_win.h \
           pager.h \
           parse.h \
           sqlite3.h \
           sqliteInt.h \
           vdbe.h \
           vdbeInt.h
SOURCES += alter.c \
           attach.c \
           auth.c \
           btree.c \
           build.c \
           date.c \
           delete.c \
           experimental.c \
           expr.c \
           func.c \
           hash.c \
           insert.c \
           legacy.c \
           main.c \
           opcodes.c \
           os_unix.c \
           os_win.c \
           pager.c \
           parse.c \
           pragma.c \
           printf.c \
           random.c \
           select.c \
           table.c \
           tokenize.c \
           trigger.c \
           update.c \
           utf.c \
           util.c \
           vacuum.c \
           vdbe.c \
           vdbeapi.c \
           vdbeaux.c \
           vdbemem.c \
           where.c
