

CREATE TABLE `alertas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `locales_id` int(11) NOT NULL,
  `coordenadas` varchar(45) NOT NULL,
  `incidencia` varchar(64) NOT NULL,
  `comentarios` text,
  `tiposacciones_id` smallint(6) DEFAULT NULL,
  `fotografia` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=InnoDB ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `logo` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) TYPE=InnoDB ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empusu`
--

CREATE TABLE `empusu` (
  `emu_id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarios_id` smallint(6) NOT NULL,
  `clientes_id` smallint(6) NOT NULL,
  PRIMARY KEY (`emu_id`)
) TYPE=InnoDB ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `generales`
--

CREATE TABLE `generales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `planillas_id` int(11) NOT NULL,
  `nombre` varchar(450) DEFAULT NULL,
  PRIMARY KEY (`id`),
) TYPE=InnoDB ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informes`
--

CREATE TABLE `informes` (
  `informes_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(64) NOT NULL,
  `periodo` date NOT NULL,
  `fecha_publicacion` date NOT NULL,
  `estado` smallint(6) NOT NULL,
  PRIMARY KEY (`informes_id`)
) TYPE=InnoDB ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `planillas_id` int(11) NOT NULL,
  `item` varchar(450) DEFAULT NULL,
  `validacion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
) TYPE=InnoDB ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `locales`
--

CREATE TABLE `locales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientes_id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `direccion` varchar(250) DEFAULT NULL,
  `ciudad` varchar(45) DEFAULT NULL,
  `region` int(11) DEFAULT NULL,
  `coordenadas` varchar(45) DEFAULT NULL,
  `retail` varchar(50) DEFAULT NULL,
  `segmento` varchar(50) DEFAULT NULL,
  `promotor` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
) TYPE=InnoDB ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `men_id` int(11) NOT NULL AUTO_INCREMENT,
  `men_nombre` varchar(64) NOT NULL,
  `men_link` varchar(32) NOT NULL,
  `men_orden` smallint(6) NOT NULL,
  PRIMARY KEY (`men_id`)
) TYPE=InnoDB ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `percat`
--

CREATE TABLE `percat` (
  `pcat_id` int(11) NOT NULL AUTO_INCREMENT,
  `men_id` int(11) DEFAULT NULL,
  `tipos_usuarios_id` smallint(6) NOT NULL,
  PRIMARY KEY (`pcat_id`),
) TYPE=InnoDB ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planillas`
--

CREATE TABLE `planillas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientes_id` int(11) NOT NULL,
  `servicio` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`),
) TYPE=InnoDB ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `items_id` int(11) NOT NULL,
  `tipos_id` int(11) NOT NULL,
  `pregunta` varchar(450) DEFAULT NULL,
  `puntaje` int(11) DEFAULT NULL,
  `padre` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
) TYPE=InnoDB ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas`
--

CREATE TABLE `respuestas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visitas_id` int(11) NOT NULL,
  `respuesta` text,
  `puntaje_obtenido` int(11) DEFAULT NULL,
  `generales_id` int(11) DEFAULT NULL,
  `items_id` int(11) DEFAULT NULL,
  `preguntas_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
) TYPE=InnoDB ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos`
--

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) TYPE=InnoDB ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_acciones`
--

CREATE TABLE `tipos_acciones` (
  `tipos_acciones_id` int(11) NOT NULL AUTO_INCREMENT,
  `accion` varchar(32) NOT NULL,
  PRIMARY KEY (`tipos_acciones_id`)
) TYPE=InnoDB ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_usuarios`
--

CREATE TABLE `tipos_usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) TYPE=InnoDB ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipos_usuarios_id` int(11) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `correo` varchar(45) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `region` int(11) DEFAULT NULL,
  `comentario` text,
  PRIMARY KEY (`id`),
) TYPE=InnoDB ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `visitas`
--

CREATE TABLE `visitas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarios_id` int(11) NOT NULL,
  `locales_id` int(11) NOT NULL,
  `planillas_id` int(11) NOT NULL,
  `fecha_visita` date DEFAULT NULL,
  `estado_visita` int(11) DEFAULT NULL,
  `estado_revision` int(11) DEFAULT NULL,
  `boleta` varchar(450) DEFAULT NULL,
  `observaciones` text,
  `fechas_disponibles` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`),
) TYPE=InnoDB ;
