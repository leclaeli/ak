<?php
$query = new WP_Query( $args );
    // The Loop
    if ( $query->have_posts() ) {
        echo '<ul>';
        $i = 0;
        while ( $query->have_posts() ) {
            $query->the_post();
            $locations = get_field('prog_location');

            
            echo '<li id="' . get_the_id() . '" class="program-list' . '' . '"><a href="' . get_the_permalink() . '" >' . get_the_title()  . '</a></li>';
            //echo '<li>' . the_field('prog_date_start') . '</li>';
            //echo '<li>' . the_field('prog_ongoing') . '</li>';
            $i++;
        }
        echo '</ul>';
    } else {
        // no posts found
    }

    //echo "<pre>"; print_r($query->query_vars); echo "</pre>";

    /* Restore original Post Data */
    wp_reset_postdata();
    //print_r($locations);
?>
<div class="acf-map">
<?php

$i = 0;
    foreach ( $locations as $location ) { ?>
    <!-- var_dump($location); -->
        <div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>">
            <p class="program-name"><?php echo $location_title[$i]; ?></p>
            <p class="address"><?php echo $location['address']; ?></p>
        </div>
    <?php $i++;
     }
?>
</div>


<script type="text/javascript">
(function($) {

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
            labelContent: index + 1,
            labelAnchor: new google.maps.Point(0, 0),
            labelClass: "labels", // the CSS class for the label
            labelStyle: {opacity: 0.75},
            title : programName[index],
    });

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

/*
*  document ready
*
*  This function will render each map when the document is ready (page has loaded)
*
*  @type    function
*  @date    8/11/2013
*  @since   5.0.0
*
*  @param   n/a
*  @return  n/a
*/

$(document).ready(function(){

    $('.acf-map').each(function(){
        render_map( $(this) );
    });
    

});

})(jQuery);
</script>


<!-- Calculate Distance - Distance Matrix Service -->
<script>
var origin1 = new google.maps.LatLng(43.0816515, -87.8762654);

function calculateDistances() {
  var service = new google.maps.DistanceMatrixService();
  service.getDistanceMatrix(
    {
      origins: [origin1],
      destinations: progLocation,
      travelMode: google.maps.TravelMode.DRIVING,
      unitSystem: google.maps.UnitSystem.METRIC,
      avoidHighways: false,
      avoidTolls: false
    }, callback);
  console.log(progLocation);
}

function callback(response, status) {
    if (status != google.maps.DistanceMatrixStatus.OK) {
    alert('Error was: ' + status);
    } else {
    var origins = response.originAddresses;
    var destinations = response.destinationAddresses;
    var outputDiv = document.getElementById('outputDiv');
    outputDiv.innerHTML = '';
    //deleteOverlays();

        for (var i = 0; i < origins.length; i++) {
            var results = response.rows[i].elements;
            //addMarker(origins[i], false);
            for (var j = 0; j < results.length; j++) {
            //addMarker(destinations[j], true);
            outputDiv.innerHTML += origins[i] + ' to ' + destinations[j]
                + ': ' + results[j].distance.text + ' in '
                + results[j].duration.text + '<br>';
            document.getElementsByClassName('location')[j].setAttribute("distance", results[j].duration.value );
            console.log(results[j].duration);
            }
        }
    }
}
</script>

<div id="content-pane">
    <div id="inputs">
        <p>
            <button type="button" onclick="calculateDistances();">Calculate distances</button>
        </p>
    </div>
    <div id="outputDiv"></div>
</div>