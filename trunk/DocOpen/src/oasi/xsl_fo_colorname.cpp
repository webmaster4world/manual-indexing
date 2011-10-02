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
 
#include "xsl_fo_colorname.h"


TColor::XSL_FO_ColorName::XSL_FO_ColorName(const QString memberID )
	: istance( memberID )
{
	Load_Name();
}

QPixmap TColor::XSL_FO_ColorName::createColorIcon( const QString colorchunk )
{
    QColor pc = color(colorchunk);
    QPixmap pixmap(50, 50);
    pixmap.fill(pc);
    return pixmap;
}

QColor TColor::XSL_FO_ColorName::color( const QString colorchunk )
{
    QString wandedcolor = colorchunk;
    QString fcol = wandedcolor.trimmed().toLower();
    
    if (COLORNAME.contains(fcol)) {
        return avaiablelist[fcol];
    }
    if (fcol.size() < 2) {
        return QColor( 0, 0, 0,255);
	}
    
    if (fcol.startsWith("rgb(")) {
		QString grep = fcol.mid(4,fcol.size() - 5);
        grep = grep.replace(")","");
		QStringList v = grep.split(",");
		if (v.size() == 3) {
                const int R1 = v.at(0).toInt();
                const int R2 = v.at(1).toInt();
                const int R3 = v.at(2).toInt();
		return QColor(R1,R2,R3,255);
		} else if(v.size() >= 4) {
                const int Rt1 = v.at(0).toInt();
                const int Rt2 = v.at(1).toInt();
                const int Rt3 = v.at(2).toInt();
                const int Ralph = v.at(3).toInt();
		QColor cctra(Rt1,Rt2,Rt3);
		cctra.setAlpha(Ralph);
		return cctra;
		} else {   
         return QColor( 0, 0, 0,255); 
    }


   }
   
   if (fcol.startsWith("#")) {
		return QColor(fcol);
   }
   return QColor( 0, 0, 0,255);
}



