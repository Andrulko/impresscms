<?php
/**
* Privacy policy display page
*
* This page displays the privacy policy of the site
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		1.0
* @author		m0nty_
* @version		$Id$
*/

$xoopsOption['pagetype'] = 'privpolicy';

include "mainfile.php";

$xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);

if ($xoopsConfigUser['priv_dpolicy'] !== 1)
{
	redirect_header('index.php', 3, _US_NOPERM);
}

$xoopsOption['template_main'] = 'system_privpolicy.html';
include ICMS_ROOT_PATH.'/header.php';

$purifier =& icms_HTMLPurifier::getPurifierInstance();

$xoopsTpl->assign('priv_poltype', 'page');
$priv = str_replace('{X_SITEURL}', XOOPS_URL.'/', $xoopsConfigUser['priv_policy']); 
$priv = str_replace('{X_SITENAME}', $xoopsConfig['sitename'], $priv);
$priv = $purifier->displayHTMLarea($priv, 2); 
$xoopsTpl->assign('priv_policy', $priv);
$xoopsTpl->assign('lang_privacy_policy', _PRV_PRIVACY_POLICY);

include ICMS_ROOT_PATH.'/footer.php';
?>