/**
 * The Common GovHack JavaScripts
 * @requires jQuery
 */
 
(function($){
    'use strict';

    // A couple of variables to drive a little [Safari detection](http://stackoverflow.com/a/7768006)
    // Chrome has both 'Chrome' and 'Safari' inside userAgent string. Safari has only 'Safari'. 
    var refreshCount = 0;
    var isSafari = navigator.userAgent.indexOf("Safari") > -1 && !(navigator.userAgent.indexOf('Chrome') > -1);

    // Configure defaults on [featherlight](https://github.com/noelboss/featherlight#configuration) lightbox
    if ($.featherlight){
        $.extend($.featherlight.defaults, {
            afterContent: function(){
                // An absolutely filthy hackaround for [Safari image caching problems](http://irama.org/news/2011/06/05/cached-images-have-no-width-or-height-in-webkit-e-g-chrome-or-safari/)
                // Reload the iframe after first load, to force pictures to somehow recache or something.
                if (isSafari && refreshCount < 1){
                    if (this.$content.length && this.$content.length > 0){
                        if (this.$content[0].contentWindow){
                            // This is the iframe we are looking for...
                            this.$content[0].contentWindow.location.reload();
                            refreshCount++;
                        }
                    }
                }
            }
        });
    }
    
    // Do some image preloading
    var preload1 = new Image();
    var preload2 = new Image();
    preload1.src = '/wp-content/themes/govhack-17/img/loading-ghblue.gif';
    preload2.src = '/wp-content/themes/govhack-17/img/loading-ghpink.gif';
    


}(jQuery));