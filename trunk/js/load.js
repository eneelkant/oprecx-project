/* 
 * jsLazyLoad
 * 
 * @author Abie Hafshin {abi.hafshin [at] ui [dot] ac [dot] id}
 * 
 * How to use
 * <script>
 * var lazyLoad = lazyLoad || [];
 * // load jquery.js and when it finished, load 'jquery.mobile.js' and 'jquery-ui.js' pararelly
 * lazyLoad.push(['jquery.js', 'jquery.mobile.js', 'jquery-ui.js']);
 * </script>
 * <script src="lazyload.js"></script>
 * 
 */

(function(win, doc) {
    
    // initialize lazyLoad global variable
    var lazyLoad = (win.lazyLoad = win.lazyLoad || {});
    if (lazyLoad.l) return;
    lazyLoad.l = true;
    
    // initialize local variable
    /** @var {Element} head */
    var head = doc.getElementsByTagName('head')[0];
    var n = 0;
    var len = lazyLoad.length || 0;
    
    /**
     * This function will append <code>script</code> element into first <code>head</code> element in html
     * @param {String} src
     * @param {Array|null} [arg=null]
     */
    function insertScript(src, arg) {
        var script = doc.createElement('script');
        script.async = true;
        script.type = 'text/javascript';
        
        function onload() {
            n--;
            if (arg) load(arg);
            if (n <= 0) lazyLoad.finished = true;
        }
        
        script.onload = onload;
        script.onreadystatechange = function() {
            //console.log(src, this, this.readyState);
            if (this.readyState === 'complete') {
                script.onload = script.onreadystatechange = null;
                head.removeChild(script);
                onload();
            }
        };
        //if (lazyLoad.disableCache) src = src + '?' + new Date().getTime();
        script.src = src;
        head.appendChild(script);
    }
    
    /**
     * 
     * @param {String|Function|Array} arg
     * @returns {Number}
     */
    function push(arg) {
        if (typeof(arg) === 'string') {
            insertScript(arg, []);
        } else if (typeof(arg) === 'function') {
            arg();
        } else if (arg.length > 0) {
            insertScript(arg.shift(), arg);
        }
        //lazyLoad[len] = arg;
        //return lazyLoad.length = ++len;
    }
    
    /**
     * 
     * @param {Array} arr
     * @returns {undefined}
     */
    function load(arr) {
        n += arr.length;
        lazyLoad.finished = false;
        
        for(var i=0; i<arr.length; ++i) {
            push(arr[i]);
        }
        
    }
    
    load(lazyLoad);
    lazyLoad.push = push;
})(window, document);

