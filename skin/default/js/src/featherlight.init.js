function initGallery(){
	// *** for gallery pictures
	$('.img-gallery').featherlightGallery({
		previousIcon: '<span class="fa fa-angle-left"></span>',
		nextIcon: '<span class="fa fa-angle-right"></span>'
	});

	$(".show-img").off('click').click(function(){
		var target = $(this).data('target');
		$(".big-image a").animate({ opacity: 0, 'z-index': 0 }, 200);
		$(target).animate({ opacity: 1, 'z-index': 1 }, 200);
		return false;
	});
}

function afterContent() {
	var caption = this.$currentTarget.find('img').attr('alt');
	this.$instance.find('.caption').remove();
	$('<div class="caption">').text(caption).appendTo(this.$instance.find('.featherlight-content'));
}

$(function() {
	if($.featherlight !== undefined) {
		$.featherlight.prototype.afterContent = afterContent();
		$('.img-zoom').featherlight();

		if($.featherlightGallery !== undefined) {
			$.featherlightGallery.prototype.afterContent = afterContent();
			initGallery();
		}
	}
});