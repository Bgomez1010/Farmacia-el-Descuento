<?php
	/*== Almacenando datos ==*/
	$promo_id_del=limpiar_cadena($_GET['promo_id_del']);

	/*== Verificando producto ==*/
	$check_promocion=conexion();
	$check_promocion=$check_promocion->query("SELECT * FROM promocion WHERE promocion_id='$promo_id_del'");

	if($check_promocion->rowCount()==1){

		$datos=$check_promocion->fetch();

		$eliminar_promocion=conexion();
		$eliminar_promocion=$eliminar_promocion->prepare("DELETE FROM promocion WHERE promocion_id=:id");

		$eliminar_promocion->execute([":id"=>$promo_id_del]);

		if($eliminar_promocion->rowCount()==1){

			if(is_file("./img/promocion/".$datos['promocion_foto'])){
				chmod("./img/promocion/".$datos['promocion_foto'], 0777);
				unlink("./img/promocion/".$datos['promocion_foto']);
			}

			echo '
				<div class="notification is-info is-light">
					<strong>¡PROMOCION ELIMINADA!</strong><br>
					Los datos de la promocion se eliminaron con exito
				</div>
			';
		}else{
			echo '
				<div class="notification is-danger is-light">
					<strong>¡Ocurrio un error inesperado!</strong><br>
					No se pudo eliminar la promocion, por favor intente nuevamente
				</div>
			';
		}
		$eliminar_promocion=null;
	}else{
		echo '
			<div class="notification is-danger is-light">
				<strong>¡Ocurrio un error inesperado!</strong><br>
				La PROMOCION que intenta eliminar no existe
			</div>
		';
	}
	$check_promocion=null;