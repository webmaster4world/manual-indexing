#ifndef OSMAC_H
#define OSMAC_H

#include "_tools.h"

#if defined Q_WS_MAC

#define COMMAND "bash"
#define CLEANCONSOLE "clear"

#ifndef WORK_CACHEDIR
#define WORK_CACHEDIR \
              QString( "%1/.%2/" ).arg( QDir::homePath() , _PROGRAM_SHORT_NAME )
#endif
#define SHORTFILE \
             QString( "%1_res.apache.xml" ).arg( WORK_CACHEDIR )
#define INCOMMINGFILE \
             QString( "%1%2_in.xml" ).arg( WORK_CACHEDIR , _PROGRAM_SHORT_NAME )
#define CONVERTERFILE \
             QString( "%1%2_con.xml" ).arg( WORK_CACHEDIR , _PROGRAM_SHORT_NAME )
#define RESULT_PDF_FILE \
             QString( "%1%2_export.pdf" ).arg( WORK_CACHEDIR , _PROGRAM_SHORT_NAME )


#define LASTDIROPENACTION \
              QString( "%1_lastdir.dat" ).arg( WORK_CACHEDIR )
           
#endif

#endif

