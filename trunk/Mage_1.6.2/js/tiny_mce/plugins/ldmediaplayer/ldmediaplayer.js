/*
 * Media Player TinyMCE
 * 
 * Author: Loaden Development
 * Website: http://www.loaden-development.com
 * Lizenz: LDOL V1
 */
var ldmpadmin = true;
var LdMediaPlayerDialog = {
	preInit : function() {		
		tinyMCEPopup.requireLangPack();
		if (typeof ldmpconfig == "undefined") {									
			var po = document.createElement('script');
		  	po.type = 'text/javascript';
		  	po.async = false;
			po.src = 'ldmediaplayerconfig.js';
			var s = document.getElementsByTagName('link')[0];
			s.parentNode.insertBefore(po, s);
		}
	},	
	init : function(ed) {
		tinyMCEPopup.resizeToInnerSize();
		TinyMCE_EditableSelects.init();
		document.getElementById('filebrowsercontainer').innerHTML = getBrowserHTML('filebrowser','src','media','media');

	},	
	insert : function() {		
		var content = '';
		var size = '';
		if (-1 == tinyMCE.activeEditor.getContent().indexOf('class="ldmediajs"'))		
			content = '<script type="text/javascript" src="http://'+ window.location.host + ldmpconfig.tiny_path + 'tiny_mce/plugins/ldmediaplayer/ldmediaplayerconfig.js" class="ldmediajs"></script>';	

		var width = document.getElementById('ldmp_width').value;
		var height = document.getElementById('ldmp_height').value;
		var width_length = width.length;
		var height_length =	height.length;		
		
		if ((width_length > 0) || (height_length > 0)) {
			size = 'style="';
			if (width_length > 0)
				size = size + 'width: ' + width +';';
			if (width_length > 0)
				size = size + 'height: ' + height +';';
			size = size + '"';
		}
		
		switch (document.getElementById('media_type').value) {		
			case 'video':	
				content = content + '<video src="' + document.getElementById('src').value + '" class="ldmediaplayer" ' + size + '></video>';		
				break;		
			case 'audio':	
				content = content + '<audio src="' + document.getElementById('src').value + '" class="ldmediaplayer" ' + size + '></audio>';
				break;		
		}
		tinyMCE.activeEditor.selection.setContent(content);	
		tinyMCEPopup.close();
	},	
	insertAndClose : function() {
	
	}
}
LdMediaPlayerDialog.preInit();
tinyMCEPopup.onInit.add(LdMediaPlayerDialog.init, LdMediaPlayerDialog);