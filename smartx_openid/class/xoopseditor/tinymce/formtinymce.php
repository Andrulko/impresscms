<?php 
/**
 * TinyMCE adapter for XOOPS
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since		4.00
 * @version		$Id$
 * @package		xoopseditor
 */
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

require_once XOOPS_ROOT_PATH."/class/xoopsform/formtextarea.php";

class XoopsFormTinymce extends XoopsFormTextArea 
{
	var $rootpath = "";
	var $_language = _LANGCODE;
    var $_width = "100%";
    var $_height = "500px";

    var $tinymce;
    var $config = array();
    
	/**
	 * Constructor
	 *
     * @param	array   $configs  Editor Options
     * @param	binary 	$checkCompatible  true - return false on failure
	 */
	function XoopsFormTinymce($configs, $checkCompatible = false)
	{
		$current_path = __FILE__;
		if ( DIRECTORY_SEPARATOR != "/" ) $current_path = str_replace( strpos( $current_path, "\\\\", 2 ) ? "\\\\" : DIRECTORY_SEPARATOR, "/", $current_path);
		$this->rootpath = substr(dirname($current_path), strlen(XOOPS_ROOT_PATH));
		
		if(is_array($configs)) {
			$vars = array_keys(get_object_vars($this));
			foreach($configs as $key => $val){
				if(in_array("_".$key, $vars)) {
					$this->{"_".$key} = $val;
				}else{
					$this->config[$key] = $val;
				}
			}
		}
		
		if($checkCompatible && !$this->isCompatible()) {
			return false;
		}
		
		$this->XoopsFormTextArea("", @$this->_name, @$this->_value);
		parent::setExtra("style='width: ".$this->_width."; height: ".$this->_height.";'");
		
		$this->initTinymce();
	}
	
	function initTinymce()
	{
		$this->config["elements"] = $this->getName();
		$this->config["language"] = $this->getLanguage();
		$this->config["rootpath"] = $this->rootpath;
		$this->config["area_width"] = $this->_width;
		$this->config["area_height"] = $this->_height;
		$this->config["fonts"] = $this->getFonts();
		
		require_once dirname(__FILE__)."/tinymce.php";
		$this->tinymce = TinyMCE::instance($this->config);
	}

	/**
	 * get language
	 *
     * @return	string
	 */
	function getLanguage()
	{
		if(defined("_XOOPS_EDITOR_TINYMCE_LANGUAGE")) {
			$language = strtolower(constant("_XOOPS_EDITOR_TINYMCE_LANGUAGE"));
		}else{
			$language = str_replace('-', '_', strtolower($this->_language));
			if(strtolower(_CHARSET) == "utf-8") {
				$language .= "_utf8";
			}
		}
		
		return $language;
	}
	
	function getFonts()
	{
		if(empty($this->config["fonts"]) && defined("_XOOPS_EDITOR_TINYMCE_FONTS")) {
			 $this->config["fonts"] = constant("_XOOPS_EDITOR_TINYMCE_FONTS");
		}
		
		return @$this->config["fonts"];
	}

	/**
	 * prepare HTML for output
	 *
     * @return	sting HTML
	 */
	function render()
	{
		$ret = $this->tinymce->render();
		$ret .= parent::render();
		
		return $ret;
	}

	/**
	 * Check if compatible
	 *
     * @return
	 */
	function isCompatible()
	{
		return is_readable(XOOPS_ROOT_PATH . $this->rootpath. "/tinymce.php");
	}
}
?>
