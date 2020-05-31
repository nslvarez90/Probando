<?php
require_once('api-functions.php');
add_action('woocommerce_thankyou', 'Insert_ventas_tienda', 10, 1);
function Insert_ventas_tienda( $order_id ) {
    if ( ! $order_id )
        return;	
    // Allow code execution only once 
    if( !get_post_meta( $order_id, '_thankyou_action_done', true ) ) {
        // wp_redirect('nipinga.com');
        // Get an instance of the WC_Order object
        $order = wc_get_order( $order_id );
		$fecha = date("Y-m-d H:i:s");
		//error_log("Esta dentro de ventas");
        // Loop through order items
        foreach ( $order->get_items() as $item_id => $item ) {
		   // Get the product object
		    $product = $item->get_product();
			$id_wordpress=$product->get_id();
			//error_log($fecha);
			//error_log($id_wordpress);
			$id_producto=productByWordPressId($id_wordpress);
			//error_log($id_producto->idProducto);
			$precio=$item->get_total();
			//error_log($precio);
			$cantidad=$item->get_quantity();
			//error_log($cantidad);
			$idpedido=$order->get_id();
			//error_log($idpedido);
			$idclientewp=$order->get_customer_id();
		//	error_log($idclientewp);
			InsertarVenta_tienda($id_producto->idProducto,$id_wordpress,$precio,$cantidad,$idpedido,0,$idclientewp,1,"'".$fecha."'");			
		}
		//Capturando datos del cliente			
		$nombre=get_post_meta($order_id,"_billing_first_name",true);
		///error_log($nombre);	
		$apellidos=get_post_meta($order_id,"_billing_last_name",true);
		//error_log($apellidos);
		$empresa=get_post_meta($order_id,"_billing_company",true);
		//error_log($empresa);
		$empresa_nif=get_post_meta($order_id,"_billing_nif",true);
	//	error_log($empresa_nif);
		$pais=get_post_meta($order_id,"_billing_country",true);
		//error_log($pais);
		$dir_1=get_post_meta($order_id,"_billing_address_1",true);
		//error_log($dir_1);
		$dir_2=get_post_meta($order_id,"_billing_address_2",true);
		//error_log($dir_2);
		$codigo_postal=get_post_meta($order_id,"_billing_postcode",true);
		//error_log($codigo_postal);
		$city= $order->get_billing_city(); 
		//error_log($city);
		$provincia=$order->get_billing_state();
		$provincia= WC()->countries->get_states( $order->get_billing_country() )[$provincia];
		//error_log($provincia);
		$telefono=get_post_meta($order_id,"_billing_phone",true);
		//error_log($telefono);
		$email=get_post_meta($order_id,"_billing_email",true);
		//error_log($email);
		$dir1=$dir_1." ".$dir_2;
		//$test_order = wc_get_product($order_id);
		$codigo = $order->get_order_key();
		$codigo=explode('_',$codigo)[2];
		$codigo = array_slice(str_split($codigo),0,6);
		$codigo=implode('',$codigo); 
		//error_log($codigo);
		if(!empty($empresa) && !empty($empresa_nif)){
			//validar NIF
			$exist=check_NIF("'".$empresa_nif."'");
			//error_log($exist);
			if($exist==0)				
			   Insertar_clienteOrganizacion("'".$apellidos."'","'".$empresa."'","'".$empresa_nif."'","'".$dir1."'",$codigo_postal,"'".$city."'","'".$provincia."'","'".$fecha."'",$telefono,"'".$email."'",$idclientewp,"'".$codigo."'","'".$nombre."'");			
		}   
		else{
			$exist=check_idWeb($idclientewp);
			if($exist==0)
				Insertar_clienteParticular("'".$apellidos."'","'".$nombre."'","'".$dir1."'",$codigo_postal,"'".$city."'","'".$provincia."'","'".$fecha."'",$telefono,"'".$email."'",$idclientewp,"'".$codigo."'");
			else{
				UpdateClient("'".$apellidos."'","'".$nombre."'","'".$dir1."'",$codigo_postal,"'".$city."'","'".$provincia."'",$telefono,"'".$email."'",$idclientewp);
			}	
		   
		}
        $order->update_meta_data('_thankyou_action_done', true );
        $order->save();		
    }
   }
