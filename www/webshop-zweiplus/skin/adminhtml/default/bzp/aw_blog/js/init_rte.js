initRTE = function(){


tinyMCE.init({
	mode : "exact",
	theme : "advanced",
	elements : "post_content,short_content",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_path_location : "bottom",
	extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
	theme_advanced_resize_horizontal : "true",
	theme_advanced_resizing : "true",
	apply_source_formatting : "true",
	cleanup : true,
	plugins : 'safari,pagebreak,style,layer,table,advhr,advimage,emotions,iespell,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras',
  convert_urls : "false",
	theme_advanced_buttons1 : 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect',
  theme_advanced_buttons2 : 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,forecolor,backcolor',
  theme_advanced_buttons3 : 'tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,media,advhr,|,ltr,rtl,|,fullscreen',
  theme_advanced_buttons4 : 'insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,pagebreak',
  
  /* corehack */
  external_link_list_url : '/js/digiswiss/linklist.php',
  external_image_list_url : '/js/digiswiss/imagelist.php',
  /* end corehack */          
	
	inline_styles : true,
	force_br_newlines : "true",
	file_browser_callback : 'myFileBrowser',
	doctype : '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'
});


}

function myFileBrowser (field_name, url, type, win) {


    var cmsURL = 'http://webshop-zweiplusch.ch/js/digiswiss/' + type + 'browser.php';     

    tinyMCE.activeEditor.windowManager.open({
        file : cmsURL,
        title : type + ' Browser',
        width : 800,  // Your dimensions may differ - toy around with them!
        height : 500,
        resizable : "yes",
        inline : "yes",  // This parameter only has an effect if you use the inlinepopups plugin!
        close_previous : "no"
    }, {
        window : win,
        input : field_name
    });
    return false;

    
}


