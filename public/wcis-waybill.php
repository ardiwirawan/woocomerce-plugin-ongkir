<?php
/*
  Add Waybill Features
*/
class WCIS_Waybill {

  private $settings;

  function __construct() {

    $this->settings   = get_option('woocommerce_wcis_settings');
    $this->api        = new WCIS_API($this->settings['key']);
    
    // Admin Metabox 
    add_action( 'add_meta_boxes', array( $this, 'meta_box_waybill') );

    // Save Waybill
    add_action( 'save_post', array( $this, 'save_wc_order_waybill') , 10, 1 );

    // Hook View Details
    add_action( 'woocommerce_view_order', array( $this, 'display_waybill_info'), 10, 2 );
  }

  public function meta_box_waybill(){
      add_meta_box( 'meta_box_waybill_num', __('Resi Pengiriman','woocommerce'), array( $this, 'add_meta_box_waybill_num'), 'shop_order', 'side', 'core' );
  }

  // 
  public function add_meta_box_waybill_num(){

    global $post;

    // Get Post Meta
    $waybill_num = get_post_meta( $post->ID, '_waybill_num', true ) ? get_post_meta( $post->ID, '_waybill_num', true ) : '';
    $waybill_name = get_post_meta( $post->ID, '_waybill_name', true ) ? get_post_meta( $post->ID, '_waybill_name', true ) : '';

    $array_ongkir = array(
      array(
        'key'   => 'jne',
        'name'  => 'JNE'
      ),
      array(
        'key'   => 'tiki',
        'name'  => 'TIKI'
      ),
      array(
        'key'   => 'wahana',
        'name'  => 'WAHANA'
      ),
      array(
        'key'   => 'jnt',
        'name'  => 'J&T'
      ),
      array(
        'key'   => 'rpx',
        'name'  => 'RPX'
      ),
    );

    $selected = "selected";
    $notselected = "";

    // Display Form
    $form = '<div class"shipment-tracking-form">';
    $form .= "<p>";
    $form .= '<label for="waybill_name" style="width:100%;display:block;padding-bottom:5px;">Kurir Pengiriman</label>';
    $form .= '<select name="waybill_name" style="width:100%;display:block;padding-bottom:5px;">';
    foreach($array_ongkir as $ongkir){
      if($ongkir["key"] == $waybill_name){
        $selected = "selected";
      }
      else{
        $selected = "";
      }
      $form .= '<option value="'.$ongkir["key"].'" '.$selected.'>'.$ongkir["name"].'</option>';
    }
    $form .= '</select>';
    $form .= "</p>";
    $form .= "<p>";
    $form .= '<label for="waybill_num">Nomor Resi</label>';
    $form .='<input type="hidden" name="waybill_num_field_nonce" value="' . wp_create_nonce() . '">';
    $form .= '<input type="text" style="width:250px;";" name="waybill_num" placeholder="' . $waybill_num . '" value="' . $waybill_num . '">';
    $form .= "</p>";
    $form .= "</div>";

    // Return Form 
    echo $form;

  }

  // Save Waybill Meta
  public function save_wc_order_waybill( $post_id ) {

      // Check if our nonce is set.
      if ( ! isset( $_POST[ 'waybill_num_field_nonce' ] ) ) {
          return $post_id;
      }
      $nonce = $_REQUEST[ 'waybill_num_field_nonce' ];

      //Verify that the nonce is valid.
      if ( ! wp_verify_nonce( $nonce ) ) {
          return $post_id;
      }

      // If this is an autosave, our form has not been submitted, so we don't want to do anything.
      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
          return $post_id;
      }

      // Check the user's permissions.
      if ( 'page' == $_POST[ 'post_type' ] ) {

          if ( ! current_user_can( 'edit_page', $post_id ) ) {
              return $post_id;
          }
      } else {

          if ( ! current_user_can( 'edit_post', $post_id ) ) {
              return $post_id;
          }
      }
      // --- Its safe for us to save the data ! --- //

      // Sanitize user input  and update the meta field in the database.
      update_post_meta( $post_id, '_waybill_num', $_POST[ 'waybill_num' ] );
      update_post_meta( $post_id, '_waybill_name', $_POST[ 'waybill_name' ] );
  }

  // Display Waybill Info on Detail 
  public function display_waybill_info($order_id){

      // Get Post Meta
      $waybill_num = get_post_meta( $order_id, '_waybill_num', true ) ? get_post_meta( $order_id, '_waybill_num', true ) : '';
      $waybill_name = get_post_meta( $order_id, '_waybill_name', true ) ? get_post_meta( $order_id, '_waybill_name', true ) : '';

      // Get Tracking Info
      $args = array(
        'waybill'       => $waybill_num,
        'courier'       => $waybill_name,
      );

      // // Get Waybill
      $waybill    = $this->api->get_waybill($args);

      // Set Plugin Path
      $plugin_path   = ABSPATH . 'wp-content/plugins/ongkir/';

      // Woocommerce Include Template
      wc_get_template( 
        'myaccount/view-order.php', 
        array( 
          'waybill_num'  => $waybill_num,
          'waybill_name' => $waybill_name, 
          'waybill_data' => $waybill 
        ), 
        'ongkir/', $plugin_path . '/templates/' 
      );

  }

}