function UpdateProductStock(){
	//var_dump("UpdateProductStock");
  $products_stock=Update_total();
   //var_dump(count($products_stock));
  $args = array(
	'post_type' => array('product'),
	'showposts' => -1);
  $wp_products = get_posts($args);
  //var_dump(count($wp_products));
  foreach($wp_products as $wp_product){
	 //var_dump($wp_product);
	  //var_dump($wp_product);
	  $product=productByWordPressId($wp_product->ID);
	  //var_dump($product);
	  foreach($products_stock as $product_stock){
		  //var_dump($product_stock->id_producto);
		 // var_dump($product->idProducto);
          if($product->idProducto==$product_stock->id_producto){
			 //var_dump("find a product");
			// var_dump($product_stock);
		     $a= update_post_meta( $wp_product->ID, '_manage_stock', "yes" );  
			 $b= update_post_meta( $wp_product->ID, '_stock',$product_stock->stock); 
			 //var_dump($a);			
			 //var_dump($b);			
			//die;
		  }
	  }
  }
}
function cron_add_minute( $schedules ) {
	// Adds once every minute to the existing schedules.
	!empty(get_option('cron_mint'))?$inter=(get_option('cron_mint')*60):$inter=7200;
    $schedules['everyminute'] = array(
	    'interval' => $inter,
	    'display' => __( 'Actualizar Stock' )
    );
    return $schedules;
}
add_filter( 'cron_schedules', 'cron_add_minute');
// create a scheduled event (if it does not exist already)
if( !wp_next_scheduled( 'mycronjob' ) ) {  
	   wp_schedule_event( time(),'everyminute','mycronjob'); 
	}
add_action ('mycronjob','UpdateProductStock'); 
// Ocultar otros métodos de envío cuando el envío gratuito está disponible.
function my_hide_shipping_when_free_is_available( $rates ) {	
	$free = array();
	$total=WC()->cart->subtotal;	
	error_log($total);
	$postcode =  WC()->customer->get_billing_postcode();
	error_log($postcode);
    //valores de los umbrales para península
	$p_cpeque=get_option('p_cpeque');
	$p_cmedia=get_option('p_cmedia');
    //valores de los umbrales para baleares
	$b_cpeque=get_option('b_cpeque');
	$b_cmedia=get_option('b_cmedia');
	$b_cgrande=get_option('b_cgrande');
	foreach ( $rates as $rate_id => $rate ) {
	if($postcode>=7000 && $postcode<=7999){
		if($total>=$b_cmedia && $rate->get_label()==='Compra Grande'){
			$free[ $rate_id ] = $rate;
		    break;
		}
		if($total<$b_cmedia && $total>=$b_cpeque && $rate->get_label()==='Compra Mediana'){
			$free[ $rate_id ] = $rate;
		    break;
		 }
		 if($total<$b_cpeque && $rate->get_label()==='Compra Pequeña'){
			$free[ $rate_id ] = $rate;
		    break;
		 }
	}	
	if ('free_shipping' === $rate->method_id ) {
		$free[ $rate_id ] = $rate;
		break;
		}	
	else if($total<$p_cmedia){
        if($total<$p_cmedia && $total>=$p_cpeque && $rate->get_label()==='Compra Mediana'){
			$free[ $rate_id ] = $rate;
		    break;
		 }
		 if($total<$p_cpeque && $rate->get_label()==='Compra Pequeña'){
			$free[ $rate_id ] = $rate;
		    break;
		 }
	}
	}
	return ! empty( $free ) ? $free : $rates;
	}
	add_filter( 'woocommerce_package_rates', 'my_hide_shipping_when_free_is_available', 100 );
?>
