<?php
/**
 * Plugin Name: Search Carousel
 * Plugin URI: http://itobuz.com/
 * Description: A brief description of the Plugin.
 * Version: 1.0.1
 * Author: Souvik Sikdar, Sneh
 * Author URI: http://itobuz.com/
 * License: A "Slug" license name e.g. GPL2
 */
/* -----------------------Jquery and css link up------------------------------------------- */




add_image_size('homepage-thumb', 313, 100, true); //(cropped)
wp_enqueue_script('jquery-carousel-js', plugins_url('search-carousel/jquery.jcarousel.min.js'), array('jquery'));
wp_enqueue_script('jcarousel-responsive-js', plugins_url('search-carousel/jcarousel.responsive.js'), array('jquery','jquery-carousel-js'));


function wpse_load_plugin_css() {
    $plugin_url = plugin_dir_url(__FILE__);
    wp_enqueue_style('style1', $plugin_url . 'jcarousel.responsive.css');

}

add_action('wp_enqueue_scripts', 'wpse_load_plugin_css');






/* -----------------------Short code------------------------------------------- */

function search_shortcode($atts) {
    $nam = shortcode_atts(array(
        'categories' => 'query-cats', 'query' => 'query-string', 'excerpt_length' => '30'), $atts, 'search_carousel');
    $cexplode = explode(",", $nam['categories']);
    foreach ($cexplode as $cf) {
        $cff[] = get_cat_ID($cf);
    }
    $cim = implode(",", $cff);
    //print_r($cim);
    global $q;
    $args = array(
        's' => $nam['query'],
        'cat' => $cim,
    );


    $q = new WP_Query($args);


    if ($q->have_posts()) {
        echo '<div class="jcarousel-wrapper"><div class="jcarousel"><ul>';
        while ($q->have_posts()) {
            $q->the_post();
            ?>
            <li>
                <div style="width: 90%;padding:20px;" >
                <a href="<?php the_permalink(); ?>"><h4><?php the_title(); ?></h4></a>
                <div class="image"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(array(313, 100)); ?></a></div>
                <p><?php echo wp_trim_words(get_the_content(), $nam['excerpt_length']); ?></p>
              
                <a href="<?php the_permalink(); ?>" class="readmore"><span>Readmore</span></a>
                </div>
            </li>  
            <?php
        }
        echo '</ul></div>';
        echo'<a href="#" class="jcarousel-control-prev">&lsaquo;</a>
                <a href="#" class="jcarousel-control-next">&rsaquo;</a>';
        echo '</div>';
    } else {
        echo '<div class="search-slider no-result">No Search Result Found</div>';
    }
    //wp_enqueue_script('search_plu-js', plugins_url('search-carousel/search_plugin.js'));
    wp_reset_postdata();
    /* Restore original Post Data */
}

add_shortcode('search_carousel', 'search_shortcode');
?>
