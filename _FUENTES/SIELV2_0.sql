SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE alertas (
  id int(11) NOT NULL AUTO_INCREMENT,
  fecha date NOT NULL,
  hora time NOT NULL,
  locales_id int(11) NOT NULL,
  coordenadas varchar(45) NOT NULL,
  incidencia varchar(64) NOT NULL,
  comentarios text,
  tiposacciones_id smallint(6) DEFAULT NULL,
  fotografia varchar(128) NOT NULL,
  PRIMARY KEY (id)
) TYPE=InnoDB ;

CREATE TABLE clientes (
  id int(11) NOT NULL AUTO_INCREMENT,
  nombre varchar(45) NOT NULL,
  logo varchar(300) DEFAULT NULL,
  PRIMARY KEY (id)
) TYPE=InnoDB ;

CREATE TABLE generales (
  id int(11) NOT NULL AUTO_INCREMENT,
  planillas_id int(11) NOT NULL,
  nombre varchar(450) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY fk_generales_planillas1 (planillas_id)
) TYPE=InnoDB ;

CREATE TABLE informes (
  informes_id int(11) NOT NULL AUTO_INCREMENT,
  nombre varchar(64) NOT NULL,
  periodo date NOT NULL,
  fecha_publicacion date NOT NULL,
  estado smallint(6) NOT NULL,
  PRIMARY KEY (informes_id)
) TYPE=InnoDB ;

CREATE TABLE items (
  id int(11) NOT NULL AUTO_INCREMENT,
  planillas_id int(11) NOT NULL,
  item varchar(450) DEFAULT NULL,
  validacion int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY fk_items_planillas1 (planillas_id)
) TYPE=InnoDB ;

CREATE TABLE locales (
  id int(11) NOT NULL AUTO_INCREMENT,
  clientes_id int(11) NOT NULL,
  nombre varchar(45) NOT NULL,
  direccion varchar(250) DEFAULT NULL,
  ciudad varchar(45) DEFAULT NULL,
  region int(11) DEFAULT NULL,
  coordenadas varchar(45) DEFAULT NULL,
  retail varchar(50) DEFAULT NULL,
  segmento varchar(50) DEFAULT NULL,
  promotor varchar(150) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY fk_locales_clientes (clientes_id)
) TYPE=InnoDB ;

CREATE TABLE planillas (
  id int(11) NOT NULL AUTO_INCREMENT,
  clientes_id int(11) NOT NULL,
  servicio varchar(1000) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY fk_planillas_clientes1 (clientes_id)
) TYPE=InnoDB ;

CREATE TABLE preguntas (
  id int(11) NOT NULL AUTO_INCREMENT,
  items_id int(11) NOT NULL,
  tipos_id int(11) NOT NULL,
  pregunta varchar(450) DEFAULT NULL,
  puntaje int(11) DEFAULT NULL,
  padre int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY fk_preguntas_items1 (items_id),
  KEY fk_preguntas_tipos1 (tipos_id)
) TYPE=InnoDB ;

CREATE TABLE respuestas (
  id int(11) NOT NULL AUTO_INCREMENT,
  visitas_id int(11) NOT NULL,
  respuesta text,
  puntaje_obtenido int(11) DEFAULT NULL,
  generales_id int(11) DEFAULT NULL,
  items_id int(11) DEFAULT NULL,
  preguntas_id int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY fk_respuestas_generales1 (generales_id),
  KEY fk_respuestas_items1 (items_id),
  KEY fk_respuestas_preguntas1 (preguntas_id),
  KEY fk_respuestas_visitas1 (visitas_id)
) TYPE=InnoDB ;

CREATE TABLE tipos (
  id int(11) NOT NULL AUTO_INCREMENT,
  tipo varchar(100) DEFAULT NULL,
  PRIMARY KEY (id)
) TYPE=InnoDB ;

CREATE TABLE tipos_acciones (
  tipos_acciones_id int(11) NOT NULL AUTO_INCREMENT,
  accion varchar(32) NOT NULL,
  PRIMARY KEY (tipos_acciones_id)
) TYPE=InnoDB ;

CREATE TABLE tipos_usuarios (
  id int(11) NOT NULL AUTO_INCREMENT,
  tipo varchar(100) DEFAULT NULL,
  PRIMARY KEY (id)
) TYPE=InnoDB ;

CREATE TABLE usuarios (
  id int(11) NOT NULL AUTO_INCREMENT,
  tipos_usuarios_id int(11) NOT NULL,
  usuario varchar(45) NOT NULL,
  nombre varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  correo varchar(45) DEFAULT NULL,
  telefono varchar(45) DEFAULT NULL,
  region int(11) DEFAULT NULL,
  comentario text,
  PRIMARY KEY (id),
  KEY fk_usuarios_tipos_usuarios1 (tipos_usuarios_id)
) TYPE=InnoDB ;

CREATE TABLE visitas (
  id int(11) NOT NULL AUTO_INCREMENT,
  usuarios_id int(11) NOT NULL,
  locales_id int(11) NOT NULL,
  planillas_id int(11) NOT NULL,
  fecha_visita date DEFAULT NULL,
  estado_visita int(11) DEFAULT NULL,
  estado_revision int(11) DEFAULT NULL,
  boleta varchar(450) DEFAULT NULL,
  observaciones text,
  fechas_disponibles varchar(300) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY fk_visitas_usuarios1 (usuarios_id),
  KEY fk_visitas_locales1 (locales_id),
  KEY fk_visitas_planillas1 (planillas_id)
) TYPE=InnoDB ;


ALTER TABLE `generales`
  ADD CONSTRAINT fk_generales_planillas1 FOREIGN KEY (planillas_id) REFERENCES planillas (id) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `items`
  ADD CONSTRAINT fk_items_planillas1 FOREIGN KEY (planillas_id) REFERENCES planillas (id) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `locales`
  ADD CONSTRAINT fk_locales_clientes FOREIGN KEY (clientes_id) REFERENCES clientes (id) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `planillas`
  ADD CONSTRAINT fk_planillas_clientes1 FOREIGN KEY (clientes_id) REFERENCES clientes (id) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `preguntas`
  ADD CONSTRAINT fk_preguntas_items1 FOREIGN KEY (items_id) REFERENCES items (id) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT fk_preguntas_tipos1 FOREIGN KEY (tipos_id) REFERENCES tipos (id) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `respuestas`
  ADD CONSTRAINT fk_respuestas_generales1 FOREIGN KEY (generales_id) REFERENCES generales (id) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT fk_respuestas_items1 FOREIGN KEY (items_id) REFERENCES items (id) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT fk_respuestas_preguntas1 FOREIGN KEY (preguntas_id) REFERENCES preguntas (id) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT respuestas_ibfk_2 FOREIGN KEY (visitas_id) REFERENCES visitas (id) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `usuarios`
  ADD CONSTRAINT fk_usuarios_tipos_usuarios1 FOREIGN KEY (tipos_usuarios_id) REFERENCES tipos_usuarios (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `visitas`
  ADD CONSTRAINT fk_visitas_locales1 FOREIGN KEY (locales_id) REFERENCES locales (id) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT fk_visitas_planillas1 FOREIGN KEY (planillas_id) REFERENCES planillas (id) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT fk_visitas_usuarios1 FOREIGN KEY (usuarios_id) REFERENCES usuarios (id) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
