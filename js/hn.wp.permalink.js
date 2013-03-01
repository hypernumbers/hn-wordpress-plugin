jQuery(document).ready(function() {
	var permalinkfn, postid, vixo, input;
	open   = jQuery("#hn_open_spreadsheet");
	insert = jQuery("#content_insertss");
	postid = jQuery(input).attr("data-postid");
	vixo   = jQuery(input).attr("data-href");

	console.log("insert is:");
	console.log(insert);
	console.log("open is:");
	console.log(open);

	// only enable the Spreadsheet button once there is a permalink
	permalinkfn = function () {

		var insertfn, openfn;

		// force a WordPress autosave to make sure the 
		// permalink is written
		autosave();

		insertfn = function () {
			var type, wpurl, dataType, success, vixourl;
			type     = "POST";
			wpurl    = "./admin-ajax/?action=vixo_get_sample_permalink&id=" + postid;
			dataType = "json";
			success = function (data) {
				var vixourl = vixo + data.path + "?view=spreadsheet";
				console.log("query fired");
				jQuery("#hn_hidden_permalink").innerHtml(vixourl);
				console.log(jQuery("#hn_hidden_permalink"));
			};
			jQuery.ajax({
				"type"     : type,
				"url"      : wpurl,
				"dataType" : dataType,
				"success"  : success
			});
		};

		jQuery(open).removeAttr('disabled');
 		
		// open the spreadsheet page
		openfn = function () {
			var type, wpurl, dataType, success;
			type     = "POST";
			wpurl    = "./admin-ajax/?action=vixo_get_sample_permalink&id=" + postid;
			dataType = "json";
			success = function (data) {
				var vixourl = vixo + data.path + "?view=spreadsheet";
				window.open(vixourl, "_vixo");
			};
			jQuery.ajax({
				"type"     : type,
				"url"      : wpurl,
				"dataType" : dataType,
				"success"  : success
			});
		};
		jQuery(insert).click(insertfn);
		jQuery(open).click(openfn);
	};
	jQuery("#titlewrap").change(permalinkfn);
});