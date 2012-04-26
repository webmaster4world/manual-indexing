/*
 * Media Player TinyMCE
 * 
 * Author: Loaden Development
 * Website: http://www.loaden-development.com
 * Lizenz: LDOL V1
 */
$.noConflict();
jQuery(document).ready(function() {
	jQuery('video, audio').each(function() {	
		var self = jQuery(this);
		self.mediaelementplayer({
			flashName: 'flashmediaelement.swf',
			enableAutosize: false,
			pauseOtherPlayers: true,
			alwaysShowControls: false,
			loop: false,
	      defaultVideoWidth: self.width(),     
	    	defaultVideoHeight: self.height(),
		   pluginWidth: -1,
		   pluginHeight: -1
		});
	});
});