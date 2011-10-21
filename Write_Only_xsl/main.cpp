
#include "ModelExcel.h"
#include <string.h>

#define MODEL_2 1

#include <QVariant>
#include <QByteArray>


#define SAVEPATH "c:\\aalaexcelcreatas.xls"   ///// or linux path 

int main (int argc, char **args)
{
  
  FILE *f = fopen (SAVEPATH, "wb");  //// open file or die()
  CMiniExcel miniexcel;
  miniexcel(0,0) = "La strega scrive";
  miniexcel(1,0) = "Item2:";
  miniexcel(2,0) = "Sum = ";
  miniexcel(2,0).setBorder(BORDER_LEFT | BORDER_TOP | BORDER_BOTTOM);  /////  border open 
  miniexcel(2,0).setAlignament(ALIGN_CENTER);
  miniexcel(0,1) = QVariant(10.55);
  miniexcel(1,1) = 20;
  miniexcel(2,1) = (double)miniexcel(0,1) + (double)miniexcel(1,1);
  miniexcel(2,1).setBorder(BORDER_RIGHT | BORDER_TOP | BORDER_BOTTOM);   //// border close 
  miniexcel.Write(f);
  return 0;
}