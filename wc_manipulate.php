<?php 
require_once('AddImages.php');
require_once('api-functions.php');
class WooManipulate{
    public function __construct() {        
    }
    public function addProduct($nombre, $precio, $sku, $largo, $ancho, $alto, $peso, $categorias, $url,$list_imagenes,$description) {
        if($url != "")
             $myImage = $url;
         else
             $myImage = get_home_url().'/fotos/baseP.jpg';
 
         if($precio == null){
             $precio = "0";
         } 
         $list_galeria = array();
         $element= [ 'src' => $myImage,
             'position'=>0];
         $ind=1;
         array_push($list_galeria,$element);
         if(count($list_imagenes) > 0)
             foreach ($list_imagenes as $img){
                 $element1=[];
                 $element1= [ 'src' => $img->Url,
                             'position'=>$ind];
 
                 array_push($list_galeria,$element1);
                 $ind++;
             }
             global $woocomerce;
             $post = array(
                'post_title' => $nombre,
                'post_content' => $description,
                'post_status' => 'publish',
                'post_date' => date('Y-m-d H:i:s'),
                'post_author' => $user_ID,
                'post_type' => 'product',        
                );
            // Coseguimos el ID del producto
            $post_id = wp_insert_post( $post);
            //cateegoria            
            $adminImages=new WooImages();
            //$tumbid= $adminImages->InsertImage($post_id,$list_galeria[0]['src']);
            
            foreach($categorias as $categoria){
               // var_dump($categoria['id']);
                 wp_set_object_terms($post_id,$categoria['id'],'product_cat',false);
                // wp_update_term( $post_id,'product_cat',$categoria['id']);
            }
                
               // Actualizamos el producto con los metas que necesitamos
                update_post_meta( $post_id, 'name', $nombre );                               
                //update_post_meta( $post_id, '_thumbnail_id', $tumbid );
                update_post_meta( $post_id, 'type', 'simple');
                update_post_meta( $post_id, '_regular_price', $precio );
                update_post_meta( $post_id, '_price', $precio );
               // var_dump($precio);
                //update_post_meta( $post_id, 'images', $list_galeria );                
                //update_post_meta( $post_id, 'categories', $categorias );
                update_post_meta( $post_id, 'dimensions' , array(
                    'length' => $largo,
                    'width' => $ancho,
                    'height' => $alto
                ));
                //var_dump($largo);
               // var_dump($ancho);
               // var_dump($alto);
                update_post_meta( $post_id, '_sku',trim($sku));
               // var_dump($sku);
                update_post_meta( $post_id, 'description', $description);
               // var_dump($description);
                update_post_meta( $post_id, 'short_description', $description);
               // var_dump($peso);
                //update_post_meta( $post_id, 'weight', $peso);
                
                $idProductsImages=array();
                foreach($list_galeria as $image){                     
                     //$imageid=$adminImages->UploadImage($image['src'],$post_id);
                      $imageid=$adminImages->UploadImage3($image['src'],$post_id);
                      //die;
                     //$imageid=$adminImages->InsertImage($post_id,$image['src']);
                     array_push($idProductsImages,$imageid);
                }   
                //var_dump($idProductsImages);            
                update_post_meta( $post_id,'_thumbnail_id', $idProductsImages[0]);
                $other="";
                $aux=0;
                foreach($idProductsImages as $idimage){
                    if($aux==(count($idProductsImages)-1))
                        $other.=$idimage;
                    else if($aux++==0)
                      continue;    
                    else    
                       $other.=$idimage.",";
                    $aux++;   
                }                 
                update_post_meta($post_id,'_product_image_gallery',$other);  
               // die;              
                return $post_id;
     }

