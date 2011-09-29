#ifndef OSWIN32_H
#define OSWIN32_H

#include "_tools.h"

#if defined Q_WS_WIN

#define COMMAND "cmd"
#define CLEANCONSOLE "pause"
#include "windows.h"
#include <winbase.h>

 //For some reason libxslt has trouble handling filenames with spaces on Unix platforms (OSX,
  //Linux, FreeBSD?). this problem can be averted by converting the filename to a URI. Converting it
  //to a URI on windows using the qt method mangles the drive name though, so only convert to
  //URI on OSX. We need to nail this weirdness at some point and be consistant IMHO but for now
  //this works...  and QDir::homePath()  as musch spaces.....  
  // create & copy to home dir! and delete created file....


#ifndef WORK_CACHEDIR
#define WORK_CACHEDIR \
              QString( "%1/.%2/" ).arg( QDir::homePath() , _PROGRAM_SHORT_NAME )
#endif
#define SHORTFILE \
             QString( "C:/_apache.xml" )
#define INCOMMINGFILE \
             QString( "C:/_incomming.xml" )
#define CONVERTERFILE \
             QString( "C:/_converter.xml" )
#define RESULT_PDF_FILE \
             QString( "C:/_export.pdf" )



#define LASTDIROPENACTION \
              QString( "%1_lastdir.dat" ).arg( WORK_CACHEDIR )


    
             
             
             
             
             

#endif
#endif

