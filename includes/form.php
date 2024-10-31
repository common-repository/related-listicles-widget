<?php
/**
 * Widget forms for the Listicor Widget
 *  
 * @package    Related_Listicles_Widget
 * @since      0.5
 * @author     Relevad
 * @copyright  Copyright (c) 2016, Relevad
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

//TODO: move esc_attr() and esc_url() and sanitize_html_class() into the update code instead ???
//does that give back any validation data? since its ajax the user won't know what errored
?>

<!-- Column 1 -->
<div class="rlw-columns-3">
	<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>">
		<?php _e( 'Title', 'related-listicles-widget' ); ?>
	</label>
	<input class="widefat" type="text" <?php 
		echo 'id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" value="' . esc_attr($instance['title']) . '"'; ?> />
	</p>

	<p>
	<label for="<?php echo $this->get_field_id( 'title_url' ); ?>">
		<?php _e( 'Title URL', 'related-listicles-widget' ); ?>
	</label>
	<input class="widefat" type="text" <?php 
		echo 'id="' . $this->get_field_id('title_url') . '" name="' . $this->get_field_name('title_url') . '" value="' . esc_url( $instance['title_url'] ) . '"'; ?> />
	</p>

	<p>
	<label for="<?php echo $this->get_field_id( 'cssID' ); ?>">
		<?php _e( 'CSS ID', 'related-listicles-widget' ); ?>
	</label>
	<input class="widefat" type="text" <?php 
		echo 'id="' . $this->get_field_id('cssID') . '" name="' . $this->get_field_name('cssID') . '" value="' . sanitize_html_class( $instance['cssID'] ) . '"'; ?> />
	</p>

	<p>
	<label for="<?php echo $this->get_field_id( 'css_class' ); ?>">
		<?php _e( 'CSS Class', 'related-listicles-widget' ); ?>
	</label>
	<input class="widefat" type="text" <?php 
		echo 'id="' . $this->get_field_id('css_class') . '" name="' . $this->get_field_name('css_class') . '" value="' . sanitize_html_class( $instance['css_class'] ) . '"'; ?> />
	</p>
</div>

<!-- Column 2 -->
<div class="rlw-columns-3">
    
	<p>
	<label for="<?php echo $this->get_field_id( 'keyword' ); ?>">
		<?php _e( 'Search Keyword', 'related-listicles-widget' ); ?>
	</label>
	<input class="widefat" type="text" <?php 
		echo 'id="' . $this->get_field_id('keyword') . '" name="' . $this->get_field_name('keyword') . '" value="' . $instance['keyword'] . '"'; ?> />
	</p>
    
	<p>
	<label for="<?php echo $this->get_field_id( 'limit' ); ?>">
		<?php _e( 'Number of posts to show (1-20)', 'related-listicles-widget' ); ?>
	</label>
	<input class="widefat" type="number" step="1" min="1" max="20" <?php 
		echo 'id="' . $this->get_field_id('limit') . '" name="' . $this->get_field_name('limit') . '" value="' . (int)($instance['limit']) . '"'; ?> />
	</p>

	<p>
	<label for="<?php echo $this->get_field_id( 'offset' ); ?>">
		<?php _e( 'Number of posts to skip over (0-100)', 'related-listicles-widget' ); ?>
	</label>
	<input class="widefat" type="number" step="1" min="0" max="100" <?php 
		echo 'id="' . $this->get_field_id('offset') . '" name="' . $this->get_field_name('offset') . '" value="' . (int)($instance['offset']) . '"'; ?> />
	</p>
    
	<p>
	<label for="<?php echo $this->get_field_id( 'orderby' ); //TODO: put both these dropdowns side by side ?>">
		<?php _e( 'Sort By', 'related-listicles-widget' ); ?>
	</label>
	<select class="widefat" style="width:100%;" <?php
		echo 'id="' . $this->get_field_id('orderby') . '" name="' . $this->get_field_name('orderby') . '"'; ?>>
		<option value="title" <?php selected( $instance['orderby'], 'title' ); ?>><?php _e( 'Title',  'related-listicles-widget' ) ?></option>
		<option value="date"  <?php selected( $instance['orderby'], 'date' );  ?>><?php _e( 'Date',   'related-listicles-widget' ) ?></option>
		<option value="rand"  <?php selected( $instance['orderby'], 'rand' );  ?>><?php _e( 'Random', 'related-listicles-widget' ) ?></option>
	</select>
	</p>
    
	<p>
	<label for="<?php echo $this->get_field_id( 'order' ); ?>">
		<?php _e( 'Order', 'related-listicles-widget' ); ?>
	</label>
	<select class="widefat" style="width:100%;" <?php
		echo 'id="' . $this->get_field_id('order') . '" name="' . $this->get_field_name('order') . '"'; ?> >
		<option value="DESC" <?php selected( $instance['order'], 'DESC' ); ?>><?php _e( 'Descending', 'related-listicles-widget' ) ?></option>
		<option value="ASC"  <?php selected( $instance['order'], 'ASC' );  ?>><?php _e( 'Ascending',  'related-listicles-widget' )  ?></option>
	</select>
	</p>

</div>

<!-- Column 3 -->
<div class="rlw-columns-3 rlw-column-last">
	<p>
	<input type="checkbox" <?php 
		echo 'id="' . $this->get_field_id('thumb') . '" name="' . $this->get_field_name('thumb') . '" '; checked( $instance['thumb'] ); ?> />
	<label for="<?php echo $this->get_field_id( 'thumb' ); ?>">
		<?php _e( 'Display Thumbnail', 'related-listicles-widget' ); ?>
	</label>
	</p>

	<p>
	<label class="rlw-block" for="<?php echo $this->get_field_id( 'thumb_height' ); ?>">
		<?php _e( 'Thumbnail (width,height,align)', 'related-listicles-widget' ); ?>
	</label>
	<input  class="small-input" type="text" <?php
		echo 'id="' . $this->get_field_id('thumb_width' ) . '" name="' . $this->get_field_name('thumb_width' ) . '" value="' . $instance['thumb_width'] . '"'; ?> />
	<input  class="small-input" type="text" <?php
		echo 'id="' . $this->get_field_id('thumb_height') . '" name="' . $this->get_field_name('thumb_height') . '" value="' . $instance['thumb_height'] . '"'; ?> />
	<select class="small-input" <?php
		echo 'id="' . $this->get_field_id('thumb_align' ) . '" name="' . $this->get_field_name('thumb_align' ) . '"'; ?> >
		<option value="rlw-alignleft"  <?php selected( $instance['thumb_align'], 'rlw-alignleft' );  ?>><?php _e( 'Left',  'related-listicles-widget' ) ?></option>
		<option value="rlw-alignright" <?php selected( $instance['thumb_align'], 'rlw-alignright' ); ?>><?php _e( 'Right', 'related-listicles-widget' ) ?></option>
		<option value="rlw-aligncenter"<?php selected( $instance['thumb_align'], 'rlw-aligncenter' );?>><?php _e( 'Center','related-listicles-widget' ) ?></option>
	</select>
	</p>

	<p>
	<label for="<?php echo $this->get_field_id( 'thumb_default' ); ?>">
		<?php _e( 'Default Thumbnail', 'related-listicles-widget' ); ?>
	</label>
	<input class="widefat" type="text" <?php 
		echo 'id="' . $this->get_field_id('thumb_default') . '" name="' . $this->get_field_name('thumb_default') . '" value="' . $instance['thumb_default'] . '"'; ?> />
	<small><?php _e( 'Leave it blank to disable.', 'related-listicles-widget' ); ?></small>
	</p>

<?php /*
	<p>
	<input id="<?php echo $this->get_field_id( 'excerpt' ); ?>" name="<?php echo $this->get_field_name( 'excerpt' ); ?>" type="checkbox" <?php checked( $instance['excerpt'] ); ?> />
	<label for="<?php echo $this->get_field_id( 'excerpt' ); ?>">
		<?php _e( 'Display Excerpt', 'related-listicles-widget' ); ?>
	</label>
	</p>

	<p>
	<label for="<?php echo $this->get_field_id( 'length' ); ?>">
		<?php _e( 'Excerpt Length', 'related-listicles-widget' ); ?>
	</label>
	//Fix to minimum length 10, clarify its number of words or characters
	<input class="widefat" id="<?php echo $this->get_field_id( 'length' ); ?>" name="<?php echo $this->get_field_name( 'length' ); ?>" type="number" step="1" min="0" value="<?php echo (int)( $instance['length'] ); ?>" />
	</p>

	<p>
	<input id="<?php echo $this->get_field_id( 'readmore' ); ?>" name="<?php echo $this->get_field_name( 'readmore' ); ?>" type="checkbox" <?php checked( $instance['readmore'] ); ?> />
	<label for="<?php echo $this->get_field_id( 'readmore' ); ?>">
		<?php _e( 'Display Readmore', 'related-listicles-widget' ); ?>
	</label>
	</p>

	<p>
	<label for="<?php echo $this->get_field_id( 'readmore_text' ); ?>">
		<?php _e( 'Readmore Text', 'related-listicles-widget' ); ?>
	</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'readmore_text' ); ?>" name="<?php echo $this->get_field_name( 'readmore_text' ); ?>" type="text" value="<?php echo strip_tags( $instance['readmore_text'] ); ?>" />
	</p>
*/ ?>
	
	<p>
	<input type="checkbox" <?php
		echo 'id="' . $this->get_field_id('date') . '" name="' . $this->get_field_name('date') . '"'; checked( $instance['date'] ); ?> />
	<label for="<?php echo $this->get_field_id( 'date' ); ?>">
		<?php _e( 'Display Date', 'related-listicles-widget' ); ?>
	</label>
	</p>
	
	<p>
	<input type="checkbox" <?php 
		echo 'id="' . $this->get_field_id('date_relative') . '" name="' . $this->get_field_name('date_relative') . '"'; checked( $instance['date_relative'] ); ?> />
	<label for="<?php echo $this->get_field_id( 'date_relative' ); ?>">
		<?php _e( 'Use Relative Date. eg: 5 days ago', 'related-listicles-widget' ); ?>
	</label>
	</p>
	

