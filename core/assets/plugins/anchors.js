;$(document).on('click', 'a[href^="#"]', function(){
	let href = $(this).attr('href'),
		scrollToObj = $(href);
	if (scrollToObj.length) {
		let scrollTo =  $($(this).attr('href')).offset().top;
	} else {
		console.error('Object '+$(this).attr('href')+' not found');
		return true;
	}
	window.location +=
	$('html, body').stop().animate({scrollTop:scrollTo}, 500, 'swing');
	return false;
});