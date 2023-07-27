<?php
	require_once "../inc/session_start.php";

	require_once "main.php";

	/*== Almacenando datos ==*/
    $nombre=limpiar_cadena($_POST['promocion_nombre']);
	$descripcion=limpiar_cadena($_POST['promocion_descripcion']);

	$descuento=limpiar_cadena($_POST['promocion_descuento']);
	$categoria=limpiar_cadena($_POST['promocion_categoria']);


	/*== Verificando campos obligatorios ==*/
    if($nombre==""  || $descripcion==""  || $descuento=="" ||  $categoria==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }


    /*== Verificando integridad de los datos ==*/
     if(verificar_datos("[a-zA-Z0-9- ]{1,500}",$nombre)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El nombre no coincide con el formato solicitado
            </div>
        ';
        exit();
    }
    if(verificar_datos("[a-zA-Z0-9- ]{1,500}",$descripcion)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La DESCRIPCION no coincide con el formato solicitado
            </div>
        ';
        exit();
    }



    if(verificar_datos("[0-9.]{1,25}",$descuento)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El DESCUENTO no coincide con el formato solicitado
            </div>
        ';
        exit();
    }


 /*== Verificando nombre ==*/
 $check_nombre=conexion();
 $check_nombre=$check_nombre->query("SELECT promocion_nombre FROM promocion WHERE promocion_nombre ='$nombre'");
 if($check_nombre->rowCount()>0){
     echo '
         <div class="notification is-danger is-light">
             <strong>¡Ocurrio un error inesperado!</strong><br>
             El nombre ingresado ya se encuentra registrado, por favor elija otro
         </div>
     ';
     exit();
 }
 $check_nombre=null;

    /*== Verificando descripcion ==*/
    $check_descripcion=conexion();
    $check_descripcion=$check_descripcion->query("SELECT promocion_descripcion FROM promocion WHERE promocion_descripcion ='$descripcion'");
    if($check_descripcion->rowCount()>0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La descripcion ingresado ya se encuentra registrado, por favor elija otro
            </div>
        ';
        exit();
    }
    $check_descripcion=null;


    


    /*== Verificando categoria ==*/
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


    /* Directorios de imagenes */
	$img_dir='../img/promocion/';


	/*== Comprobando si se ha seleccionado una imagen ==*/
	if($_FILES['promocion_foto']['name']!="" && $_FILES['promocion_foto']['size']>0){

        /* Creando directorio de imagenes */
        if(!file_exists($img_dir)){
            if(!mkdir($img_dir,0777)){
                echo '
                    <div class="notification is-danger is-light">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        Error al crear el directorio de imagenes
                    </div>
                ';
                exit();
            }
        }

		/* Comprobando formato de las imagenes */
		if(mime_content_type($_FILES['promocion_foto']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['promocion_foto']['tmp_name'])!="image/png"){
			echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                La imagen que ha seleccionado es de un formato que no está permitido
	            </div>
	        ';
	        exit();
		}


		/* Comprobando que la imagen no supere el peso permitido */
		if(($_FILES['promocion_foto']['size']/1024)>3072){
			echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                La imagen que ha seleccionado supera el límite de peso permitido
	            </div>
	        ';
			exit();
		}


		/* extencion de las imagenes */
		switch(mime_content_type($_FILES['promocion_foto']['tmp_name'])){
			case 'image/jpeg':
			  $img_ext=".jpg";
			break;
			case 'image/png':
			  $img_ext=".png";
			break;
		}

		/* Cambiando permisos al directorio */
		chmod($img_dir, 0777);

		/* Nombre de la imagen */
		$img_nombre=renombrar_fotos($categoria);

		/* Nombre final de la imagen */
		$foto=$img_nombre.$img_ext;

		/* Moviendo imagen al directorio */
		if(!move_uploaded_file($_FILES['promocion_foto']['tmp_name'], $img_dir.$foto)){
			echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                No podemos subir la imagen al sistema en este momento, por favor intente nuevamente
	            </div>
	        ';
			exit();
		}

	}else{
		$foto="";
	}


	/*== Guardando datos ==*/
    $guardar_promocion=conexion();
    $guardar_promocion=$guardar_promocion->prepare("INSERT INTO promocion(promocion_nombre,promocion_descripcion,promocion_descuento,promocion_foto,usuario_id,categoria_id) VALUES(:nombre,:descripcion,:descuento,:foto,:usuario,:categoria)");

    $marcadores=[
        ":nombre"=>$nombre,
        ":descripcion"=>$descripcion,
        ":descuento"=>$descuento,
        ":foto"=>$foto,
        ":categoria"=>$categoria,
        ":usuario"=>$_SESSION['id']
        
    ];

    $guardar_promocion->execute($marcadores);

    if($guardar_promocion->rowCount()==1){
        echo '
            <div class="notification is-info is-light">
                <strong>¡PROMOCION REGISTRADA!</strong><br>
                La promocion se registro con exito
            </div>
        ';
    }else{

    	if(is_file($img_dir.$foto)){
			chmod($img_dir.$foto, 0777);
			unlink($img_dir.$foto);
        }

        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo registrar la promocion, por favor intente nuevamente
            </div>
        ';
    }
    $guardar_promocion=null;