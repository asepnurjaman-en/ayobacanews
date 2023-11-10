import * as bootstrap from 'bootstrap';
import Swal from 'sweetalert2';

var nav_header = $(".ayobaca-header");
$(window).scroll(function () {
	if ($(this).scrollTop() > 46) {
		nav_header.addClass('fixed');
	} else {
		nav_header.removeClass('fixed');
	}
});

var navLeft = $(".navLeft"),
	navRight = $(".navRight");
$('nav').scroll(function () {
	if ($(this).scrollLeft() > 80) {
		navLeft.removeClass('hide');
	} else {
		navLeft.addClass('hide');
	}
	if ($(this).scrollLeft() < ($(this).width() * 2) - 120) {
		navRight.removeClass('hide');
	} else {
		navRight.addClass('hide');
	}
});

navLeft.on('click', function(e) {
	e.preventDefault();
	$('nav').animate({
		scrollLeft: 0
	}, 'slow');
})

if ($(".skeleton").length > 0) {
	let skeleton = $(document).find(".skeleton");
	setTimeout(() => {
		skeleton.find('img').removeClass('d-none');
		skeleton.removeClass('skeleton');
	}, 2000);
}

if ($(".counting-input").length > 0) {
	$(".counting-input").on('keyup', function() {
		$(".counting").text($(this).val().length);
	});
}

$(".ad").on('click', function(e) {
	let destin = $(this).data('url');
	if (destin=='' || destin=='#') {
		return false;
	} else {
		window.open(destin, '_blank');
	}
});

$("img.img-fluid").contextmenu(function() {
	return false;
});