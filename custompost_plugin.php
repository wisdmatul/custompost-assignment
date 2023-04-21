<?php
/* 
 * Plugin Name: Custompost Plugin
 * Description: This plugin is for creating and displaying some custom post
 * Author: Atul Wisdm
 * Author URI: http://wisdmlabs.atul.com/
 * Version: 1.0.0
 * Text Domain: custompost-plugin
*/
function custom_post_script()
{
    wp_enqueue_script('my-plugin-script', plugins_url( '/script.js', __FILE__ ));
}
add_action('wp_enqueue_scripts','custom_post_script');

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
    
    ob_start();
?>
    <form method = "get">
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
$output = ob_get_contents();
ob_get_clean();
return $output;
}

//TO define shortcode
function custompost_shortcodes()
{
    add_shortcode('my_shortcode', 'show_form_table');
}

add_action('init', 'custompost_shortcodes');

function display_post_table($content) {
    if (isset($_GET['submit'])) {
        // $post_type = $_POST['select'];
        // $entries = $_POST['entries'];

        $post_type = $_GET['select'];
        $entries = intval($_GET['entries']);
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;

        $args = array(
            'post_type' => $post_type,
            // '' => $entries
            'posts_per_page' => $entries,
            'paged' => $paged
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            echo '<h2>Post Table</h2>';

            // Display the form for selecting the number of posts to display
            echo '<form method="get">';
            echo '<label for="post_type">Post Type:</label>';
            echo '<select name="post_type">';
            $post_types = get_post_types();
            foreach ($post_types as $type) {
                echo '<option value="' . $type . '"' . ($type == $post_type ? ' selected="selected"' : '') . '>' . ucfirst($type) . '</option>';
            }
            echo '</select>';
            echo '<label for="entries">Entries per page:</label>';
            echo '<input type="number" name="entries" min="1" value="' . $entries . '">';
            
            echo '</form>';

            // Display the table
            echo '<table id="upcoming-table">';
            echo '<thead><tr><th>ID</th><th>Title</th><th>Content</th><th>Slug</th><th>Date</th></tr></thead>';
            echo '<tbody>';
            while ($query->have_posts()) {
                $query->the_post();
                echo '<tr>';
                echo '<td>' . get_the_ID() . '</td>';
                echo '<td>' . get_the_title() . '</td>';
                echo '<td>' . wp_trim_words(get_the_content(), 20) . '</td>';
                echo '<td>' . basename(get_permalink()) . '</td>';
                echo '<td>' . get_the_date() . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';

            // Display the pagination links
            // $total_pages = $query->max_num_pages;
            $big=999999999;
            echo '<div class="pagination">';
            echo paginate_links(array(
                'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format' => 'page/%#%',
                'current' => max( 1, get_query_var('paged') ),
                'total' => $query->max_num_pages,
                'prev_next' => true,
                'prev_text' => __('&laquo; Previous'),
                'next_text' => __('Next &raquo;'),
            ));
            echo '</div>';

            wp_reset_postdata();
        }
    } else {
        // Display the form for selecting the number of posts to display
        echo '<form method="post">';
        echo '<label for="post_type">Post Type:</label>';
        echo '<select name="post_type">';
        $post_types = get_post_types();
        foreach ($post_types as $type) {
            echo '<option value="' . $type . '">' . ucfirst($type) . '</option>';
        }
        echo '</select>';
        echo '<label for="entries">Entries per page:</label>';
        echo '<input type="number" name="entries" min="1" value="10">';
        echo '<input type="submit" name="submit" value="Submit">';
        echo '</form>';
    }

    return $content;
}



//add_action('init', 'display_post_table');
add_filter('the_content','display_post_table');


wp_enqueue_style( 'my-plugin-style', plugins_url( '/style.css', __FILE__ ) );

