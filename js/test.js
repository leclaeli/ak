(function($){
    $(function() {
        $( "#datepicker" ).datepicker({
            changeMonth: true,
            changeYear: true
        });
        // change view
        // $( "#map-view" ).click(function(event) {
        //     /* Act on the event */
        //     $( '.acf-map' ).show();
        //     $( '#list-view' ).hide();
        // });

        $( '#content' ).on('click', '#map-view:not(.clicked)', function(event) {
            event.preventDefault();
            /* Act on the event */
            $( this ).addClass('clicked');

            $( '#programs-list' ).animate({
                left: '-100%'
            }, 500, function() {
                $(this).css('left', '150%');
                $(this).appendTo('#container');
            });

            $( '#programs-list' ).next().animate({
                left: '0%'
            }, 500);

            $( '#list-view' ).removeClass('clicked');
        });

        $( '#content' ).on('click', '#list-view:not(.clicked)', function(event) {
            event.preventDefault();
            /* Act on the event */
            $( this ).addClass('clicked');
            $( '#programs-map' ).animate({
                left: '-100%'
            }, 500, function() {
                $(this).css( { 'left': '150%', } );
                $(this).appendTo('#container');
            });

            $( '#programs-map' ).next().animate({
                left: '0%'
            }, 500);

            $( '#map-view' ).removeClass('clicked');
        });
        
    });
    
})(jQuery)

    /*  jQuery ready function. Specify a function to execute when the DOM is fully loaded.  */
// $(document).ready();