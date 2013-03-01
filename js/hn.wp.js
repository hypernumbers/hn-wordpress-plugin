HN = {};

HN.init = function () {
    HN.wordpress.postMessage();
}


HN.wordpress = {};

HN.wordpress.postMessage = function () {

    var receiveFun, style;

    receiveFun = function (e) {

	var h, w, name, recursiveFn;

        recursiveFn = function () {
            jQuery.receiveMessage(receiveFun);
        };

        setTimeout(recursiveFn, 1, "");

        h = Number(e.data.replace(/.*height=(\d+)(.*$)/, '$1'));
        w = Number(e.data.replace(/.*width=(\d+)(.*$)/, '$1'));
        name = decodeURIComponent(e.data.replace(/.*name=(.+)(.*$)/, '$1'));
	// only resize if it is NOT marked don't resize
	if (!$("#" + name).hasClass("hn_dont_resize")) {	
            jQuery("#" + name).css("height", h + 1);
            jQuery("#" + name).css("width", w + 1);
	}
    };

    // show the iframes
    jQuery(".hn_wordpress").css("display", "block");

    jQuery.receiveMessage(receiveFun);
};


// Now execute the fns
jQuery(document).ready(function () {

    // Firefox bug https://bugzilla.mozilla.org/show_bug.cgi?id=356558
    // Firefox uses cached iframe sources by mistake
    var i, iframes;

    iframes = $(".hn_wordpress");
    for (i = 0; i < iframes.length; i = i + 1) {
	iframes[i].contentWindow.location.href = iframes[i].src;
    }

    // as you were...
    HN.init();
});