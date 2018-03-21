// @ts-nocheck

var l = console.log.bind(console);
var w = console.warn.bind(console);
var e = console.error.bind(console);



// @ts-nocheck
//返回顶部
function gotop(el) {
	// $("<div class='am-gotop am-gotop-fixed'>\
	// 			<a href='#top' title='回到顶部'>\
	// 				<span class='am-gotop-title'>回到顶部</span>\
	// 				<i class='am-gotop-icon am-icon-chevron-up'></i>\
	// 			</a>\
	// 		</div>").appendTo((el != null ? el : 'body'));


	var $fk = $(".fk");

	$(document).scroll(function () {

		var $this = $(this);

		if ($this.scrollTop() > 100) {
			$fk.css("opacity", "1");
		} else {
			$fk.css("opacity", "0");
		}

	})

	$(".fk").on("click", function () {

		$(document).animate({
			scrollTop: 0
		}, 300);


	})

}

function gotop2(el) {

	var $top = $('<div class="fk"><i class="am-gotop-icon am-icon-chevron-up"></i></div>');
	var $el = $(el);
	$el.each(function () {
		var $this = $(this);
		var $top_c = $('<div class="fk"><i class="am-gotop-icon am-icon-chevron-up"></i></div>');
		$this.append($top_c);

		$(document).on("click", $top_c, function () {
			$this.animate({
				scrollTop: 0
			}, 300);
		});

		$this.scroll(function () {
			console.log($this.scrollTop());

			if ($this.scrollTop() > 100) {
				$top_c.css("opacity", "1");
				//				$top_c.css("transform", "none");

			} else {
				$top_c.css("opacity", "0");

			}

		})
	});

}




function isNav(el) {
	var $el = $(el);
	if ($el.length > 0) {
		var $nav = $($el[0]);
		var height = $nav.height();
		var $con = $nav.next();
		$con.css("padding-top", height + "px");
	}
}


//将字符串按照格式转化成数组或者json
function strToArr(str, code, f) {

	var array = str.split(code);
	var arr = [];

	for (var i = 0; i < array.length; i++) {
		var element = array[i];
		if (f != null) {

			if (typeof (f) == 'string') {
				arr[i] = {};
				arr[i][f] = element;

			} else {
				arr[i] = f(i, arr, element);
			}


		} else {
			arr[i] = element;
		}
	}
	return arr;

}


var tool = {

	alert: function (conf) {

		var zz = '<div class="alert-zz "></div>';
		var $zz = $(zz);
		$zz.appendTo('body');

		var alert = '<div class="alert ">\
		<div class="alert-body">\
			<div class="alert-title">'
			+ ((conf.title) ? conf.title : '') +
			'</div>\
			<div class="alert-info">'
			+ ((conf.info) ? conf.info : '') +
			'</div>\
		</div>\
		<div class="alert-footer">\
			<div class="alert-btn-group">\
				<div class="alert-btn yes">\
					确定\
				</div>\
				<div class="alert-btn no">\
					取消\
				</div>\
			</div>\
		</div>\
	</div>';

		var $alert = $(alert);

		$alert.appendTo('body');

		$alert.find('.yes').on('touchstart', function () {
			if (conf.yes != null) {
				conf.yes();
			}
			tool.hide($alert, $zz);
		});

		$alert.find('.no').on('touchstart', function () {
			if (conf.no != null) {
				conf.no();
			}
			tool.hide($alert, $zz);
		});


		setTimeout(function () {
			$alert.addClass('active');
			$zz.addClass('active');
		}, 0);



		w(conf);
	},
	hide: function ($alert, $zz) {

		$alert.removeClass('active');
		$zz.removeClass('active');
		setTimeout(function () {
			$alert.remove();
			$zz.remove();
		}, 400);

	}
};



function showPanel(index) {


	$('.zz-box[data-index=' + index + ']').css('opacity', '1');
	$('.zz-box[data-index=' + index + ']').css('z-index', '999');
	$('.zf-box[data-index=' + index + ']').css('bottom', '0');

}

function heidPanel(index) {
	if (!index) {

		$('.zz-box').css('opacity', '0');
		$('.zf-box').css('bottom', '-100%');

		setTimeout(function () {
			$('.zz-box').css('z-index', '-1');
		}, 500);

		return;
	}

	$('.zz-box[data-index=' + index + ']').css('opacity', '0');
	$('.zf-box[data-index=' + index + ']').css('bottom', '-100%');

	setTimeout(function () {

		$('.zz-box[data-index=' + index + ']').css('z-index', '-1');

	}, 500);

}

init();
function init() {

	var eventName = 'touchstart';
	var el = '.zz-box,.close';
	var fun = function (event) {
		heidPanel();
	}
	$(el).on(eventName, fun);

}


var msg = {
	s: function (conf) {

		var $msg = $('<div/>')
		$msg.appendTo('body');
		$msg.addClass('msg');
		$msg.text(conf.title);
		// $msg.css('bottom', conf.bottom);
		setTimeout(function () {
			$msg.addClass('active');
		}, 0);

		setTimeout(function () {
			$msg.removeClass('active');
		}, 1000);

		setTimeout(function () {
			$msg.remove();
		}, 3000);
	}

}




