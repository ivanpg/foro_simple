<div class="pregunta">
	<?php if($_SESSION['id_usuario'] == 3 || $_SESSION['id_usuario'] == 2) 
		echo "<a class='borrar' href='?s=foro/index.php&id=".$id."&borrar=1' onclick='return confirm(\"¿Esta seguro que desea borrar la pregunta y sus repuestas?\");'>Borrar</a>" 
	?>
	<p><?=$contenido?></p>
	<ul class="datos">
		<li><?=$autor?></li>
		<li><?=$fecha?></li>
		<li class="boton"><a href="#responder">Responder</a></li>
	</ul>
	<div class="clear"></div>
</div>