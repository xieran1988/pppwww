
var bro = $.browser;
if (bro.msie) {
	if (bro.version != '9.0') {
//		alert('您的 IE 浏览器版本太低(IE' + bro.version + ')！请使用 IE9 或者 Chrome 浏览器！');
//		window.location.href = "http://www.baidu.com/s?wd=chrome&rsv_spt=1&issp=1&rsv_bp=0&ie=utf-8&tn=baiduhome_pg";
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
	$('#right-pan form').append('<input type=hidden name=t value=' + m.substr(2) + ' >');
	$('#right-pan form').attr('action', '?' + m);
	var a = $('#right-pan input[type="submit"]');
	a.replaceWith('<button type=submit>提交</button>');
	$('#right-pan button').addClass("btn");
	$('#right-pan form').addClass("well");
	$('#right-pan a').each(function (i) {
		//console.log('this=', this);
		var h = $(this).attr('href');
		if (h) {
			h = '?' + m + '&' + h.substr(h.indexOf('?') + 1);
			//console.log(h);
			$(this).attr('href', h);
		}
	});
	$('#right-pan > table, #right-pan > div > table').css({'margin-top':'20px'});
	$('#right-pan a > button').each(function() {
		//console.log('ab', $(this));
		var a = $(this).parent();
		var href = a.attr('href');
		$(this).click(function() {
			if ($(this).hasClass('btn-del')) {
				if (!confirm('确认删除？')) {
					return;
				}
			} 
			window.location.href = href;
		});
	});
	$('.nav-list a[href^="?' + m + '"]').parent().addClass('active');
	$('.datetime').datepicker({
		format: 'yyyy-mm-dd'
	});
	$('#tc_select').change(function() {
		if ($('#tc_select').val() == -2) {
			$('#opt_menu').show();
		} else {
			$('#opt_menu').hide();
		}
	});
	if ($('#tc_select').val() == -2) {
		$('#opt_menu').show();
	}

	$('.graph').each(function () {
		var regions = $(this).html().split(',');
		regions.pop();
		var data = [];
		var w = 1./(regions.length*2);
		var nrmonths = 3;

		for (var j = 0; j < regions.length; j++) {
			var d = [];
			for (var i = 0; i < nrmonths; i++) {
				d.push([i+j*w, Math.random()*100]);
			}
			data.push({data:d, bars: {show:true, barWidth:w}, label:regions[j]});
		}

		var ticks = [];
		for (var i = 0; i < nrmonths; i++) {
			ticks.push([i+regions.length*w/2, (9+i)+'月份']);
		}

		var options = {
			xaxis: { ticks: ticks, min: -0.2, max: nrmonths, },
		};
		$.plot($(this), data, options);
		$(this).show();
	});

});

