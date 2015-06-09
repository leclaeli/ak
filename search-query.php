<?php
/*
* Template Name: Search Query
*/
get_header();
?>

<?php
    $interests = get_query_var( 'ai' ); // associated_interests
    $daysofweek = get_query_var( 'dow' ); // day of week
    $start_date = get_query_var( 'sd' ); // start date 
    $end_date = get_query_var( 'ed' ); // end date 
    $prog_orgs = get_query_var( 'org' ); // end date
    $age = get_query_var( 'age' ); 
    $user_address = ( get_query_var( 'addy' ) != 0 ? get_query_var( 'addy' ) : "Milwaukee, WI" );
    $sr = get_query_var( 'sr' ); // sort results
    switch ($sr) {
        case "title_za" :
            $order = 'DESC';
            $order_by = 'title';
            break;
        case "title_az" :
            $order = 'ASC';
            $order_by = 'title';
            break;
        case "date" :
            $order = 'ASC';
            $order_by = 'prog_date_start';
            break;
        default :
            $order = 'ASC' ;
            $order_by = array( 'prog_date_start' => 'ASC');
    }
?>

<?php 
    // if (empty($age)) {
    //     $min_age = 4;
    // }
    // if (empty($age)) {
    //     $max_age = 19;
    // }
    // if (empty($start_date)) {
    //     $start_date = 0;
    // }
    // if (empty($end_date)) {
    //     $end_date = 0;
    // }
    $start_date = date('Ymd');
    $end_date = 20991231;


?>

<?php 
    /**
     * The WordPress Query class.
     * @link http://codex.wordpress.org/Function_Reference/WP_Query
     *
     */

    //$daysofweek = array($monday, $tuesday);
    //$daysofweek = array('Saturday','Monday');
    //$interests = array(84,85);
    //$activity_level = array('Beginner', 'Advanced');
    // $date = date('Ymd'); // find todays date
    // $newdate = new DateTime();
    // $newdate->setDate(2099,12,31);
    // echo $newdate->format('m-d-Y');
    $args = array(
        
        'posts_per_page' => -1,
        'post_type' => 'cpt_program',
        'orderby' => $order_by,
        'order' => $order,
        //'meta_key' => 'prog_ongoing',
        'meta_query' => array(
            array (
                    'relation' => 'OR',
                    array (
                        'key' => 'prog_ongoing',
                        'value' => true,
                        'compare' => '=',
                    ),
                    array (
                        'relation' => 'AND',
                        array (
                            'key' => 'prog_date_start',
                            'value' => $start_date,
                            'compare' => '>=',
                        ),
                        array (
                            'key' => 'prog_date_end',
                            'value' => $end_date,
                            'compare' => '<=',
                        ),
                    )
                )
            )
            // array (
            //     'relation' => 'AND',
            //     array (
            //         'key' => 'prog_date_start',
            //         'value' => $start_date,
            //         'compare' => '>=',
            //     ),
            //     array (
            //         'key' => 'prog_date_end',
            //         'value' => $end_date,
            //         'compare' => '<=',
            //     ),
            // ))
            
            // array (
            //     'key' => 'prog_cost',
            //     'value' => $cost,
            //     'compare' => '<=',
            //     'type' => 'NUMERIC'
            // ),
        

    );

    // if ( !empty( $start_date ) ) {
    //     array_push( $args['meta_query'], array (
    //         array (
    //             'relation' => 'OR',
    //             array (
    //                 'key' => 'prog_ongoing',
    //                 'value' => true,
    //                 'compare' => '=',
    //             ),
    //             array (
    //                 'relation' => 'AND',
    //                 array (
    //                     'key' => 'prog_date_start',
    //                     'value' => $start_date,
    //                     'compare' => '>=',
    //                 ),
    //                 array (
    //                     'key' => 'prog_date_end',
    //                     'value' => $end_date,
    //                     'compare' => '<=',
    //                 ),
    //             )
    //         )
    //     ));
    // }

    if (!empty( $age )) {
        array_push($args['meta_query'],  array (
                'key' => 'prog_age_min',
                'value' => $age,
                'compare' => '<=',
                'type' => 'NUMERIC'
            ),
            array (
                'key' => 'prog_age_max',
                'value' => $age,
                'compare' => '>=',
                'type' => 'NUMERIC'
            ));
    }

    if (!empty( $interests )) {
        $i = 0;
        $ints['relation'] = 'OR';
        foreach ($interests as $interest) {
            $ints[$i] = array(
                'key' => 'associated_interests',
                'value' => '"' . $interest . '"',
                'compare' => 'LIKE'
            );
            $i++;
        }
        array_push( $args['meta_query'], $ints);
    }

    if (!empty( $daysofweek )) {
        $i = 0;
        $days['relation'] = 'OR';
        foreach ( $daysofweek as $day ) {
            $days[$i] = array(
                'key' => 'prog_days_offered',
                'value' => '"' . $day . '"',
                'compare' => 'LIKE'
            );
            $i++;
        }
        array_push( $args['meta_query'], $days );
    }

    if (!empty( $activity_level )) {
        $i = 0;
        $levels['relation'] = 'OR';
        foreach ( $activity_level as $level ) {
            $levels[$i] = array(
                'key' => 'prog_activity_level',
                'value' => '"' . $level . '"',
                'compare' => 'LIKE'
            );
            $i++;
        }
        array_push( $args['meta_query'], $levels );
    }

    if (!empty( $prog_orgs )) {
        $i = 0;
        $orgs['relation'] = 'OR';
        foreach ( $prog_orgs as $org ) {
            $orgs[$i] = array(
                'key' => 'prog_organization',
                'value' => '"' . $org . '"',
                'compare' => 'LIKE'
            );
            $i++;
        }
        array_push( $args['meta_query'], $orgs );
    }