void TColor::XSL_FO_ColorName::Load_Name()
{
    foplist.clear();
    record("aliceblue" 	,QColor(240, 248, 255,255));
    record("authoren"	,QColor(240, 255, 255,255));
    record("scribe"	,QColor(240, 255, 255,255));
	record("antiquewhite"	,QColor(250, 235, 215,255));
	record("aqua" 	,QColor( 0, 255, 255,255));
	record("aquamarine"	,QColor(127, 255, 212,255));
	record("azure"	,QColor(240, 255, 255,255));
	record("beige"	,QColor(245, 245, 220,255));
	record("bisque"	,QColor(255, 228, 196,255));
	record("black" 	,QColor( 0, 0, 0,255));
	record("blanchedalmond"	,QColor(255, 235, 205,255));
	record("blue"	,QColor( 0, 0, 255,255));
	record("blueviolet" 	,QColor(138, 43, 226,255));
	record("brown"	,QColor(165, 42, 42,255));
	record("burlywood"	,QColor(222, 184, 135,255));
	record("cadetblue"	,QColor( 95, 158, 160,255));
	record("chartreuse"	,QColor(127, 255, 0,255));
	record("chocolate"	,QColor(210, 105, 30,255));
	record("coral" 	,QColor(255, 127, 80,255));
	record("cornflowerblue"	,QColor(100, 149, 237,255));
	record("cornsilk" 	,QColor(255, 248, 220,255));
	record("crimson"	,QColor(220, 20, 60,255));
	record("cyan"	,QColor( 0, 255, 255,255));
	record("darkblue"	,QColor( 0, 0, 139,255));
	record("darkcyan"	,QColor( 0, 139, 139,255));
	record("darkgoldenrod"	,QColor(184, 134, 11,255));
	record("darkgray" 	,QColor(169, 169, 169,255));
	record("darkgreen"	,QColor( 0, 100, 0,255));
	record("darkgrey" 	,QColor(169, 169, 169,255));
	record("darkkhaki" 	,QColor(189, 183, 107,255));
	record("darkmagenta" 	,QColor(139, 0, 139,255));
	record("darkolivegreen"	,QColor( 85, 107, 47,255));
	record("darkorange"	,QColor(255, 140, 0,255));
	record("darkorchid"	,QColor(153, 50, 204,255));
	record("darkred"	,QColor(139, 0, 0,255));
	record("darksalmon"	,QColor(233, 150, 122,255));
	record("darkseagreen"	,QColor(143, 188, 143,255));
	record("darkslateblue"	,QColor( 72, 61, 139,255));
	record("darkslategray" 	,QColor( 47, 79, 79,255));
	record("darkslategrey" 	,QColor( 47, 79, 79,255));
	record("darkturquoise"	,QColor( 0, 206, 209,255));
	record("darkviolet" 	,QColor(148, 0, 211,255));
	record("deeppink" 	,QColor(255, 20, 147,255));
	record("deepskyblue"	,QColor( 0, 191, 255,255));
	record("dimgray" 	,QColor(105, 105, 105,255));
	record("dimgrey" 	,QColor(105, 105, 105,255));
	record("dodgerblue"	,QColor( 30, 144, 255,255));
	record("firebrick" 	,QColor(178, 34, 34,255));
	record("floralwhite"	,QColor(255, 250, 240,255));
	record("forestgreen"	,QColor( 34, 139, 34,255));
	record("fuchsia" 	,QColor(255, 0, 255,255));
	record("gainsboro" 	,QColor(220, 220, 220,255));
	record("ghostwhite"	,QColor(248, 248, 255,255));
	record("gold"	,QColor(255, 215, 0,255));
	record("goldenrod"	,QColor(218, 165, 32,255));
	record("gray" 	,QColor(128, 128, 128,255));
	record("grey" 	,QColor(128, 128, 128,255));
	record("green"	,QColor( 0, 128, 0,255));
	record("greenyellow" 	,QColor(173, 255, 47,255));
	record("honeydew" 	,QColor(240, 255, 240,255));
	record("hotpink" 	,QColor(255, 105, 180,255));
	record("indianred"	,QColor(205, 92, 92,255));
	record("indigo" 	,QColor( 75, 0, 130,255));
	record("ivory" 	,QColor(255, 255, 240,255));
	record("khaki" 	,QColor(240, 230, 140,255));
	record("lavender" 	,QColor(230, 230, 250,255));
	record("lavenderblush" 	,QColor(255, 240, 245,255));
	record("lawngreen"	,QColor(124, 252, 0,255));
	record("lemonchiffon"	,QColor(255, 250, 205,255));
	record("lightblue"	,QColor(173, 216, 230,255));
	record("lightcoral" 	,QColor(240, 128, 128,255));
	record("lightcyan"	,QColor(224, 255, 255,255));
	record("lightgoldenrodyellow" 	,QColor(250, 250, 210,255));
	record("lightgray" 	,QColor(211, 211, 211,255));
	record("lightgreen"	,QColor(144, 238, 144,255));
	record("lightgrey" 	,QColor(211, 211, 211,255));
    record("lightpink"  	,QColor(255, 182, 193,255));
	record("lightsalmon"	,QColor(255, 160, 122,255));
	record("lightseagreen"	,QColor( 32, 178, 170,255));
	record("lightskyblue"	,QColor(135, 206, 250,255));
	record("lightslategray" 	,QColor(119, 136, 153,255));
	record("lightslategrey" 	,QColor(119, 136, 153,255));
	record("lightsteelblue"	,QColor(176, 196, 222,255));
	record("lightyellow" 	,QColor(255, 255, 224,255));
	record("lime"	,QColor( 0, 255, 0,255));
	record("limegreen"	,QColor( 50, 205, 50,255));
	record("linen"	,QColor(250, 240, 230,255));
	record("magenta" 	,QColor(255, 0, 255,255));
	record("maroon"	,QColor(128, 0, 0,255));
	record("mediumaquamarine"	,QColor(102, 205, 170,255));
	record("mediumblue"	,QColor( 0, 0, 205,255));
	record("mediumorchid"	,QColor(186, 85, 211,255));
	record("mediumpurple"	,QColor(147, 112, 219,255));
	record("mediumseagreen"	,QColor( 60, 179, 113,255));
	record("mediumslateblue"	,QColor(123, 104, 238,255));
	record("mediumspringgreen"	,QColor( 0, 250, 154,255));
	record("mediumturquoise"	,QColor( 72, 209, 204,255));
	record("mediumvioletred"	,QColor(199, 21, 133,255));
	record("midnightblue"	,QColor( 25, 25, 112,255));
	record("mintcream" 	,QColor(245, 255, 250,255));
	record("mistyrose"	,QColor(255, 228, 225,255));
	record("moccasin"	,QColor(255, 228, 181,255));
	record("navajowhite"	,QColor(255, 222, 173,255));
	record("navy" 	,QColor( 0, 0, 128,255));
	record("oldlace"	,QColor(253, 245, 230,255));
	record("olive"	,QColor(128, 128, 0,255));
	record("olivedrab" 	,QColor(107, 142, 35,255));
	record("orange"	,QColor(255, 165, 0,255));
	record("orangered"	,QColor(255, 69, 0,255));
	record("orchid",QColor(218, 112, 214,255));
	record("palegoldenrod"	,QColor(238, 232, 170,255));
	record("palegreen"	,QColor(152, 251, 152,255));
	record("paleturquoise"	,QColor(175, 238, 238,255));
	record("palevioletred"	,QColor(219, 112, 147,255));
	record("papayawhip"	,QColor(255, 239, 213,255));
	record("peachpuff" 	,QColor(255, 218, 185,255));
	record("peru"	,QColor(205, 133, 63,255));
	record("pink" 	,QColor(255, 192, 203,255));
	record("plum" 	,QColor(221, 160, 221,255));
	record("powderblue"	,QColor(176, 224, 230,255));
	record("purple"	,QColor(128, 0, 128,255));
	record("red"	,QColor(255, 0, 0,255));
	record("rosybrown"	,QColor(188, 143, 143,255));
	record("royalblue"	,QColor( 65, 105, 225,255));
	record("saddlebrown"	,QColor(139, 69, 19,255));
	record("salmon"	,QColor(250, 128, 114,255));
	record("sandybrown"	,QColor(244, 164, 96,255));
	record("seagreen"	,QColor( 46, 139, 87,255));
	record("seashell" 	,QColor(255, 245, 238,255));
	record("sienna" 	,QColor(160, 82, 45,255));
	record("silver" 	,QColor(192, 192, 192,255));
	record("skyblue"	,QColor(135, 206, 235,255));
	record("slateblue"	,QColor(106, 90, 205,255));
	record("slategray" 	,QColor(112, 128, 144,255));
	record("slategrey" 	,QColor(112, 128, 144,255));
	record("snow" 	,QColor(255, 250, 250,255));
	record("springgreen"	,QColor( 0, 255, 127,255));
	record("steelblue"	,QColor( 70, 130, 180,255));
	record("tan"	,QColor(210, 180, 140,255));
	record("teal" 	,QColor( 0, 128, 128,255));
	record("thistle"	,QColor(216, 191, 216,255));
	record("tomato" 	,QColor(255, 99, 71,255));
	record("turquoise"	,QColor( 64, 224, 208,255));
	record("violet" 	,QColor(238, 130, 238,255));
	record("wheat" 	,QColor(245, 222, 179,255));
	record("white"	,QColor(255, 255, 255,255));
	record("whitesmoke"	,QColor(245, 245, 245,255));
	record("yellow" 	,QColor(255, 255, 0,255));
	record("yellowgreen"	,QColor(154, 205, 50,255));

         QStringList colorNames = QColor::colorNames();
             foreach (QString name, colorNames) {
           record(name,QColor(name));
        /*  qt overwrite color ? */
       }
       
    //////foplist.sort();  /* sort names is full */
    Q_ASSERT(foplist.size() < 10);
    COLORNAME.clear();
    QMapIterator<QString,QColor> i(foplist);
         while (i.hasNext()) {
             i.next();
             COLORNAME.append(i.key().toLower().trimmed());
         }
    COLORNAME.sort();
    
         
          for (int i = 0; i < COLORNAME.size(); ++i)  {
              const QString humanname = COLORNAME.at(i).toLower().trimmed();
              QColor iter = foplist[humanname];
              avaiablelist.insert(humanname,iter);
          }

      qDebug() << "### avaiablelist.size()   " << avaiablelist.size();
      qDebug() << "### COLORNAME.size()   " << COLORNAME.size();
}


void TColor::XSL_FO_ColorName::record( const QString colorchunk , QColor item )
{
	const QString qcname = colorchunk.toLower().trimmed();
    foplist.insert(qcname,item);
}








