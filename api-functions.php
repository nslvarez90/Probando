<?php

function getConnection(){
    //$db = new mysqli("86.109.107.13", "royuela", "Ryla@2019", "royuela");
    //$db = new mysqli("localhost", "root", "", "base_erp_gallegos");
    $host=get_option('bd_host');
    $name=get_option('bd_name');
    $user=get_option('bd_user');
    $pass=get_option('bd_pass');
    if(!empty($host)&&!empty($name)&&!empty($user)&&!empty($pass))
        $db = new mysqli($host, $user, $pass, $name);
    else
        $db = new mysqli("localhost", "root", "", "ki401536_royuela");
    
if ($db->connect_error) {
    die("ERROR: No se puede conectar al servidor: " . $conn->connect_error);
  }
return $db;  
}

function getProdutById($code) {
    /*$context = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query(
                    array(
                        'tUser' => $user,
                        'tPass' => $pass,
                        'tCode' => $code
                    )
            ),
            'timeout' => 60
        )
    ));

    $resp = file_get_contents($url, FALSE, $context);
    $mipro = json_decode($resp);
    $prod = json_decode(json_encode($mipro[0]), true);
    return $prod;*/
    //Codigo nuevo
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);


    $result=$db->query("SELECT * FROM productos WHERE productos.idProducto = ". $code);
    $lista = array();
    $numfilas = $result->num_rows;
    for ($x=0;$x<$numfilas;$x++) {
        $fila = $result->fetch_object();
        //array_push($lista,$fila);

    }
    //if($result!=false)
    //$result->free();
    $db->close();

    return $fila;

}


function getAllProduct($lim, $off) {
    /*$context = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query(
                    array(
                        'tUser' => $user,
                        'tPass' => $pass,
                        'tLimit' => $lim,
                        'tOffset' => $off,
                    )
            ),
            'timeout' => 60
        )
    ));
    $resp = file_get_contents($url, FALSE, $context);
    $mipro = json_decode($resp);
    $prod = json_decode(json_encode($mipro), true);
    return $prod;*/

    //Codigo nuevo
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
    $limit = $lim;
    $offset = $off;
    $result=$db->query("SELECT * FROM productos WHERE productos.borrado = 0 AND productos.DisponibleEnWeb = 1 LIMIT " . $offset . "," . $limit);
    $lista = array();
    $numfilas = $result->num_rows;
    for ($x=0;$x<$numfilas;$x++) {
        $fila = $result->fetch_object();
        array_push($lista,$fila);
    }
    //if($result!=false)
    //$result->free();
    $db->close();

    return $lista;

}

function getProductPriceById($id) {
   /* $context = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query(
                    array(
                        'tUser' => $user,
                        'tPass' => $pass,
                        'tId' => $id
                    )
            ),
            'timeout' => 60
        )
    ));

    $resp = file_get_contents($url, FALSE, $context);
    $mipro = json_decode($resp);
    $prod = json_decode(json_encode($mipro[0]), true);
    return $prod;*/

    //Codigo nuevo
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
    $code = $id;
    $idalmacen=get_option('id_almacen');
    if(!empty($idalmacen)){ 
         //var_dump("SELECT * FROM productosalmacen WHERE productosalmacen.idProducto =".$code." AND productosalmacen.idAlmacen=".$idalmacen);
         $result=$db->query("SELECT * FROM productosalmacen WHERE productosalmacen.idProducto = ".$code." AND productosalmacen.idAlmacen =".$idalmacen);
    }else
         $result=$db->query("SELECT * FROM productosalmacen WHERE productosalmacen.idProducto = ".$code);
    
    $lista = array();
    $fila="";
    $numfilas = $result->num_rows;
    for ($x=0;$x<$numfilas;$x++) {
        $fila = $result->fetch_object();
        $fila->TarifaWeb = utf8_encode($fila->TarifaWeb);
        array_push($lista,$fila);
    }
    //if($result!=false)
    //$result->free();
    $db->close();
    return $fila;
}


function getProductDimensionsById($id) {
    /*$context = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query(
                    array(
                        'tUser' => $user,
                        'tPass' => $pass,
                        'tId' => $id
                    )
            ),
            'timeout' => 60
        )
    ));

    $resp = file_get_contents($url, FALSE, $context);
    $mipro = json_decode($resp);
    $prod = json_decode(json_encode($mipro[0]), true);
    return $prod;*/

    //Codigo nuevo
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
    $code = $id;
    $result=$db->query("SELECT * FROM productos WHERE productos.idProducto = ".$id);
    $lista = array();
    $numfilas = $result->num_rows;
    for ($x=0;$x<$numfilas;$x++) {
        $fila = $result->fetch_object();
        $fila->idProducto = utf8_encode($fila->idProducto);
        $fila->codigo = utf8_encode($fila->codigo);
        $fila->MedidaAlto = utf8_encode($fila->MedidaAlto);
        $fila->MedidaAncho = utf8_encode($fila->MedidaAncho);
        $fila->MedidaLargo = utf8_encode($fila->MedidaLargo);

        array_push($lista,$fila);

    }
    //if($result!=false)
    //$result->free();
    $db->close();

    return $fila;
}


