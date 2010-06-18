<?php
/**
 * core_Tpl
 *
 * Gateway file to include class/theme_blocks.php when xos_logos_PageBuilder class is needed
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.1
 * @author		marcan <marcan@impresscms.org>
 * @todo		Remove this in 1.4
 * @deprecated	include class/theme_blocks.php instead
 * @version		$Id$
 */

if (!defined("ICMS_ROOT_PATH")) die("ImpressCMS root path not defined");
icms_deprecated( '', 'class/xoopstpl.php will be removed in ImpressCMS 1.4 - use class/theme_blocks.php' );
include_once(ICMS_ROOT_PATH . '/class/theme_blocks.php');
?>