</div>

<div class="clear"></div>

<div class="rlw-bottom">

	<?php $presets = array('Sidebar','Footer','No Preset'); //NOTE: this is a loop because we might add many more presets ?>
	<p>
	<label for="<?php echo $this->get_field_id( 'style_preset' ); ?>">
		<?php _e( 'Style Preset', 'related-listicles-widget' ); ?>
	</label>
	<select class="widefat" <?php
		echo 'id="' . $this->get_field_id('style_preset' ) . '" name="' . $this->get_field_name('style_preset' ) . '"'; ?> >
	<?php foreach ($presets as $style ) { ?>
		<option <?php echo "value='$style'"; selected( $instance['style_preset'], $style ); ?>><?php _e($style, 'related-listicles-widget'); ?></option>
	<?php
	} ?>
	</select>
	</p>

	<p>
	<label for="<?php echo $this->get_field_id( 'css' ); ?>">
		<?php _e( 'Custom CSS', 'related-listicles-widget' ); ?>
	</label><small><?php _e( 'Rules apply to all style presets.', 'related-listicles-widget'); ?></small>
	<textarea class="widefat" style="height:180px;" <?php 
		echo 'id="' . $this->get_field_id('css') . '" name="' . $this->get_field_name('css') . '"'; ?>><?php echo $instance['css']; ?></textarea>
	</p>
</div>

