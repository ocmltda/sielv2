<?php
require_once('dompdf-0.5.1/dompdf_config.inc.php');
require_once('includeM/setglobals.php');
require_once('includeM/class.TemplatePower.inc.php');
require_once('includeM/db_mysql.inc');
$html =
       '<html><body>'.
 
        '<!-- panel izquierdo contenido -->
	<div id="p_contenido1">
<div id="panel_celeste"><!-- id del panel gral celeste que encierra los paneles amarillos de contenido -->
  
  <!-- titulo panel contenido --><div class="titulo_panel">
<img src="imagenes/Panel_contenido/Panelcabecera_informes.jpg" width="645" height="138" />
 <!-- titulo panel contenido --></div>
  
  
  <div class="contenido"><!-- caja contenedora de los bloques amarillos de contenido específico -->
    <div class="pan_amarillo_abrir"></div>
    <div class="pan_amarillo_central">
                <!-- <div class="quimicos">-->
                 <div class="empresa">
                 <div class="personal2">
               		 <label id="empresa_format">EMPRESA: EL ABRA2</label>
	                 <label id="empresa_format">CONTACTO: MIGUEL SANCHEZ</label>
	                 <label id="empresa_format">E-MAIL:  msanchez@phelpsd.cl</label>	                
					 <label id="empresa_format">FONO:  55-818467</label>
	                 <label id="empresa_format">DIRECCION:  SAN JOSÉ DEL ABRA S/N - CALAMA</label>               
	                 <label id="empresa_format">CIUDAD:  CALAMA</label>
	                 <label id="empresa_format">PAIS:  CHILE</label>
	                 <label id="empresa_format">SOLICITADO POR:  MIGUEL SANCHEZ</label>
                 </div><!--fin personal-->                 
                 <div class="personal2">
                 		<label id="empresa_format">EQUIPO: BOMBA 2 501-H101P2</label> 
                        <label id="empresa_format">APLICACION:  SISTEMA HIDRAULICO</label>
                        <label id="empresa_format">CAPACIDAD:  </label>	
                        <label id="empresa_format"> MODELO: </label>	                
                        <label id="empresa_format">Nª SERIE:  NO INDICA</label>
                        <label id="empresa_format">NORMA ISO:  NO INDICA </label>               
                        <label id="empresa_format">FAENA:  NO INDICA </label>
                        <label id="empresa_format">Km/Hra FINALES: </label>
                        <label id="empresa_format">ACEITE:  TELLUS T-46</label>
                 </div>
                 </div><!--fin empresa-->
                 <div class="contenido_titulo"></div>
              <div class="datos2">
                  <div class="personal">         
                 	<label id="per">FECHA MUESTRA:</label> 
	                <label id="per">FECHA CAMBIO:</label>
                    <label id="per">ACUMULADOS:</label>	
	                <label id="per">HRS ACEITE:</label>	                
					<label id="per">MUESTRA Nº:</label>
	                <label id="per">OBSERVACIONES:</label>                  
                 </div>
                 <div class="personal22">
                   <div class="fechas">
                      <label id="per4">11-10-2006</label>
	                  <label id="per4">00-00-0000</label>
                      <label id="per5"></label>
                      <label id="per5">-</label>              
					  <label id="per5">60685</label>
	                  <label id="per6"></label>              
                   </div>
                </div>
                    <div class="personal22">
              <div class="fechas">
                       <label id="per4">26-10-2006</label>
	                   <label id="per4">00-00-0000</label>
                       <label id="per5"></label>
                       <label id="per5">-</label>              
					   <label id="per5">60871</label>
	                   <label id="per6"></label>               
	                </div>
                    </div>
                    <div class="personal22">
                   <div class="fechas">
                       <label id="per4">01-02-2007</label>
	                   <label id="per4">08-02-2007</label>
                       <label id="per5"></label>
                       <label id="per5">-</label>              
					   <label id="per5">62326</label>
	                   <label id="per6"></label>                
	                </div>
                 </div><!--fin personal2-->
                  <div class="personal22">
                 <div class="fechas">
                       <label id="per4">28-08-2007</label>
	                   <label id="per4">00-00-0000</label>
                       <label id="per5"></label>
                       <label id="per5">-</label>              
					   <label id="per5">65046</label>
	                   <label id="per6">H101P2</label>                
	                </div>
                 </div><!--fin personal2-->
                  <div class="personal22">
                 <div class="fechas">
                		<label id="per4">04-09-2007</label>
	                    <label id="per4">00-00-0000</label>
                        <label id="per5"></label>
                        <label id="per5">-</label>              
					    <label id="per5">65208</label>
	                    <label id="per6">501H101P2</label>               
	                </div>
                 </div><!--fin personal2-->
                 <div class="personal22">
                 <div class="fechas">
                 		<label id="per4">13-09-2007</label>
	                    <label id="per4">00-00-0000</label>
                        <label id="per5"></label>
                        <label id="per5">-</label>              
					    <label id="per5">65269</label>
	                    <label id="per6"></label>                
                   </div>
                 </div><!--fin personal2-->
                <div class="personal22">
                 <div class="fechas">
                 <label id="per4">  01-10-2007</label>
	                  <label id="per4">00-00-0000</label>
                      <label id="per5"></label>
                    <label id="per5">-</label>              
					   <label id="per5">65451</label>
	                  <label id="per6"></label>                
                  </div>
                </div><!--fin personal2-->
                <div id="comentario">
               	  <div class="contenido_titulo"> Comentarios </div>
                  <textarea name="comentarios" cols="70" rows="10" readonly="readonly" id="formato_text">PRECAUCIÓN                     
                      DIAGNOSTICO

