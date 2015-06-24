<?php
require_once('custom-post-types.php');
// require_once('custom-media-fields.php');
require_once('admin-columns.php');

function custom_js_script() {
    wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/js/test.js', array( 'jquery'), false, false);
    //wp_enqueue_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?v=3.exp', array(), false, false);
    wp_enqueue_script('marker-with-label', get_stylesheet_directory_uri() . '/js/markerwithlabel.js', array(), false, true);
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('plugin_name-admin-ui-css',
        'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/smoothness/jquery-ui.css',
        false,
        false,
        false
    );
}
add_action('wp_enqueue_scripts', 'custom_js_script');


// function restrict_admin()
// {
//     if ( ! current_user_can( 'manage_options' ) && '/wp-admin/admin-ajax.php' != $_SERVER['PHP_SELF'] ) {
//                 wp_redirect( site_url() );
//     }
// }
// add_action( 'admin_init', 'restrict_admin', 1 );

add_action( 'wp_login_failed', 'pu_login_failed' ); // hook failed login

function pu_login_failed( $user ) {
    // check what page the login attempt is coming from
    $referrer = $_SERVER['HTTP_REFERER'];

    // check that were not on the default login page
    if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') && $user!=null ) {
        // make sure we don't already have a failed login attempt
        if ( !strstr($referrer, '?login=failed' )) {
            // Redirect to the login page and append a querystring of login failed
            wp_redirect( $referrer . '?login=failed');
        } else {
            wp_redirect( $referrer );
        }

        exit;
    }
}

add_action( 'authenticate', 'pu_blank_login');

function pu_blank_login( $user ){
    // check what page the login attempt is coming from
    $referrer = $_SERVER['HTTP_REFERER'];

    $error = false;

    if($_POST['log'] == '' || $_POST['pwd'] == '')
    {
        $error = true;
    }

    // check that were not on the default login page
    if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') && $error ) {

        // make sure we don't already have a failed login attempt
        if ( !strstr($referrer, '?login=failed') ) {
            // Redirect to the login page and append a querystring of login failed
            wp_redirect( $referrer . '?login=failed' );
        } else {
            wp_redirect( $referrer );
        }

    exit;

    }
}


// Hide the preview button
function posttype_admin_css() {
    global $post_type;
    $post_types = array(
                        /* set post types */ 
                        'book',
                  );
    if(in_array($post_type, $post_types))
    echo '<style type="text/css">#post-preview, #view-post-btn{display: none;}</style>';
}
add_action( 'admin_head-post-new.php', 'posttype_admin_css' );
add_action( 'admin_head-post.php', 'posttype_admin_css' );

// Change Update to Save
add_filter( 'gettext', 'change_publish_button', 10, 2 );

function change_publish_button( $translation, $text ) {
if ( 'book' == get_post_type())
if ( $text == 'Update' || $text == 'Publish' )
    return 'Save';

return $translation;
}

// Change Post Updated Messages
// add_filter( 'post_updated_messages', 'codex_book_updated_messages' );
// /**
//  * Book update messages.
//  *
//  * See /wp-admin/edit-form-advanced.php
//  *
//  * @param array $messages Existing post update messages.
//  *
//  * @return array Amended post update messages with new CPT update messages.
//  */
// function codex_book_updated_messages( $messages ) {
//     $post             = get_post();
//     $post_type        = get_post_type( $post );
//     $post_type_object = get_post_type_object( $post_type );

//     $messages['book'] = array(
//         0  => '', // Unused. Messages start at index 1.
//         1  => __( 'Book saved.', 'your-plugin-textdomain' ),
//         2  => __( 'Custom field updated.', 'your-plugin-textdomain' ),
//         3  => __( 'Custom field deleted.', 'your-plugin-textdomain' ),
//         4  => __( 'Book updated.', 'your-plugin-textdomain' ),
//         /* translators: %s: date and time of the revision */
//         5  => isset( $_GET['revision'] ) ? sprintf( __( 'Book restored to revision from %s', 'your-plugin-textdomain' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
//         6  => __( 'Book published.', 'your-plugin-textdomain' ),
//         7  => __( 'Book saved.', 'your-plugin-textdomain' ),
//         8  => __( 'Book submitted.', 'your-plugin-textdomain' ),
//         9  => sprintf(
//             __( 'Book scheduled for: <strong>%1$s</strong>.', 'your-plugin-textdomain' ),
//             // translators: Publish box date format, see http://php.net/date
//             date_i18n( __( 'M j, Y @ G:i', 'your-plugin-textdomain' ), strtotime( $post->post_date ) )
//         ),
//         10 => __( 'Book draft updated.', 'your-plugin-textdomain' )
//     );

