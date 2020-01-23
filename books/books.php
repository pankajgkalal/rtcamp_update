<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.facebook.com/panakaj.kalal
 * @since             1.0.0
 * @package           Books
 *
 * @wordpress-plugin
 * Plugin Name:       wp-book
 * Plugin URI:        https://www.facebook.com/panakaj.kalal
 * Description:       This is Book management plugin.
 * Version:           1.0.0
 * Author:            pankaj
 * Author URI:        https://www.facebook.com/panakaj.kalal
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       books
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
define( "PLUGIN_URL", plugins_url() );
if ( !defined( 'WPINC' ) ) {
    die;
} //!defined( 'WPINC' )
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BOOKS_VERSION', '1.0.0' );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-books-activator.php
 */
function activate_books( )
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-books-activator.php';
    Books_Activator::activate();
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-books-deactivator.php
 */
function deactivate_books( )
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-books-deactivator.php';
    Books_Deactivator::deactivate();
}
register_activation_hook( __FILE__, 'activate_books' );
register_deactivation_hook( __FILE__, 'deactivate_books' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-books.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_books( )
{
    $plugin = new Books();
    $plugin->run();
}
run_books();
//Create a custom post type Book
function book_post_type( )
{
    $labels = array(
         'name' => 'Books',
        'singular_name' => 'Book',
        'menu_name' => 'Books',
        'parent_item_colon' => 'Parent Book',
        'all_items' => 'All Books',
        'view_item' => 'View Book',
        'add_new_item' => 'Add New Book',
        'add_new' => 'Add New',
        'edit_item' => 'Edit Book',
        'update_item' => 'Update Book',
        'search_items' => 'Search Book',
        'not_found' => 'Not Found',
        'not_found_in_trash' => 'Not found in Trash' 
    );
    $args   = array(
         'label' => 'books',
        'description' => 'Books',
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
    register_post_type( 'books', $args );
}
add_action( 'init', 'book_post_type', 0 );
//Create a custom hierarchical taxonomy Book Category
function create_books_category_taxonomy( )
{
    $labels = array(
         'name' => 'Book Category',
        'singular_name' => 'Type',
        'search_items' => 'Search Book Category',
        'all_items' => 'All Book Category',
        'parent_item' => 'Parent Type',
        'parent_item_colon' => 'Parent Type:',
        'edit_item' => 'Edit Ty    pe',
        'update_item' => 'Update Type',
        'add_new_item' => 'Add New Type',
        'new_item_name' => 'New Type Name',
        'menu_name' => 'Book Category' 
    );
    register_taxonomy( 'Book_Category', array(
         'books' 
    ), array(
         'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array(
             'slug' => 'type') 
             )
    );
}
add_action( 'init', 'create_books_category_taxonomy' );
//Create a custom non-hierarchical taxonomy Book Tag
function create_books_non_hierarchical_custom_taxonomy_tag( )
{
    $labels = array(
         'name' => 'Book Tag',
        'singular_name' => 'Book Tag',
        'search_items' => 'Search Tags',
        'all_items' => 'All Book Tag',
        'parent_item' => 'Parent Book Tag',
        'parent_item_colon' => 'Parent Book Tag:',
        'edit_item' => 'Edit Book Tag',
        'update_item' => 'Update Book Tag',
        'add_new_item' => 'Add New Book Tag',
        'new_item_name' => 'New Book Tag Name',
        'menu_name' => 'Book Tags' 
    );
    register_taxonomy( 'tags', array(
         'books' 
    ), array(
         'hierarchical' => false,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array(
             'slug' => 'tag' 
        ) 
    ) );
}
add_action( 'init', 'create_books_non_hierarchical_custom_taxonomy_tag' );
//Create a custom meta box to save book meta information like Author Name, Price, Publisher, Year, Edition, URL, etc.
// override normal meta methods with custom methods 
function book_meta_install( )
{
    global $wpdb;
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    $table_name       = $wpdb->prefix . 'bookmeta';
    $max_index_length = 191;
    $install_query    = "CREATE TABLE $table_name (
        meta_id bigint(20) unsigned NOT NULL auto_increment,
        book_id bigint(20) unsigned NOT NULL default '0',
        meta_key varchar(255) default NULL,
        meta_value longtext,
        PRIMARY KEY  (meta_id),
        KEY book (book_id),
        KEY meta_key (meta_key($max_index_length))
    )";
    dbDelta( $install_query );
}
register_activation_hook( __FILE__, 'book_meta_install' );
// hook into init for single site, priority 0 = highest priority
add_action( 'init', 'bookmeta_integrate_wpdb', 0 );

add_action( 'switch_blog', 'bookmeta_integrate_wpdb', 0 );
/**
 * Integrates bookmeta table with $wpdb
 *
 */
function bookmeta_integrate_wpdb( )
{
    global $wpdb;
    $wpdb->bookmeta  = $wpdb->prefix . 'bookmeta';
    $wpdb->tables[ ] = 'bookmeta';
    return;
}
/**
 * Adds meta data field to a book.
 */
