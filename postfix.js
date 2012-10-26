
$(document).ready(function() {
	var qry = window.location.search.substr(1);
	var params = qry.split('&');
	var get = {};
	var m;
	for (var i in params) {
		var t = params[i];
		var ta = t.split('=');
		get[ta[0]] = ta[1];
	}
	m = 't=' + get['t'];

	console.log('params:', params);
	$('#right-pan form').append('<input type=hidden name=t value=' + m.substr(2) + ' >');
	$('#right-pan form').attr('action', function(i, val) {
		if (!val) {
			val = '?';
		}
		return val + '&' + m;
	});
	var a = $('#right-pan input[type="submit"]');
	a.replaceWith('<button type=submit>提交</button>');
	$('#right-pan button').addClass("btn");
	$('#right-pan form[auto!=no]').addClass("well");
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

	function graph_create_form(div) {
		var form = div.find('.graph-form');
		var j = jQuery.parseJSON(form.find('data').html());
		var rows = j.rows;

		for (var i in rows) {
			var r = rows[i];
			var html = '<label class="checkbox">' + 
				    		 '<input type="checkbox" checked row=' + i + '>' + r + 
						  	 '</label>';
			form.append($(html).change(function() {
				graph_do_plot($(this).closest('.graph-div'));
			}));
		}

		graph_do_plot(div);
	}

	function graph_do_plot(div, opts) {
		var form = div.find('.graph-form');
		var j = jQuery.parseJSON(form.find('data').html());
		var rows = j.rows;
		var cols = j.cols;
		var data = j.data;
		var yunit = j.yunit;
		var w = 1./(rows.length*2);
		var checks = form.find('input[checked]');

		//console.log(checks);

		for (var j = 0; j < rows.length; j++) {
			if (form.find('input[row=' + j + ']').attr('checked')) {
				var d = [];
				for (var i = 0; i < cols.length; i++) {
					d.push([i+j*w, data[i][j]]);
				}
				data.push({data:d, bars:{show:true, barWidth:w}, label:rows[j]});
			}
		}

		var ticks = [];
		for (var i = 0; i < cols.length; i++) {
			ticks.push([i+rows.length*w/2, cols[i]]);
		}

		var options = {
			xaxis: { ticks: ticks, min: -0.2, max: cols.length, },
			yaxis: { tickFormatter: function (num, obj) { return num + ' ' + yunit;} },
		};

		var graph = div.find('.graph');
		$.plot(graph, data, options);
		graph.show();
	}

	$('.graph-div').each(function () {
		graph_create_form($(this));
	});

	$('[rel=popover]').each(function() {
		$(this).popover({trigger:'hover'});
		//console.log($(this));
	});

	$('select').each(function() {
		var name = $(this).attr('name');
		if (name in get) {
			var v = get[name];
			$(this).find('option').each(function() {
				if ($(this).val() == v) {
					$(this).attr('selected', '1');
				}	
			});
		}
	});

	$('a[clickopen]').click(function(e) {
		console.log($(e.target));
		console.log($(e.target).attr('clickopen'));
		window.open($(e.target).attr('clickopen'));
	});

	$('a[confirm]').click(function(e) {
		if (!confirm($(this).attr('confirm'))) {
			e.preventDefault();
			return ;
		}
	});

	setTimeout(function() {
		$('div[fade]').fadeOut('slow');
	}, 3000);
});

