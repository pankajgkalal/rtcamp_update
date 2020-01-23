<?php 
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

function enqueue_parent_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}



function portfolio( )
{
    $labels = array(
        'name' => 'Portfolio',
        'singular_name' => 'Portfolio',
        'menu_name' => 'Portfolio',
        'parent_item_colon' => 'Parent Portfolio',
        'all_items' => 'All Portfolio',
        'view_item' => 'View Portfolio',
        'add_new_item' => 'Add New Portfolio',
        'add_new' => 'Add New',
        'edit_item' => 'Edit Portfolio',
        'update_item' => 'Update Portfolio',
        'search_items' => 'Search Portfolio',
        'not_found' => 'Not Found',
        'not_found_in_trash' => 'Not found in Trash' 
    );
    $args   = array(
         'label' => 'Portfolios',
        'description' => 'Portfolio',
        'labels' => $labels,
        'supports' => array(
             'title',
            'editor',
            'excerpt',
            'author',
            'thumbnail',
            'revisions',
            'custom-fields' 
        ),
        'public' => true,
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'has_archive' => true,
        'can_export' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'page' 
    );
    register_post_type( 'Portfolios', $args );
}
add_action( 'init', 'portfolio', 0 );

?>

