#!/bin/bash
# script to reset permission
#  usage ./scriptname ownername

USER=$(whoami)
TOPDIR=$(pwd)
##  find . -type d -not -name ".svn" | xargs chmod 0777

if [ $USER != 'root' ]; then
echo .Only user root can run this permission file!.
exit 0
fi

echo "Topdir as working dir is -> $TOPDIR ..."
# alles ist bereits dev user setting 
echo "Clear var/cache/ var/report/ var/session/ ...... ""
rm -rf  $TOPDIR/var/cache
rm -rf $TOPDIR/var/session
rm -rf $TOPDIR/var/report
rm -rf $TOPDIR/var/log

mkdir -p $TOPDIR/var/cache
mkdir -p $TOPDIR/var/session
mkdir -p $TOPDIR/var/report
mkdir -p $TOPDIR/var/log


chmod -R 0777 $TOPDIR/
echo "end "
exit 0



