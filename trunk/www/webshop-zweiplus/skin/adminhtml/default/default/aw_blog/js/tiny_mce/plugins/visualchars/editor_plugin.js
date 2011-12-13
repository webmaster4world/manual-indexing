(function(){tinymce.create("tinymce.plugins.VisualChars",{init:function(a,b){var c=this;c.editor=a;a.addCommand("mceVisualChars",c._toggleVisualChars,c);a.addButton("visualchars",{title:"visualchars.desc",cmd:"mceVisualChars"});a.onBeforeGetContent.add(function(d,e){if(c.state){c.state=true;c._toggleVisualChars()}})},getInfo:function(){return{longname:"Visual characters",author:"Moxiecode Systems AB",authorurl:"http://tinymce.moxiecode.com",infourl:"http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/visualchars",version:tinymce.majorVersion+"."+tinymce.minorVersion}},_toggleVisualChars:function(){var m=this,g=m.editor,a,e,f,k=g.getDoc(),l=g.getBody(),j,n=g.selection,c;m.state=!m.state;g.controlManager.setActive("visualchars",m.state);if(m.state){a=[];tinymce.walk(l,function(b){if(b.nodeType==3&&b.nodeValue&&b.nodeValue.indexOf("\u00a0")!=-1){a.push(b)}},"childNodes");for(e=0;e<a.length;e++){j=a[e].nodeValue;j=j.replace(/(\u00a0+)/g,'<span class="mceItemHidden mceVisualNbsp">$1</span>');j=j.replace(/\u00a0/g,"\u00b7");g.dom.setOuterHTML(a[e],j,k)}}else{a=tinymce.grep(g.dom.select("span",l),function(b){return g.dom.hasClass(b,"mceVisualNbsp")});for(e=0;e<a.length;e++){g.dom.setOuterHTML(a[e],a[e].innerHTML.replace(/(&middot;|\u00b7)/g,"&nbsp;"),k)}}}});tinymce.PluginManager.add("visualchars",tinymce.plugins.VisualChars)})();;