function add_book_meta( $book_id, $meta_key, $meta_value, $unique = false )
{
    return add_metadata( 'book', $book_id, $meta_key, $meta_value, $unique );
}
/**
 * Removes metadata matching criteria from a book.
 */
function delete_book_meta( $book_id, $meta_key, $meta_value = '' )
{
    return delete_metadata( 'book', $book_id, $meta_key, $meta_value );
}
/**
 */
function get_book_meta( $book_id, $key = '', $single = false )
{
    return get_metadata( 'book', $book_id, $key, $single );
}
/**
 * Update book meta field based on book ID.
 *
 * Use the $prev_value parameter to differentiate between meta fields with the same key and book ID.
 */
function update_book_meta( $book_id, $meta_key, $meta_value, $prev_value = '' )
{
    return update_metadata( 'book', $book_id, $meta_key, $meta_value, $prev_value );
}
//form
function custom_meta_box_markup( $object )
{
    wp_nonce_field( basename( __FILE__ ), "meta-box-nonce" );
    require_once plugin_dir_path( __FILE__ ) . 'includes/form-meta.php';
}
function add_custom_meta_box( )
{
    add_meta_box( "demo-meta-box", "Custom Meta Box", "custom_meta_box_markup", "Books", "normal", "high", null );
}
add_action( "add_meta_boxes", "add_custom_meta_box" );
//save data in custom fields
function save_custom_meta_box( $post_id, $post, $update )
{
    if ( !isset( $_POST[ "meta-box-nonce" ] ) || !wp_verify_nonce( $_POST[ "meta-box-nonce" ], basename( __FILE__ ) ) )
        return $post_id;
    if ( !current_user_can( "edit_post", $post_id ) )
        return $post_id;
    if ( defined( "DOING_AUTOSAVE" ) && DOING_AUTOSAVE )
        return $post_id;
    $slug = "books";
    if ( $slug != $post->post_type )
        return $post_id;
    if ( isset( $_POST[ "book_author" ] ) ) {
        $Book_author = $_POST[ "book_author" ];
    } //isset( $_POST[ "book_author" ] )
    update_book_meta( $post_id, "Author_name", $Book_author );
    if ( isset( $_POST[ "book_price" ] ) ) {
        $book_price = $_POST[ "book_price" ];
    } //isset( $_POST[ "book_price" ] )
    update_book_meta( $post_id, "Book_price", $book_price );
    if ( isset( $_POST[ "book_publisher" ] ) ) {
        $book_publisher = $_POST[ "book_publisher" ];
    } //isset( $_POST[ "book_publisher" ] )
    update_book_meta( $post_id, "Book_publisher", $book_publisher );
    if ( isset( $_POST[ "book_publisher" ] ) ) {
        $Book_publishing_year = $_POST[ "book_publishing_year" ];
    } //isset( $_POST[ "book_publisher" ] )
    update_book_meta( $post_id, "Year", $Book_publishing_year );
    if ( isset( $_POST[ "book_edition" ] ) ) {
        $Book_edition = $_POST[ "book_edition" ];
    } //isset( $_POST[ "book_edition" ] )
    update_book_meta( $post_id, "Edition", $Book_edition );
    if ( isset( $_POST[ "book_publisher" ] ) ) {
        $book_publisher = $_POST[ "book_publisher" ];
    } //isset( $_POST[ "book_publisher" ] )
    update_book_meta( $post_id, "Book_publisher", $book_publisher ); {
        $URL = get_post_permalink( $post_id, $leavename, $sample );
    }
    update_book_meta( $post_id, "Url", $URL );
}
add_action( "save_post", "save_custom_meta_box", 10, 3 );
//Create a custom admin settings page for Book. Settings option should contain options for changing currency, number of books displayed per page, etc. Settings menu should be displayed under the Books menu.
add_action( 'admin_menu', 'stp_api_add_admin_menu' );
add_action( 'admin_init', 'stp_api_settings_init' );

function stp_api_add_admin_menu( )
{
    add_submenu_page( 'edit.php?post_type=books', 'Settings API Page', 'Settings ', 'manage_options', 'settings-api-page', 'stp_api_options_page' );
}

