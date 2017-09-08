/**
 * MAGIX CMS
 * @copyright  MAGIX CMS Copyright (c) 2010 Gerits Aurelien,
 * http://www.magix-cms.com, magix-cms.com http://www.magix-cjquery.com
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    1.0
 * @author Gérits Aurélien <aurelien@magix-cms.com>
 * JS theme default
 *
 */
// Creare's 'Implied Consent' EU Cookie Law Banner v:2.4
// Conceived by Robert Kent, James Bavington & Tom Foyster
// Modified by Simon Freytag for syntax, namespace, jQuery and Bootstrap

C = {
	createCookie: function() {
		var date = new Date();
		date.setTime(date.getTime() + (14*24*60*60*1000));
		var expires = date.toGMTString();
		document.cookie = 'complianceCookie=on; expires=' + expires + '; path=/';
		$("#cookies").removeClass('in').addClass('hide');
	},

	checkCookie: function() {
		var nameEQ = 'complianceCookie=';
		var ca = document.cookie.split(';');
		for(var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) === ' ')
				c = c.substring(1, c.length);

			if (c.indexOf(nameEQ) === 0)
				return c.substring(nameEQ.length, c.length);
		}
		return null;
	},

	init: function() {
		if (this.checkCookie() !== 'on')
			$("#cookies").removeClass('hide');
	}
};

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

$(function()
{
	C.init();

	// *** target_blank
	$('a.targetblank').click( function() {
		window.open($(this).attr('href'));
		return false;
	});

	// *** Smooth Scroll to Top
	$('.toTop').click( function() {
		$('html, body').animate({ scrollTop: 0 }, 450);
		return false;
	});

	// *** Cross effect on mobile
	$('button.navbar-toggle').click(function(){
		var target = $($(this).data('target'));
		if($(this).hasClass('open') || $(target).hasClass('collapse in')){
			$(this).removeClass('open');
		}else{
			$(this).addClass('open');
		}
	});

	if($.featherlight !== undefined) {
		var afterContent = function () {
			var caption = this.$currentTarget.find('img').attr('alt');
			this.$instance.find('.caption').remove();
			this.$instance.find('figure').remove();
			$('<figure />').appendTo(this.$instance.find('.featherlight-content'));
			this.$content.appendTo(this.$instance.find('.featherlight-content figure'));
			$('<p />')
				.text(caption)
				.appendTo(this.$instance.find('.featherlight-content figure'))
				.wrapAll('<figcaption class="caption">');
			this.$instance.find('.caption').width(this.$instance.find('img').width());
		};

		$.featherlight.prototype.afterContent = afterContent;

		$('.img-zoom').featherlight();

		if($.featherlightGallery !== undefined) {
			$.featherlightGallery.prototype.afterContent = afterContent;
			$.featherlightGallery.prototype.previousIcon = '<span class="fa fa-angle-left"></span>';
			$.featherlightGallery.prototype.nextIcon = '<span class="fa fa-angle-right"></span>';

			initGallery();
		}
	}
});