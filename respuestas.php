<div class="respuesta">
	<?php if($_SESSION['id_usuario'] == 3 || $_SESSION['id_usuario'] == 2) 
		echo "<a class='borrar' href='?s=foro/index.php&id=".$id."&borrar=1' onclick='return confirm(\"¿Esta seguro que desea borrar la respuesta?\");'>Borrar</a>" 
	?>
	<p><?=$contenido?></p>
	<ul class="datos">
		<li><?=$autor?></li>
		<li><?=$fecha?></li>
	</ul>
	<div class="clear"></div>
</div>