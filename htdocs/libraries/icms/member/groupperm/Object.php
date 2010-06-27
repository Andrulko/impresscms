<?php
/**
 * Manage groups and memberships
 *
 * @copyright	The ImpressCMS Project <http://www.impresscms.org/>
 * @license		LICENSE.txt
 *
 * @author		Kazumi Ono (aka onokazo)
 * @author		Gustavo Alejandro Pilla (aka nekro) <nekro@impresscms.org> <gpilla@nube.com.ar>
 * @category	ICMS
 * @package		Member
 * @subpackage	Groupperm
 * @version		SVN: $Id: groupperm.php 19450 2010-06-18 14:15:29Z malanciault $
 */

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

/**
 * A group permission
 *
 * These permissions are managed through a {@link icms_member_groupperm_Handler} object
 *
 * @package     kernel
 * @subpackage	member
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class icms_member_groupperm_Object extends icms_core_Object {
	/**
	 * Constructor
	 *
	 */
	function __construct() {
		$this->icms_core_Object();
		$this->initVar('gperm_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('gperm_groupid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('gperm_itemid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('gperm_modid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('gperm_name', XOBJ_DTYPE_OTHER, null, false);
	}
}

