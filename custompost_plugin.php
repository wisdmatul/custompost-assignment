<?php
/* 
 * Plugin Name: Custompost Plugin
 * Description: This plugin is for creating and displaying some custom post
 * Author: Atul Wisdm
 * Author URI: http://wisdmlabs.atul.com/
 * Version: 1.0.0
 * Text Domain: custompost-plugin
*/

//for security puposes
// if ( !define('ABSPATH') )
// {
//     die("");
// }

//creating custom post type for courses
function custom_post_course()
{
    $labels = array(
        'name'               => __( 'Courses', 'custompost-plugin' ),
        'singular_name'      => __( 'Courses', 'custompost-plugin' ),
        'add_new'            => __( 'Add New', 'custompost-plugin' ),
        'add_new_item'       => __( 'Add New Courses', 'custompost-plugin' ),
        'edit_item'          => __( 'Edit Courses', 'custompost-plugin' ),
        'new_item'           => __( 'New Courses', 'custompost-plugin' ),
        'view_item'          => __( 'View Courses', 'custompost-plugin' ),
        'search_items'       => __( 'Search Courses', 'custompost-plugin' ),
        'not_found'          => __( 'No Courses found', 'custompost-plugin' ),
        'not_found_in_trash' => __( 'No Courses found in Trash', 'custompost-plugin' ),
        'parent_item_colon'  => __( 'Parent Courses:', 'custompost-plugin' ),
        'menu_name'          => __( 'Courses', 'custompost-plugin' ),
      );
    register_post_type(
        'courses',
        array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
			'menu_icon' => 'dashicons-store',
            'description'        => __( 'Description.', 'custompost-plugin' ),
        )
    );
}

//creating custom post type for session
function custom_post_session()
{
    register_post_type(
        'session',
        array(
            'labels' => array(
                'name' => __('session'),
                'singula_name' => __('session')
            ),
            'public' => true,
            'has_archive' => true,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
			'menu_icon' => 'dashicons-store',
            'description'        => __( 'Description.', 'custompost-plugin' ),
        )
    );
}

//actions hook for the custompost type
add_action( 'init', 'custom_post_course' );
add_action( 'init', 'custom_post_session' );

//function to create form and show table
function show_form_table()
{
    
    
?>
    <form method = "post">
        <label for="choice">Select</label>
        <select name="select" id="select">
            <option value = "courses">Courses</option>
            <option value = "session">Session</option>
        </select>
        <br>
        <label for="entries">no. of entries</label>
        <select name="entries" id="entries">
            <option value = "1">1</option>
            <option value = "2">2</option>
            <option value = "3">3</option>
            <option value = "4">4</option>
            <option value = "5">5</option>
            <option value = "6">6</option>
            <option value = "7">7</option>
            <option value = "8">8</option>
            <option value = "9">9</option>
            <option value = "10">10</option>
        </select>
        <br><br>
        <input type="submit" name="submit" values="Submit">
    </form>

<?php 
    

}

//TO define shortcode
function custompost_shortcodes()
{
    add_shortcode('my_shortcode', 'show_form_table');
}

add_action('init', 'custompost_shortcodes');

function display_table()
{
    if( isset( $_POST['submit'] ))
    {
        $p_type = $_POST["select"];
        $entries = $_POST["entries"];
        
        
    $args = array(
        'post_type' => $p_type, // set the post type to your custom post type
        'posts_per_page' => $entries // set the number of posts to be fetched
    );

    // $result = new WP_Query($args);
    // echo $result;
    $comstum_post = get_posts( $args );
    
?>
    <br><br>
    <h1 class="table-title">Posts Details</Details></h1>
<?php
    global $wpdb;

    echo '<table id="upcoming-table">';
    echo '<thead><tr><th>ID</th><th>Title</th><th>Description</th><th>Slug</th><th>date</th></tr></thead>';

    foreach ($comstum_post as $row) {
        echo '<tr>';
        echo '<td>' . $row->ID . '</td>';
        echo '<td>' . $row->post_title . '</td>';
        echo '<td>' . wp_trim_words($row->post_content,20) . '</td>';
        echo '<td>'. $row->post_name . '</td>';
        echo '<td>' . $row->post_date . '</td>';
        echo '</tr>';
    }

    echo '</table>';
}
}

add_action('init', 'display_table');


wp_enqueue_style( 'my-plugin-style', plugins_url( '/style.css', __FILE__ ) );
  