function stp_api_settings_init( )
{
    register_setting( 'stpPlugin', 'stp_api_settings' );
    add_settings_section( 'stp_api_stpPlugin_section', __( 'Book Setting page', 'wordpress' ), 'stp_api_settings_section_callback', 'stpPlugin' );
    add_settings_field( 'no_books', 'Number of books per page', 'no_book_render', 'stpPlugin', 'stp_api_stpPlugin_section' );
}
function no_book_render( )
{
    $options = get_option( 'stp_api_settings' );
?>
 <input type='number'  style="width:50px" name='stp_api_settings[no_book_render]' value='<?php
    echo $options[ 'no_book_render' ];
?>'>
    
    <?php
}
function stp_api_settings_section_callback( )
{

}
function stp_api_options_page( )
{
?>
 <form action='options.php' method='post'>

        <?php
    settings_fields( 'stpPlugin' );
    do_settings_sections( 'stpPlugin' );
    submit_button();
?>

    </form>
    <?php
}
function set_posts_per_page_for_deals( $query )
{
    $options = ( get_option( 'stp_api_settings' ) );
    $no      = $options[ 'no_book_render' ];
    if ( !is_admin() && $query->is_main_query() && is_post_type_archive( 'books' ) ) {
        $no;
        $query->set( 'posts_per_page', $no );
    } //!is_admin() && $query->is_main_query() && is_post_type_archive( 'books' )
}
add_action( 'pre_get_posts', 'set_posts_per_page_for_deals' );
//Create a shortcode [book] to display the book(s) information. Shortcode attributes should be id, author_name, year, category, tag, and publisher.
add_shortcode( 'book', 'book' );
function book()
{
    // Attributesglobal $wpdb;
    //category for book
    global $wpdb;
    $post_id  = get_the_ID();
    $querystr = "
        SELECT wp_posts.ID, wp_posts.post_title, wp_bookmeta.meta_key, wp_bookmeta.meta_value,wp_terms.term_id,wp_terms.name
        FROM wp_posts, wp_bookmeta,wp_terms
        WHERE wp_posts.ID = wp_bookmeta.book_id
        
        AND wp_bookmeta.meta_key NOT LIKE '\_%'
        AND wp_posts.post_type='books'
        AND wp_posts.post_status = 'publish'
        AND wp_bookmeta.book_id=' .$post_id. '
         ";
    //wordpress function that returns the query as an array of associative arrays
    $results  = $wpdb->get_results( $querystr, ARRAY_A );
    //create an empty array
    $books    = array( );
    foreach ( $results as $row ) {
        //if row's ID doesn't exist in the array, add a new array
        if ( !isset( $books[ $row[ 'ID' ] ] ) ) {
            $books[ $row[ 'ID' ] ] = array( );
        } //!isset( $books[ $row[ 'ID' ] ] )
        //add all the values to the array with matching IDs
        $books[ $row[ 'ID' ] ] = array_merge( $books[ $row[ 'ID' ] ], array(
             'post_title' => $row[ 'post_title' ] 
        ), array(
             $row[ 'meta_key' ] => $row[ 'meta_value' ] 
        ) );
        $id                    = $books[ $row[ 'ID' ] ];
    } //$results as $row
    $catgory_name = get_the_terms( $post->ID, 'Book_Category' );
    $tag_name     = get_the_terms( $post->ID, 'tags' );
    //extract data from each event
    foreach ( $books as $book ) {
        echo "Book ID:";
        echo $post_id;
        echo "<br>"; 
        echo "Author Name:";
        echo $book[ 'Author_name' ];
        echo "<br>"; 
        echo "Year:";
        echo $book[ 'Year' ];
        echo "<br>";
		if(	$catgory_name==""){
			echo "Category:";
        	echo" No Category";
		}
		else{
        echo "Category:";
        echo $catgory_name[ 0 ]->name ;
        }
		echo "<br>";
        if( $tag_name==""){
			echo "Tags:";
			echo "No Tags";
		}
		else{
		echo "Tags:";
        echo $tag_name[ 0 ]->name ;
        }
		echo "<br>";
        echo "Publisher:";
        echo $book[ 'Book_publisher' ];
        echo "<br>";
    
	} 
}



// flush rules
function myplugin_flush_rewrites( )
{
    myplugin_custom_post_types_registration();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'flush_rewrite_rules' );


/*
*custom widgets
*/

add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');
  
function my_custom_dashboard_widgets() {
global $wp_meta_boxes;
 
wp_add_dashboard_widget('custom_help_widget', 'Show top 5 category', 'custom_dashboard_help');
}
 
function custom_dashboard_help() {
    $categories = get_categories([
        'taxonomy' => 'Book_Category',
        'orderby'  => 'count',
        'order'    => 'DESC',
        'number'    =>'5'
        ]);
    
    ?>
    <ul>
 
    <?php foreach ($categories as $category) { ?>
        <li><?php echo $category->name; echo '('; echo $category->count; echo ')';?>
          
    
    <?php }?>
     </ul>
 
 
    
<?php } 
// Register and load the widget
function wpb_load_widget() {
    register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );
 
// Creating the widget for side bar 
class wpb_widget extends WP_Widget {
 
function __construct() {
parent::__construct(
 
// Base ID of your widget
'wpb_widget', 
 
// Widget name will appear in UI
__('Custom Widgets', 'wpb_widget_domain'), 
 
// Widget description
array( 'description' => __( 'category show for custom post type', 'wpb_widget_domain' ), ) 
);
}
 
// Creating widget front-end
 
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
 
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];
 
// This is where you run the code and display the output


$taxonomy = 'Book_Category';
$terms = get_terms($taxonomy); // Get all terms of a taxonomy

if ( $terms && !is_wp_error( $terms ) ) :


?>
    <ul>
        <?php foreach ( $terms as $term ) { ?>
            <li><a href="<?php echo get_term_link($term->slug, $taxonomy); ?>"><?php echo $term->name;  echo  '(';
            echo $term->count; echo ')';?></a></li>
        <?php } ?>
    </ul>
<?php endif;
}
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'wpb_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
     
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class wpb_widget ends here
?>