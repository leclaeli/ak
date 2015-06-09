<?php 
/**
* Registers a new post type
* @uses $wp_post_types Inserts new post type object into the list
*
* @param string  Post type key, must not exceed 20 characters
* @param array|string  See optional args description above.
* @return object|WP_Error the registered post type object, or an error object
*/
// function prefix_register_name() {

//     $labels_customers = array(
//         'name'                => __( 'Customers', 'text-domain' ),
//         'Customer_name'       => __( 'Customer', 'text-domain' ),
//         'add_new'             => _x( 'Add New Customer Name', 'text-domain', 'text-domain' ),
//         'add_new_item'        => __( 'Add New Customer Name', 'text-domain' ),
//         'edit_item'           => __( 'Edit Customer Name', 'text-domain' ),
//         'new_item'            => __( 'New Customer Name', 'text-domain' ),
//         'view_item'           => __( 'View Customer Name', 'text-domain' ),
//         'search_items'        => __( 'Search Customers Name', 'text-domain' ),
//         'not_found'           => __( 'No Customers Name found', 'text-domain' ),
//         'not_found_in_trash'  => __( 'No Customers Name found in Trash', 'text-domain' ),
//         'parent_item_colon'   => __( 'Parent Customer Name:', 'text-domain' ),
//         'menu_name'           => __( 'Customers', 'text-domain' ),
//     );

//     $args = array(
//         'labels'              => $labels_customers,
//         'hierarchical'        => false,
//         'description'         => 'description',
//         'taxonomies'          => array(),
//         'public'              => false,
//         'show_ui'             => true,
//         'show_in_menu'        => true,
//         'show_in_admin_bar'   => true,
//         'menu_position'       => null,
//         'menu_icon'           => null,
//         'show_in_nav_menus'   => true,
//         'publicly_queryable'  => false,
//         'exclude_from_search' => false,
//         'has_archive'         => true,
//         'query_var'           => false,
//         'can_export'          => true,
//         'rewrite'             => true,
//         'capability_type'     => 'customer',
//         'capabilities'        => array(
//             'publish_posts' => 'publish_customers',
//             'edit_posts' => 'edit_customers',
//             'edit_others_posts' => 'edit_others_customers',
//             'delete_posts' => 'delete_customers',
//             'delete_others_posts' => 'delete_others_customers',
//             'read_private_posts' => 'read_private_customers',
//             'edit_post' => 'edit_customer',
//             'delete_post' => 'delete_customer',
//             'read_post' => 'read_customer',
//         ),
//         'supports'            => array(
//             'title', 'editor', 'author', 'thumbnail',
//             'excerpt','custom-fields', 'trackbacks', 'comments',
//             'revisions', 'page-attributes', 'post-formats'
//             )
//     );

//     register_post_type( 'cpt-customers', $args );
// }

// add_action( 'init', 'prefix_register_name' );



function create_my_post_types() {
    register_post_type(
        'cpt_child',
        array(
            'public' => true,
            'show_ui' => true,
            'label' => 'Children',
            'capability_type' => 'child',
            'capabilities' => array(
                'publish_posts' => 'publish_childs',
                'edit_posts' => 'edit_childs',
                'edit_others_posts' => 'edit_others_childs',
                'delete_posts' => 'delete_childs',
                'delete_others_posts' => 'delete_others_childs',
                'read_private_posts' => 'read_private_childs',
                'edit_post' => 'edit_child',
                'delete_post' => 'delete_child',
                'read_post' => 'read_child',
            ),
            'supports' => array('author','title'),
        )
    );

    register_post_type(
        'cpt_interest',
        array(
            'public' => false,
            'show_ui' => true,
            'label' => 'Interests',
            'capability_type' => 'interest',
            'taxonomies' => array('category'),
            'capabilities' => array(
                'publish_posts' => 'publish_interests',
                'edit_posts' => 'edit_interests',
                'edit_others_posts' => 'edit_others_interests',
                'delete_posts' => 'delete_interests',
                'delete_others_posts' => 'delete_others_interests',
                'read_private_posts' => 'read_private_interests',
                'edit_post' => 'edit_interest',
                'delete_post' => 'delete_interest',
                'read_post' => 'read_interest',
            ),
            'supports' => array('thumbnail', 'title'),
        )
    );

    register_post_type(
        'cpt_organization',
        array(
            'public' => false,
            'show_ui' => true,
            'label' => 'Organizations',
        )
    );

    register_post_type(
        'cpt_program',
        array(
            'public' => true,
            'show_ui' => true,
            'label' => 'Programs',
            'taxonomies' => array('category'),
            'supports' => array( 'title', 'editor', 'thumbnail' ),
        )
    );
}

add_action( 'init', 'create_my_post_types' );

// Register Taxonomy for Media Library/Attachments
/**
 * Create a taxonomy
 *
 * @uses  Inserts new taxonomy object into the list
 * @uses  Adds query vars
 *
 * @param string  Name of taxonomy object
 * @param array|string  Name of the object type for the taxonomy object.
 * @param array|string  Taxonomy arguments
 * @return null|WP_Error WP_Error if errors, otherwise null.
 */
function image_cats() {

    $labels = array(
        'name'                    => _x( 'Image Categories', 'Taxonomy plural name', 'text-domain' ),
        'singular_name'            => _x( 'Image Category', 'Taxonomy singular name', 'text-domain' ),
        'search_items'            => __( 'Search Image Categories', 'text-domain' ),
        'popular_items'            => __( 'Popular Image Categories', 'text-domain' ),
        'all_items'                => __( 'All Image Categories', 'text-domain' ),
        'parent_item'            => __( 'Parent Image Category', 'text-domain' ),
        'parent_item_colon'        => __( 'Parent Image Category', 'text-domain' ),
        'edit_item'                => __( 'Edit Image Category', 'text-domain' ),
        'update_item'            => __( 'Update Image Category', 'text-domain' ),
        'add_new_item'            => __( 'Add New Image Category', 'text-domain' ),
        'new_item_name'            => __( 'New Image Category Name', 'text-domain' ),
        'add_or_remove_items'    => __( 'Add or remove Image Categories', 'text-domain' ),
        'choose_from_most_used'    => __( 'Choose from most used text-domain', 'text-domain' ),
        'menu_name'                => __( 'Image Categories', 'text-domain' ),
    );

    $args = array(
        'labels'            => $labels,
        'show_admin_column' => true,
        'hierarchical'      => true,
    );

    register_taxonomy( 'image-categories', array( 'attachment' ), $args );
}

add_action( 'init', 'image_cats' );
