<?php
require_once('wc_manipulate.php');
require_once('api-functions.php');
function CreateProduct(){
$comm=new WooManipulate();
try{
    $count = getProductCount(); 
}catch(Exception $ex){
    $comm->doNotification("Error obteniendo cantidad total de productos", "El error ha sido: ". $ex->getMessage());
}
$count = intval($count);
$fulled = $count;
$frac = $count / 50;
$count = ceil($frac);
$off = 0;
if($count > 0)
$comm->doNotification("Actualización de productos", "Se ha  comenzado la sincronización de nuevos productos");

for ($i = 1; $i <= $count; $i++) {    
    $listProduct = getAllProduct(50, $off);
    $off = $i * 50;    
    foreach ($listProduct as $item) {            
        $actualizar= false;
        if ($item->idWeb == "" || $item->idWeb == 0 || $item->idWeb == null ){
            $actualizar = true;            
        }
        // $is = $getProdut = $comm->getProduct(556489);
        if ($actualizar) {
            try{
                $prod = getProdutById($item->idProducto );
               // var_dump($prod);
            }catch (Exception $ex){
                $comm->doNotification("Error obteniendo el producto ". $item->idProducto ." del ERP", "Error en getProdutById para el producto: " . $item->idProducto . ". El error es: ". $ex->getMessage() );
            }
            try{
                $prodPrice = getProductPriceById( $prod->idProducto );
            }catch (Exception $ex){
                $comm->doNotification("Error obteniendo el precio producto ". $item->idProducto ." del ERP", "Error en getProductPriceById para el producto: " . $item->idProducto . ". El error es: ". $ex->getMessage() );
            }
            try{
                //$prodCategories = getProductCategoryById($prod->idProducto );
                $prodCategories =array();
                $sa = [ 'id' => 129 ];
                array_push( $prodCategories, $sa );

            }catch (Exception $ex){
                $comm->doNotification("Error obteniendo la categoría del producto ". $item->idProducto ." del ERP", "Error en getProductCategoryById para el producto: " . $item->idProducto . ". El error es: ". $ex->getMessage() );
            }
            if ( count( $prodCategories ) == 0 ) {
                $sa = [ 'id' => 129 ];
                //$prodCategories=array();
                array_push( $prodCategories, $sa );
            }        
            try{
                // obteniendo listado de fotos
                $list_imagenes = getAllImagesOfProduct( $prod->idProducto );

            }catch (Exception $ex){
                $comm->doNotification("Error obteniendo las imagenes del producto ". $item->idProducto ." del ERP", "Error en getAllImagesOfProduct para el producto: " . $item->idProducto . ". El error es: ". $ex->getMessage() );
            }
              try{
                   //var_dump($prod->idProducto);
                   //var_dump(utf8_encode($prod->URL));
                   //die;
                  // var_dump(utf8_encode($prod->Nombre));
                    $eject = $comm->addProduct(utf8_encode($prod->Nombre), $prodPrice->TarifaWeb!=""?utf8_encode($prodPrice->TarifaWeb):0, utf8_encode($prod->Codigo), utf8_encode($prod->MedidaLargo), utf8_encode($prod->MedidaAncho), utf8_encode($prod->MedidaAlto), utf8_encode($prod->Peso), $prodCategories, utf8_encode($prod->URL),$list_imagenes,utf8_encode($prod->Description));
                    
                }catch (Exception $ex){
                    $eject=false;
                    $comm->doNotification("Error durante inserción de producto con id: ". $item->idProducto , "Ocurrió un error durante la actualización: ".$ex->getMessage());
                }
                if($eject != false){
                   // $simpleID = json_decode(json_encode($eject), true);
                    try{
                        $prodUpda = setProductIDWebSite( $eject, $prod->idProducto);
                        $msg = "Se ha analizado e insertado el producto  ".utf8_encode($prod->Nombre);
                        $comm->doNotification("Creación de productos", $msg);
                    }catch (Exception $ex){
                        $eject=false;
                        $comm->doNotification("Error durante insercion de idWeb en ERP para el producto ". $item->idProducto , "Ocurrió un error durante la actualización: ".$ex->getMessage());
                    }
                }           
        }
    }

}
return $eject;
}
function UpdateProduct(){
    $comm=new WooManipulate();
    try{
        $count = getProductCount(); 
    }catch(Exception $ex){
        $comm->doNotification("Error obteniendo cantidad total de productos", "El error ha sido: ". $ex->getMessage());
    }
    $count = intval($count);
    $fulled = $count;
    $frac = $count / 50;
    $count = ceil($frac);
    $off = 0;
    if($count > 0)
    $comm->doNotification("Actualización de productos", "Se ha comenzado la actualización de producto(s)");
    
    for ($i = 1; $i <= $count; $i++) {    
        $listProduct = getAllProduct(50, $off);
        $off = $i * 50;    
        foreach ($listProduct as $item) {            
            $actualizar= false;
            if ($item->Actualizado == 1){
                $actualizar = true;            
            }
            // $is = $getProdut = $comm->getProduct(556489);
            if ($actualizar){
                try{
                    $prod = getProdutById($item->idProducto );
                }catch (Exception $ex){
                    $comm->doNotification("Error obteniendo el producto ". $item->idProducto ." del ERP", "Error en getProdutById para el producto: " . $item->idProducto . ". El error es: ". $ex->getMessage() );
                }
                try{
                    $prodPrice = getProductPriceById($prod->idProducto);
                }catch (Exception $ex){
                    $comm->doNotification("Error obteniendo el precio producto ". $item->idProducto ." del ERP", "Error en getProductPriceById para el producto: " . $item->idProducto . ". El error es: ". $ex->getMessage() );
                }
                try{
                    $prodCategories = getProductCategoryById($prod->idProducto );
    
                }catch (Exception $ex){
                    $comm->doNotification("Error obteniendo la categoría del producto ". $item->idProducto ." del ERP", "Error en getProductCategoryById para el producto: " . $item->idProducto . ". El error es: ". $ex->getMessage() );
                }
                if ( count( $prodCategories ) == 0 ) {
                    $sa = [ 'id' => 129 ];
                    array_push( $prodCategories, $sa );
                }          
                try{
                    // obteniendo listado de fotos
                    $list_imagenes = getAllImagesOfProduct( $prod->idProducto );
    
                }catch (Exception $ex){
                    $comm->doNotification("Error obteniendo las imagenes del producto ". $item->idProducto ." del ERP", "Error en getAllImagesOfProduct para el producto: " . $item->idProducto . ". El error es: ". $ex->getMessage() );
                } try{
                        $eject = $comm->updateProduct(utf8_encode($prod->Nombre), $prodPrice->TarifaWeb!=""?$prodPrice->TarifaWeb:0, utf8_encode($prod->Codigo), utf8_encode($prod->MedidaLargo),
                            utf8_encode($prod->MedidaAncho), utf8_encode($prod->MedidaAlto), utf8_encode($prod->Peso), $prodCategories, utf8_encode($prod->URL),$list_imagenes,
                            utf8_encode($prod->Description), $item->idWeb);                        
                    }catch (Exception $ex){
                        $eject=false;
                        $comm->doNotification("Error durante actualización del producto: ". $item->idProducto , "Ocurrió un error durante la actualización: ".$ex->getMessage());
                    }
                    if($eject != false) {
                        $prodUpda = setProductActualizado( $prod->idProducto );
                        $msg      = "Se ha analizado y modificado el producto  " . utf8_encode($prod->Nombre);
                        $comm->doNotification( "Actualización de productos", $msg );                   
                    }else {
                        $comm->doNotification("Error durante actualización del producto ". $item->idProducto , "Ocurrió un error durante la actualización");
                    }           
            }
        }
    
    }
    return $eject;
    }
    function CreateCategories(){
        $comm=new WooManipulate();        
        try{
            $listCategories = getCategories();            
        }catch(Exception $ex){
            $comm->doNotification("Error obteniendo las categorias", "El error ha sido: ". $ex->getMessage());
        }
        try{
             $eject=$comm->AddCategory($listCategories);
             //var_dump($eject);
             if($eject==0)
                $comm->doNotification("Operacion sobre categorias", "Sin acciones a efectuar");
             else
                $comm->doNotification("Se han adicionado categorias", "Se han adicionado categoria(s)");
        }catch (Exception $ex){
            $comm->doNotification("Error obteniendo del ERP", "Error ". $ex->getMessage() );
        }
        return $eject;
    }

    function UpdateCategories(){
        $comm=new WooManipulate();        
        try{
            $listCategories = setCategories();
        }catch(Exception $ex){
            $comm->doNotification("Error obteniendo las categorias", "El error ha sido: ". $ex->getMessage());
        }
        try{
            //var_dump($listCategories);
             $eject=$comm->ActCategorias($listCategories);
             //var_dump($eject);
             if($eject==0)
                $comm->doNotification("Operacion sobre categorias", "No hay eventos Pendientes");
             else if($eject==1)
                $comm->doNotification("Operacion sobre categorias", "Se han eliminado categoria(s)");
             else
                $comm->doNotification("Se han actualizado categorias", "Se han actualizado categoria(s)");
        }catch (Exception $ex){
            $comm->doNotification("Error obteniendo  del ERP", "Error " );
        }
        return $eject;
    }
    function ProductosCategories(){
        $comm=new WooManipulate();        
        try{
            $listCategories = getProductCategories();
        }catch(Exception $ex){
            $comm->doNotification("Error obteniendo las categorias", "El error ha sido: ". $ex->getMessage());
        }
        try{
            //var_dump($listCategories);
             $eject=$comm->ProductoCategorias($listCategories);
             //var_dump($eject);
             if($eject==0)
                $comm->doNotification("Operacion de Sincronización", "No es preciso sincornizar");
             else
                $comm->doNotification("Operacion de Sincronización", "Se han actualizado Productos-categoria(s)");
        }catch (Exception $ex){
            $comm->doNotification("Error obteniendo  del ERP", "Error " );
        }
        return $eject;
    }
?>