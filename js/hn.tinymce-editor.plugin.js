// add the TinyMCE button is mostly taken from this tutorial
// http://wp.tutsplus.com/tutorials/theme-development/wordpress-shortcodes-the-right-way/

(function() {
	tinymce.create('tinymce.plugins.insertss', {
		init : function(ed, url) {
			ed.addButton('insertss', {
				title : 'Insert The Spreadsheet',
				image : url+'/../images/spreadsheet.png',
				onclick : function() { 
					//idPattern = /(?:(?:[^v]+)+v.)?([^&=]{11})(?=&|$)/;
					var cells = prompt("Range to show", "a1:h10");
					var url = document.getElementById('hn_insertss_tinymce');
					var scode = '[hn url="' + url.innerHTML + '" cells="' + cells + '"]';
					//var m = idPattern.exec(vidId);
					//if (m != null && m != 'undefined') {
							ed.execCommand('mceInsertContent', false, scode);
					//}
				}
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname : "Insert HyperNumbers Spreadsheet",
				author : 'Gordon Guthrie',
				authorurl : 'http://vixo.com/',
				infourl : 'http://wordpress.vixo.com/',
				version : "1.0"
			};
		}
	});
	tinymce.PluginManager.add('insertss', tinymce.plugins.insertss);
})();
