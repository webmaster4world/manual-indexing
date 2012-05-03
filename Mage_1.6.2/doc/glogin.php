<?php
if (is_array($_POST) && $_POST['user']!='') {
    $user_n= $_POST['user'];
    $Ib_user = md5('ibrams-' . $_POST['user']);
    $go_url = '/index.php/alpiqlogin/login/try/username/'.$user_n.'/hash/'.$Ib_user;
    //////@mail($_POST['mail'],'Access Stage alpiq server.',"Your Stage login url: http://".$_SERVER[HTTP_HOST].$go_url."\nReset on http://apronstage.mishost.ch/glogin.php ");
    header('Location: ' .$go_url);
    exit();
}
////  destroy all session
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Login Generator</title>
<script type="text/javascript">

function trim(str) {
        return str.replace(/^\s+|\s+$/g,"");
 }


function Get_Cookie( check_name ) {
	// first we'll split this cookie up into name/value pairs
	// note: document.cookie only returns name=value, not the other components
	var a_all_cookies = document.cookie.split( ';' );
	var a_temp_cookie = '';
	var cookie_name = '';
	var cookie_value = '';
	var b_cookie_found = false; // set boolean t/f default f

	for ( i = 0; i < a_all_cookies.length; i++ )
	{
		// now we'll split apart each name=value pair
		a_temp_cookie = a_all_cookies[i].split( '=' );
        var cacher = parseInt(a_temp_cookie[0]);
        if (isNaN(cacher)) {  /* not fill number as name */ 
		cookie_name = trim(a_temp_cookie[0]);
		//////$("#idebug").append('...('+cookie_name+')..cookie='+a_all_cookies[i]+'<br/>');

		// if the extracted name matches passed check_name
		if ( cookie_name == check_name )
		{
			b_cookie_found = true;
			// we need to handle case where cookie has no value but exists (no = sign, that is):
			if ( a_temp_cookie.length > 1 )
			{
				cookie_value = unescape( a_temp_cookie[1].replace(/^\s+|\s+$/g, '') );
			}
			// note that in cases where cookie is initialized but no value, null is returned
			return cookie_value;
			break;
		}
		}
		a_temp_cookie = null;
		cookie_name = '';
	}
	if ( !b_cookie_found )
	{
		return false;
	}
}






function xGetElementById(e)   {
    if (typeof(e) == 'string') {
  if (document.getElementById) e = document.getElementById(e);
   else if (document.all) e = document.all[e];
  else e = null;
   }
   return e;
 }

  
  
 function xInnerHtml(e,h)   {
          if(!(e=xGetElementById(e)) || !xStr(e.innerHTML)) return null;
          var s = e.innerHTML;
          if (xStr(h)) {e.innerHTML = h;}
          return s;
  }
  
 function setCookie(c_name,value,exdays)  {
 var exdate=new Date();
  exdate.setDate(exdate.getDate() + exdays);
  var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
 document.cookie=c_name + "=" + c_value;
  }
  
  
function CopyForm(xvalue) {
  if (xvalue!='') {
  /////emulator.mail.value = value;
  var changer = xGetElementById('MailBoxer');	
  changer.value = xvalue;
  setCookie('APmail',xvalue,'2');
  } else {
     alert('Bitte username eingeben!');
  }
}


function get_cookies_array() {
 

    var cookies = { };
 

    if (document.cookie && document.cookie != '') {

        var split = document.cookie.split(';');

        for (var i = 0; i < split.length; i++) {

            var name_value = split[i].split("=");

            name_value[0] = name_value[0].replace(/^ /, '');

            cookies[decodeURIComponent(name_value[0])] = decodeURIComponent(name_value[1]);

        }

    }


    return cookies;

    
}


function Delete_Cookie( name, path, domain ) {
if ( Get_Cookie( name ) ) document.cookie = name + "=" +
( ( path ) ? ";path=" + path : "") +
( ( domain ) ? ";domain=" + domain : "" ) +
";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}

function RemoveCookieLong() {
var cookr = get_cookies_array();
for(var name in cookr) {
  if (name!='APmail') {
  Delete_Cookie(name,'/','apronstage.mishost.ch');
  Delete_Cookie(name,'/','.apronstage.mishost.ch');
  }
}

location.reload();


}




</script>
</head>
<body>

<h1>Samba Login emulator stage</h1>
<p><i>stage</i></p>
<form action="/glogin.php" method="post" name="emulator" id="emulator" accept-charset="utf-8">
  <p>Username:<br><input name="user" id="UserBoxer" value="" type="text" size="30" maxlength="40"></br>
  <input type="button" value="Gleicher user und mail" onclick="CopyForm(this.form.user.value)"/></br>
  Mail:<br><input name="mail" id="MailBoxer" value="" type="text" size="30" maxlength="40"></p>
  
  <input type="submit" value=" Login ">
        <input type="reset" value=" Abbrechen">
</form>

<div id="CooDebug"><h2>Cookie or session found:</h2>
<p>
<script type="text/javascript">
var cookies = get_cookies_array();
for(var name in cookies) {
  document.write( "Name:  " + name + " : Value:  " + cookies[name] + "<br />" );
     if ( name == 'APmail') {
	     /* autofill form */
		 var changer = xGetElementById('UserBoxer');	
             changer.value = cookies[name];
	 }
}
</script>
</p>


 <button name="Cookiereset" type="button"  value="Cookiereset" onclick="RemoveCookieLong();" ><var>Cookiereset</var></button>
</div>

</body>
</html>