function getProductCount() {
 
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
    $result=$db->query("SELECT Count(productos.idProducto) as Cantidad FROM productos WHERE productos.borrado = 0 AND productos.DisponibleEnWeb = 1");

    $numfilas = $result->num_rows;
    for ($x=0;$x<$numfilas;$x++) {
        $fila = $result->fetch_object();
    }
    //if($result!=false)
    //$result->free();
    $db->close();
    return $fila->Cantidad;
}

function setProductIDWebSite($idWebSite, $idProducto) {
    //Codigo nuevo
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);

    $result=$db->query("UPDATE  productos SET  productos.idWeb = ".$idWebSite." WHERE  productos.idProducto = ".$idProducto);
    $result2=$db->query("UPDATE  stock_tienda SET  stock_tienda.idWeb = ".$idWebSite." WHERE  stock_tienda.id_producto = ".$idProducto);
   /* $lista = array();
    $numfilas = $result->num_rows;
    for ($x=0;$x<$numfilas;$x++) {
        $fila = $result->fetch_object();

        array_push($lista,$fila);

    }
    //if($result!=false)
        //$result->free();
    $db->close();*/
    return $result;

}

function setCategory( $idCategoria, $idWeb) {   
    //Codigo nuevo
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
   // var_dump($idCategoria);
   // var_dump($idWEB);
    $result=$db->query("UPDATE categorias SET  categorias.idWeb = ".$idWeb." WHERE  categorias.idCategoria =".$idCategoria);
    //$result=$db->query("UPDATE  productos SET  productos.idWeb = ".$idWebSite." WHERE  productos.idProducto = ".$idProducto);
    //var_dump($result);
   /* $lista = array();
    $numfilas = $result->num_rows;
    for ($x=0;$x<$numfilas;$x++) {
        $fila = $result->fetch_object();
        array_push($lista,$fila);
    }
    //if($result!=false)
    //$result->free();
    $db->close();*/
    return $result;
}
function setCategoryUpdate( $idCategoria) {   
    //Codigo nuevo
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);

    $result=$db->query("UPDATE categorias SET  categorias.Actualizado = 0  WHERE  categorias.idCategoria =".$idCategoria);
    /*$lista = array();
    $numfilas = $result->num_rows;
    for ($x=0;$x<$numfilas;$x++) {
        $fila = $result->fetch_object();
        array_push($lista,$fila);
    }
    //if($result!=false)
    //$result->free();*/
    $db->close();
    return $result;
}
function getParentCategory($id) {
    $db = getConnection();
     if ($db->connect_error)
         die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error); 
     $result=$db->query("SELECT * FROM categorias WHERE categorias.idCategoria= ".$id);
     $lista = array();
     $numfilas = $result->num_rows;
     for ($x=0;$x<$numfilas;$x++) {
         $fila = $result->fetch_object();
         array_push($lista,$fila); 
     }
     //if($result!=false)
     //$result->free();
     $db->close(); 
     return $fila->Nombre;
 }
 function getProductCategories(){
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
    $limit = $lim;
    $offset = $off;
    $result=$db->query("SELECT * FROM productoscategorias  WHERE productoscategorias.borrado = 0");
    $lista = array();
    $numfilas = $result->num_rows;
    for ($x=0;$x<$numfilas;$x++) {
        $fila = $result->fetch_object();
        array_push($lista,$fila);
    }
    //if($result!=false)
    //$result->free();
    $db->close();

    return $lista;
 }
function addCategory( $idCategoria, $nombreCategoria) {
    
    $sql = "INSERT INTO categorias values (".$idCategoria.",'".$nombreCategoria."')";
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
    $result=$db->query($sql);
}

function getProductCategoryById($id) {  
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
    $result=$db->query("SELECT * FROM productoscategorias WHERE productoscategorias.idProducto = ".$id);
    $lista=array();
    $numfilas = $result->num_rows;
    for ($x=0;$x<$numfilas;$x++) {
        $fila = $result->fetch_object();

        array_push($lista,$fila);
    }
    //if($result!=false)
    //$result->free();
    $db->close();
    return $lista;
}

