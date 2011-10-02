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

#ifndef XSL_FO_COLORNAME_H
#define XSL_FO_COLORNAME_H

#include <QtCore>
#include <QDebug>
#include <QPixmap>
#include <QColor>
#include <QMap>
#include <QStringList>

/* svn nr. */

#define POINT_TO_CM(cm) ((cm)/28.3465058)
#define POINT_TO_MM(mm) ((mm)/2.83465058)     ////////  0.352777778
#define POINT_TO_DM(dm) ((dm)/283.465058)
#define POINT_TO_INCH(inch) ((inch)/72.0)
#define POINT_TO_PI(pi) ((pi)/12)
#define POINT_TO_DD(dd) ((dd)/154.08124)
#define POINT_TO_CC(cc) ((cc)/12.840103)

#define MM_TO_POINT(mm) ((mm)*2.83465058)
#define CM_TO_POINT(cm) ((cm)*28.3465058)     ///// 28.346456693
#define DM_TO_POINT(dm) ((dm)*283.465058)
#define INCH_TO_POINT(inch) ((inch)*72.0)
#define PI_TO_POINT(pi) ((pi)*12)
#define DD_TO_POINT(dd) ((dd)*154.08124)
#define CC_TO_POINT(cc) ((cc)*12.840103)

/*  QColor  alpha color 0 - 100%  100% = full transparent */
#define ALPHACOLPER(aa) ((aa)*2.555555)


static inline qreal OoColorAlpha( const int i )
{
    if (i > 0 && i < 101) {
    return 255 - ALPHACOLPER(i);
    } else {
    return 255;
    }
}

typedef enum
{  
  DarkColor = 100,
  LightColor = 200,
  Transparent = 300
} AlternateColor;

namespace TColor  {


class XSL_FO_ColorName 
{
    

public:	 
   explicit XSL_FO_ColorName( const QString memberID );
   QColor color( const QString colorname );
   QPixmap createColorIcon( const QString colorname );

protected:	 

   QMap<QString,QColor> foplist;   /* forever construct unsort  */
   QMap<QString,QColor> avaiablelist;  /* working item sort*/
   QStringList COLORNAME;
   QString istance; /* objekt having load this */
private:
   void record( const QString colorchunk , QColor item );
 void Load_Name();
};



}


//
#endif // XSL_FO_COLORNAME_H


