<?php
/**
 * @wordpress-plugin
 * Plugin Name:       InstantCharts
 * Plugin URI:        http://extensions.poolparty.io/downloads/instantcharts
 * Description:       The easiest way to add sizing charts to your store.
 * Version:           1.0.0
 * Author:            PoolParty
 * Author URI:        http://extensions.poolparty.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

 /**
 * Add meta box to the post editing screen
 */

 add_action( 'add_meta_boxes', 'instantcharts_add_metabox' );

 function instantcharts_add_metabox () {
 	add_meta_box( 'sizingchartdiv', __( 'Sizing Chart', 'instantcharts' ), 'instantcharts_metabox', 'product', 'side', 'low');
 }

 function instantcharts_metabox ( $post ) {
 	global $content_width, $_wp_additional_image_sizes;

 	$image_id = get_post_meta( $post->ID, '_sizingchart_image_id', true );

 	$old_content_width = $content_width;
 	$content_width = 254;

 	if ( $image_id && get_post( $image_id ) ) {

 		if ( ! isset( $_wp_additional_image_sizes['post-thumbnail'] ) ) {
 			$thumbnail_html = wp_get_attachment_image( $image_id, array( $content_width, $content_width ) );
 		} else {
 			$thumbnail_html = wp_get_attachment_image( $image_id, 'post-thumbnail' );
 		}

 		if ( ! empty( $thumbnail_html ) ) {
 			$content = $thumbnail_html;
 			$content .= '<p class="hide-if-no-js"><a href="javascript:;" id="remove_sizingchart_image_button" >' . esc_html__( 'Remove sizing chart', 'instantcharts' ) . '</a></p>';
 			$content .= '<input type="hidden" id="upload_sizingchart_image" name="_sizingchart_cover_image" value="' . esc_attr( $image_id ) . '" />';
 		}

 		$content_width = $old_content_width;
 	} else {

 		$content = '<img src="" style="width:' . esc_attr( $content_width ) . 'px;height:auto;border:0;display:none;" />';
    $content .= '<p class="hide-if-no-js"><a title="' . esc_attr__( 'Set sizing chart', 'instantcharts' ) . '" href="javascript:;" id="upload_sizingchart_image_button" id="set-sizingchart-image">' . esc_html__( 'Set sizing chart', 'instantcharts' ) . '</a></p>';
 		$content .= '<input type="hidden" id="upload_sizingchart_image" name="_sizingchart_cover_image" value="" />';

 	}

 	echo $content;
 }

 /**
  * Save custom meta data
  */
 add_action( 'save_post', 'instantcharts_image_save', 10, 1 );

   function instantcharts_image_save ( $post_id ) {
   	if( isset( $_POST['_sizingchart_cover_image'] ) ) {
   		$image_id = (int) $_POST['_sizingchart_cover_image'];
   		update_post_meta( $post_id, '_sizingchart_image_id', $image_id );
   	}
  };

  /**
   * Loads the image management javascript
   */
 add_action( 'admin_enqueue_scripts', 'instantcharts_image_enqueue' );

  function instantcharts_image_enqueue() {
      wp_enqueue_media();

      wp_register_script( 'sizing_chart_image', plugin_dir_url( __FILE__ ) . 'includes/instantcharts.js', array( 'jquery' ) );
      wp_enqueue_script( 'sizing_chart_image' );
  }

  /**
   * Loads the js/css for lightbox
   */
 add_action( 'wp_enqueue_scripts', 'instantcharts_frontend_enqueue' );

  function instantcharts_frontend_enqueue() {
      wp_register_script( 'featherlight_js', plugin_dir_url( __FILE__ ) . 'includes/featherlight.js', array( 'jquery' ) );
      wp_register_style( 'instantcharts_style', plugin_dir_url( __FILE__ ) . 'includes/instantcharts.css' );

      wp_enqueue_script( 'featherlight_js' );
      wp_enqueue_style( 'instantcharts_style' );

  }

  /**
  * Add sizing chart link on frontend
  */
  add_action( 'woocommerce_after_add_to_cart_button', 'instantcharts_add_link', 30 );

  function instantcharts_add_link( $post ) {
    global $post;
    $instantcharts_stored_meta = get_post_meta( $post->ID );
    $sizing_chart_url = wp_get_attachment_image_src($instantcharts_stored_meta['_sizingchart_image_id'][0], 'full');
    if( !empty ( $sizing_chart_url ) ) {
      echo '<a href="#" class="sizing_chart_link" data-featherlight="' . $sizing_chart_url[0] . '">View Sizing Chart</a>';
    }
  }
?>