function getCategories() {    
    //Codigo nuevo
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
    $result=$db->query("SELECT * FROM categorias WHERE categorias.borrado=0");
    $lista=array();
    $numfilas = $result->num_rows;
    for ($x=0;$x<$numfilas;$x++) {
        $fila = $result->fetch_object();
        array_push($lista,$fila);
    }
    //if($result!=false)
    //$result->free();
    $db->close();
    return $lista;
}
function setCategories() {    
    //Codigo nuevo
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
    $result=$db->query("SELECT * FROM categorias WHERE categorias.Actualizado=1 OR categorias.borrado=1");
    $lista=array();
    $numfilas = $result->num_rows;
    for ($x=0;$x<$numfilas;$x++) {
        $fila = $result->fetch_object();
        array_push($lista,$fila);
    }
    //if($result!=false)
    //$result->free();
    $db->close();
    return $lista;
}

function getAllImagesOfProduct( $id) {
	
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);

    $result=$db->query("SELECT * FROM multiimages WHERE multiimages.idProduct = ".$id);
    $lista = array();
    $numfilas = $result->num_rows;
    for ($x=0;$x<$numfilas; $x++) {
        $fila = $result->fetch_object();
        array_push($lista,$fila);

    }
    //if($result!=false)
    //$result->free();
    $db->close();
    return $lista;
}

/**
 * Cambia a 0 l indicador de actualizar producto
 * @param $url
 * @param $user
 * @param $pass

 * @param $idProducto
 *
 * @return mixed
 */
function setProductActualizado($idProducto) {
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);

    $result=$db->query("UPDATE  productos SET  productos.Actualizado = 0 WHERE  productos.idProducto = ".$idProducto );
    $lista = array();
    $numfilas = $result->num_rows;
    for ($x=0;$x<$numfilas;$x++) {
        $fila = $result->fetch_object();


        array_push($lista,$fila);

    }
    //if($result!=false)
        //$result->free();
    $db->close();

    return $fila;
}

/***
 * Para registrar valores cuando se vende un producto
 * @param $idWeb
 * @return array
 */
function productByWordPressId ($idWeb){
    //Codigo nuevo
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);

    $result=$db->query("SELECT * FROM productos WHERE productos.idWeb = ".$idWeb );
    $lista = array();
    $numfilas = $result->num_rows;
    for ($x=0;$x<$numfilas; $x++) {
        $fila = $result->fetch_object();
        array_push($lista,$fila);

    }
    //if($result!=false)
    //$result->free();
    $db->close();
    return $fila;
}
//////////ADD VENTAS//////////////////////////////////////////////////////////////////////////////////////////////////////////

/***
 * Para registrar valores cuando se vende un producto
 * @param $idWeb
 * @return array
 */
function addVenta ($idProd, $idWeb , $precio){
    //Codigo nuevo
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
    $sql = "INSERT INTO ventas_tienda(id_producto,id_wordpress, precio_venta, cantidad) VALUES (".$idProd.",".$idWeb.",".$precio.",1)";

    $result=$db->query($sql);
} 
/**
 * Para al activación y dasctivación de botones 
 */
function NewsCategories(){    
    //Codigo nuevo
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
    $result=$db->query("SELECT * FROM categorias WHERE categorias.borrado=0 AND categorias.idWeb=0");    
    return $result->num_rows;
}
function UpdCategories(){    
    //Codigo nuevo
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
    $result=$db->query("SELECT * FROM categorias WHERE (categorias.borrado=1 AND categorias.idWeb>0) OR categorias.Actualizado=1 ");    
    return $result->num_rows;
}
function NewsProducts(){    
    //Codigo nuevo
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
    $result=$db->query("SELECT * FROM productos WHERE  productos.borrado = 0 AND productos.DisponibleEnWeb = 1 AND productos.idWeb=0");    
    return $result->num_rows;
}
function UpdateProducts(){    
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
    $result=$db->query("SELECT * FROM productos WHERE  productos.borrado = 0 AND productos.Actualizado=1");    
    return $result->num_rows;
}
/***
 * Ventas insertadas
 */
