(function($){
    $(function() {
        $( "#datepicker" ).datepicker({
            changeMonth: true,
            changeYear: true
        });
     
// Programs list and map view slider (should be able to consolidate)
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
        
// AJAX Test
        $.ajax({
            url: testing.ajax_url,
            type: 'post',
            data: {
                action: 'post_love_add_love',
                post_id: 'Great to be AJAXED', 
            },
            success: function( response ) {
                //console.log(response);
            }
        });
    });
})(jQuery)

// Count results (needs to run after google maps) - called from howFarIsIt() in google-maps.js
function myLateFunction() {
    var totalResults = jQuery( '.program-list' ).filter(':visible').length;
    jQuery( '.total-results' ).text( "Programs Found: " + totalResults );
    console.log('test');
}

/*  jQuery ready function. Specify a function to execute when the DOM is fully loaded.
$(document).ready(); */