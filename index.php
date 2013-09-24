<?php 
session_start();
$error=0;
if (isset($_SESSION['se_puede']) && ($_SESSION['se_puede'] == '1'))
{?>
<div id="foro">
<?php 
	
	if(isset($_GET['id']))
		echo "<a style='display:block;text-align:left' href='?s=foro/index.php'>&lt; &lt; Volver al foro</a>";
	else 
		echo "<a style='display:block;text-align:left' href='?s=entrada.php'>&lt; &lt; Volver a la entrada</a>";

	function mostrarPreguntas(){
		echo "<a class='boton' href='#responder'>Nueva pregunta</a><h2>Preguntas</h2>";
		$consultaPregunta="SELECT nombre, respuesta_id, respuesta_titulo, respuesta_fecha
						   FROM respuesta JOIN usuarios ON usuarios.codigo = respuesta.usuario_id
						   WHERE respuesta_id_padre = 0 ORDER BY fecha_modificacion DESC";
		$result = mysql_query($consultaPregunta);
			
		while($fila = mysql_fetch_assoc($result)){
			$titulo = $fila['respuesta_titulo'];
			$id =$fila['respuesta_id']; 			
			$mysqlFecha = $fila['respuesta_fecha'];
			$fecha = date("d-m-Y H:i",strtotime($mysqlFecha));			
			$autor = $fila['nombre'];

			//numero de respuestas a esa pregunta
			$consultaNumResp = "SELECT * FROM respuesta WHERE respuesta_id_padre = '$id'";
			$result2 = mysql_query($consultaNumResp);	
			$numRespuestas = mysql_num_rows($result2);
			
			include('preguntas.php');/*cada una de las filas que representa una pregunta diferente*/	
		}	
		include("nueva_pregunta.php");
	}

	
	function mostrarRespuestas($id_padre){
		$id_padre= (int)$id_padre;
		$consultaPregunta="SELECT nombre,respuesta_contenido,respuesta_titulo,respuesta_fecha 
						   FROM respuesta JOIN usuarios ON usuarios.codigo = respuesta.usuario_id
						  WHERE respuesta_id = '$id_padre'";
		$result = mysql_query($consultaPregunta);		
		$fila = mysql_fetch_assoc($result);
		$titulo = $fila["respuesta_titulo"];
		echo "<h2>".$titulo."</h2>";
		$contenido = nl2br($fila['respuesta_contenido']);		
		$mysqlFecha = $fila['respuesta_fecha'];
		$fecha = date("d-m-Y H:i",strtotime($mysqlFecha));
		$autor = $fila['nombre'];
		$id = $id_padre;
		include('pregunta.php');/*pregunta qu inicia el hilo de respuestas*/


		$consultaRespuestas = "SELECT nombre,usuario_id,respuesta_contenido,respuesta_id,respuesta_fecha 
							   FROM respuesta  JOIN usuarios ON usuarios.codigo = respuesta.usuario_id
							   WHERE respuesta_id_padre = '$id_padre' ORDER BY respuesta_fecha ASC";
		$result = mysql_query($consultaRespuestas);	
		while($fila = mysql_fetch_assoc($result)){
			$contenido = $fila['respuesta_contenido'];
			$usuario_id = $fila['usuario_id'];
			$mysqlFecha = $fila['respuesta_fecha'];
			$fecha = date("d-m-Y H:i",strtotime($mysqlFecha));
			$autor = $fila['nombre'];
			$id = $fila['respuesta_id'];

			include('respuestas.php');/*cada una de las filas que representan las respuestas a una pregunta*/
		}	
		include("nueva_respuesta.php");
	}

	function insertarPregunta(){
		if($_POST['contenido']!="" || $_POST['titulo']!=""){
			$usuario_id = $_SESSION['id_usuario'];
		
			$contenido =  mysql_real_escape_string($_POST['contenido']);
			$titulo =  mysql_real_escape_string($_POST['titulo']);
			$insertarPregunta = "INSERT INTO respuesta (usuario_id,respuesta_contenido,respuesta_fecha,respuesta_id_padre,respuesta_titulo,fecha_modificacion) VALUES ('$usuario_id','$contenido',NOW(),0,'$titulo',NOW())";
			mysql_query($insertarPregunta);
			echo mysql_error();
		}
		else {
			global $error;
			$error=1;
		}			
	}

	function insertarRespuesta(){
		if($_POST['contenido']!=""){
			$usuario_id =  mysql_real_escape_string($_SESSION['id_usuario']);
			$contenido =  mysql_real_escape_string($_POST['contenido']);
			$id_padre = (int)$_POST['id_padre'];
	 		$insertarRespuesta = "INSERT INTO respuesta (usuario_id,respuesta_contenido,respuesta_fecha,respuesta_id_padre,respuesta_titulo,fecha_modificacion) VALUES ('$usuario_id','$contenido',NOW(),'$id_padre','',NOW())";
	 		mysql_query($insertarRespuesta);
			echo mysql_error();

			$actualizarPadre = "UPDATE respuesta set fecha_modificacion = NOW() where respuesta_id = '$id_padre'";
			mysql_query($actualizarPadre);
			echo mysql_error();
		}		
	}

	function borrarRespuesta($respuesta_id){
                $respuesta_id = (int) $respuesta_id;
		$borrarRespuesta = "DELETE FROM respuesta where respuesta_id = '$respuesta_id'";
		mysql_query($borrarRespuesta);
		echo mysql_error();
	}

	function borrarTema($respuesta_id_padre){
                 $respuesta_id_padre = (int) $respuesta_id_padre;
		//borramos cada una de las respuestas
		$borrarRespuesta = "DELETE FROM respuesta where respuesta_id_padre = '$respuesta_id_padre'";
		mysql_query($borrarRespuesta);
		echo mysql_error();

		//borramos la respuesta padre y por lo tanto el tema
		//usar funcion borrarRespuesta(id) 
		$borrarRespuestaTema = "DELETE FROM respuesta where respuesta_id = '$respuesta_id_padre'";
		mysql_query($borrarRespuestaTema);
		echo mysql_error();
	}

	if(isset($_GET['borrar']) && $_GET['borrar'] == 1){		
		$id=(int)$_GET["id"];
		$consultaId="SELECT respuesta_id_padre from respuesta where respuesta_id = '$id'";
		$result = mysql_query($consultaId);	
		$fila = mysql_fetch_assoc($result);

		if($fila['respuesta_id_padre'] == 0){
			borrarTema($id);
			echo '<script type="text/javascript">;';
			echo 'window.location="?s=foro/index.php";';
			echo '</script>';
		} 
		else{ 
			borrarRespuesta($id);
			echo '<script type="text/javascript">;';
			echo 'window.location="?s=foro/index.php&id='.$fila['respuesta_id_padre'].'";';
			echo '</script>';
			//mostrarRespuestas($fila['respuesta_id_padre']);		
		}
	}
	else{
		if(!isset($_GET['id']) && !isset($_POST['tipo'])){
			mostrarPreguntas();
		}	
		else if(isset($_GET['id']) && !isset($_POST['tipo'])){
			mostrarRespuestas($_GET['id']);
		}
		else if(isset($_POST['tipo']) && $_POST['tipo'] == "pregunta"){
			insertarPregunta();
			if($error==1) {
				echo "<div class='error'>No ha rellenado todos los campos necesarios</div>";
			}
			mostrarPreguntas();
		}
		else if(isset($_POST['tipo']) && $_POST['tipo'] == "respuesta"){
			insertarRespuesta();
			echo '<script type="text/javascript">;';
			echo 'window.location="?s=foro/index.php&id='.$_GET['id'].'";';
			echo '</script>';

			mostrarRespuestas($_GET['id']);
		}
	}

?>
</div>
<script type="text/javascript">
	var enviar = document.getElementById('enviar');
	var titulo = document.getElementById('titulo');
	var contenido = document.getElementById('contenido');

	titulo.onkeyup = validar;
	contenido.onkeyup = validar;
	enviar.disabled = true;

	function trim (myString) {
		return myString.replace(/^\s+/g,'').replace(/\s+$/g,'');
	}
	function validar() {
		if(trim(titulo.value) != "" && trim(contenido.value) != "") {
			enviar.disabled = false;
		} else {
			enviar.disabled = true;
		}
	}
</script>
<?php } 
else echo "ACCESO DENEGADO"?>