function InsertarVenta_tienda($id_producto,$id_wordpress,$precio_venta, $cantidad,$idpedido,$idcliente,$idclientewp,$idestadopedido,$fecha)
{   //error_log('Llamo a la funcion tienda');
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
    $sql = "INSERT INTO ventas_tienda(  id_producto ,  id_wordpress ,  precio_venta ,  cantidad ,  idpedido,idcliente,idclientewp,idestadopedido,fecha) VALUES (".utf8_encode($id_producto).",".utf8_encode($id_wordpress).",".utf8_encode($precio_venta).",".utf8_encode($cantidad).",".utf8_encode($idpedido).",".utf8_encode($idcliente).",".utf8_encode($idclientewp).",".$idestadopedido.",".$fecha.")";
    error_log($sql);
    $result=$db->query($sql);
    error_log($result);    
}
function Insertar_clienteParticular($apellidos,$nombre,$direccion,$cp,$ciudad,$provincia,$fecha,$telf,$email,$idWeb,$codigo){
    ///error_log('Llamo a la funcion cliente partcicular');
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
     $id_empresa=get_option('no_empresa');
     empty($id_empresa)?$id_empresa=119:$id_empresa;
     $sql="INSERT INTO clientes(Codigo, idEmpresa, Borrado, RazonSocial, NombreComercial, Nombre, Cif, Estado, Direccion, CP, Poblacion, Provincia, ApdoCorreos, FechaAlta, CtaContable, Telefono, email1,idCreacion,idBorrado,idWeb) VALUES (".$codigo.",".$id_empresa.",0,'-','-',".$nombre.' '.$apellidos.",'-',1,".$direccion.",".$cp.",".$ciudad.",".$provincia.",'-',".$fecha.",000,".$telf.",".$email.",".$fecha.",".$fecha.",".$idWeb.")";
   // $sql = "INSERT INTO ventas_tienda(  id_producto ,  id_wordpress ,  precio_venta ,  cantidad ,  idpedido,idcliente,idclientewp,idestadopedido,fecha) VALUES (".utf8_encode($id_producto).",".utf8_encode($id_wordpress).",".utf8_encode($precio_venta).",".utf8_encode($cantidad).",".utf8_encode($idpedido).",".utf8_encode($idcliente).",".utf8_encode($idclientewp).",".$idestadopedido.",".$fecha.")";
    error_log($sql);
    $result=$db->query($sql);
    error_log($result); 
}
function Insertar_clienteOrganizacion($apellidos,$nombre,$nif,$direccion,$cp,$ciudad,$provincia,$fecha,$telf,$email,$idWeb,$codigo,$name){
    error_log('Llamo a la funcion cliente empresa');
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
     $id_empresa=get_option('no_empresa');
     empty($id_empresa)?$id_empresa=119:$id_empresa;
     $sql="INSERT INTO clientes(Codigo, idEmpresa, Borrado, RazonSocial, NombreComercial, Nombre, Cif, Estado, Direccion, CP, Poblacion, Provincia, ApdoCorreos, FechaAlta, CtaContable, Telefono, email1,idCreacion,idBorrado,idWeb) VALUES (".$codigo.",".$id_empresa.",0,'-',".$nombre.",".$name.' '.$apellidos.",".$nif.",1,".$direccion.",".$cp.",".$ciudad.",".$provincia.",'-',".$fecha.",000,".$telf.",".$email.",".$fecha.",".$fecha.",".$idWeb.")";
   // $sql = "INSERT INTO ventas_tienda(  id_producto ,  id_wordpress ,  precio_venta ,  cantidad ,  idpedido,idcliente,idclientewp,idestadopedido,fecha) VALUES (".utf8_encode($id_producto).",".utf8_encode($id_wordpress).",".utf8_encode($precio_venta).",".utf8_encode($cantidad).",".utf8_encode($idpedido).",".utf8_encode($idcliente).",".utf8_encode($idclientewp).",".$idestadopedido.",".$fecha.")";
    error_log($sql);
    $result=$db->query($sql);
    error_log($result); 
}
function check_NIF($id){
   // error_log('Llamo a la funcion check NIF');
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
    $sql="SELECT * FROM clientes WHERE Cif=".$id;
   // error_log($sql);
    $result=$db->query($sql);
   //
   $total= $result->num_rows;
 //  error_log($total);
    return $total;
}
function check_idWeb($id){
   // error_log('Llamo a la funcion check IDWEB');
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
    $sql="SELECT * FROM clientes WHERE idWeb=".$id;
   // error_log($sql);
    $result=$db->query($sql);
   $total= $result->num_rows;
  // error_log($total);
    return $total;
}
function UpdateClient($apellidos,$nombre,$direccion,$cp,$ciudad,$provincia,$telf,$email,$id){
    //error_log('Llamo a la funcion update');
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
    $sql="UPDATE  clientes SET RazonSocial ='-' ,Nombre = ".$nombre." ".$apellidos." ,Direccion = ".$direccion." ,CP=".$cp." ,Poblacion=".$ciudad." ,Provincia=".$provincia." ,Telefono=".$telf." ,email1=".$email."  WHERE idWeb= ".$id;
   // error_log($sql);
    $result=$db->query($sql);
   // error_log($result);
    $total= $result->num_rows;
   // error_log($total);
    return $total;
}
/**
 * Actualizar cantidad de productos disponibles
 */
function Update_total(){
    $db = getConnection();
    if ($db->connect_error)
        die('Error de Conexion ('.$db->connect_errno.')'.$db->connect_error);
    $sql="SELECT * FROM stock_tienda";
   // error_log($sql);
    $result=$db->query($sql);
   $lista = array();
    $numfilas = $result->num_rows;
    for ($x=0;$x<$numfilas; $x++) {
        $fila = $result->fetch_object();
        array_push($lista,$fila);
    }
    //if($result!=false)
    //$result->free();
    $db->close();
    return $lista;
  // error_log($total);
}
?>