    public function updateProduct($nombre, $precio, $sku, $largo, $ancho, $alto, $peso, $categorias, $url,  $list_imagenes,$description,$idWeb) {
        if($url != "")
        $myImage = $url;
        else
        $myImage = get_home_url().'/fotos/baseP.jpg';

         if($precio == null){
             $precio = "0";
         } 
         $list_galeria = array();
         $element= [ 'src' => $myImage,
             'position'=>0];
         $ind=1;
         array_push($list_galeria,$element);
         if(count($list_imagenes) > 0)
             foreach ($list_imagenes as $img){
                 $element1=[];
                 $element1= [ 'src' => $img->Url,
                             'position'=>$ind];
 
                 array_push($list_galeria,$element1);
                 $ind++;
             }
             global $woocomerce;
             $post = array(
                'ID'=>$idWeb,
                'post_title' => $nombre,
                'post_content' => $description,
                'post_status' => 'publish',
                'post_date' => date('Y-m-d H:i:s'),
                'post_author' => $user_ID,
                'post_type' => 'product',        
                );
                wp_update_post($post);
            //cateegoria
            $adminImages=new WooImages();
            //$tumbid= $adminImages->InsertImage($post_id,$list_galeria[0]['src']);  
               // Actualizamos el producto con los metas que necesitamos
                update_post_meta( $idWeb, 'name', $nombre );                               
                //update_post_meta( $post_id, '_thumbnail_id', $tumbid );
                update_post_meta( $idWeb, 'type', 'simple');
                update_post_meta( $idWeb, '_regular_price', $precio );
                update_post_meta( $idWeb, '_price', $precio );
               // var_dump($precio);
                //update_post_meta( $post_id, 'images', $list_galeria );                
                //update_post_meta( $post_id, 'categories', $categorias );
                update_post_meta( $idWeb, 'dimensions' , array(
                    'length' => $largo,
                    'width' => $ancho,
                    'height' => $alto
                ));
                //var_dump($largo);
               // var_dump($ancho);
               // var_dump($alto);
                update_post_meta( $idWeb, '_sku',trim($sku));
               // var_dump($sku);
                update_post_meta( $idWeb, 'description', $description);
               // var_dump($description);
                update_post_meta( $idWeb, 'short_description', $description);
               // var_dump($peso);
                //update_post_meta( $post_id, 'weight', $peso);
                
                $idProductsImages=array();
                foreach($list_galeria as $image){                     
                     //$imageid=$adminImages->UploadImage($image['src'],$post_id);
                      $imageid=$adminImages->UploadImage3($image['src'],$idWeb);
                     //$imageid=$adminImages->InsertImage($post_id,$image['src']);
                     array_push($idProductsImages,$imageid);
                }   
               // var_dump($idProductsImages);            
                update_post_meta( $idWeb,'_thumbnail_id', $idProductsImages[0]);
                $other="";
                $aux=0;
                foreach($idProductsImages as $idimage){
                    if($aux==(count($idProductsImages)-1))
                        $other.=$idimage;
                    else if($aux++==0)
                        continue;     
                    else    
                       $other.=$idimage.",";
                    $aux++;   
                }                 
                update_post_meta($idWeb,'_product_image_gallery',$other);                   
                return $idWeb;
      }
    public function doNotification($titulo, $mensaje) {
        
        global $user_ID;
        $new_post = array(
        'post_title' => $titulo,
        'post_content' => $mensaje,
        'post_status' => 'publish',
        'post_date' => date('Y-m-d H:i:s'),
        'post_author' => $user_ID,
        'post_type' => 'api_log',        
        );
        $post_id = wp_insert_post( $new_post,true);        
     }  
    public function AddCategory($listCategories){
        //var_dump($listCategories);
        $total=0;
        $args = array(
            'post_type' => array('product'),
            'showposts' => -1);
        $productos = get_posts($args);
        if(empty($productos)){
            return 0;
        } 
        $idscategories= array();        
        foreach($listCategories as $category){ 
          if(($category->idWeb==0)&&!empty($category->Nombre)&&($category->borrado==0)){ 
               $args=array(
                'name'=>utf8_encode($category->Nombre),
               'description'=>utf8_encode($category->Nombre),
               'slug'=>strtolower(utf8_encode($category->Nombre))
            );
            try {
                $catId= wp_insert_term(utf8_encode($category->Nombre),'product_cat',$args); 
            } catch (Exception $th) {
                 continue;
            }
            if(!is_array($catId))
                continue;     
           
           setCategory( $category->idCategoria,$catId['term_id']);  
         $total++;       
          array_push($idscategories,$catId);
         }                        
        }
        if(count($idscategories)==0){
            return 0;
        }
       // die;
        $cates = get_terms(array(
            'taxonomy' => "product_cat",
            'hide_empty' => false
        )); 
        $cont=0;
        if((count($cates)-1)!=count($idscategories)){            
            $aux=$idscategories;
            $idscategories=array();
            foreach($cates as $cate){
                if($cate->name!='Accesorios')
                  array_push($idscategories,array('term_id'=>$cate->term_id,'term_taxonomy_id'=>$cate->term_taxonomy_id));
            }
            for ($i=0; $i < count($aux) ; $i++) { 
                array_push($idscategories,$aux[$i]);
            }
        }            
      
        //actualizo los niveles de descendencia
        foreach($listCategories as $category){           
            if($category->idCategoriaPadre!=0||$category->idCategoriaPadre!=null||$category->idCategoriaPadre!=""){  
               //var_dump($category);     
               if($category->idCategoriaPadre>0){                     
                $name=getParentCategory($category->idCategoriaPadre);
                foreach($idscategories as $id){                    
                    $cat= get_term_by('id',$id['term_id'],'product_cat');
                    //var_dump($cat->name); 
                    if($cat->name==utf8_encode($name)){
                        $dev= $id['term_id'];
                        break;
                    }
                } 
                $args=array(
                    'parent'=>$dev
                 );         
               //$isdo= update_term_meta($idscategories[$cont]['term_id'],'product_cat',$parentid );
               wp_update_term($idscategories[$cont]['term_id'],'product_cat',$args);                
           }  
           }
           $cont++;             
        }
        //obtener los productos y sus categorias
        $productcate=getProductCategories();
        //var_dump(count($productcate));              
        //die;
        foreach($productos as $prod){ 
            $this->haveCategory($prod,$productcate,$idscategories);
        }
        $this->doNotification("Operación sobre Categorías", "Se han adicionado ".$total." categorias(s)");
        return $idscategories;
     } 
    function updateCat($ids,$name){  
     foreach($ids as $id){ 
           $cat= get_term_by('id', $id['term_id'], 'product_cat'); 
           if($cat->name==$name){
               $dev= $id['term_id'];
               return $dev;
           }
        }
        return 0;     
     }
    function haveCategory($post,$listproductos,$idscategories){     
        foreach($listproductos as $producto){                 
            $prod=getProdutById($producto->idProducto); 
              if($post->ID==$prod->idWeb){                
                   wp_remove_object_terms($post->ID,'accessories','product_cat');
                 $name=getParentCategory($producto->idCategoria);
                 $parentid=$this->updateCat($idscategories,$name);
                return  wp_set_object_terms($post->ID,$parentid,'product_cat',true);
               }
         }          
        return 0;
     }
    function ActCategorias($listCategories){ 
        $actualizadas=0;
        $borradas=0;
       if(count($listCategories)==0){
           return 0;
       }     
        $product_categories = get_terms(array(
            'taxonomy' => "product_cat",
            'hide_empty' => false
        )); 
        $revomeCats=array(); 
        foreach($product_categories as $prod_cat){                         
           foreach($listCategories as $category){

               $myid=utf8_encode($category->id);   
               if($prod_cat->term_id==$category->idWeb && $category->borrado==0){
                  $name=getParentCategory($category->idCategoriaPadre);
                 $dev=0;
                  foreach($product_categories as $id){
                    if($id->name==utf8_encode($name)){
                        $dev= $id->term_id;
                        break;
                    }}  
                   $args=array(
                       'name'=>utf8_encode($category->Nombre),
                      'description'=>utf8_encode($category->Nombre),
                      'slug'=>strtolower(utf8_encode($category->Nombre)),
                      'parent'=>$dev
                       );                       
                     $id= wp_update_term(utf8_encode($prod_cat->term_id),'product_cat',$args);
                     if(!is_array($id)){
                         continue;
                     } 
                     $actualizadas++;                      
                      setCategoryUpdate($category->idCategoria);
                   } 
                   else if($prod_cat->term_id==$category->idWeb && $category->borrado==1){
                     //wp_delete_category($category->idWeb);
                     error_log("YEp I'm here 2");
                     $data=wp_delete_term( $prod_cat->term_id,'product_cat');
                     setCategory($category->idCategoria,0); 
                     $id=1;
                     $borradas++;
                   }               
                }    
            } 
            $this->doNotification("Operación sobre Categorías", "Se han actualizado ".$actualizadas." categorias(s)");
            $this->doNotification("Operación sobre Categorías", "Se han retirado ".$borradas." categorias(s)");
         return $id;
     }
    function ProductoCategorias($listCategories){
        $productcate=getProductCategories();
       //var_dump(count($productcate));             
        //die;
        $args = array(
            'post_type' => array('product'),
            'showposts' => -1);
        $productos = get_posts($args);                    
        //var_dump(count($productos));   
        if(empty($productos))
            return 0;        
        if(empty($listCategories))
            return 0;
         $cates = get_terms(array(
                'taxonomy' => "product_cat",
                'hide_empty' => false
            ));    
        $idscategories=array();
        foreach($cates as $cate){
            if($cate->name!='Accesorios')
              array_push($idscategories,array('term_id'=>$cate->term_id,'term_taxonomy_id'=>$cate->term_taxonomy_id));
        }
        //var_dump(count($idscategories));
        foreach($productos as $prod){ 
           // var_dump($prod->ID);
            $this->haveCategory($prod,$productcate,$idscategories);
        }
        //die;
        return $idscategories;
     }
          
}?>