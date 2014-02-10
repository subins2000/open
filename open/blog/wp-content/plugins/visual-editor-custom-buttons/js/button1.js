// JavaScript Document

function getBaseURL () {
   return location.protocol + '//' + location.hostname + 
      (location.port && ':' + location.port) + '/';
}

(function() {
    tinymce.create('tinymce.plugins.vecb_button1', {
        init : function(ed, url) {
            ed.addButton('vecb_button1', {
                title : 'Code',image : url+'/icons/box.png',onclick : function() {
                     ed.selection.setContent('<pre class="prettyprint"><code>' + ed.selection.getContent() + '</code></pre>');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('vecb_button1', tinymce.plugins.vecb_button1);
})();