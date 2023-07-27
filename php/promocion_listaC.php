<?php
	$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;
	$tabla="";

	$campos="promocion.producto_id,
	promocion.promocion_id,
	promocion.promocion_nombre,
	promocion.promocion_descripcion,
	promocion.promocion_descuento,
	promocion.promocion_foto,
	promocion.categoria_id,
	promocion.usuario_id,
	categoria.categoria_id,
	categoria.categoria_nombre,
	usuario.usuario_id,
	usuario.usuario_nombre,
	usuario.usuario_apellido";

	if(isset($busqueda) && $busqueda!=""){
    
        
		$consulta_datos="SELECT $campos FROM promocion pr 
		INNER JOIN producto p ON pr.producto_id=p.producto_id 
		INNER JOIN categoria c ON pr.categoria_id=c.categoria_id 
		INNER JOIN usuario u ON pr.usuario_id=u.usuario_id 
		WHERE pr.promocion_nombre LIKE '%$busqueda%'  
		ORDER BY pr.promocion_nombre ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(promocion_id)
		 FROM promocion WHERE promocion.categoria_id LIKE '%$busqueda%'  
		 OR promocion_nombre LIKE '%$busqueda%'";

	}elseif($categoria_id>0){

		$consulta_datos="SELECT $campos FROM promocion
		INNER JOIN categoria ON promocion.categoria_id=categoria.categoria_id 
		INNER JOIN usuario ON promocion.usuario_id=usuario.usuario_id 
		WHERE promocion.categoria_id='$categoria_id' 
		ORDER BY promocion.promocion_nombre ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(promocion_id) FROM promocion
		 WHERE categoria_id='$categoria_id'";

	}else{

		$consulta_datos="SELECT $campos FROM promocion 
		INNER JOIN categoria ON promocion.categoria_id=categoria.categoria_id
		INNER JOIN usuario ON promocion.usuario_id=usuario.usuario_id 
		ORDER BY promocion.promocion_nombre ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(promocion_id) FROM promocion";

	}

	$conexion=conexion();

	$datos = $conexion->query($consulta_datos);
	$datos = $datos->fetchAll();

	$total = $conexion->query($consulta_total);
	$total = (int) $total->fetchColumn();

	$Npaginas =ceil($total/$registros);

	if($total>=1 && $pagina<=$Npaginas){
		$contador=$inicio+1;
		$pag_inicio=$inicio+1;
		foreach($datos as $rows){
			$tabla.='
				<article class="media">
				<figure class="media-left">
					<p class="image is-64x64">';
					
					if(is_file("./img/promocion/".$rows['promocion_foto'])){
						$tabla.='<img src="./img/promocion/'.$rows['promocion_foto'].'">';
					}else{
						$tabla.='<img src="./img/promocion.png">';
					}
			   $tabla.='</p>
					</figure>
					<div class="media-content">
						<div class="content">
							<p>
								<strong>'.$contador.' - '.$rows['promocion_nombre'].'</strong><br>
								<strong>ID:</strong> '.$rows['promocion_id'].',
							 	<strong>PRECIO OFERTA:</strong> $'.$rows['promocion_descuento'].',
							  	<strong>DESCRIPCION:</strong> '.$rows['promocion_descripcion'].', 
							  	<strong>CATEGORIA:</strong> '.$rows['categoria_nombre'].', 
							  
							</p>
						</div>
						
					</div>
				</article>

			    <hr>
            ';
            $contador++;
		}
		$pag_final=$contador-1;
	}else{
		if($total>=1){
			$tabla.='
				<p class="has-text-centered" >
					<a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
						Haga clic acá para recargar el listado
					</a>
				</p>
			';
		}else{
			$tabla.='
				<p class="has-text-centered" >No hay registros en el sistema</p>
			';
		}
	}

	if($total>0 && $pagina<=$Npaginas){
		$tabla.='<p class="has-text-right">Mostrando promociones <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
	}

	$conexion=null;
	echo $tabla;

	if($total>=1 && $pagina<=$Npaginas){
		echo paginador_tablas($pagina,$Npaginas,$url,7);
	}
