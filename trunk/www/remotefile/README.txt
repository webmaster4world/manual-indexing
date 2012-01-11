
Edit parameter on file jobrunner.xml

<sshjob>
<comment_1>http://www.motobit.com/util/base64-decoder-encoder.asp</comment_1>
<_sftp_host>81.18.25.90</_sftp_host>
<_sftp_port>22</_sftp_port>
<_sftp_user>misystems</_sftp_user>
<_sftp_pass must="encoded 64">NG0xU3lzdDNtc19HMg==</_sftp_pass>
<ssh_pub_key_file></ssh_pub_key_file>
<ssh_pri_key_file></ssh_pri_key_file>
<comment_2>use or key or password base64 encoded, not two config</comment_2>
<_sftp_monitors_remote_dir>/home/misystems/ConsoleTest/EventManager</_sftp_monitors_remote_dir>
<_sftp_monitors_local_dir>/home/pero/parking</_sftp_monitors_local_dir>
<_sftp_monitors_loglocal com="Writable">./log/</_sftp_monitors_loglocal>
<_sftp_admin_mail com="ExeptionHandler">peter.hohl@misystems.ch</_sftp_admin_mail>
</sshjob>


include 2 class

require_once('SSH2_Exception.php'); /* Exception  handler by job */
require_once('Remote_File_Handler.php'); /* Handler from remote ssh2 file  */


 $puts = new Remote_File_Handler('jobrunner.xml');
 $puts->stamp("List file on home dir:");
 $gets = $puts->dirlist('/home/misystems/');
 print_r($gets);
 $puts->stamp("End line ->exit(1)");
 