//     if ( $post_type_object->publicly_queryable ) {
//         $permalink = get_permalink( $post->ID );

//         $view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View book', 'your-plugin-textdomain' ) );
//         $messages[ $post_type ][1] .= $view_link;
//         $messages[ $post_type ][6] .= $view_link;
//         $messages[ $post_type ][9] .= $view_link;

//         $preview_permalink = add_query_arg( 'preview', 'true', $permalink );
//         $preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview book', 'your-plugin-textdomain' ) );
//         $messages[ $post_type ][8]  .= $preview_link;
//         $messages[ $post_type ][10] .= $preview_link;
//     }

//     return $messages;
// }

// Remove update notifications for anyone who isn't admin
function hide_update_notice_to_all_but_admin_users() {
    if (!current_user_can('update_core')) {
        remove_action( 'admin_notices', 'update_nag', 3 );
    } 
}

add_action( 'admin_head', 'hide_update_notice_to_all_but_admin_users', 1 );

/*
** Add Custom Roles
*/
$result = add_role( 'registered_user', 'Registered User', array(
    'read' => true, // True allows that capability
    'edit_posts' => true,
    'delete_posts' => false, // Use false to explicitly deny
));

add_filter('wp_count_posts', 'wpse149143_wp_count_posts', 10, 3);


    /**
     * Modify returned post counts by status for the current post type.
     *  Only retrieve counts of own items for users without rights to 'edit_others_posts'
     *
     * @since   26 June 2014
     * @version 26 June 2014
     * @author  W. van Dam
     *
     * @notes   Based on wp_count_posts (wp-includes/posts.php)
     *
     * @param object $counts An object containing the current post_type's post
     *                       counts by status.
     * @param string $type   Post type.
     * @param string $perm   The permission to determine if the posts are 'readable'
     *                       by the current user.
     * 
     * @return object Number of posts for each status
     */
    function wpse149143_wp_count_posts( $counts, $type, $perm ) {
        global $wpdb;

        // We only want to modify the counts shown in admin and depending on $perm being 'readable' 
        if ( ! is_admin() || 'readable' !== $perm ) {
            return $counts;
        }

        // Only modify the counts if the user is not allowed to edit the posts of others
        $post_type_object = get_post_type_object($type);
        if (current_user_can( $post_type_object->cap->edit_others_posts ) ) {
            return $counts;
        }

        $query = "SELECT post_status, COUNT( * ) AS num_posts FROM {$wpdb->posts} WHERE post_type = %s AND (post_author = %d) GROUP BY post_status";
        $results = (array) $wpdb->get_results( $wpdb->prepare( $query, $type, get_current_user_id() ), ARRAY_A );
        $counts = array_fill_keys( get_post_stati(), 0 );

        foreach ( $results as $row ) {
            $counts[ $row['post_status'] ] = $row['num_posts'];
        }

        return (object) $counts;
    }

    // function remove_views( $views ) {
    //     unset($views['all']);
    //     // print_r($views);
    //     return $views;
    // }
    // add_action( 'views_edit-child', 'remove_views' );

    /** 
     * Snippet Name: Only show current author posts and content in wp-admin 
     * Snippet URL: http://www.wpcustoms.net/snippets/only-show-current-author-posts-and-content-in-wp-admin/ 
     */  
     // Show only posts and media related to logged in author  
    function query_set_only_author( $wp_query ) {  
        global $current_user;  
        if( is_admin() && !current_user_can('edit_others_posts') ) {  
            $wp_query->set( 'author', $current_user->ID );  
            add_filter('views_edit-child', 'fix_post_counts');
        }  
    }  
    add_action('pre_get_posts', 'query_set_only_author' );  
      
      
    // Fix post counts  
    function fix_post_counts($views) {  
        global $current_user, $wp_query;  
        //unset($views['all']);  
        $types = array(  
            array( 'status' => 'mine' ),
            array( 'status' => 'all' ),
            array( 'status' => 'publish' ),  
            array( 'status' => 'draft' ),  
            array( 'status' => 'pending' ),  
            array( 'status' => 'trash' )  
        );  
        //echo '<pre>'; print_r($wp_query->query_vars); echo '</pre>'; 
        $i = 1;
        foreach( $types as $type ) {  
            $query = array(  
                'author'      => $current_user->ID,  
                'post_type'   => 'child',  
                'post_status' => $type['status'],
            );
            $result = new WP_Query($query);
        
            if( $type['status'] == 'mine' ) :
                $class = ( strpos($_SERVER['REQUEST_URI'], "author") !== false ) ? ' class="current"' : '';
                $views['mine'] = sprintf(__('<a href="%s" ' . $class . '>My Children <span class="count">(%d)</span></a>', 'mine'),  
                    admin_url('edit.php?post_type=' . $wp_query->query['post_type'] . '&author=' . $current_user->ID ),  
                    $result->found_posts);
        
            elseif( $type['status'] == 'all' ):
                //$class = 'class="current"';
                $class = ( strpos($_SERVER['REQUEST_URI'], "all_posts") !== false ) ? ' class="current"' : '';
                $views['all'] = sprintf(__('<a href="%s" ' . $class . '>All Children<span class="count">(%d)</span></a>', 'all'),  
                    admin_url('edit.php?post_type=' . $wp_query->query['post_type'] . '&all_posts=1'),  
                    $result->found_posts);

            //$wp_query->query_vars['post_status'] = 0;
            elseif( $type['status'] == 'publish' ):
                if (isset($wp_query->query_vars['post_status'])) {  
                    $class = ($wp_query->query_vars['post_status'] == 'publish') ? ' class="current"' : '';  
                } else {
                    $class = 'class=""';
                }
                $views['publish'] = sprintf(__('<a href="%s" ' . $class . '>Active Children <span class="count">(%d)</span></a>', 'publish'),  
                    admin_url('edit.php?post_status=publish&post_type=' . $wp_query->query['post_type']),  
                    $result->found_posts);
            elseif( $type['status'] == 'draft' ):
                if (isset($wp_query->query_vars['post_status'])) {
                    $class = ($wp_query->query_vars['post_status'] == 'draft') ? ' class="current"' : '';  
                } else {
                    $class = 'class=""';
                }
                $views['draft'] = sprintf(__('<a href="%s" ' . $class . '>Draft'. ((sizeof($result->posts) > 1) ? "s" : "") .' <span class="count">(%d)</span></a>', 'draft'),  
                    admin_url('edit.php?post_status=draft&post_type=' . $wp_query->query['post_type']),  
                    $result->found_posts);  
            elseif( $type['status'] == 'pending' ):  
                if (isset($wp_query->query_vars['post_status'])) {
                    $class = ($wp_query->query_vars['post_status'] == 'pending') ? ' class="current"' : '';  
                } else {
                    $class = 'class=""';
                }
                $views['pending'] = sprintf(__('<a href="%s" ' . $class . '="">Pending <span class="count">(%d)</span></a>', 'pending'),  
                    admin_url('edit.php?post_status=pending&post_type=' . $wp_query->query['post_type']),  
                    $result->found_posts);  
            elseif( $type['status'] == 'trash' ):
                if (isset($wp_query->query_vars['post_status'])) {
                    $class = ($wp_query->query_vars['post_status'] == 'trash') ? ' class="current"' : '';  
                } else {
                    $class = 'class=""';
                }
                $views['trash'] = sprintf(__('<a href="%s" ' . $class . '="">Trash <span class="count">(%d)</span></a>', 'trash'),  
                    admin_url('edit.php?post_status=trash&post_type=' . $wp_query->query['post_type']),  
                    $result->found_posts);  
            endif;
        }
        return $views;  
    }

