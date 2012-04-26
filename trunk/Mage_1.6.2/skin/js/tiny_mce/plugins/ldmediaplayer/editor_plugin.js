/*
 * Media Player TinyMCE
 * 
 * Author: Loaden Development
 * Website: http://www.loaden-development.com
 * Lizenz: LDOL V1
 */
(function() {
    tinymce.create('tinymce.plugins.ldmediaplayer', {
        init : function(ed, uri) {
            ed.onInit.add(function() {             
             	ed.serializer.addRules('script[type|src|class],video[src|width|height|id|class|style|rel|alt|title],audio[src|width|height|id|class|style|rel|alt|title]');              	
             	if (ed.settings.content_css !== false)
						ed.dom.loadCSS(uri + '/css/content.css');
            });	
            ed.addButton('ldmediaplayer', {                
               onclick : function() {
						ed.windowManager.open({
								file : uri + '/ldmediaplayer.htm',
								width : 480,
								height : 385,
								inline : 1
							}, {
								plugin_url : uri
						});
			      },
					title : 'Media Player - Video & Audio',
               image: uri + '/img/ldmediaplayer.png'		
             });
        },
        getInfo : function() {
            return {				
                longname : 'TinyMCE Mediaplayer - Video & Audio - HTML5 / Flash Fallback - TinyMCE 3.x - Loaden Development',
                authorurl : 'http://www.loaden-development.com/',
                infourl : 'http://www.loaden-development.com/',
                author : 'Loaden Development',
                version : '1.0'				
            };			
        }
    });
    tinymce.PluginManager.add('ldmediaplayer', tinymce.plugins.ldmediaplayer);
})();

