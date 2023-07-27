<?php
    require_once "main.php";

	/*== Almacenando datos ==*/
    $promo_id=limpiar_cadena($_POST['img_up_id']);

    /*== Verificando producto ==*/
    $check_promocion=conexion();
    $check_promocion=$check_promocion->query("SELECT * FROM promocion WHERE promocion_id='$promo_id'");

    if($check_promocion->rowCount()==1){
        $datos=$check_promocion->fetch();
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La imagen de la PROMOCION que intenta actualizar no existe
            </div>
        ';
        exit();
    }
    $check_promocion=null;


    /*== Comprobando si se ha seleccionado una imagen ==*/
    if($_FILES['promocion_foto']['name']=="" || $_FILES['promocion_foto']['size']==0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No ha seleccionado ninguna imagen o foto
            </div>
        ';
        exit();
    }


    /* Directorios de imagenes */
    $img_dir='../img/promocion/';


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


    /* Cambiando permisos al directorio */
    chmod($img_dir, 0777);


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

    /* Nombre de la imagen */
    $img_nombre=renombrar_fotos($datos['promocion_nombre']);

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


    /* Eliminando la imagen anterior */
    if(is_file($img_dir.$datos['promocion_foto']) && $datos['promocion_foto']!=$foto){

        chmod($img_dir.$datos['promocion_foto'], 0777);
        unlink($img_dir.$datos['promocion_foto']);
    }


    /*== Actualizando datos ==*/
    $actualizar_promocion=conexion();
    $actualizar_promocion=$actualizar_promocion->prepare("UPDATE promocion SET promocion_foto=:foto WHERE promocion_id=:id");

    $marcadores=[
        ":foto"=>$foto,
        ":id"=>$promo_id
    ];

    if($actualizar_promocion->execute($marcadores)){
        echo '
            <div class="notification is-info is-light">
                <strong>¡IMAGEN O FOTO ACTUALIZADA!</strong><br>
                La imagen de la promocion ha sido actualizada exitosamente, pulse Aceptar para recargar los cambios.

                <p class="has-text-centered pt-5 pb-5">
                    <a href="index.php?vista=promocion_img&promo_id_up='.$promo_id.'" class="button is-link is-rounded">Aceptar</a>
                </p">
            </div>
        ';
    }else{

        if(is_file($img_dir.$foto)){
            chmod($img_dir.$foto, 0777);
            unlink($img_dir.$foto);
        }

        echo '
            <div class="notification is-warning is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No podemos subir la imagen al sistema en este momento, por favor intente nuevamente
            </div>
        ';
    }
    $actualizar_promocion=null;
