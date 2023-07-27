<div class="container is-fluid mb-6">
	<h1 class="title">Promociones</h1>
	<h2 class="subtitle">Actualizar Promocion</h2>
</div>

<div class="container pb-6 pt-6">
	<?php
		include "./inc/btn_back.php";

		require_once "./php/main.php";

		$id = (isset($_GET['promo_id_up'])) ? $_GET['promo_id_up'] : 0;
		$id=limpiar_cadena($id);

		/*== Verificando promocion ==*/
    	$check_promocion=conexion();
    	$check_promocion=$check_promocion->query("SELECT * FROM promocion WHERE promocion_id='$id'");

        if($check_promocion->rowCount()>0){
        	$datos=$check_promocion->fetch();
	?>

	<div class="form-rest mb-6 mt-6"></div>
	
	<h2 class="title has-text-centered"><?php echo $datos['promocion_nombre']; ?></h2>

	<form action="./php/promocion_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off" >

		<input type="hidden" name="promocion_id" value="<?php echo $datos['promocion_id']; ?>" required >

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="promocion_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100" required value="<?php echo $datos['promocion_nombre']; ?>" >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Descripcion</label>
				  	<input class="input" type="text" name="promocion_descripcion" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,500}" maxlength="500" required value="<?php echo $datos['promocion_descripcion']; ?>" >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Descuento</label>
				  	<input class="input" type="text" name="promocion_descuento" pattern="[0-9.]{1,25}" maxlength="25" required value="<?php echo $datos['promocion_descuento']; ?>" >
				</div>
		  	</div>
              <div class="column">
				<label>Codigo Producto</label><br>
		    	<div class="select is-rounded">
				  	<select name="promocion_producto" >
                      <option value="" selected="" >Seleccione una opción</option>
				    	<?php
    						$productos=conexion();
    						$productos=$productos->query("SELECT * FROM producto");
    						if($productos->rowCount()>0){
    							$productos=$productos->fetchAll();
    							foreach($productos as $row){
    								if($datos['producto_id']==$row['producto_id']){
    									echo '<option value="'.$row['producto_id'].'" selected="" >'.$row['producto_nombre'].' (Actual)</option>';
    								}else{
    									echo '<option value="'.$row['producto_id'].'" >'.$row['producto_nombre'].'</option>';
    								}
				    			}
				   			}
				   			$productos=null;
				    	?>
				  	</select>
				</div>
		  	</div>
		  	<div class="column">
				<label>Categoría</label><br>
		    	<div class="select is-rounded">
				  	<select name="promocion_categoria" >
				    	<?php
    						$categorias=conexion();
    						$categorias=$categorias->query("SELECT * FROM categoria");
    						if($categorias->rowCount()>0){
    							$categorias=$categorias->fetchAll();
    							foreach($categorias as $row){
    								if($datos['categoria_id']==$row['categoria_id']){
    									echo '<option value="'.$row['categoria_id'].'" selected="" >'.$row['categoria_nombre'].' (Actual)</option>';
    								}else{
    									echo '<option value="'.$row['categoria_id'].'" >'.$row['categoria_nombre'].'</option>';
    								}
				    			}
				   			}
				   			$categorias=null;
				    	?>
				  	</select>
				</div>
		  	</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-success is-rounded">Actualizar</button>
		</p>
	</form>
	<?php 
		}else{
			include "./inc/error_alert.php";
		}
		$check_promocion=null;
	?>
</div>