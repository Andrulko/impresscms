<?php
/**
* Renders the comments
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license	LICENSE.txt
* @package	core
* @since	XOOPS
* @author	http://www.xoops.org The XOOPS Project
* @author	modified by UnderDog <underdog@impresscms.org>
* @version	$Id$
*/

/**
 * Display comments
 *
 * @package		kernel
 * @subpackage	comment
 * @deprecated	Use icms_data_comment_Renderer, instead
 * @author		Kazumi Ono 	<onokazu@xoops.org>
 * @copyright	(c) 2000-2003 The Xoops Project - www.xoops.org
 */
class XoopsCommentRenderer extends icms_data_comment_Renderer {
	private $_deprecated;

	/**
	 * Access the only instance of this class
	 *
	 * @param   object  $tpl        reference to a {@link Smarty} object
	 * @param   boolean $use_icons
	 * @param   boolean $do_iconcheck
	 * @return
	 **/
	static function &instance(&$tpl, $use_icons = true, $do_iconcheck = false) {
		$class = new XoopsCommentRenderer();
		$class->_deprecated = icms_core_Debug::setDeprecated('icms_data_comment_Renderer', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
		static $instance;
		if (!isset($instance)) {
			$instance = new icms_data_comment_Renderer($tpl, $use_icons, $do_iconcheck);
		}
		return $instance;
	}
}