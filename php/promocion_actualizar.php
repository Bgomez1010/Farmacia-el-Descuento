<?php
	require_once "main.php";

	/*== Almacenando id ==*/
    $id=limpiar_cadena($_POST['promocion_id']);


    /*== Verificando producto ==*/
	$check_promocion=conexion();
	$check_promocion=$check_promocion->query("SELECT * FROM promocion WHERE promocion_id='$id'");

    if($check_promocion->rowCount()<=0){
    	echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La Promocion no existe en el sistema
            </div>
        ';
        exit();
    }else{
    	$datos=$check_promocion->fetch();
    }
    $check_promocion=null;


    /*== Almacenando datos ==*/
	$nombre=limpiar_cadena($_POST['promocion_nombre']);
    $descripcion=limpiar_cadena($_POST['promocion_descripcion']);
	$descuento=limpiar_cadena($_POST['promocion_descuento']);
	$categoria=limpiar_cadena($_POST['promocion_categoria']);


	/*== Verificando campos obligatorios ==*/
    if( $nombre=="" || $descripcion=="" || $descuento=="" ||  $categoria==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }


    /*== Verificando integridad de los datos ==*/

    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}",$nombre)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,700}",$descripcion)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if(verificar_datos("[0-9.]{1,25}",$descuento)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El PRECIO no coincide con el formato solicitado
            </div>
        ';
        exit();
    }





    /*== Verificando nombre ==*/
    if($nombre!=$datos['promocion_nombre']){
	    $check_nombre=conexion();
	    $check_nombre=$check_nombre->query("SELECT promocion_nombre FROM promocion WHERE promocion_nombre='$nombre'");
	    if($check_nombre->rowCount()>0){
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                El NOMBRE ingresado ya se encuentra registrado, por favor elija otro
	            </div>
	        ';
	        exit();
	    }
	    $check_nombre=null;
    }


    /*== Verificando categoria ==*/
    if($categoria!=$datos['categoria_id']){
	    $check_categoria=conexion();
	    $check_categoria=$check_categoria->query("SELECT categoria_id FROM categoria WHERE categoria_id='$categoria'");
	    if($check_categoria->rowCount()<=0){
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                La categoría seleccionada no existe
	            </div>
	        ';
	        exit();
	    }
	    $check_categoria=null;
    }


    /*== Actualizando datos ==*/
    $actualizar_promocion=conexion();
    $actualizar_promocion=$actualizar_promocion->prepare("UPDATE promocion SET promocion_nombre=:nombre,promocion_descripcion=:descripcion,promocion_descuento=:descuento,categoria_id=:categoria WHERE promocion_id=:id");

    $marcadores=[
        ":nombre"=>$nombre,
        ":descripcion"=>$descripcion,
        ":descuento"=>$descuento,
        ":categoria"=>$categoria,
        ":id"=>$id
    ];


    if($actualizar_promocion->execute($marcadores)){
        echo '
            <div class="notification is-info is-light">
                <strong>¡PROMOCION ACTUALIZADA!</strong><br>
                La Promocion se actualizo con exito
            </div>
        ';
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo actualizar la Promocion, por favor intente nuevamente
            </div>
        ';
    }
    $actualizar_promocion=null;