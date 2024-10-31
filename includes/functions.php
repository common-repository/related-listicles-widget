<?php
/**
 * Functions Include
 *  
 * @package    Related_Listicles_Widget
 * @since      0.5
 * @author     Relevad
 * @copyright  Copyright (c) 2016, Relevad
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * Sets the default arguments for a new widget
 * @since  0.5
 *
 * returns array - the default parameters
 */ 
function rlw_get_default_args() {

	$defaults = array(
		'title'            => esc_attr__( 'Related Listicles', 'rlw' ),
		'title_url'        => '',

		'limit'            => 6,
		'offset'           => 0,
		'order'            => 'DESC',
		'orderby'          => 'rand',
		'keyword'          => '',
		'auto_insert'      => true,

		'excerpt'          => false,
		'length'           => 10,
		'thumb'            => true,
		'thumb_height'     => '45px',
		'thumb_width'      => '45px',
		'thumb_default'    => 'http://placehold.it/150x150/f0f0f0/ccc',
		'thumb_align'      => 'rlw-alignleft',
		'date'             => false,
		'date_relative'    => false,
		'readmore'         => false,
		'readmore_text'    => 'Read More &raquo;',

		'style_preset'     => 'Sidebar',
		'css'              => '',
		'cssID'            => '',
		'css_class'        => ''
	);

	// Allow plugins/themes developer to filter the default arguments.
	return apply_filters( 'rlw_default_args', $defaults );

}

//NOTE: these 2 functions rlw_recent_posts() & rlw_get_recent_posts()  are similar to the_post() and get_the_post()
/*
 * Outputs the Listicles.
 * @since  0.5
 */
function rlw_recent_posts( $args = array() ) {
	echo rlw_get_recent_posts( $args );
}

/*
 * Generates the posts markup.
 * @since  0.5
 *
 * @param  array  $args
 * @return string|array The HTML for the random posts.
 */
function rlw_get_recent_posts( $args = array() ) {
        
	// Merge the input arguments and the defaults.
	$args = wp_parse_args( $args, rlw_get_default_args() );
    
	// Extract the array to allow easy use of variables.
	extract( $args );

	// Set up a default, empty variable.
	$html = '';

	//for all preset-styles
	$generic_css = <<<CSSDOC
.rlw-block ul{
    list-style:   none !important;
    margin-left:  0 !important;
    padding-left: 0 !important;
}
.rlw-block .rlw-li {
    list-style-type: none;
}
.rlw-block a {
    display:         inline !important;
    text-decoration: none;
    font-weight:     normal;
}
.rlw-block h4 {
    background: none !important;
}
.rlw-block .rlw-thumb {
    border:     1px solid #eee !important;
    box-shadow: none !important;
    margin:     2px 10px 2px 0;
    padding:    3px !important;
    width:      {$thumb_width}; 
    height:     {$thumb_height};
    object-fit: cover;
}
.rlw-summary {
    font-size: 12px;
}
.rlw-time {
    color:     #bbb;
    font-size: 11px;
}
.rlw-comment {
    color:        #bbb;
    font-size:    11px;
    padding-left: 5px;
}
.rlw-alignleft {
    display: inline;
    float:   left;
}
.rlw-alignright {
    display: inline;
    float:   right;
}
.rlw-aligncenter {
    display:      block;
    margin-left:  auto;
    margin-right: auto;
}
.rlw-clearfix:before,
.rlw-clearfix:after {
    content: "";
    display: table !important;
}
.rlw-clearfix:after {
    clear: both;
}
.rlw-clearfix {
    zoom: 1;
}
CSSDOC;

	$sidebar_css = <<<CSSDOC
.rlw-block .rlw-li {
    border-bottom:  1px solid #eee;
    margin-bottom:  10px;
    padding-bottom: 10px;
}
.rlw-block h4 {
    clear:         none;
    margin-bottom: 0 !important;
    margin-top:    0 !important;
    font-weight:   400;
    font-size:     12px;
    line-height:   1.5em;
}
CSSDOC;

	$footer_css = <<<CSSDOC
.rlw-block .rlw-li {
    float:   left; 
    width:   30%;
    padding: 1%;
    margin:  0 1% 0 0;
}

.rlw-block .rlw-li:nth-child(3n) {
    margin-right: 0;
}

.rlw-block .rlw-li:nth-child(3n+1) {
    clear: both;
}

.rlw-block h4 {
    clear:       both;
    font-weight: bold;
    font-size:   16px;
    font-family: serif;
}

.rlw-block h4 a {
    color: black;
}
CSSDOC;
//NOTE: rlw-block h4 is for the article titles, not the widget header


	// Allow devs to hook in stuff before the loop.
	do_action( 'rlw_before_loop' );

	// Custom  = Generic CSS +               Custom CSS 
	// Footer  = Generic CSS + Footer CSS  + Custom CSS
	// Sidebar = Generic CSS + Sidebar CSS + Custom CSS
	switch ($args['style_preset']) {
	case 'Custom':
		echo '<style>' . $args['css'] . '</style>';
		break;
            
	case 'Sidebar':
		echo '<style>' . $generic_css.$sidebar_css.$args['css'] . '</style>';
		break;
            
	case 'Footer':
	default:
		echo '<style>' . $generic_css.$footer_css.$args['css'] . '</style>';
		break;
	}

        // Get the posts query.
        $results = rlw_get_posts( $args );

        if ($results != false) {

            // The total number of results shall not be greater than the number of requests or the number actually returned
            $results_count = min(($args['limit']), count($results));

            //NOTE: technically not the same rules apply to ids vs classes
            //NOTE: isset check just in case $args is empty for some reason
            $cssClass = ( isset( $args['css_class'] ) ?          sanitize_html_class( $args['css_class'] )   : '' );
            $cssID    = ( isset( $args['cssID'] )     ? 'id="' . sanitize_html_class( $args['cssID'] ) . '"' : '' );
            
            // Listicles wrapper
            $html  = '<div ' . $cssID . ' class="rlw-block ' . $cssClass . '">';

            $html .= '<ul class="rlw-ul">';

            for ($i = 0; $i < $results_count; $i++) {

                $url = rlw_make_dest_url($results[$i], $args['keyword']);

                // Start Listicles markup.
                $html .= '<li class="rlw-li rlw-clearfix">';

                //if we want a thumb, and we have one (or a default)
                if ( $args['thumb'] && (!empty($results[$i]->iu) || !empty( $args['thumb_default'] )) ) {

                    $thumb_class = ' rlw-thumb';
                    // Check if post has post thumbnail.
                    if (!empty($results[$i]->iu)) {
                        $img_url = $results[$i]->iu;
                    } else {
                        $img_url = $args['thumb_default'];
                        $thumb_class .= ' rlw-default-thumb';
                    }

                    $html .= '<a class="rlw-img" href="'.$url.'" rel="bookmark">';
                    $html .= '<img'                                                    //NOTE: trying to put this on one line looks horrible
                               . ' width=' . $args['thumb_width']
                               . ' height='. $args['thumb_height']
                               . ' class="'. $args['thumb_align'] . $thumb_class . '"'
                               . ' src="'  . esc_url(  $img_url ) . '"'
                               . ' alt="'  . esc_attr( $results[$i]->t )
                               . '">';
                    $html .= '</a>';
                } //endif thumb

                $html .= '<h4 class="rlw-title"><a href="' . $url . '" title="' . esc_attr( $results[$i]->t ) . '" rel="bookmark">' . esc_attr( $results[$i]->t ) . '</a></h4>';

                if ( $args['date'] ) {
                    $date = $results[$i]->pu;
                    if ( $args['date_relative'] ) {
                        $unix_pu = strtotime($results[$i]->pu);
                        $date = sprintf( __( '%s ago', 'related-listicles-widget' ), human_time_diff( $unix_pu, current_time( 'timestamp' ) ) );
                    }
                    $html .= '<time class="rlw-time published" datetime="' . esc_html( $date ) . '">' . esc_html( $date ) . '</time>';
                }

                /* currently unused
                if ( $args['excerpt'] ) {
                    $html .= '<div class="rlw-summary">';
                        $html .= wp_trim_words( apply_filters( 'rlw_excerpt', get_the_excerpt() ), $args['length'], ' &hellip;' );
                        if ( $args['readmore'] ) {
                            $html .= '<a href="'.$url.'" class="more-link">' . $args['readmore_text'] . '</a>';
                        }
                    $html .= '</div>';
                } */
                $html .= '</li>';
            } //end for loop
            $html .= '</ul>';
            $html .= '</div><!-- Powered by listicor : related-listicles-widget -->';

        } // end if results


	// Allow devs to hook in stuff after the loop.
	do_action( 'rlw_after_loop' );

	// Return the  posts markup.
	return apply_filters( 'rlw_markup', $html );

}

