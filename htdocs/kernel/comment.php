<?php
defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

class XoopsComment extends icms_comment_Object {
	private $_deprecated;
	public function __construct() {
		parent::getInstance();
		$this->_deprecated = icms_deprecated('icms_comment_Object', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

class XoopsCommentHandler extends icms_comment_Handler {
	private $_deprecated;
	public function __construct() {
		parent::getInstance();
		$this->_deprecated = icms_deprecated('icms_comment_Handler', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}