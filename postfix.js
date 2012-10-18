
var bro = $.browser;
if (bro.msie) {
	if (bro.version != '9.0') {
		alert('您的 IE 浏览器版本太低！请使用 IE9 或者 Chrome 浏览器！');
		window.location.href = "http://www.baidu.com/s?wd=chrome&rsv_spt=1&issp=1&rsv_bp=0&ie=utf-8&tn=baiduhome_pg";
	}
}

$(document).ready(function() {
	var qry = window.location.search.substr(1);
	var params = qry.split('&');
	var m;
	for (var i in params) {
		var t = params[i];
		if (t.match(/^t=/)) {
			m = t;
		}
	}
	//console.log('params:', params);
	$('form').append('<input type=hidden name=t value=' + m.substr(2) + ' >');
	$('form').attr('action', '?');
	var a = $('#right-pan input[type="submit"]');
	a.replaceWith('<button type=submit>提交</button>');
	$('#right-pan button').addClass("btn");
	$('#right-pan form').addClass("well");
	$('#right-pan a').each(function (i) {
		//console.log('this=', $(this));
		var h = $(this).attr('href');
		if (h) {
			h = '?' + m + '&' + h.substr(h.indexOf('?') + 1);
			//console.log(h);
			$(this).attr('href', h);
		}
	});
	$('.nav-list a[href^="?' + m + '"]').parent().addClass('active');
});
