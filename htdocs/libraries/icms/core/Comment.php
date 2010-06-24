<?php
/**
 * Core class for managing comments
 *
 * @category	ICMS
 * @package     core
 * @subpackage	Comment
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright 	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @version		$Id$
 */

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

/**
 * A Comment
 *
 * @category	ICMS
 * @package     core
 * @subpackage	Comment
 *
 */
class icms_core_Comment extends icms_core_Object {

	/**
	 * Constructor
	 **/
	public function __construct() {
		parent::__construct();
		$this->initVar('com_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('com_pid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('com_modid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('com_icon', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('com_title', XOBJ_DTYPE_TXTBOX, null, true, 255, true);
		$this->initVar('com_text', XOBJ_DTYPE_TXTAREA, null, true, null, true);
		$this->initVar('com_created', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('com_modified', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('com_uid', XOBJ_DTYPE_INT, 0, true);
		$this->initVar('com_ip', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('com_sig', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('com_itemid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('com_rootid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('com_status', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('com_exparams', XOBJ_DTYPE_OTHER, null, false, 255);
		$this->initVar('dohtml', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('dosmiley', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('doxcode', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('doimage', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('dobr', XOBJ_DTYPE_INT, 0, false);
	}

	/**
	 * Is this comment on the root level?
	 *
	 * @return  bool
	 **/
	public function isRoot() {
		return ($this->getVar('com_id') == $this->getVar('com_rootid'));
	}
}
