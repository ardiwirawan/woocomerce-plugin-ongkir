<?php
/**
 * View Order: Tracking information
 *
 * Shows tracking numbers view order page
 *
 * @author  WooThemes
 * @package WooCommerce Shipment Tracking/templates/myaccount
 * @version 1.3.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Check if Waybill Num is Not Empty
if ( $waybill_num ) : 

	function format_ind_date($date, $show_day = false){

	    $days = array ( 
	      1 => 'Senin','Selasa',
	          'Rabu',
	          'Kamis',
	          'Jumat',
	          'Sabtu',
	          'Minggu'
	        );
	        
	    $month = array (1 =>   'Januari',
	          'Februari',
	          'Maret',
	          'April',
	          'Mei',
	          'Juni',
	          'Juli',
	          'Agustus',
	          'September',
	          'Oktober',
	          'November',
	          'Desember'
	        );
	    $split        = explode('-', $date);
	    $date_format  = $split[2] . ' ' . $month[ (int)$split[1] ] . ' ' . $split[0];
	    
	    if ($show_day) {
	      $num = date('N', strtotime($date));
	      return $days[$num] . ', ' . $date_format;
	    }

	    return $date_format;

	  }

	if($waybill_data['status']['code'] == 200):
	// If Waybill Return is Okay
?>
<h2 style="margin-top: 15px;">
	<?php echo apply_filters( 'woocommerce_shipment_tracking_my_orders_title', __( 'Informasi Pengiriman', 'woocommerce-shipment-tracking' ) ); ?>		
</h2>
<table class="shop_table shop_table_responsive my_account_tracking">
	<thead>
		<tr>
			<th class="tracking-provider">
				<span class="nobr">
					<?php _e( 'Kurir Pengiriman', 'woocommerce-shipment-tracking' ); ?>
				</span>
			</th>
			<th class="tracking-number">
				<span class="nobr">
					<?php _e( 'Nomor Resi', 'woocommerce-shipment-tracking' ); ?>	
				</span>
			</th>
			<th class="tracking-number">
				<span class="nobr">
					<?php _e( 'Berat Barang', 'woocommerce-shipment-tracking' ); ?>	
				</span>
			</th>
			<th class="tracking-number">
				<span class="nobr">
					<?php _e( 'Tgl Pengiriman', 'woocommerce-shipment-tracking' ); ?>	
				</span>
			</th>
			<th class="tracking-number">
				<span class="nobr">
					<?php _e( 'Status Pengiriman', 'woocommerce-shipment-tracking' ); ?>		
				</span>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr class="tracking">
			<td class="courier-provider">
				<?php echo $waybill_data['result']['summary']['courier_name'].' - '.$waybill_data['result']['summary']['service_code']; ?>
			</td>
			<td class="waybill-number">
				<?php echo $waybill_data['result']['summary']['waybill_number']; ?>
			</td>
			<td class="waybill-number">
				<?php echo $waybill_data['result']['details']['weight'].' Kg'; ?>
			</td>
			<td class="waybill-number">
				<?php echo format_ind_date($waybill_data['result']['details']['waybill_date']); ?>
			</td>
			<td class="tracking-number">
				<?php echo $waybill_data['result']['summary']['status']; ?>
			</td>
		</tr>
	</tbody>
</table>
<table class="shop_table shop_table_responsive my_account_tracking_detail">
	<thead>
		<tr>
			<th class="tracking-provider" colspan="2">
				<span class="nobr">
					<?php _e( 'History Pengiriman', 'woocommerce-shipment-tracking' ); ?>	
				</span>
			</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($waybill_data['result']['manifest'] as $detail): ?>
			<tr>
				<td class="tracking-provider">
					<?php echo format_ind_date($detail['manifest_date'],true).' - '.$detail['manifest_time']; ?>
				</td>
				<td class="tracking-provider">
					<?php echo $detail['manifest_description']; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php 
	if($waybill_data['delivery_status'] != null ):
?>
<table class="shop_table shop_table_responsive my_account_tracking_detail">
	<thead>
		<tr>
			<th class="tracking-provider" colspan="2">
				<span class="nobr">
					<?php _e( 'Status Pengiriman', 'woocommerce-shipment-tracking' ); ?>	
				</span>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="tracking-provider">
				<?php _e( 'Status Pengiriman', 'woocommerce-shipment-tracking' ); ?>
			</td>
			<td class="tracking-provider">
				<?php $waybill_data['delivery_status']; ?>
			</td>
		</tr>
		<tr>
			<td class="tracking-provider">
				<?php _e( 'Nama Penerima', 'woocommerce-shipment-tracking' ); ?>
			</td>
			<td class="tracking-provider">
				<?php echo $waybill_data['delivery_status']['pod_receiver']; ?>
			</td>
		</tr>
		<tr>
			<td class="tracking-provider">
				<?php _e( 'Tanggal / Waktu Diterima', 'woocommerce-shipment-tracking' ); ?>
			</td>
			<td class="tracking-provider">
				<?php echo format_ind_date($waybill_data['delivery_status']['pod_date']).' - '.$waybill_data['delivery_status']['pod_time']; ?>
			</td>
		</tr>
	</tbody>
</table>
<?php endif; ?>
<?php 
	else:
	// If Waybill Return is Not Okay
?>
<h2 style="margin-top: 15px;">
	<?php echo apply_filters( 'woocommerce_shipment_tracking_my_orders_title', __( 'Informasi Pengiriman', 'woocommerce-shipment-tracking' ) ); ?>		
</h2>
<table class="shop_table shop_table_responsive my_account_tracking">
	<thead>
		<tr>
			<th class="tracking-provider">
				<span class="nobr">
					<?php _e( 'Kurir Pengiriman', 'woocommerce-shipment-tracking' ); ?>
				</span>
			</th>
			<th class="tracking-number">
				<span class="nobr">
					<?php _e( 'Nomor Resi', 'woocommerce-shipment-tracking' ); ?>	
				</span>
			</th>
			<th class="tracking-number">
				<span class="nobr">
					<?php _e( 'Status Pengiriman', 'woocommerce-shipment-tracking' ); ?>		
				</span>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr class="tracking">
			<td class="courier-provider">
				<?php echo $waybill_name; ?>
			</td>
			<td class="waybill-number">
				<?php echo $waybill_num; ?>
			</td>
			<td class="tracking-number">
				-
			</td>
		</tr>
	</tbody>
</table>
<table class="shop_table shop_table_responsive my_account_tracking_detail">
	<thead>
		<tr>
			<th class="tracking-provider" colspan="2">
				<span class="nobr">
					<?php _e( 'History Pengiriman', 'woocommerce-shipment-tracking' ); ?>	
				</span>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="tracking-provider" colspan="2">
				-
			</td>
		</tr>
	</tbody>
</table>
<?php
endif;
endif;
