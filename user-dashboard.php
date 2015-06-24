<?php
/*
* Template Name: User Dashboard
*/
?>

<?php get_header(); ?>
<script>
// This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

var placeSearch, autocomplete;
var componentForm = {
  street_number: 'short_name',
  route: 'long_name',
  locality: 'long_name',
  administrative_area_level_1: 'short_name',
  country: 'long_name',
  postal_code: 'short_name'
};

function initialize() {
  // Create the autocomplete object, restricting the search
  // to geographical location types.
  autocomplete = new google.maps.places.Autocomplete(
      /** @type {HTMLInputElement} */(document.getElementById('autocomplete')),
      { types: ['geocode'] });
  // When the user selects an address from the dropdown,
  // populate the address fields in the form.
  google.maps.event.addListener(autocomplete, 'place_changed', function() {
    fillInAddress();
  });
}

// [START region_fillform]
function fillInAddress() {
  // Get the place details from the autocomplete object.
  var place = autocomplete.getPlace();

  for (var component in componentForm) {
    document.getElementById(component).value = '';
    document.getElementById(component).disabled = false;
  }

  // Get each component of the address from the place details
  // and fill the corresponding field on the form.
  for (var i = 0; i < place.address_components.length; i++) {
    var addressType = place.address_components[i].types[0];
    if (componentForm[addressType]) {
      var val = place.address_components[i][componentForm[addressType]];
      document.getElementById(addressType).value = val;
    }
  }
}
// [END region_fillform]

// [START region_geolocation]
// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var geolocation = new google.maps.LatLng(
          position.coords.latitude, position.coords.longitude);
      var circle = new google.maps.Circle({
        center: geolocation,
        radius: position.coords.accuracy
      });
      autocomplete.setBounds(circle.getBounds());
    });
  }
}
// [END region_geolocation]

</script>


<h1>Dashboard</h1>
<?php
if ( is_user_logged_in() ) {
    /**
     * @example Safe usage: $current_user = wp_get_current_user();
     * if ( !($current_user instanceof WP_User) )
     *     return;
     */
    $current_user = wp_get_current_user();
    // echo 'Welcome ' . $current_user->display_name;
    // echo 'Username: ' . $current_user->user_login . '<br />';
    // echo 'User email: ' . $current_user->user_email . '<br />';
    // echo 'User first name: ' . $current_user->user_firstname . '<br />';
    // echo 'User last name: ' . $current_user->user_lastname . '<br />';
    // echo 'User display name: ' . $current_user->display_name . '<br />';
    // echo 'User ID: ' . $current_user->ID . '<br />';
?>

<div>
    
    <?php 
    global $post;
    $get_kids = array (
            'numberposts' => -1, 
            'post_type' => 'cpt_child',
            'author' => $current_user->ID,
        );
    $posts = get_posts( $get_kids );
        foreach( $posts as $post ) : setup_postdata($post); ?>
            <option value="<? echo $post->ID; ?>"><?php the_title(); ?></option> 
        <?php endforeach; ?> 
    <?php wp_reset_postdata(); ?>
</div>

<div>
    <form action="search-results/" method="get">
        <ul>
            <li><label for="dow2"><input id="dow2" type="checkbox" value="monday" name="dow[]">Monday</label></li>
            <li><label for="dow3"><input id="dow3" type="checkbox" value="tuesday" name="dow[]">Tuesday</label></li>
            <li><label for="dow4"><input id="dow4" type="checkbox" value="wednesday" name="dow[]">Wednesday</label></li>
            <li><label for="dow5"><input id="dow5" type="checkbox" value="thursday" name="dow[]">Thursday</label></li>
            <li><label for="dow6"><input id="dow6" type="checkbox" value="friday" name="dow[]">Friday</label></li>
            <li><label for="dow7"><input id="dow7" type="checkbox" value="saturday" name="dow[]">Saturday</label></li>
            <li><label for="dow1"><input id="dow1" type="checkbox" value="sunday" name="dow[]">Sunday</label></li>
        </ul>
        <label>Interests:</label>
        <select multiple name="ai[]" id="page_id" style="width: 300px;">
            <?php 
            global $post; 
            $args = array( 'numberposts' => -1, 'post_type' => 'cpt_interest' ); 
            $posts = get_posts($args);
            foreach( $posts as $post ) : setup_postdata($post); ?>
                <option value="<? echo $post->ID; ?>"><?php the_title(); ?></option> 
            <?php endforeach; 
            wp_reset_postdata();
            ?>
        </select>
        <label>Age:</label><input type="number" name="age" id="age" min="4" max="19" >
        <p>Start Date Before : <input type="text" id="datepicker" /></p>
        <label>Organizations:</label>
        <select multiple name="org[]" id="page_id" style="width: 300px;">
            <?php 
            global $post; 
            $args = array( 'numberposts' => -1, 'post_type' => 'cpt_organization' ); 
            $posts = get_posts($args);
            foreach( $posts as $post ) : setup_postdata($post); ?>
                <option value="<? echo $post->ID; ?>"><?php the_title(); ?></option> 
            <?php endforeach; ?>
        </select>
    <div id="locationField">
        <input id="autocomplete" placeholder="Enter your address" onFocus="geolocate()" type="text" name="addy"></input>
    </div>

<div>
<table id="address">
    <tr>
        <td class="label">Street address</td>
        <td class="slimField"><input class="field" id="street_number" disabled="true"></input></td>
        <td class="wideField" colspan="2"><input class="field" id="route" disabled="true"></input></td>
    </tr>
    <tr>
    <td class="label">City</td>
    <td class="wideField" colspan="3"><input class="field" id="locality"
          disabled="true"></input></td>
    </tr>
    <tr>
    <td class="label">State</td>
    <td class="slimField"><input class="field"
          id="administrative_area_level_1" disabled="true"></input></td>
    <td class="label">Zip code</td>
    <td class="wideField"><input class="field" id="postal_code"
          disabled="true"></input></td>
    </tr>
    <tr>
    <td class="label">Country</td>
    <td class="wideField" colspan="3"><input class="field"
          id="country" disabled="true"></input></td>
    </tr>
</table>
</div>
        <?php wp_reset_postdata(); ?>
        <input type="submit" value="Filter Results">
    </form>
    <?php //echo do_shortcode( '[searchandfilter taxonomies="category"]' ); ?>
</div>
    

    <a href="<?php echo wp_logout_url( home_url() ); ?>">Logout</a>
<?php
} else {
    echo 'Welcome, visitor!';
 

    if(isset($_GET['login']) && $_GET['login'] == 'failed')
    {
        ?>
            <div id="login-error" style="background-color: #FFEBE8;border:1px solid #C00;padding:5px;">
                <p>Login failed: You have entered an incorrect Username or password, please try again.</p>
            </div>
        <?php
    }

    wp_login_form();
    //wp_register();
    ?>
    <div><a href="<?php echo wp_registration_url(); ?>">Register</a></div>
    <div><a href="<?php echo wp_lostpassword_url(); ?>" title="Lost Password">Lost Password</a></div>
<?php 
}
?>
<script>
jQuery( document ).ready(function($) {
    initialize();
});
</script>


<?php 
get_sidebar();
//echo '<a href="' . esc_url( add_query_arg( 'associated_interests', '85', 'http://elijah.uwm.edu/wordpress/search-query'  ) ) . '">LINK</a>';
get_footer(); 
?>