var about = (function ($, undefined) {
    return {
        run: function(){
            $('[data-toggle="popover"]').popover();
            $('[data-toggle="popover"]').click(function(e){
                e.preventDefault(); return false;
            });
        }
    }
})(jQuery);