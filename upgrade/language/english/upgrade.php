<?php
// $Id: upgrade.php 558 2006-06-20 06:35:23Z skalpa $
//Traducci�n para ImpressCMS 0.5 por debianus

define( "_XOOPS_UPGRADE", "Actualizaci�n del sistema ImpressCMS" );
define( "_CHECKING_APPLIED", "Comprobando las actualizaciones aplicadas:" );
define( "_SET_FILES_WRITABLE", "Debe asignar permisos de escritura a los siguientes archivos antes de proceder:" );
define( "_NO_NEED_UPGRADE", "La actualizaci�n no es necesaria. Por favor, elimine este directorio del servidor" );
define( "_NEED_UPGRADE", "La actualizaci�n es necesaria" );
define( "_PROCEED_UPGRADE", "Proceder con la actualizadi�n" );
define( "_PERFORMING_UPGRADE", "Ejecutando actualizaci�n %s" );

define( "_USER_LOGIN", "Inicio de sesi�n como usuario" );

define( "_MANUAL_INSTRUCTIONS", "Instrucciones de actualizaci�n: manual" );

// %s is filename
define( "_FAILED_PATCH", "Fall� el parche %s" );
define( "_APPLY_NEXT", "Aplicar pr�xima actualizaci�n (%s)" );
define( "_COPY_RED_LINES", "Copiar las l�neas rojas siguientes a %s" );

define( "_FINISH", "Finalizado" );
define( "_RELOAD", "Recargar" );

define('_UPGRADE_CHARSET', _CHARSET); 

