<div class="container is-fluid mb-6">
	<h1 class="title">Promociones</h1>
	<h2 class="subtitle">Nueva promocion</h2>
</div>

<div class="container pb-6 pt-6">
	<?php
		require_once "./php/main.php";
	?>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/promocion_guardar.php" method="POST" class="FormularioAjax" autocomplete="off" enctype="multipart/form-data" >
		<div class="columns">
		<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="promocion_nombre" pattern="[a-zA-Z0-9- ]{1,500}" maxlength="500" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Descripcion</label>
				  	<input class="input" type="text" name="promocion_descripcion" pattern="[a-zA-Z0-9- ]{1,500}" maxlength="500" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Descuento</label>
				  	<input class="input" type="text" name="promocion_descuento" pattern="[0-9.]{1,25}" maxlength="25" required >
				</div>
		  	</div>
		  	<div class="column">
				<label>Categoría</label><br>
		    	<div class="select is-rounded">
				  	<select name="promocion_categoria" >
				    	<option value="" selected="" >Seleccione una opción</option>
				    	<?php
    						$categorias=conexion();
    						$categorias=$categorias->query("SELECT * FROM categoria");
    						if($categorias->rowCount()>0){
    							$categorias=$categorias->fetchAll();
    							foreach($categorias as $row){
    								echo '<option value="'.$row['categoria_id'].'" >'.$row['categoria_nombre'].'</option>';
				    			}
				   			}
				   			$categorias=null;
				    	?>
				  	</select>
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
            
		</div>
		<div class="columns">
			<div class="column">
				<label>Foto o imagen de la promocion</label><br>
				<div class="file is-small has-name">
				  	<label class="file-label">
				    	<input class="file-input" type="file" name="promocion_foto" accept=".jpg, .png, .jpeg" >
				    	<span class="file-cta">
				      		<span class="file-label">Imagen</span>
				    	</span>
				    	<span class="file-name">JPG, JPEG, PNG. (MAX 3MB)</span>
				  	</label>
				</div>
			</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>