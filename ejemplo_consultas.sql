/*Si respuesta_id_padre = 0 significa que es la respuesta que abre el tema
  Si respuesta_id_padre != 0 respuesta_id_padre identifica al tema (respuesta que inicia el tema) 
  al que pertenece
*/

CREATE TABLE respuesta(
	respuesta_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	usuario_id SMALLINT UNSIGNED NOT NULL,
	respuesta_contenido varchar(1023) NOT NULL,
	respuesta_fecha DATETIME NOT NULL,
	respuesta_id_padre SMALLINT UNSIGNED NOT NULL,
	respuesta_titulo varchar(255) NULL,
	PRIMARY KEY  (respuesta_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci ROW_FORMAT=DYNAMIC;
	
/*consulta xa mostrar los diferentes temas*/
SELECT respuesta_id,respuesta_titulo,usuario_id,respuesta_fecha FROM respuesta WHERE respuesta_id_padre = 0;

/*mostrar respustas de 1 tema*/
SELECT respuesta_contenido FROM respuesta WHERE respuesta_id_padre = '$id_padre';

/*crear tema*/
INSERT INTO respuesta ('usuario_id','respuesta_contenido','respuesta_fecha','respuesta_id_padre','$respuesta_titulo') VALUES ('$usuario_id','$contenido',NOW(),0,'$titulo');

/*crear respuesta*/
INSERT INTO respuesta ('usuario_id','respuesta_contenido','respuesta_fecha','respuesta_id_padre','$respuesta_titulo') VALUES ('$usuario_id','$contenido',NOW(),'$id_padre','');

