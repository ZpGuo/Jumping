(function ($, window) {
    $(function() {
        var $a = $('#archives'),
            $m = $('.al_mon', $a),
            $l = $('.al_post_list', $a),
            $l_f = $('.al_post_list:first', $a);
        $l.hide();
        $l_f.show();
        $m.css('cursor', 'pointer').on('click', function(){
            $(this).next().slideToggle(400);
        });
        var animate = function(index, status, s) {
            if (index > $l.length) {
                return;
            }
            if (status == 'up') {
                $l.eq(index).slideUp(s, function() {
                    animate(index+1, status, (s-10<1)?0:s-10);
                });
            } else {
                $l.eq(index).slideDown(s, function() {
                    animate(index+1, status, (s-10<1)?0:s-10);
                });
            }
        };
        $('#al_expand_collapse').on('click', function(e){
            e.preventDefault();
            if ( $(this).data('s') ) {
                $(this).data('s', '');
                animate(0, 'up', 100);
            } else {
                $(this).data('s', 1);
                animate(0, 'down', 100);
            }
        });
    });
})(jQuery, window);