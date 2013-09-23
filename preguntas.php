<!-- plantilla preguntas -->
<div class="preguntas">
	<?php if($_SESSION['id_usuario'] == 3 || $_SESSION['id_usuario'] == 2) 
		echo "<a class='borrar' href='?s=foro/index.php&id=".$id."&borrar=1' onclick='return confirm(\"¿Esta seguro que desea borrar la pregunta y sus respuestas?\");'>Borrar</a>" 
	?>
	<h4><a href="?s=foro/index.php&id=<?=$id?>"><?=$titulo?></a></h4>
	<ul class="datos">
		<li><?=$autor?></li>
		<li><?=$fecha?></li>
		<li class="boton"><a href="?s=foro/index.php&id=<?=$id?>#responder">Responder</a></li>
		<li class="boton"><a href="?s=foro/index.php&id=<?=$id?>">Ver <?=$numRespuestas?> respuestas</a></li>
	</ul>
	<div class="clear"></div>
</div>