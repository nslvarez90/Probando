<?php
if(!function_exists('Shiping_cost')):
    function Shiping_cost(){error_log('IS call');?>
       
       <div class="wrap">
      <form method="post" action="options.php">
        <?php 
           settings_fields('shiping-costs');
           do_settings_sections('shiping-costs');
        ?>
        <h2 class="wc-shipping-zones-heading">Zona de envío: Península	</h2>
        <table class="wp-list-table widefat stripe">
            <tr>
                <th class="manage-column"><?= __('Tarifa Máxima Compra pequeña','tallaj')?></th>
                <th class="manage-column"><?= __('Tarifa Máxima Compra mediana','tallaj')?></th>               
                <th class="manage-column"><?= __('Tarifa Máxima Compra grande','tallaj')?></th>               
            </tr>
            <tr>
                <td>
                    <input type="number" name="p_cpeque" value="<?php echo esc_attr(get_option('p_cpeque')) ?>"
                        placeholder="25" style="width: 50%;">
                </td>
                <td>
                    <input type="number" name="p_cmedia" value="<?php echo esc_attr(get_option('p_cmedia')) ?>"
                        placeholder="60" style="width: 50%;">
                </td> 
                <td>
                    <input type="text" name="p_cgrande" disabled value="<?php echo esc_attr(get_option('p_cgrande')) ?>"
                        placeholder="gratis" style="width: 50%;">
                </td>             
            </tr>
        </table>
        <h2 class="wc-shipping-zones-heading">Zona de envío: Baleares</h2>
        <table class="wp-list-table widefat stripe">
            <tr>
                <th class="manage-column"><?= __('Tarifa Máxima Compra pequeña','tallaj')?></th>
                <th class="manage-column"><?= __('Tarifa Máxima Compra mediana','tallaj')?></th>             
                <th class="manage-column"><?= __('Tarifa Máxima Compra grande','tallaj')?></th>             
            </tr>
            <tr>
                <td>
                    <input type="number" name="b_cpeque" value="<?php echo esc_attr(get_option('b_cpeque')) ?>"
                        placeholder="25" style="width: 50%;">
                </td>
                <td>
                    <input type="number" name="b_cmedia" value="<?php echo esc_attr(get_option('b_cmedia')) ?>"
                        placeholder="60" style="width: 50%;">
                </td>    
                
                <td>
                    <input type="text" name="b_cgrande" value="<?php echo esc_attr(get_option('b_cgrande')) ?>"
                        placeholder="+60" style="width: 50%;">
                </td>         
            </tr>
        </table>
        
        <?php submit_button('Guardar')?>
       </form>
 <?php } endif;
 if(!function_exists('shiping_plugin_register')):
    function shiping_plugin_register(){
       /**SLIDERS */       
        register_setting('shiping-costs','p_cpeque');
        register_setting('shiping-costs','p_cmedia');
        register_setting('shiping-costs','p_cgrande');
        register_setting('shiping-costs','b_cpeque');
        register_setting('shiping-costs','b_cmedia');
        register_setting('shiping-costs','b_cgrande');
    }
endif;
add_action('admin_init','shiping_plugin_register');
?>