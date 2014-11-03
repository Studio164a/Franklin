(function() {
    tinymce.create('tinymce.plugins.FontAwesome', {
 
        init : function(ed, url){     

            // Register command
            ed.addCommand('mceFontAwesome', function() {
                ed.windowManager.open({
                    file : url + '/FontAwesome.php',
                    width : 720 + parseInt(ed.getLang('highlight.delta_width', 0)),
                    height : 600 + parseInt(ed.getLang('highlight.delta_height', 0)),
                    inline : 1
                }, { 
                    plugin_url : url
                });
            });

            // Register button
            ed.addButton('fontawesome', {
                title : 'Insert FontAwesome icon',
                image : url + '/flag.png',
                cmd : 'mceFontAwesome'
            });
        }, 
        
        getInfo : function() {
            return {
                    longname : 'FontAwesome Plugin for TinyMCE',
                    author : 'Eric Daams',
                    authorurl : 'http://164a.com',
                    infourl : 'http://164a.com',
                    version : "1.0"
            };
        }
    });
 
    tinymce.PluginManager.add('fontawesome', tinymce.plugins.FontAwesome);
})();