// Allows query vars to be added, removed, or changed prior to executing the query.
function themeslug_query_vars( $qvars ) {
    $qvars[] = 'ai'; //associated_interests
    $qvars[] = 'dow';
    $qvars[] = 'chid'; // child's id
    $qvars[] = 'age'; // min age
    $qvars[] = 'maa'; // max age
    $qvars[] = 'sd'; // start end
    $qvars[] = 'ed'; // end date
    $qvars[] = 'org'; // organization
    $qvars[] = 'addy'; // user's address
    $qvars[] = 'sr'; // sort results
    $qvars[] = 'pr'; // price
    return $qvars;
}
add_filter( 'query_vars', 'themeslug_query_vars' , 10, 1 );

// update empty prog_date_start fields

// function update_empty_prog_date_start() {
//     $today = date( 'Ymd' );
//     //var_dump($today);
//     $args = array( 'post_type' => 'cpt_program');
//     $myposts = get_posts( $args );
//     foreach ( $myposts as $post ) : setup_postdata( $post );
//         if ( get_field( 'prog_ongoing' ) ) {
//             print_r('true');
//             update_field( 'field_553fd33b74777', $today, $post->ID );
//         }
//         else { print_r('false'); }
//     endforeach;
//     wp_reset_postdata();
// }
// add_action( 'init', 'update_empty_prog_date_start' );