/*
 * Sets the url that posts will link to
 * @since  0.5
 * 
 * returns string - URL
 */
function rlw_make_dest_url($a, $kw) {
    //TODO: extend with opt-in url
    $remote_url = "http://listicor.com/landing?id={$a->id}&q={$kw}";

    return apply_filters( 'rlw_remote_url', $remote_url, $a, $kw );
}

/*
 * Custom date/title sort functions for usort
 * @since  0.5
 *
 * returns integer - (0,1,-1) for sort ordering
 */
function rlw_sort_date($a, $b) {
    $t1 = strtotime($a->pu);
    $t2 = strtotime($b->pu);
    return $t1 - $t2;
}
function rlw_sort_title($a,$b) {
    return strnatcmp($a->t,$b->t);
}


/*
 * Get/Process the results from listicor
 * @since  0.5
 *
 * @param  array  $args
 * @return array or false on failure
 */
function rlw_get_posts( $args = array() ) {

    if (empty($args)) return false;

    $limit  = $args['limit'];
    //TODO: any additional reasons we'd need to grab MORE results than should be displayed?
    if ($args['orderby'] == 'rand') {
        $limit = min( ($limit * 3), 60); //capped at 20 results on a page
    }
    
    //get_listicor_api($query, $limit=20, $offset=0, $video=0, $debug=false)
    $results = get_listicor_api($args['keyword'], $limit, $args['offset']);

    if (isset($results) && is_array($results)) {
        if     ($args['orderby'] == 'rand')  shuffle($results); //NOTE: this is an in place modification
        elseif ($args['orderby'] == 'date' ) usort(  $results, 'rlw_sort_date');
        elseif ($args['orderby'] == 'title') usort(  $results, 'rlw_sort_title');

        if ($args['order']   == 'ASC') $results = array_reverse($results); //NOTE: yes this happens on random, but it doesn't matter really
        return $results;
    }

    //TODO: proper error reporting back to the user
    return false;
}

