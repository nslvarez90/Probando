<?php
if(!function_exists('Configuraciones')):
    function Configuraciones(){?>
       <div class="wrap">
      <form method="post" action="options.php">
        <?php 
           settings_fields('configurations');
           do_settings_sections('configurations');
        ?>
        <table class="wp-list-table widefat stripe">
            <tr>
                <th class="manage-column"><?= __('Url de la base de datos','tallaj')?></th>
                <th class="manage-column"><?= __('Nombre de la base de datos','tallaj')?></th>
                <th class="manage-column"><?= __('Usuario base de datos','tallaj')?></th>
                <th class="manage-column"><?= __('Password de acceso ','tallaj')?></th>
                <th class="manage-column"><?= __('Id Almacen ','tallaj')?></th>
                <th class="manage-column"><?= __('No Empresa ','tallaj')?></th>
            </tr>
            <tr>
                <td>
                    <input type="text" name="bd_host" value="<?php echo esc_attr(get_option('bd_host')) ?>"
                        placeholder="localhost" style="width: 50%;">
                </td>
                <td>
                    <input type="text" name="bd_name" value="<?php echo esc_attr(get_option('bd_name')) ?>"
                        placeholder="localhost" style="width: 50%;">
                </td>
                <td>
                    <input type="text" name="bd_user" value="<?php echo esc_attr(get_option('bd_user')) ?>"
                        placeholder="root" style="width: 50%;">
                </td>
                <td>
                    <input type="password" name="bd_pass" value="<?php echo esc_attr(get_option('bd_pass')) ?>"
                        placeholder="root" style="width: 50%;">
                </td>
                <td>
                    <input type="number" name="id_almacen" value="<?php echo esc_attr(get_option('id_almacen')) ?>"
                        placeholder="root" style="width: 50%;">
                </td>
                <td>
                    <input type="number" pattern=".[\d]{3,3}"  title="Solo tres caracteres" name="no_empresa" value="<?php echo esc_attr(get_option('no_empresa')) ?>"
                        placeholder="119" style="width: 50%;">
                </td>
            </tr>
        </table>
        <table class="wp-list-table widefat stripe">
            <thead>
               <th>
               <th class="manage-column"><?= __('Tiempo para la actualización del Stock de los Productos','tallaj')?></th> 
               </th>
            </thead>
           <tr>
           <td>Tiempo en minutos</td>
           <td>
               <input type="number"  name="cron_mint" value="<?php echo esc_attr(get_option('cron_mint')) ?>"
                        placeholder="120" style="width:100%;"></td>
           </tr>          
        </table>
        <?php submit_button('Guardar')?>
       </form>
 <?php } endif;
 if(!function_exists('configurations_plugin_register')):
    function configurations_plugin_register(){
       /**SLIDERS */       
        register_setting('configurations','bd_host');
        register_setting('configurations','bd_name');
        register_setting('configurations','bd_user');
        register_setting('configurations','bd_pass');
        register_setting('configurations','id_almacen');
        register_setting('configurations','no_empresa');
        register_setting('configurations','cron_mint');
    }
endif;
add_action('admin_init','configurations_plugin_register');
?>