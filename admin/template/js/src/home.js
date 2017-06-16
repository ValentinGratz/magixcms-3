var home = (function ($, undefined) {
    return {
        run: function(){
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $('.dropdown-menu li.active').removeClass('active');
                $(this).parent('li').addClass('active');
                $('.dropdown .lang').text($(this).text());
                $('[data-toggle="toggle"]').each(function(){
                    $(this).bootstrapToggle('destroy');
                }).each(function(){
                    $(this).bootstrapToggle();
                });
            })
        }
    }
})(jQuery);