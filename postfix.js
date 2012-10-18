
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
	console.log('params:', params);
	$('form').append('<input type=hidden name=t value=' + m.substr(2) + ' >');
	$('form').attr('action', '?');
	$('#right-pan button').addClass("btn");
	$('#right-pan form').addClass("well");
	$('#right-pan a').each(function (i) {
		console.log('this=', $(this));
		var h = $(this).attr('href');
		if (h) {
			h = '?' + m + '&' + h.substr(h.indexOf('?') + 1);
			//console.log(h);
			$(this).attr('href', h);
		}
	});
	$('.nav-list a[href^="?' + m + '"]').parent().addClass('active');
});
