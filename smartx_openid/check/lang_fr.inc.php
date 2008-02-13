<?php

// Warning: Some of the constant values here contain the sprintf format code "%s".  That format code must not be removed.

// header text for initial page
$bchk['LANG_HEADER_INITIAL_PAGE'] = "
	<h3>Les caract�ristiques suivantes du navigateur vont �tre test�es :</h3>
";

// footer text for initial page
$bchk['LANG_FOOTER_INITIAL_PAGE'] = "
";

// header text for test results page
$bchk['LANG_HEADER_RESULTS_PAGE'] = "
	<h3>R�sultats des tests de votre navigateur :</h3>
";

// footer text for test results page
$bchk['LANG_FOOTER_RESULTS_PAGE'] = "
	<!-- exemple
	<p>Si l'un de ces tests �choue, sa r�f�rence peut �tre utile pour corriger le probl�me :
	<a href='http://example.com/' target='_blank'>Exemple</a>
	</p>
	-->
";

// text for link
$bchk['LANG_CLICK_HERE1'] = 'Cliquez';
$bchk['LANG_CLICK_HERE2'] = 'ICI';
$bchk['LANG_CLICK_HERE3'] = 'pour lancer les tests.';

$bchk['LANG_SELECT_LANGUAGE'] = 'Choisissez la langue';

// text for link
$bchk['LANG_CLICK_HERE4'] = 'Cliquez';
$bchk['LANG_CLICK_HERE5'] = 'ICI';
$bchk['LANG_CLICK_HERE6'] = 'pour relancer les tests.';

$bchk['LANG_DO_NOT_RELOAD'] = "
	Veuillez ne <em>pas</em> utiliser les boutons de retour vers la page pr�c�dente, de rafra�chissement ou de rechargement de la page car ils peuvent produire des r�sultats incorrects.
";

// errors
$bchk['LANG_ERROR_MISSING_POST_VALUE'] = "Erreur interne : valeur POST manquante '%s'";
$bchk['LANG_ERROR_INTERNAL']           = 'Erreur interne : %s';

// test results column headers
$bchk['LANG_FEATURE']     = 'Fonctionnalit�s';
$bchk['LANG_DESCRIPTION'] = 'Description';
$bchk['LANG_TEST_RESULT'] = 'R�sultats';

// test names and descriptions
$bchk['LANG_COOKIES']                     = 'Cookies';
$bchk['LANG_COOKIES_DESC']                = 'Les cookies peuvent �tre �crits et lus. (via les ent�tes HTTP)';
$bchk['LANG_REFERRER_H']                  = 'Referrer-H';
$bchk['LANG_REFERRER_H_DESC']             = 'L\'adresse de la page de provenance peut �tre lue. (nom de l\'h�te)';
$bchk['LANG_REFERRER_HS']                 = 'Referrer-HS';
$bchk['LANG_REFERRER_HS_DESC']            = 'L\'adresse de la page de provenance peut �tre lue. (nom de l\'h�te + nom du script)';
$bchk['LANG_REFERRER_HSQ']                = 'Referrer-HSQ';
$bchk['LANG_REFERRER_HSQ_DESC']           = 'L\'adresse de la page de provenance peut �tre lue. (nom de l\'h�te + nom du script + cha�ne de requ�te)';
$bchk['LANG_JAVASCRIPT']                  = 'Javascript';
$bchk['LANG_JAVASCRIPT_DESC']             = 'Le code Javascript peut �tre ex�cut� dans la page.';
$bchk['LANG_JAVASCRIPT_READ_COOKIE']      = 'Lecture des cookies dans Javascript';
$bchk['LANG_JAVASCRIPT_READ_COOKIE_DESC'] = 'Les cookies peuvent �tre lus depuis Javascript.';
$bchk['LANG_JAVASCRIPT_SET_COOKIE']       = 'Cr�ation des cookies dans Javascript';
$bchk['LANG_JAVASCRIPT_SET_COOKIE_DESC']  = 'Les cookies peuvent �tre �cris depuis Javascript.';
$bchk['LANG_CLOCK']                       = 'Horloge';
$bchk['LANG_CLOCK_DESC']                  = 'Le serveur et le client sont d\'accord sur l\'heure de %s minutes. (utilisation de Javascript)';

$bchk['LANG_NO_TESTS'] = 'Aucun test effectu�';

// test results
$bchk['LANG_PASS'] = 'R�ussi';
$bchk['LANG_FAIL'] = 'Echec';

// clock test details
$bchk['LANG_SECONDS']                = 'secondes';
$bchk['LANG_MINUTES']                = 'minutes';
$bchk['LANG_HOURS']                  = 'heures';
$bchk['LANG_DAYS']                   = 'jours';
$bchk['LANG_SERVER_CLOCK']           = 'Heure du serveur';
$bchk['LANG_CLIENT_CLOCK']           = 'Heure du client';
$bchk['LANG_DIFFERENCE']             = 'Diff�rence';
$bchk['LANG_SIMULATING_CLOCK_ERROR'] = 'Simulation de l\'horloge du client, erreur de %s secondes.';
$bchk['LANG_NOTE_INTERNET_LAG']      = 'Notez que m�me si les horloges sont r�gl�es avec pr�cision, des diff�rences entre les horloges sont normales, cela est d�e au d�calage d\'internet.';

$bchk['LANG_'] = '';

?>