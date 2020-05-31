<?php
/**
 * Classic Editor
 * @package sincronization_pluging
 * @version 1.0
 * Plugin Name: Sincronizacion de Productos
 * Plugin URI:  
 * Description: Allows Make Product sincronization, inserting new products to woocomerce tables
 * Version:     1.0
 * Author:      Nelson Sánchez Alvarez
 * Author URI:  
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: sincronize
 * Domain Path: /languages
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
require_once('createProducts.php');
require_once('configurations.php');
require_once('shiping-costs.php');
require_once('api-functions.php');
require_once('venta.php');

//require_once('venta.php');
if (!empty($_GET['no_page'])) {$no_page = $_GET['no_page']; } else{$no_page = 1;}

if(!function_exists('Main_Form')):
function Main_Form(){
    add_menu_page( 'Srincronización de productos', 'Srincronización', 'administrator','sincronize',
    'Data_table_Logs', 'dashicons-format-aside',20);
    add_submenu_page('sincronize', 'configurations','Configuraciones', 'administrator','configurations-settings','Configuraciones');
    add_submenu_page('sincronize', 'shiping-costs','Costos Envios', 'administrator','shiping-settings','Shiping_cost');
    //add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function)
}
endif;
add_action('admin_menu','Main_Form');
//add_action('admin_menu','Configuraciones');
if(!function_exists('Data_table_Logs')):
    function Data_table_Logs(){
        global $post;
        $no_page=null;
        if (!empty($_GET['no_page'])) {
            $no_page = $_GET['no_page'];              
          } else {
            $no_page = 1;
          }
        $args = array(
            'post_type' => array('api_log'),
            'showposts' => -1);
          $articles = get_posts($args);
              $no_of_records_per_page = 25;
              $offset = ($no_page-1) * $no_of_records_per_page;
              $counter=$offset;
              if($counter<25){
              $limit=25;
              }
              else{
              $limit=$no_page*25;
              }
              $total_pages_sql = count($articles);
              $total_pages = ceil($total_pages_sql/ $no_of_records_per_page);
              //for botons enable and disable
              $new_products=NewsProducts();
              $update_products=UpdateProducts();
              $update_cats=UpdCategories();
              $new_categories=NewsCategories();
              UpdateProductStock();
        ?>
<div class="wrap">
    <form method="post" >   
        <table class="wp-list-table widefat stripe">
            <tr>
                <td>
                    <?php if($new_products>0): ?>
                         <button type="submit" class="button button-primary" name="operacion" value="SincProductos"
                         id="SincProductos">Sincronizar <small style="color: #000;"> <?=$new_products.' Nuevo(s) '?> </small> Productos</button>
                    <?php else:?>  
                        <button type="submit" disabled class="button button-primary" name="operacion" value="SincProductos"
                         id="SincProductos">Sincronizar Productos</button>   
                    <?php endif;?> 
                </td>
                <td>
                    <?php if($update_products>0): ?>
                         <button type="submit" class="button button-primary" name="operacion" value="updateProductos"
                         id="updateProductos">Actualizar <small style="color: #000;"><?=$update_products.' Nuevo(s) '?></small> Productos</button>
                    <?php else:?>  
                         <button type="submit" disabled class="button button-primary" name="operacion" value="updateProductos"
                         id="updateProductos">Actualizar Productos</button>
                    <?php endif;?>       

                </td>
                <td>
                    <?php if($new_categories>0):?>  
                         <button type="submit" class="button button-primary" name="operacion" value="updateCategorias"
                          id="updateCategorias">Sincronizar<small style="color: #000;"> <?=$new_categories.' Nueva(s) '?></small> Categorias</button>
                    <?php else:?> 
                        <button type="submit" disabled class="button button-primary" name="operacion" value="updateCategorias"
                          id="updateCategorias">Sincronizar Categorias</button>
                    <?php endif;?> 
                </td>
                <td>
                    <?php if($update_cats>0):?> 
                         <button type="submit" class="button button-primary" name="operacion" value="updateCat"
                           id="updateCat">Actualizar <small style="color: #000;"> <?=$update_cats.' Nueva(s) '?></small> Categorias</button>
                   <?php else:?>
                        <button type="submit" disabled class="button button-primary" name="operacion" value="updateCat"
                        id="updateCat">Actualizar Categorias</button>
                    <?php endif;?>  
                </td>
                <td>
                    <button type="submit" class="button button-primary" name="operacion" value="product_cat"
                        id="product_cat">Productos/Categorias</button>
                </td>
            </tr>
        </table>
    </form>
    <h1><?php _e('Todos los registros','tallaj'); ?></h1>
    <table class="wp-list-table widefat stripe">
        <thead>
            <tr>
                <th class="manage-column" style="width:10%;"><?= __('Id','tallaj')?></th>
                <th class="manage-column" style="width:25%;"><?= __('Titulo','tallaj')?></th>
                <th class="manage-column" style="width:15%;"><?= __('Fecha','tallaj')?></th>
                <th class="manage-column" style="width:50%;"><?= __('Descripción','tallaj')?></th>
            </tr>
        </thead>
        <tbody>
            <?php            
            if(count($articles)>0):                  
            while ($counter<count($articles)):
                $articles2=array();
                   for($i=0;$i<25;$i++){
                      if(empty($articles[$counter])){
                        $counter++;    
                    break;}
                       $articles2[$i]=$articles[$counter++];
                   }
                  foreach($articles2 as $post):                    
                    setup_postdata($post);
                    if(!empty($post)):?>
            <tr>
                <td class="manage-column" style="width:10%;"><?= get_the_ID() ?></td>
                <td class="manage-column" style="width:25%;"><?= get_the_title() ?></td>
                <td class="manage-column" style="width:15%;"><?= get_the_date()?></td>
                <td class="manage-column" style="width:50%;"><?= get_the_content()?></td>
            </tr>
            <?php endif; ?>
            <?php endforeach; wp_reset_postdata();?>
            <?php endwhile; 
            endif;?>
        </tbody>
    </table>
     <table class="wp-list-table widefat stripe" style="margin-top: 25px;">
            <tr>
                <td>
                    <?php  $home= get_home_url();?>
                    <div class="tablenav-pages" style="margin-top:5px;"><span class="displaying-num"
                            style="margin-top:5px;"><?=$total_pages_sql?> elementos</span>
                        <span class="pagination-links"><a class="first-page button"
                                href="<?=$home?>/wp-admin/admin.php?page=sincronize&no_page=<?=1?>"><span
                                    class="screen-reader-text">Primera página</span><span
                                    aria-hidden="true">«</span></a>
                            <a class="prev-page button"
                                href="<?=$home?>/wp-admin/admin.php?page=sincronize&no_page=<?=$no_page>2?$no_page=($no_page-1):1?>"><span
                                    class="screen-reader-text">Página anterior</span><span
                                    aria-hidden="true">‹</span></a>
                            <?php  if (!empty($_GET['no_page'])) {$no_page = $_GET['no_page']; } else{$no_page = 1;};?>
                            <span class="paging-input"><label for="current-page-selector"
                                    class="screen-reader-text">Página
                                    actual</label><span class="tablenav-paging-text">
                                    <span class="total-pages"
                                        style="margin-top:5px;"><?=$no_page?>&nbsp;&nbsp;</span>de&nbsp;&nbsp;<span
                                        class="total-pages"><?=$total_pages?></span></span></span>
                            <?php  if (!empty($_GET['no_page'])) {$no_page = $_GET['no_page']; } else{$no_page = 1;};?>
                            <a class="next-page button"
                                href="<?=$home?>/wp-admin/admin.php?page=sincronize&no_page=<?=$no_page<$total_pages?$no_page=$no_page+1:$total_pages?>"><span
                                    class="screen-reader-text">Página siguiente</span><span
                                    aria-hidden="true">›</span></a>
                            <?php  if (!empty($_GET['no_page'])) {$no_page = $_GET['no_page']; } else{$no_page = 1;};?>
                            <a class="last-page button"
                                href="<?=$home?>/wp-admin/admin.php?page=sincronize&no_page=<?=$total_pages?>"><span
                                    class="screen-reader-text">Última página</span><span
                                    aria-hidden="true">»</span></a></span></div>
                </td>
                <td>
                <form method="post">
                    <button type="submit" class="button button-primary" name="operacion" value="delete" id="delete">Eliminar
                        Registros</button>
                </form>    
                </td>
            </tr>
        </table>
    <?php }
endif;
if(!function_exists('UpdateSystem')):
    function UpdateSystem(){  
        $host=get_option('bd_host');
        $name=get_option('bd_name');
        $user=get_option('bd_user');
        $pass=get_option('bd_pass');
        if(empty($_POST['operacion']))
          return;
        $var_accion=$_POST['operacion'];
        
        if(empty($host)||empty($name)||empty($user)||empty($pass)){
            $mesage ='<div id="message" class="notice notice-warning is-dismissible"><p>Configuración de base de datos incompleta</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Descartar este aviso.</span></button></div>';
        } 
        if($var_accion==='updateProductos'){ 
        $result= UpdateProduct();  
        $no_page = 1;
        if($result)
             $mesage ='<div id="message" class="updated notice is-dismissible"><p>Actualización efectuada'.$response.'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Descartar este aviso.</span></button></div>';
        else
            $mesage ='<div id="message" class="notice notice-warning is-dismissible"><p>No hay producto(s) para actualizar</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Descartar este aviso.</span></button></div>';
         echo $mesage;
        }
       else if($var_accion==='SincProductos'){ 
        $result= CreateProduct();  
        $no_page = 1;
        if($result)
           $mesage ='<div id="message" class="updated notice is-dismissible"><p>Sincronización efectuada'.$response.'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Descartar este aviso.</span></button></div>';
        else
           $mesage ='<div id="message" class="notice notice-warning is-dismissible"><p>No hay producto(s) para Sinronizar</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Descartar este aviso.</span></button></div>';
            echo $mesage;
        }
       else if($var_accion==='updateCategorias'){  
        $result=CreateCategories();
         if($result>0)      
             $mesage ='<div id="message" class="updated notice is-dismissible"><p>Categorias Sinronizadas</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Descartar este aviso.</span></button></div>';
         else 
             $mesage ='<div id="message" class="notice notice-warning is-dismissible"><p>No hay categoria(s) para Sinronizar</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Descartar este aviso.</span></button></div>'; 
        echo $mesage;
          }
          else if($var_accion==='updateCat'){  
            $result=UpdateCategories();
             if($result>0)      
                 $mesage ='<div id="message" class="updated notice is-dismissible"><p>Categorias Actualizada</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Descartar este aviso.</span></button></div>';
             else 
                 $mesage ='<div id="message" class="notice notice-warning is-dismissible"><p>No hay categoria(s) para actualizar</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Descartar este aviso.</span></button></div>'; 
            echo $mesage;
              }
              else if($var_accion==='product_cat'){  
                $result=ProductosCategories();
                 if($result>0)      
                     $mesage ='<div id="message" class="updated notice is-dismissible"><p>Cambios Efectuados</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Descartar este aviso.</span></button></div>';
                 else 
                     $mesage ='<div id="message" class="notice notice-warning is-dismissible"><p>Nada que actualizar</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Descartar este aviso.</span></button></div>'; 
                echo $mesage;
            }   
          else if($var_accion==='delete'){         
            global $wpdb;
            $post_table=$wpdb->posts;
            $query="DELETE FROM {$post_table} WHERE post_type='api_log'";
             $wpdb->query($query);          
          }   
 }
endif; 
add_action('init','UpdateSystem'); 
?>