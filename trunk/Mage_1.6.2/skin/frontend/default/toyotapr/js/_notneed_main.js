jQuery(document).ready(function () {
	var getdomainname = function(url) {
		return url.split('/')[2].split(':')[0];
	}
	var domain = getdomainname(jQuery('link[rel=icon]').attr('href'));
	jQuery('video, audio').mediaelementplayer({
		flashName: 'http://' + domain + '/skin/frontend/default/toyotapr/flashmediaelement.swf'
	});
});