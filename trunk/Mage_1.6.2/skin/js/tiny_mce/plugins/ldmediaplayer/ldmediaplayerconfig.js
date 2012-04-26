/*
 * Media Player TinyMCE
 * 
 * Author: Loaden Development
 * Website: http://www.loaden-development.com
 * Lizenz: LDOL V1
 */
if (typeof ldmpconfig == "undefined") {
	/* 
	 * start config
	 */
	var ldmpconfig = {
		tiny_path: '/js/',
	}
	var ldmptoload = [
		'http://' + window.location.host + ldmpconfig.tiny_path + 'tiny_mce/plugins/ldmediaplayer/jquery.min.js',
		'http://' + window.location.host + ldmpconfig.tiny_path + 'tiny_mce/plugins/ldmediaplayer/mediaelement-and-player.min.js', 
		'http://' + window.location.host + ldmpconfig.tiny_path + 'tiny_mce/plugins/ldmediaplayer/css/mediaelementplayer.min.css',
		'http://' + window.location.host + ldmpconfig.tiny_path + 'tiny_mce/plugins/ldmediaplayer/ldmediaplayerinit.js'
	];
	/* 
	 * end config
	 */
	if (typeof ldmpadmin == "undefined") {
		for (var i = 0; i < ldmptoload.length; i++) {
	  		if (/.css$/i.test(ldmptoload[i])) {	
				var link = document.createElement('link');
			  	link.type = 'text/css';
			   link.media = 'all';
				link.rel  = 'stylesheet';
				link.href = ldmptoload[i];	
				document.getElementsByTagName('head')[0].appendChild(link);
			} else {
			  	var po = document.createElement('script');
			  	po.type = 'text/javascript';
			  	po.async = false;
				po.src = ldmptoload[i];
				var s = document.getElementsByTagName('link')[0];
				s.parentNode.insertBefore(po, s);
			}
		}
	}
}
