<?php
	require_once "main.php";

	/*== Almacenando datos ==*/
    $promo_id=limpiar_cadena($_POST['img_del_id']);

    /*== Verificando producto ==*/
    $check_promocion=conexion();
    $check_promocion=$check_promocion->query("SELECT * FROM promocion WHERE promocion_id='$promo_id'");

    if($check_promocion->rowCount()==1){
    	$datos=$check_promocion->fetch();
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La imagen de la PROMOCION que intenta eliminar no existe
            </div>
        ';
        exit();
    }
    $check_producto=null;


    /* Directorios de imagenes */
	$img_dir='../img/promocion/';

	/* Cambiando permisos al directorio */
	chmod($img_dir, 0777);


	/* Eliminando la imagen */
	if(is_file($img_dir.$datos['promocion_foto'])){

		chmod($img_dir.$datos['promocion_foto'], 0777);

		if(!unlink($img_dir.$datos['promocion_foto'])){
			echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                Error al intentar eliminar la imagen de la promocion, por favor intente nuevamente
	            </div>
	        ';
	        exit();
		}
	}


	/*== Actualizando datos ==*/
    $actualizar_promocion=conexion();
    $actualizar_promocion=$actualizar_promocion->prepare("UPDATE promocion SET promocion_foto=:foto WHERE promocion_id=:id");

    $marcadores=[
        ":foto"=>"",
        ":id"=>$promo_id
    ];

    if($actualizar_promocion->execute($marcadores)){
        echo '
            <div class="notification is-info is-light">
                <strong>¡IMAGEN O FOTO ELIMINADA!</strong><br>
                La imagen de la promocion ha sido eliminada exitosamente, pulse Aceptar para recargar los cambios.

                <p class="has-text-centered pt-5 pb-5">
                    <a href="index.php?vista=promocion_img&promo_id_up='.$promo_id.'" class="button is-link is-rounded">Aceptar</a>
                </p">
            </div>
        ';
    }else{
        echo '
            <div class="notification is-warning is-light">
                <strong>¡IMAGEN O FOTO ELIMINADA!</strong><br>
                Ocurrieron algunos inconvenientes, sin embargo la imagen de la promocion ha sido eliminada, pulse Aceptar para recargar los cambios.

                <p class="has-text-centered pt-5 pb-5">
                    <a href="index.php?vista=promocion_img&promo_id_up='.$promo_id.'" class="button is-link is-rounded">Aceptar</a>
                </p">
            </div>
        ';
    }
    $actualizar_promocion=null;