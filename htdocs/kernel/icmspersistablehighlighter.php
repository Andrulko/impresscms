<?php
class IcmsPersistableHighlighter extends icms_ipf_Highlighter{
	private $_deprecated;
	public function __construct() {
		parent::getInstance();
		$this->_deprecated = icms_deprecated('icms_ipf_Highlighter', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}
?>