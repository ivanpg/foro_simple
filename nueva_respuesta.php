<div id="responder">
	<form action="?s=foro/index.php&id=<?=$_GET['id']?>" method="post">
		<input type="hidden" name="tipo" value="respuesta" />
		<input type="hidden" name="titulo" id="titulo" value="respuesta" />
		<input type="hidden" name="id_padre" value="<?=$_GET['id']?>">
		<label for="contenido">Tu respuesta</label>
		<textarea name="contenido" id="contenido" cols="30" rows="10"></textarea>
		<input type="submit" value="Enviar" class="boton" id="enviar" />
	</form>
	<div class="clear"></div>
</div>