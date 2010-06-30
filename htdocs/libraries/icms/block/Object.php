<?php
/**
 * ImpressCMS Block Persistable Class
 *
 * @copyright 	The ImpressCMS Project <http://www.impresscms.org>
 * @license		GNU General Public License (GPL) <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @category	ICMS
 * @package		Block
 * @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 * @version		SVN: $Id$
 */

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * ImpressCMS Core Block Object Class
 *
 * @since ImpressCMS 1.2
 * @author Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 */
class icms_block_Object extends icms_ipf_Object {

	public function __construct(& $handler) {

		parent::__construct($handler);
//		$this->icms_ipf_Object($handler);

		$this->quickInitVar('name', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('bid', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('mid', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('func_num', XOBJ_DTYPE_INT);
		$this->quickInitVar('title', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('content', XOBJ_DTYPE_TXTAREA);
		$this->quickInitVar('side', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('weight', XOBJ_DTYPE_INT, true, false, false, 0);
		$this->quickInitVar('visible', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('block_type', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('c_type', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('isactive', XOBJ_DTYPE_INT);
		$this->quickInitVar('dirname', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('func_file', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('show_func', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('edit_func', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('template', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('bcachetime', XOBJ_DTYPE_INT);
		$this->quickInitVar('last_modified', XOBJ_DTYPE_INT);
		$this->quickInitVar('options', XOBJ_DTYPE_TXTBOX);
	}

	// The next Methods are for backward Compatibility

	public function getContent($format = 'S', $c_type = 'T'){
		switch ( $format ) {
			case 'S':
				if ( $c_type == 'H' ) {
					return str_replace('{X_SITEURL}', ICMS_URL . '/', $this->getVar('content', 'n'));
				} elseif ( $c_type == 'P' ) {
					ob_start();
					echo eval($this->getVar('content', 'n'));
					$content = ob_get_contents();
					ob_end_clean();
					return str_replace('{X_SITEURL}', ICMS_URL . '/', $content);
				} elseif ( $c_type == 'S' ) {
					$myts =& icms_core_Textsanitizer::getInstance();
					$content = str_replace('{X_SITEURL}', ICMS_URL . '/', $this->getVar('content', 'n'));
					return $myts->displayTarea($content, 1, 1);
				} else {
					$myts =& icms_core_Textsanitizer::getInstance();
					$content = str_replace('{X_SITEURL}', ICMS_URL . '/', $this->getVar('content', 'n'));
					return $myts->displayTarea($content, 0, 0);
				}
				break;

			case 'E':
				return $this->getVar('content', 'e');
				break;

			default:
				return $this->getVar('content', 'n');
				break;
		}
	}

	/**
	 * (HTML-) form for setting the options of the block
	 *
	 * @return string|false $edit_form is HTML for the form, FALSE if no options defined for this block
	 **/
	public function getOptions() {
		if ( $this->getVar('block_type') != 'C' ) {
			$edit_func = $this->getVar('edit_func');
			if ( !$edit_func ) {
				return false;
			}
			icms_loadLanguageFile($this->getVar('dirname'), 'blocks');
			include_once ICMS_ROOT_PATH . '/modules/'.$this->getVar('dirname') . '/blocks/' . $this->getVar('func_file');
			$options = explode('|', $this->getVar('options'));
			$edit_form = $edit_func($options);
			if ( !$edit_form ) {
				return false;
			}
			return $edit_form;
		} else {
			return false;
		}
	}

	/**
	 * For backward compatibility
	 *
	 * @todo improve with IPF
	 * @return unknown
	 */
	public function isCustom() {
		if ( $this->getVar("block_type") == "C" || $this->getVar("block_type") == "E" ) {
			return true;
		}
		return false;
	}

	/**
	 * Builds the block
	 *
	 * @return array $block the block information array
	 *
	 * @todo improve with IPF
	 */
	public function buildBlock(){
		global $icmsConfig, $xoopsOption;
		$block = array();
		// M for module block, S for system block C for Custom
		if ( !$this->isCustom() ) {
			// get block display function
			$show_func = $this->getVar('show_func');
			if ( !$show_func ) {
				return false;
			}
			// Must get lang files before execution of the function.
			if ( file_exists(ICMS_ROOT_PATH . "/modules/" . $this->getVar('dirname') . "/blocks/" . $this->getVar('func_file')) ) {
				icms_loadLanguageFile($this->getVar('dirname'), 'blocks');
				include_once ICMS_ROOT_PATH . "/modules/" . $this->getVar('dirname') . "/blocks/" . $this->getVar('func_file');
				$options = explode("|", $this->getVar("options"));
				if ( function_exists($show_func) ) {
					// execute the function
					$block = $show_func($options);
					if ( !$block ) {
						return false;
					}
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			// it is a custom block, so just return the contents
			$block['content'] = $this->getContent("S",$this->getVar("c_type"));
			if ( empty($block['content']) ) {
				return false;
			}
		}
		return $block;
	}

	/**
	 * Aligns the content of a block
	 * If position is 0, content in DB is positioned
	 * before the original content
	 * If position is 1, content in DB is positioned
	 * after the original content
	 *
	 * @todo remove this? It is not found anywhere else in the core
	 */
	public function buildContent($position,$content="",$contentdb=""){
		if ( $position == 0 ) {
			$ret = $contentdb.$content;
		} elseif ( $position == 1 ) {
			$ret = $content.$contentdb;
		}
		return $ret;
	}

	/**
	 * Build Block Title
	 *
	 * @param string $originaltitle
	 * @param string $newtitle
	 * @return string
	 *
	 * @todo remove this? it is not found anywhere else in the core
	 */
	public function buildTitle($originaltitle, $newtitle=""){
		if ( $newtitle != "" ) {
			$ret = $newtitle;
		} else {
			$ret = $originaltitle;
		}
		return $ret;
	}

	/**
	 * Get Block Positions
	 *
	 * @param boolean $full
	 * @return array
	 *
	 * @deprecated use the handler method, instead
	 */
	public function getBlockPositions($full=false){
		icms_deprecated('icms_block_Handler->getBlockPositions');
		return $this->handler->getBlockPositions($full);
	}

	/**
	 * Load a Block
	 *
	 * @param integer $id
	 *
	 * @deprecated use the handler method, instead
	 */
	public function load($id){
		icms_deprecated('icms_block_Handler->getObject');
		$this->$this->handler->getObject($id);
	}

	/**
	 * Save this block
	 *
	 * @return integer
	 *
	 * @deprecated use the handler method 'insert', instead
	 */
	public function store() {
		icms_deprecated('icms_block_Handler->insert');
		$this->handler->insert( $this );
		return $this->getVar('bid');
	}

	/**
	 * Delete this block
	 *
	 * @return boolean
	 *
	 * @deprecated use the handler method, instead
	 */
	public function delete(){
		icms_deprecated('icms_block_Handler->delete');
		return $this->handler->delete($this);
	}

	/**
	 * Get all the blocks that match the supplied parameters
	 *
	 * @param $side   0: sideblock - left
	 *		1: sideblock - right
	 *		2: sideblock - left and right
	 *		3: centerblock - left
	 *		4: centerblock - right
	 *		5: centerblock - center
	 *		6: centerblock - left, right, center
	 * @param $groupid   groupid (can be an array)
	 * @param $visible   0: not visible 1: visible
	 * @param $orderby   order of the blocks
	 * @return array of block objects
	 *
	 * @deprecated use the handler method, instead
	 */
	public function getAllBlocksByGroup($groupid, $asobject=true, $side=null, $visible=null, $orderby="b.weight,b.bid", $isactive=1){
		icms_deprecated('icms_block_Handler->getAllBlocksByGroup');
		return $this->handler->getAllBlocksByGroup($groupid, $asobject, $side, $visible, $orderby, $isactive);
	}

	/**
	 * Get All Blocks
	 *
	 * @since XOOPS
	 *
	 * @param unknown_type $rettype
	 * @param unknown_type $side
	 * @param unknown_type $visible
	 * @param unknown_type $orderby
	 * @param unknown_type $isactive
	 * @return unknown
	 *
	 * @deprecated use the handler method, instead
	 */
	public function getAllBlocks( $rettype = "object", $side = null, $visible = null, $orderby = "side,weight,bid", $isactive = 1 ){
		icms_deprecated('icms_block_Handler->getAllBlocks');
		return $this->handler->getAllBlocks($rettype, $side, $visible, $orderby, $isactive);
	}

	/**
	 * Get Block By Module ID (mid)
	 *
	 * @since XOOPS
	 *
	 * @param integer $moduleid
	 * @param boolean $asobject
	 * @return unknown
	 *
	 * @deprecated use the handler method, instead
	 */
	public function getByModule($moduleid, $asobject=true){
		icms_deprecated('icms_block_Handler->getByModule');
		return $this->handler->getByModule( $moduleid, $asobject );
	}

	/**
	 * Get All Blocks By Group and Module
	 *
	 * @since XOOPS
	 *
	 * @param integer $groupid
	 * @param integer $module_id
	 * @param boolean $toponlyblock
	 * @param boolean $visible
	 * @param string $orderby
	 * @param booelan $isactive
	 * @return unknown
	 *
	 * @deprecated use the handler method, instead
	 *
	 */
	public function getAllByGroupModule($groupid, $module_id='0-0', $toponlyblock=false, $visible=null, $orderby='b.weight,b.bid', $isactive=1){
		icms_deprecated('icms_block_Handler->getAllByGroupModule');
		return $this->handler->getAllByGroupModule($groupid, $module_id, $toponlyblock, $visible, $orderby, $isactive);
	}

	/**
	 * Get Non Grouped Blocks
	 *
	 * @param integer $module_id
	 * @param unknown_type $toponlyblock
	 * @param boolean $visible
	 * @param string $orderby
	 * @param boolean $isactive
	 * @return array
	 *
	 * @deprecated use the handler method, instead
	 */
	public function getNonGroupedBlocks($module_id=0, $toponlyblock=false, $visible=null, $orderby='b.weight,b.bid', $isactive=1){
		icms_deprecated('icms_block_Handler->getNonGroupedBlocks');
		return $this->handler->getNonGroupedBlocks($module_id, $toponlyblock, $visible, $orderby, $isactive);
	}

	/**
	 * Count Similar Blocks
	 *
	 * This method has been implemented in the block handler, because is was thought as usefull.
	 *
	 * @since XOOPS
	 *
	 * @param integer $moduleId
	 * @param integer $funcNum
	 * @param string $showFunc
	 *
	 * @return integer
	 *
	 * @deprecated use the handler method, instead
	 */
	public function countSimilarBlocks($moduleId, $funcNum, $showFunc = null) {
		icms_deprecated('icms_block_Handler->getCountSimilarBlocks');
		return $this->handler->getCountSimilarBlocks($moduleId, $funcNum, $showFunc);
	}

}

/**
 * @deprecated use icms_block_Object instead
 * @todo Remove in version 1.4 - all instances have been removed from the core
 */

class IcmsBlock extends icms_block_Object {
	public function __construct(&$db) {
		parent::__construct(&$db);
		$this->setErrors = icms_deprecated('icms_block_Object');
	}

}