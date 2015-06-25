/*
*  ACF Google Maps
*/

(function($) {
console.log('running');
/*
*  render_map
*
*  This function will render a Google Map onto the selected jQuery element
*
*  @type    function
*  @date    8/11/2013
*  @since   4.3.0
*
*  @param   $el (jQuery element)
*  @return  n/a
*/
function render_map( $el ) {

    // var
    var $markers = $el.find('.marker');

    programName = [];
    $('.program-name').each(function(index, el) {
        programName[index] = $(this).html();
    });

    // vars
    var args = {
        zoom        : 16,
        center      : new google.maps.LatLng(0, 0),
        mapTypeId   : google.maps.MapTypeId.ROADMAP
    };

    // create map               
    var map = new google.maps.Map( $el[0], args);
    geocoder = new google.maps.Geocoder();

    // add a markers reference
    map.markers = [];

    // add markers
    $markers.each(function(index){
        add_marker( $(this), map, index );
    });

    // center map
    center_map( map );

    //center labels
    $( '#map-view' ).one( "click", function(event) {
        centerMarkerLabels();
    });
    
}

/*
*  add_marker
*
*  This function will add a marker to the selected Google Map
*
*  @type    function
*  @date    8/11/2013
*  @since   4.3.0
*
*  @param   $marker (jQuery element)
*  @param   map (Google Map object)
*  @return  n/a
*/
progLocation = [];
function add_marker( $marker, map, index ) {
    
    // var
    var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );
    var onlyLatLng = $marker.attr('data-lat') + ', ' + $marker.attr('data-lng');
    progLocation[index] = onlyLatLng;
    // create marker
    var marker = new MarkerWithLabel({
        position: latlng,
        map: map,
        draggable: true,
        labelContent: programName[index],
        labelAnchor: new google.maps.Point(0, 0),
        labelClass: "labels", // the CSS class for the label
        labelStyle: {opacity: 0.75},
        title : programName[index],
    });
    console.log(marker);
    // add to array
    map.markers.push( marker );

    // if marker contains HTML, add it to an infoWindow
    if( $marker.html() )
    {
        // create info window
        var infowindow = new google.maps.InfoWindow({
            content     : $marker.html()
        });

        // show info window when marker is clicked
        google.maps.event.addListener(marker, 'click', function() {
            infowindow.open( map, marker );
        });
    }

}

/*
*  center_map
*
*  This function will center the map, showing all markers attached to this map
*
*  @type    function
*  @date    8/11/2013
*  @since   4.3.0
*
*  @param   map (Google Map object)
*  @return  n/a
*/

function center_map( map ) {

    // vars
    var bounds = new google.maps.LatLngBounds();

    // loop through all markers and create bounds
    $.each( map.markers, function( i, marker ){
        var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );
        bounds.extend( latlng );
    });

    // only 1 marker?
    if( map.markers.length == 1 )
    {
        // set center of map
        map.setCenter( bounds.getCenter() );
        map.setZoom( 16 );
    }
    else
    {
        // fit to bounds
        map.fitBounds( bounds );
    }
}

function centerMarkerLabels() {
    // get width of google maps marker labels and center them
    $( '.labels' ).each(function(index, el) {
        var labelWidth = $( el ).width() / 2;
        var cssLeft = parseInt( $( el ).css( 'left' ) );
        var newLeft = cssLeft - labelWidth ;
        $( el ).css('left', newLeft );
    });
}

/*
*  This function will render each map and calculate distances when the document is ready (page has loaded)
*/

$(document).ready(function(){
    var $markers = $( '#programs-map' ).find( '.marker' );
    $markers.each(function(index){
        var onlyLatLng = $( this ).attr('data-lat') + ', ' + $( this    ).attr('data-lng');
        progLocation[index] = onlyLatLng;
    });

    calculateDistances();
});

// Calculate Distance - Distance Matrix Service
// var origin1 = '<?php echo $user_address; ?>';
var origin1 = 'Milwaukee, WI';

function calculateDistances() {
  var service = new google.maps.DistanceMatrixService();
  service.getDistanceMatrix(
    {
      origins: [origin1],
      destinations: progLocation,
      travelMode: google.maps.TravelMode.DRIVING,
      unitSystem: google.maps.UnitSystem.IMPERIAL,
      avoidHighways: false,
      avoidTolls: false
    }, callback);
}

function callback(response, status) {
    if (status != google.maps.DistanceMatrixStatus.OK) {
    console.log('Error was: ' + status);
    } else {
    var origins = response.originAddresses;
    var destinations = response.destinationAddresses;
    var outputDiv = document.getElementById('outputDiv');
    outputDiv.innerHTML = '';
    //deleteOverlays();

        for (var i = 0; i < origins.length; i++) {
            console.log(response);
            var results = response.rows[i].elements;
            //addMarker(origins[i], false);
            for (var j = 0; j < results.length; j++) {
                //addMarker(destinations[j], true);
                outputDiv.innerHTML += origins[i] + ' to ' + destinations[j]
                + ': ' + results[j].distance.text + ' in '
                + results[j].duration.text + '<br>';
                document.getElementsByClassName('program-list pinned')[j].setAttribute("data-distance", results[j].distance.value );
                document.getElementsByClassName('distance pinned')[j].innerHTML = "Distance: " + results[j].distance.text;
            }
        }
        howFarIsIt();
    }
}

// Distance filter based on user input
function howFarIsIt() {
    $( '.program-list.pinned').each(function(index, el) {
        var howFar = $( el ).attr('data-distance');
        var progId = $( el ).attr( 'id' );
        var maxDistance = gmap.distance;
        console.log( howFar + ' : ' + maxDistance );
        if (howFar > parseInt( maxDistance ) ) {
            $( el ).remove();
            $( '.marker[id="' + progId + '"]' ).remove();
            //$( '.marker' ).hide();
        };
        // render the map
    });

    $('.acf-map').each(function(){
        render_map( $(this) );
    });
    myLateFunction();
}

})(jQuery);