?>

<div>
    <form action="search-results/" method="get">
        <select name="sr" id="sort-results" onchange="this.form.submit()">
            <option value="title_az">Sort by Title: A-Z</option>
            <option value="title_za">Sort by Title: Z-A</option>
            <option value="date">Sort by Date</option>
        </select>
    </form>
</div>

<?php
$query = new WP_Query( $args );
echo '<span>' . ($query->found_posts) . '</span>';
    // The Loop
    if ( $query->have_posts() ) {
        echo '<ul>';
        $locations = array();
        $location_titles = array();
         //echo '<pre>'; print_r($query->query_vars); echo '</pre>'; 
        while ( $query->have_posts() ) {
            $query->the_post();
           if ( !get_field( 'prog_date_start' ) ) {
        //echo "not empty";
    }
            // ACF Fields
            $age_min = get_field( 'prog_age_min' );
            $age_max = get_field( 'prog_age_max' );
            if ( get_field( 'prog_date_start' ) ) {
                $date_start_obj = DateTime::createFromFormat( 'Ymd', get_field( 'prog_date_start' ) );
                $date_start = $date_start_obj->format('m/d/y');
            } else {
                //$date_start = "Contact organization";
                //$date_start = get_field( 'prog_ongoing' );
                $date_start = get_field( 'prog_date_start' );
                // var_dump($date_start);
            }
            if ( get_field( 'prog_date_end' ) ) {
                $date_end_obj = DateTime::createFromFormat( 'Ymd', get_field( 'prog_date_end' ) );
                $date_end = '&#150;' . $date_end_obj->format('m/d/y');
            } else {
                $date_end = "";
            }
            $cost = get_field( 'prog_cost' );
            $days_offered = get_field( 'prog_days_offered' );
            $experience = ( get_field( 'prog_activity_level' ) ? implode( ", ", get_field( 'prog_activity_level' ) ) : "All" );
            $organization = get_field( 'prog_organization' );
            $ongoing = get_field( 'prog_ongoing' );
            //$prog_cats = get_field( 'prog_categories' );


            $loc = new Location();
            array_push($locations, $loc->my_location);
            ?>

            <li id="<?php the_id(); ?>" class="program-list <?php echo $loc->has_loc; ?> " >
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <?php the_excerpt(); ?>
                <div class="programs-meta-fields">
                    <ul>
                        <li>Age: <?php echo $age_min . '&#150;' . $age_max; ?></li>
                        <li>Date: <?php echo $date_start . $date_end . $ongoing; ?> </li>
                        <li>Cost: <?php echo '$' . $cost; ?></li>
                    </ul>
                    <ul>
                        <li class="distance <?php echo $loc->has_loc; ?>">Distance: Contact organization for location.</li>
                        <li>Days: <?php echo implode( ", ", $days_offered ); ?> </li>
                        <li>Experience: <?php echo $experience; ?> </li>
                    </ul>
                </div>
            </li>  

        <?php
        }  
        echo '</ul>';
    } else {
        // no posts found
    }

    /* Restore original Post Data */
    wp_reset_postdata();
?>


<div>
</div>

<div class="acf-map">
<?php

$i = 0;
    foreach ( $locations as $location ) { 
        if ( $location != NULL ) {?>
        <div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>">
            <p class="program-name"><?php echo $location_titles[$i]; ?></p>
            <p class="address"><?php echo $location['address']; ?></p>
        </div>
        <?php 
        }
        $i++;
     }
?>
</div>


<!-- ACF Google Maps -->

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

    // calculate distance on load
    calculateDistances();
    // append distance to program list
    // $('.program-list').each(function(index, el) {
    //     var dist = $( el ).attr( 'distance' );
    //     console.log(dist);
    //     $( el ).find('.distance').html( dist );
    // });

});

})(jQuery);
</script>

<!-- Calculate Distance - Distance Matrix Service -->
<script>
// var origin1 = new google.maps.LatLng(43.0816515, -87.8762654);
var origin1 = '<?php echo $user_address; ?>';

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
            document.getElementsByClassName('pinned')[j].setAttribute("distance", results[j].duration.value );
            document.getElementsByClassName('pinned distance')[j].innerHTML = "Distance: " + results[j].distance.text;
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







<?php get_footer(); ?>
