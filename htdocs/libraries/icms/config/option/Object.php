<?php
/**
 * Manage configuration options
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Config
 * @subpackage	Option
 * @author		Kazumi Ono (aka onokazo)
 * @version		SVN: $Id: configoption.php 19586 2010-06-24 11:48:14Z malanciault $
 */

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

/**
 * A Config-Option
 *
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @category	ICMS
 * @package     Config
 * @subpackage	Option
 */
class icms_config_option_Object extends icms_core_Object {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->icms_core_Object();
		$this->initVar('confop_id', XOBJ_DTYPE_INT, null);
		$this->initVar('confop_name', XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar('confop_value', XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar('conf_id', XOBJ_DTYPE_INT, 0);
	}
}
