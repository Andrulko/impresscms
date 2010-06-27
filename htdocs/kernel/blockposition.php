<?php
defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

class IcmsBlockposition extends icms_block_position_Object {
	private $_deprecated;
	public function __construct() {
		parent::getInstance();
		$this->_deprecated = icms_deprecated('icms_block_position_Object', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

class IcmsBlockpositionHandler extends icms_block_position_Handler {
	private $_deprecated;
	public function __construct() {
		parent::getInstance();
		$this->_deprecated = icms_deprecated('icms_block_position_Handler', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}