define("_UPGRADE_PRIVPOLICY", "<p>Es necesario que establezca la pol�tica de privacidad de su sitio web y que la publique en el mismo en el caso de admitir el registro de nuevos usuarios. Probablemente en su pa�s exista legislaci�n sobre la materia que debe conocer y cumplir. Por ejemplo, en Espa�a hay que tener en cuenta lo dispuesto en la Ley Org�nica 15/1999, de 13 de Diciembre, de Protecci�n de Datos de car�cter personal (en adelante LOPD) y el Real Decreto 994/1999, de 11 de Junio, que aprueba el Reglamento de medidas de seguridad de los ficheros automatizados que contengan datos de car�cter personal (en adelante Reglamento 994/1999). 
</p><p>
Tambi�n debe tener en cuenta que los requisitos de la pol�tica de privacidad pueden ser distintos seg�n cual sea la informaci�n exigida a los visitantes para registrarse o la que estos pueden introducir al hacer uso del sitio; piense por ejemplo en el caso de que una web realice alg�n tipo de transacci�n econ�mica proporcion�ndose datos bancarios, o que recoga informaci�n m�dica o pol�tica. Normalmente las legislaciones son mucho m�s estrictas con relaci�n al tratamiento de este tipo de datos de car�cter personal.
</p><p>
A continuaci�n presentamos un ejemplo de pol�tica de privacidad general para un sitio que no gestiona informaci�n cuya seguridad deba ser atendida con medidas especiales. Por favor, ajuste el texto seg�n tus necesidades o introduce uno nuevo. Para ello, en el panel de control del sitio vaya a 'Preferencias' y dentro de �stas a la'Configuraci�n del usuario'; haga clic en 'Editar' y al final de la p�gina que se mostrar� tiene la opci�n de activar la pol�tica de privacidad y fijar el texto de la misma.
</p><p>
<h2>Informaci�n general</h2>
</p><p>
La pol�tica de privacidad que se describe a continuaci�n s�lo es aplicable al presente sitio web (puedes poner su nombre), entendiendo como tal todas las p�ginas y subp�ginas incluidas en el dominio (nombre de tu dominio). Declinamos cualquier responsabilidad sobre las diferentes pol�ticas de privacidad y protecci�n de datos de car�cter personal que puedan contener los sitios web a los cuales pueda accederse a trav�s de los hiperv�nculos ubicados en este sitio y no gestionados directamente por nosotros. La presente pol�tica de privacidad est� vigente en este sitio desde el d�a.
</p><p>
Ponemos en conocimiento de los usuarios de este sitio web que la presente declaraci�n refleja la pol�tica en materia de protecci�n de datos del mismo. Esta pol�tica se ha configurado respetando escrupulosamente la normativa vigente en materia de protecci�n de datos personales, esto es, entre otras, la regulada en la Ley Org�nica 15/1999, de 13 de Diciembre, de Protecci�n de Datos de car�cter personal (en adelante LOPD) y el Real Decreto 994/1999, de 11 de Junio, que aprueba el Reglamento de medidas de seguridad de los ficheros automatizados que contengan datos de car�cter personal (en adelante Reglamento 994/1999).
</p>
<h2>Recogida de datos de car�cter personal.</h2>
<p>
No recopilamos datos de car�cter personal sin el consentimiento de los usuarios. En caso de que decidas registrarte como usuario en este sitio los �nicos datos que solicitaremos con car�cter obligatorio son:
</p>
<p>
<ul>
<li>Nombre de usuario, pudiendo elegir el que prefieras. No tiene porqu� ser tu nombre y apellidos y el nombre social de las personas f�sicas</li>
<li>Una direcci�n de correo electr�nico. Es necesaria para activar tu cuenta de usuario y para enviarte una nueva contrase�a en caso de haber olvidado la que elegiste o tengas otro problema.</li>
<li>Una contrase�a de usuario para acceder al sitio; es de tu libre elecci�n, salvo en cuanto a su extensi�n.</li></ul>
</p><p>
Nota: caso de tener un sitio web que almacene un informaci�n personal, como puede ser un sitio de soporte t�cnico, de gesti�n del personal de una empresa, una intranet etc, quiz�s deber�as a�adir algo como:
</p><p>
Dichos datos proporcionados voluntariamente por el usuario podr�n ser incorporados a un fichero automatizado, registrado ante la Agencia Espa�ola de Protecci�n de Datos, bajo la titularidad de (due�o de la web). En consecuencia, el usuario que voluntariamente nos proporcione sus datos personales acepta expresamente el tratamiento de los mismos, con la exclusiva finalidad de gestionar su condici�n de usuario registrado de los servicios que prestamos. En cualquier caso, los datos recogidos ser�n tratados siempre respetando la normativa vigente en materia de protecci�n de datos de car�cter personal.
</p>
<h2>Medidas de seguridad.</h2> 
<p>
Los datos personales comunicados por el usuario pueden ser almacenados en bases de datos automatizadas o no, cuya titularidad nos corresponde en exclusiva, asumiendo nosotros todas las medidas de �ndole t�cnica, organizativa y de seguridad que garantizan la confidencialidad, integridad y calidad de la informaci�n contenida en las mismas de acuerdo con lo establecido en la LOPD y en el Reglamento 994/1999.
</p><p>
La comunicaci�n con los usuarios no utiliza un canal seguro y los datos transmitidos no son cifrados, por lo que se solicita a los usuarios que se abstengan de enviar aquellos datos personales que merezcan la consideraci�n de datos especialmente protegidos en los t�rminos del art�culo 7 de la LOPD, ya que las medidas de seguridad aplicables a un canal no seguro lo hacen desaconsejable.
</p>
<h2>Finalidad del tratamiento de los datos</h2>
<p>
Los datos de car�cter personal que sean comunicados voluntariamente por el usuario se destinar�n �nicamente a la finalidad concreta de gestionar su condici�n de tal y ser�n tratados con la m�s absoluta confidencialidad, destin�ndose �nicamente a aquellas finalidades para las que fueron recabados y de las que expresamente se informa al usuario en el momento de su recogida por medio de la presente Pol�tica de Privacidad.
</p>
<h2>Cesi�n de datos</h2>
<p>
Los datos de car�cter personal recogidos a trav�s de este sitio no ser�n objeto de cesi�n a ninguna otra persona f�sica o jur�dica, salvo que dicha cesi�n se encuentre amparada por la LOPD tanto en cuanto a los destinatarios de la misma como en cuanto a su objeto.
</p><p>
Si tu sitio web tiene car�cter comercial, puedes a�adir algo como:
</p><p>
El usuario que voluntariamente nos comunique sus datos a trav�s de este sitio consiente expresamente la utilizaci�n de los mismos para el env�o de informaci�n comercial por v�a electr�nica de los productos y servicios comercializados por nosotros, en estricto cumplimiento de lo dispuesto en la legislaci�n vigente en materia de Servicios de la Sociedad de la Informaci�n en lo que a comunicaciones comerciales se refiere, salvo que manifieste su oposici�n.
<p>
<h2>Calidad de los datos: derechos de acceso, oposici�n, rectificaci�n y cancelaci�n.</h2>
<p>
Nos comprometemos a mantener actualizados en todo momento los datos personales que voluntariamente nos hayan proporcionado los usuarios de este sitio, de manera que respondan verazmente a la identidad y caracter�sticas personales de dichos usuarios. Por ello, cualquier usuario puede en cualquier momento ejercer el derecho a acceder, rectificar y, en su caso, cancelar sus datos de car�cter personal suministrados mediante comunicaci�n dirigida al correo de este sitio.
</p>
<h2>Sobre c�mo usamos 'cookies'</h2>
<p>
Se denominan as� a unos peque�os archivos con respecto a los cuales se te pregunta (seg�n la configuraci�n que hayas elegido en tu navegador) si aceptas que sean almacenados en tu disco duro. Si est�s de acuerdo, el archivo ayuda a analizar el tr�fico web y conocer cuando visitas nuestro sitio. Tambi�n son �tiles para almacenar informaci�n acerca de tus preferencias al visitar nuestro sitio.
</p><p>
Puedes elegir aceptar o rechazar 'cookies'. Muchos navegadores autom�ticamente los aceptan, pero normalmente puedes modificar la configuraci�n del que uses para rechazarlos como regla general; sin embargo, esto puede impedir el registro y la entrada en un sitio web.
</p>");
?>