Para poder redirigir autom�ticamente las p�ginas 400 y 500 a la p�gina "NotFound", necesitas a�adir estas l�neas en tu archivo .htaccess:

ErrorDocument 404 http://yoursite.com/notfound/
ErrorDocument 500 http://yoursite.com/notfound/