- Persiste alto desgaste de cobre, provenientes de las piezas de cobre o bronce del sistema.
- Calcio, puede ser aportado por cambio de formulación del lubricante o por una contaminación con
  otro tipo de fluido
- Aumentan las partículas menores a 50 micrones, lo que genera un mayor código de limpieza.

RECOMENDACIONES

- Inspeccionar piezas de cobre o bronce del sistema.
- Verificar e inspeccionar sistema  de filtración de aceite por probable rotura o saturación de filtros.
- Investigar el origen del contenido de calcio
- Aumentar la frecuencia de muestreo para controlar tendencia de contaminantes
                  </textarea>
                </div>
                </div><!--fin personal2-->
       	       <div class="contenido_titulo"> Propiedades fisico quimicas </div>
               		<div class="contenido_panamarillo">
	                 <div class="qui">
	             <label id="qui2"><input name="pfq[]" type="checkbox" value="VIS40|40.06;40.39;41.02;41.53;41.54;41.67" id="check">VISCOSIDAD @40ºC</label>                <label id="qui2"><input name="pfq[]" type="checkbox" value="VIS100|;;;;;" id="check">VISCOSIDAD @100ºC</label>
                <label id="qui2"><input name="pfq[]" type="checkbox" value="INDVI|;;;;;" id="check">INDICE DE VISCOCIDAD</label>
                <label id="qui2"><input name="pfq[]" type="checkbox" value="OXIDACION|0.03;0.08;0.09;0.07;0.06;0.07" id="check">OXIDACION (A/mm)</label> 
	            <label id="qui2"><input name="pfq[]" type="checkbox" value="SULFATACION|0.00;0.00;0.03;0.06;0.07;0.08" id="check">SULFATACION (A/mm)</label>
	            <label id="qui2"><input name="pfq[]" type="checkbox" value="NITRACION|0.04;0.06;0.06;0.10;0.06;0.06" id="check">NITRACION (A/mm)</label>	
				<label id="qui2"><input name="pfq[]" type="checkbox" value="AGUA|0.00;0.00;0.00;0.00;0.00;0.00" id="check">AGUA %</label>
	            <label id="qui2"><input name="pfq[]" type="checkbox" value="DILUCION|0.00;0.00;0.00;0.00;0.00;0.00" id="check">DILUCION %</label>
	            <label id="qui2"><input name="pfq[]" type="checkbox" value="GLICOL|0.00;0.00;0.00;0.00;0.00;0.00" id="check">GLICOL %</label>
                <label id="qui2"><input name="pfq[]" type="checkbox" value="HOLLIN|0.00;0.00;0.08;0.06;0.00;0.00" id="check">HOLLIN <br>(A/mm)</label>
	            <label id="qui2"><input name="pfq[]" type="checkbox" value="ADITIVO|-0.03;-0.04;-0.04;-0.13;-0.05;-0.16" id="check">
	            ADITIVO<br>
	            (A/mm)</label>
	            <label id="qui2"><input name="pfq[]" type="checkbox" value="TBN|;;;;;" id="check">
	            TBN<br>
	            (mg.KOH/gr)</label>
	            <label id="qui2"><input name="pfq[]" type="checkbox" value="TAN|;;;;;" id="check">
	            TAN<br>
	            (mg.KOH/gr)</label>
	            <label id="qui2"><input name="pfq[]" type="checkbox" value="GA|;;;;;" id="check">GA</label>
	            <label id="qui2"><input name="pfq[]" type="checkbox" value="IPEN|;;;;;" id="check">INSOLUBLE EN PENTANO (%)</label>
	            <label id="qui2"><input name="pfq[]" type="checkbox" value="GCONT|;;;;;" id="check">GCONT</label>
                <label id="quidos"><input name="pfq[]" type="checkbox" value="INDPU|;;;;;" id="check">INDICE PQ</label>
                   </div>
                       <div class="quimi">
                      <label id="quimi2">48.69</label>
                      <label id="quimi2"></label>
	                  <label id="quimi2"></label>
                      <label id="quimi2">0.00</label>
                      <label id="quimi2">0.00</label>
                      <label id="quimi2">0.00</label>
                      <label id="quimi2">0.00</label>
                      <label id="quimi2">0.00</label> 
                      <label id="quimi2">0.00</label>
                      <label id="quimi2">0.00</label>
	                  <label id="quimi2">0.00</label>     	                  
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
                       
                       </div>
                       <div class="quimi">
                      <label id="quimi2">40.06</label>
                      <label id="quimi2"></label>
	                  <label id="quimi2"></label>
                      <label id="quimi2">0.03</label>
                      <label id="quimi2">0.00</label>
                      <label id="quimi2">0.04</label>
                      <label id="quimi2">0.00 </label>
                      <label id="quimi2">0.00</label>
                      <label id="quimi2">0.00</label>
                      <label id="quimi2">0.00</label>
                      <label id="quimi2">-0.03</label>	                       
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
                       
                       </div>
                       <div class="quimic">
                      <label id="quimi2">40.39</label>     
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2">0.08</label>              
					  <label id="quimi2">0.00</label>
	                  <label id="quimi2">0.06</label>
	                  <label id="quimi2">0.00</label>                      
	                  <label id="quimi2">0.00</label>              
	                  <label id="quimi2">0.00</label>
                      <label id="quimi2">0.00</label>
	                  <label id="quimi2">-0.04</label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
                       </div><!--fin quimic-->
                      <div class="quimic">
                      <label id="quimi2">41.02</label>     
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
                      <label id="quimi2">0.09</label>
	                  <label id="quimi2">0.03</label>
	                  <label id="quimi2">0.06</label>              
					  <label id="quimi2">0.00</label>
	                  <label id="quimi2">0.00</label>              
	                  <label id="quimi2">0.00</label>
                      <label id="quimi2">0.08</label>
	                  <label id="quimi2">-0.04</label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
                      </div><!--fin quimic-->
                        <div class="quimic">
                      <label id="quimi2">41.53</label>     
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
                      <label id="quimi2">0.07</label>
                      <label id="quimi2">0.06</label>
                      <label id="quimi2">0.10</label>
                      <label id="quimi2">0.00</label>
                      <label id="quimi2">0.00</label> 
                      <label id="quimi2">0.00</label> 
                      <label id="quimi2">0.06</label>
	                  <label id="quimi2">-0.13</label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"></label>
	                  <label id="quimi2"> </label>
                       </div><!--fin quimic-->
                        <div class="quimic">
                        <label id="quimi2">41.54</label>     
	                    <label id="quimi2"></label>
	                    <label id="quimi2"></label>
                        <label id="quimi2">0.06</label>
                        <label id="quimi2">0.07</label>
                        <label id="quimi2">0.06</label>
                        <label id="quimi2">0.00 </label>
                        <label id="quimi2">0.00</label>
                        <label id="quimi2">0.00</label> 
                        <label id="quimi2">0.00</label>
	                    <label id="quimi2">-0.05</label>
	                    <label id="quimi2"></label>
	                    <label id="quimi2"></label>
	                    <label id="quimi2"></label>
	                    <label id="quimi2"></label>
	                    <label id="quimi2"></label>
	                    <label id="quimi2"> </label>
                       </div><!--fin quimic-->
                        <div class="quimic">
                        <label id="quimi2">41.67</label>     
	                    <label id="quimi2"></label>
	                    <label id="quimi2"></label>
                        <label id="quimi2">0.07</label>
                        <label id="quimi2">0.08</label>
                        <label id="quimi2"> 0.06</label> 
                        <label id="quimi2"> 0.00</label>
                        <label id="quimi2"> 0.00</label>
                        <label id="quimi2"> 0.00</label>
                        <label id="quimi2">  0.00</label>
	                    <label id="quimi2"> -0.16</label>
	                    <label id="quimi2"></label>
	                    <label id="quimi2"></label>
	                    <label id="quimi2"></label>
	                    <label id="quimi2"></label>
	                    <label id="quimi2"></label>
	                    <label id="quimi2"> </label>
              </div><!--fin quimic-->
     <div id="graf_btn"><label id="emp_format2">Seleccione las propiedades a graficar.</label><input name="graficar1" type="submit" value="Graficar"></div>

                  </div>
                  <div class="contenido_titulo"> ESPECTROEMISION FINE(PPM) </div>
                  <div class="contenido_panamarillo">

                  <div class="qui">
				<label id="qui22"><input name="ef[]" type="checkbox" value="Fe|2;1;1;2;2;2" id="check">Fe:</label>                
				<label id="qui22"><input name="ef[]" type="checkbox" value="Cr|0;0;0;0;0;0" id="check">Cr:</label>
				<label id="qui22"><input name="ef[]" type="checkbox" value="Pb|0;0;0;0;0;0" id="check">Pb:</label>
				<label id="qui22"><input name="ef[]" type="checkbox" value="Cu|193;98;132;90;87;91" id="check">Cu:</label>
				<label id="qui22"><input name="ef[]" type="checkbox" value="Sn|0;0;0;0;0;0" id="check">Sn:</label>
				<label id="qui22"><input name="ef[]" type="checkbox" value="Al|0;0;0;0;0;0" id="check">Al:</label>
				<label id="qui22"><input name="ef[]" type="checkbox" value="Ni|0;0;0;0;0;0" id="check">Ni:</label>
				<label id="qui22"><input name="ef[]" type="checkbox" value="Ag|0;0;0;0;0;0" id="check">Ag:</label>
				<label id="qui22"><input name="ef[]" type="checkbox" value="Si|0;1;0;5;2;0" id="check">Si:</label>
				<label id="qui22"><input name="ef[]" type="checkbox" value="B|0;0;0;0;0;0" id="check">B:</label>
				<label id="qui22"><input name="ef[]" type="checkbox" value="Na|1;1;1;1;1;1" id="check">Na:</label>
				<label id="qui22"><input name="ef[]" type="checkbox" value="Mg|0;0;1;1;1;1" id="check">Mg:</label>
				<label id="qui22"><input name="ef[]" type="checkbox" value="Ca|3;3;18;24;21;25" id="check">Ca:</label>
				<label id="qui22"><input name="ef[]" type="checkbox" value="Ba|0;0;0;0;0;0" id="check">Ba:</label>
				<label id="qui22"><input name="ef[]" type="checkbox" value="P|571;516;515;459;490;520" id="check">P:</label>
				<label id="qui22"><input name="ef[]" type="checkbox" value="N|794;817;677;682;708;713" id="check">n:</label>
				<label id="qui22"><input name="ef[]" type="checkbox" value="O|0;0;1;0;0;0" id="check">o:</label>
				<label id="qui22"><input name="ef[]" type="checkbox" value="Ti|0;0;0;0;0;0" id="check">Ti:</label>
				<label id="qui22"><input name="ef[]" type="checkbox" value="V|0;0;0;0;0;0" id="check">V:</label>
				<label id="qui22"><input name="ef[]" type="checkbox" value="K|2;2;1;1;3;2" id="check">K:</label>
				  </div><!--fin qui-->
                     <div class="quimi">
                     
                     <label id="quimico2"></label>
                     <label id="quimico2"></label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">1</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">2</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">607</label>
                     <label id="quimico2">661</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">3</label>
                     
                     </div><!--fin quimi-->
                     <div class="quimi">
                     
                     <label id="quimico2">2</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">193</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">1</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">3</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">571</label>
                     <label id="quimico2">794</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">2</label>
                     
                     </div><!--fin quimi-->
                    <div class="quimic">
                    <label id="quimico2">1</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">98</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">1</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">1</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">3</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">516</label>
                     <label id="quimico2">817</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">2</label>
                    
                    </div><!--fin quimic-->
                    <div class="quimic">
                    <label id="quimico2">1</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">132</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">1</label>
                     <label id="quimico2">1</label>
                     <label id="quimico2">18</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">515</label>
                     <label id="quimico2">677</label>
                     <label id="quimico2">1</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">1</label>
                    
                    </div><!--fin quimic-->
                    <div class="quimic">
                    <label id="quimico2">2</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">90</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">5</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">1</label>
                     <label id="quimico2">1</label>
                     <label id="quimico2">24</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">459</label>
                     <label id="quimico2">682</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">1</label>
                    
                    </div><!--fin quimic-->
                    <div class="quimic">
                    <label id="quimico2">2</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">87</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">2</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">1</label>
                     <label id="quimico2">1</label>
                     <label id="quimico2">21</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">490</label>
                     <label id="quimico2">708</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">3</label>
                    
                    </div><!--fin quimic-->
                    <div class="quimic">
                    <label id="quimico2">2</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">91</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">1</label>
                     <label id="quimico2">1</label>
                     <label id="quimico2">25</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">520</label>
                     <label id="quimico2">713</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">2</label>
                    
                    </div><!--fin quimic-->
     <div id="graf_btn"><label id="emp_format2">Seleccione las propiedades a graficar.</label><input name="graficar2" type="submit" value="Graficar"></div>

                  </div>
                  <div class="contenido_titulo"> ESPECTROEMISION COARSE(PPM) </div>
                  <div class="contenido_panamarillo">

                   <div class="qui8">
					<label id="qui22"><input name="ec[]" type="checkbox" value="Fe C|;;;;;" id="check">Fe C:</label>
					<label id="qui22"><input name="ec[]" type="checkbox" value="Cr C|;;;;;" id="check">Cr C:</label>
					<label id="qui22"><input name="ec[]" type="checkbox" value="Pb C|;;;;;" id="check">Pb C:</label>
					<label id="qui22"><input name="ec[]" type="checkbox" value="Cu C|;;;;;" id="check">Cu C:</label>
					<label id="qui22"><input name="ec[]" type="checkbox" value="Sn C|;;;;;" id="check">Sn C:</label>
					<label id="qui22"><input name="ec[]" type="checkbox" value="Al C|;;;;;" id="check">Al C:</label>
					<label id="qui22"> <input name="ec[]" type="checkbox" value="Ni C|;;;;;" id="check">Ni C:</label>
					<label id="qui22"><input name="ec[]" type="checkbox" value="Ag C|;;;;;" id="check">Ag C:</label>
					<label id="qui22"><input name="ec[]" type="checkbox" value="Si C|;;;;;" id="check">Si C:</label>
					<label id="qui22"><input name="ec[]" type="checkbox" value="B C|;;;;;" id="check">B C:</label>
					<label id="qui22"><input name="ec[]" type="checkbox" value="Mo C|;;;;;" id="check">Mo C:</label>
					<label id="qui22"><input name="ec[]" type="checkbox" value="Ti C|;;;;;" id="check">Ti C:</label>
					</div><!--fin qui-->
                    <div class="quimicos2">
                   <label id="quimico2"> </label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
                    </div>
					<div class="quimicos2">
                   <label id="quimico2"> </label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
                    </div>
                    <div class="quimicos2">
                    
                    <label id="quimico2"> </label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
                    
                    </div><!--fin quimic-->
                         <div class="quimicos2">
                    
                    <label id="quimico2"> </label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
                    
                    </div><!--fin quimic-->
                         <div class="quimicos2">
                    
                    <label id="quimico2"> </label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
                    
                    </div><!--fin quimic-->
                         <div class="quimicos2">
                    
                    <label id="quimico2"> </label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
                    </div><!--fin quimic-->
                         <div class="quimicos2">
                    <label id="quimico2"> </label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
					<label id="quimico2"></label>
                    </div><!--fin quimic-->
     <div id="graf_btn"><label id="emp_format2">Seleccione las propiedades a graficar.</label><input name="graficar3" type="submit" value="Graficar"></div>

                  </div>
                  <div class="contenido_titulo"> PARTICULAS(PPM) </div>
                  <div class="contenido_panamarillo">

                  <div class="qui8">
				<label id="qui22"><input name="par[]" type="checkbox" value="2-5|231;173;29;144;58;563" id="check">2-5</label>                
				<label id="qui22"><input name="par[]" type="checkbox" value="5-15|47;28;9;52;24;163" id="check">5-15</label>
				<label id="qui22"><input name="par[]" type="checkbox" value="15-25|4;4;1;7;6;24" id="check">15-25</label>
				<label id="qui22"><input name="par[]" type="checkbox" value="25-50|2;3;0;1;2;7" id="check">25-50</label>
				<label id="qui22"><input name="par[]" type="checkbox" value="50-100|0;1;0;0;0;0" id="check">50-100</label>
				<label id="qui22"><input name="par[]" type="checkbox" value=">100|0;0;0;0;0;0" id="check">>100</label>
				<label id="qui22">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ISO</label>
				<label id="qui22"><input name="par[]" type="checkbox" value="IA|0;0;0;0;0;0" id="check">IA</label>
					</div><!--fin qui-->
                     <div class="quimicos2">
                     <label id="quimico2">3476</label>
                     <label id="quimico2">526</label>
                     <label id="quimico2">21</label>
                     <label id="quimico2">6</label>
                     <label id="quimico2">1</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">19/16/12</label>
                     <label id="quimico2">0.02</label>
                     </div><!--fin quimi-->
                     <div class="quimicos2">
                     <label id="quimico2">231</label>
                     <label id="quimico2">47</label>
                     <label id="quimico2">4</label>
                     <label id="quimico2">2</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">15/13/10</label>
                     <label id="quimico2">0</label>
                     </div><!--fin quimi-->
                     <div class="quimicos2">
                      <label id="quimico2">173</label>
                     <label id="quimico2">28</label>
                     <label id="quimico2">4</label>
                     <label id="quimico2">3</label>
                     <label id="quimico2">1</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">15/12/10</label>
                     <label id="quimico2">0</label>
                     </div><!--fin quimi-->
                      <div class="quimicos2">
                      <label id="quimico2">29</label>
                     <label id="quimico2">9</label>
                     <label id="quimico2">1</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">12/11/8</label>
                     <label id="quimico2">0</label>
                     </div><!--fin quimi-->
                      <div class="quimicos2">
                      <label id="quimico2">144</label>
                     <label id="quimico2">52</label>
                     <label id="quimico2">7</label>
                     <label id="quimico2">1</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">15/13/10</label>
                     <label id="quimico2">0</label>
                     </div><!--fin quimi-->
                      <div class="quimicos2">
                      <label id="quimico2">58</label>
                     <label id="quimico2">24</label>
                     <label id="quimico2">6</label>
                     <label id="quimico2">2</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">14/12/10</label>
                     <label id="quimico2">0</label>
                     </div><!--fin quimi-->
                      <div class="quimicos2">
                      <label id="quimico2">563</label>
                     <label id="quimico2">163</label>
                     <label id="quimico2">24</label>
                     <label id="quimico2">7</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">0</label>
                     <label id="quimico2">17/15/12</label>
                      </div><!--fin quimi-->
     <div id="graf_btn"><label id="emp_format2">Seleccione las propiedades a graficar.</label><input name="graficar4" type="submit" value="Graficar"></div>

     <div id="volver_informe"><a href="javascript:history.back();">Volver Atras</a></div>
                  </div>       
    </div>
    
    <div class="pan_amarillo_cierre"></div>
    <!-- cierre panel celeste de contenido -->
    <div class="panel_celeste_cierre"></div>
    
    </div><!-- final de la caja contenedora de los bloques amarillos de contenido específico -->
</div><!-- final del panel gral celeste que encierra los paneles amarillos de contenido -->

</div><!-- cierre panel izquierdo contenido -->'.
 
        '</body></html>';

$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("doc.pdf");

?>