/* Change default excerpt length */

function new_custom_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'new_custom_excerpt_length', 999 );



// Return areas by country
function interests_by_category( $selected_category ) {
 
  // Verify nonce
  if( !isset( $_POST['pi_nonce'] ) || !wp_verify_nonce( $_POST['pi_nonce'], 'pi_nonce' ) )
    die('Permission denied');
 
    // Get country var
    $selected_category = $_POST['interest_cats'];
 
    // Pull interests based on category

    $args = array(
        'category__in' => $selected_category,
        'post_type' => 'cpt_interest',
    );  
    $arr_data['id'] = $arr_data['name'] = $arr_data['url'] = array();
    $the_query = new WP_Query( $args );
    if ( $the_query->have_posts() ) {
        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            $postid = get_the_id();
            $post_title = get_the_title();
            $int_permalink = get_the_permalink();
            array_push( $arr_data['id'], $postid );
            array_push( $arr_data['name'], $post_title );
            array_push( $arr_data['url'], $int_permalink );
        }
    }
    // Convert areas to array
  //   $arr_data = explode( ', ', $countries[$selected_country] );
  //   return wp_send_json($arr_data);

    return wp_send_json($arr_data);
 
    die();
}
 
add_action('wp_ajax_pi_add_interests', 'interests_by_category');
add_action('wp_ajax_nopriv_pi_add_interests', 'interests_by_category');



// ACF Dynamic Filter
// function my_relationship_query( $args, $field, $post )
// {
//     // Pull interests based on category
//     $args['category__in'] = array('7');
//     return $args;
// }
// filter for a specific field based on it's name
// add_filter('acf/fields/relationship/query/name=associated_interests', 'my_relationship_query', 10, 3);

// Enqueue AJAX script to autopopulate ACF filed based on another fields choices
function acf_admin_enqueue( $hook ) {
 
    // $type = get_post_type(); // Check current post type
    // $types = array( 'cpt_program' ); // Allowed post types

    // if( !in_array( $type, $types ) )
    //     return; // Only applies to post types in array

    wp_enqueue_script( 'populate-interest', get_stylesheet_directory_uri() . '/js/ajax-autopopulate.js' );

    wp_localize_script( 'populate-interest', 'pi_vars', array(
            'pi_nonce' => wp_create_nonce( 'pi_nonce' ), // Create nonce which we later will use to verify AJAX request
        )
    );
}
 
add_action( 'admin_enqueue_scripts', 'acf_admin_enqueue' );



// Location Class

    class Location {

        function __construct() {
            $this->check_prog_location();
        }
        
        function check_prog_location() {
            global $location_titles;
            if( get_field('prog_location') ) {
                // create object and return it
                $this->my_location = get_field('prog_location');
                //$this->location_title = get_the_title();
                
                $this->has_loc = " pinned";
                //array_push( $locations[$i], get_the_title() );
                // var_dump($has_location);
            } else {
                $this->has_loc = "";
                $this->my_location = "";
                
            }
            $this->location_title = get_the_title();
            array_push($location_titles, $this->location_title);
        }
        
    }

function orderbyreplace($orderby) {
    return str_replace('wp_posts.menu_order', 'mt1.meta_value', $orderby);
}
add_filter('posts_orderby','orderbyreplace');

remove_filter('posts_orderby','orderbyreplace');
