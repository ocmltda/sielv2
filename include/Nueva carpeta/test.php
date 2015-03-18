<?php

//incluimos la clase html2fpdf indicando la ruta del archivo donde está contenida
require_once('html2fpdf/html2fpdf.php');
require_once('includeM/setglobals.php');
require_once('includeM/class.TemplatePower.inc.php');
require_once('includeM/db_mysql.inc');
// ———– Texto Html almacenado en la variable $html —————–
$html = "
<html>
<head>
<title>Generando un PDF</title>
</head>
<body>
<p><img src=”http://peachep.files.wordpress.com/2007/10/cabecerablog2.jpg” alt=”Cabecera Blog” width=”95%” /></p>
<h2>Html2Fpdf, Creando PDF “al vuelo” con PHP</h2>
<p>En este tutorial vamos a tratar de explicar como generar PDFs on line o al vuelo desde nuestras páginas escritas con PHP.</p>
<p>Para ello vamos a utilizar el proyecto html2fpdf. Este proyecto se basa fundamentalmente en la utilización de 3 clases escritas en PHP: <b>FPDF, HTML2FPDF (extensión de la clase FPDF) y PDF (site Version)</b>. Se incluye otro script complementario contenido en el archivo htmltoolkit.php.</p>
<p>Para descargar los archivos necesarios id a esta dirección sourceforge.net/projects/html2fpdf.</p>
<p>Una vez descomprimido el archivo zip descargado nos encontraremos con una lista de archivos, de los cuales, algunos de ellos no nos serán necesarios. Por ejemplo, source2doc.php, es una clase que podemos utilizar para volcar en pantalla toda la información relativa a las variables, constantes o métodos que componen una determinada clase que le sería indicada. Pero este archivo no nos resultará necesario para generar PDFs.</p>
<p>Los archivos y directorio necesarios de todos los descargados para la generación de PDFs son:
<ul>
<li>fpdf.php</li>
<li>html2fpdf.php</li>
<li>gif.php</li>
<li>htmltoolkit.php</li>
<li>incluir también el directorio o carpeta font</li>
</ul>
</p>
<p><a href=”http://peachep.wordpress.com”>peachep.wordpress.com</a></p>
</body>
</html>
";
// ———– Texto Html —————–

$pdf = new HTML2FPDF(); // Creamos una instancia de la clase HTML2FPDF

$pdf -> AddPage(); // Creamos una página

$pdf -> WriteHTML($html);//Volcamos el HTML contenido en la variable $html para crear el contenido del PDF

$pdf -> Output('doc.pdf', 'D');//Volcamos el pdf generado con nombre ‘doc.pdf’. En este caso con el parametro ‘D’ forzamos la descarga del mismo.

?>
