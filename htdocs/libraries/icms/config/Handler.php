<?php
/**
 * Manage configuration items
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @package		core
 * @subpackage	config
 * @since		XOOPS
 * @author		Kazumi Ono (aka onokazo)
 * @author		http://www.xoops.org The XOOPS Project
 * @version		$Id: config.php 19431 2010-06-16 20:46:34Z david-sf $
 */

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

require_once XOOPS_ROOT_PATH.'/kernel/configoption.php';
require_once XOOPS_ROOT_PATH.'/kernel/configitem.php';

/**
 * Configuration handling class.
 * This class acts as an interface for handling general configurations of XOOPS
 * and its modules.
 *
 * @package 	kernel
 * @subpackage 	config
 * @author  Kazumi Ono <webmaster@myweb.ne.jp>
 * @todo    Tests that need to be made:
 *          - error handling
 * @access  public
 */
class icms_config_Handler {

	/**
	 * holds reference to config item handler(DAO) class
	 *
	 * @var     object
	 * @access	private
	 */
	private $_cHandler;

	/**
	 * holds reference to config option handler(DAO) class
	 *
	 * @var	    object
	 * @access	private
	 */
	private $_oHandler;

	/**
	 * holds an array of cached references to config value arrays,
	 *  indexed on module id and category id
	 *
	 * @var     array
	 * @access  private
	 */
	private $_cachedConfigs = array();

	/**
	 * Constructor
	 *
	 * @param	object  &$db    reference to database object
	 */
	public function icms_config_Handler(&$db) {
		$this->_cHandler = new XoopsConfigItemHandler($db);
		$this->_oHandler = new XoopsConfigOptionHandler($db);
	}

	/**
	 * Create a config
	 *
	 * @see     XoopsConfigItem
	 * @return	object  reference to the new {@link XoopsConfigItem}
	 */
	public function &createConfig() {
		$instance =& $this->_cHandler->create();
		return $instance;
	}

	/**
	 * Get a config
	 *
	 * @param	int     $id             ID of the config
	 * @param	bool    $withoptions    load the config's options now?
	 * @return	object  reference to the {@link XoopsConfig}
	 */
	public function &getConfig($id, $withoptions = false) {
		$config =& $this->_cHandler->get($id);
		if ($withoptions == true) {
			$config->setConfOptions($this->getConfigOptions(new icms_core_Criteria('conf_id', $id)));
		}
		return $config;
	}

	/**
	 * insert a new config in the database
	 *
	 * @param	object  &$config    reference to the {@link XoopsConfigItem}
	 * @return	true|false if inserting config succeeded or not
	 */
	public function insertConfig(&$config) {
		if (!$this->_cHandler->insert($config)) {
			return false;
		}
		$options =& $config->getConfOptions();
		$count = count($options);
		$conf_id = $config->getVar('conf_id');
		for ($i = 0; $i < $count; $i++) {
			$options[$i]->setVar('conf_id', $conf_id);
			if (!$this->_oHandler->insert($options[$i])) {
				foreach($options[$i]->getErrors() as $msg){
					$config->setErrors($msg);
				}// END foreach
			}// END if...
		}// END for... next

		if (!empty($this->_cachedConfigs[$config->getVar('conf_modid')][$config->getVar('conf_catid')])) {
			unset ($this->_cachedConfigs[$config->getVar('conf_modid')][$config->getVar('conf_catid')]);
		}// END if
		return true;
	}

	/**
	 * Delete a config from the database
	 *
	 * @param	object  &$config    reference to a {@link XoopsConfigItem}
	 * @return	true|false if deleting config item succeeded or not
	 */
	public function deleteConfig(&$config) {
		if (!$this->_cHandler->delete($config)) {
			return false;
		}
		$options =& $config->getConfOptions();
		$count = count($options);
		if ($count == 0) {
			$options = $this->getConfigOptions(new icms_core_Criteria('conf_id', $config->getVar('conf_id')));
			$count = count($options);
		}
		if (is_array($options) && $count > 0) {
			for ($i = 0; $i < $count; $i++) {
				$this->_oHandler->delete($options[$i]);
			}
		}
		if (!empty($this->_cachedConfigs[$config->getVar('conf_modid')][$config->getVar('conf_catid')])) {
			unset ($this->_cachedConfigs[$config->getVar('conf_modid')][$config->getVar('conf_catid')]);
		}
		return true;
	}

	/**
	 * get one or more Configs
	 *
	 * @param	object  $criteria       {@link icms_core_CriteriaElement}
	 * @param	bool    $id_as_key      Use the configs' ID as keys?
	 * @param	bool    $with_options   get the options now?
	 *
	 * @return	array   Array of {@link XoopsConfigItem} objects
	 */
	public function getConfigs($criteria = null, $id_as_key = false, $with_options = false) {
		return $this->_cHandler->getObjects($criteria, $id_as_key);
	}

	/**
	 * Count some configs
	 *
	 * @param	object  $criteria   {@link icms_core_CriteriaElement}
	 * @return	int count result
	 */
	public function getConfigCount($criteria = null) {
		return $this->_cHandler->getCount($criteria);
	}

	/**
	 * Get configs from a certain category
	 *
	 * @param	int $category   ID of a category
	 * @param	int $module     ID of a module
	 *
	 * @return	array   array of {@link XoopsConfig}s
	 */
	public function &getConfigsByCat($category, $module = 0) {
		static $_cachedConfigs;

		if (is_array($category)) {
			$criteria = new icms_core_CriteriaCompo(new icms_core_Criteria('conf_modid', (int) ($module)));
			$criteria->add(new icms_core_Criteria('conf_catid', '(' . implode(',', $category) . ')', 'IN'));
			$configs = $this->getConfigs($criteria, true);
			if (is_array($configs)) {
				foreach (array_keys($configs) as $i) {
					$ret[$configs[$i]->getVar('conf_catid')][$configs[$i]->getVar('conf_name')] = $configs[$i]->getConfValueForOutput();
				}
				foreach ($ret as $key => $value) {
					$_cachedConfigs[$module][$key] = $value;
				}
				return $ret;
			}
		} else {
			if (!empty($_cachedConfigs[$module][$category])) return $_cachedConfigs[$module][$category];

			$criteria = new icms_core_CriteriaCompo(new icms_core_Criteria('conf_modid', (int) ($module)));
			if (!empty($category)) {
				$criteria->add(new icms_core_Criteria('conf_catid', (int) ($category)));
			}
			$configs = $this->getConfigs($criteria, true);
			if (is_array($configs)) {
				foreach (array_keys($configs) as $i) {
					$ret[$configs[$i]->getVar('conf_name')] = $configs[$i]->getConfValueForOutput();
				}
			}
			$_cachedConfigs[$module][$category] = $ret;
			return $_cachedConfigs[$module][$category];
		}
	}

	/**
	 * Make a new {@link XoopsConfigOption}
	 *
	 * @return	object  {@link XoopsConfigOption}
	 */
	public function &createConfigOption() {
		$inst =& $this->_oHandler->create();
		return $inst;
	}

	/**
	 * Get a {@link XoopsConfigOption}
	 *
	 * @param	int $id ID of the config option
	 *
	 * @return	object  {@link XoopsConfigOption}
	 */
	public function &getConfigOption($id) {
		$inst =& $this->_oHandler->get($id);
		return $inst;
	}

	/**
	 * Get one or more {@link XoopsConfigOption}s
	 *
	 * @param	object  $criteria   {@link icms_core_CriteriaElement}
	 * @param	bool    $id_as_key  Use IDs as keys in the array?
	 *
	 * @return	array   Array of {@link XoopsConfigOption}s
	 */
	public function getConfigOptions($criteria = null, $id_as_key = false) {
		return $this->_oHandler->getObjects($criteria, $id_as_key);
	}

	/**
	 * Count some {@link XoopsConfigOption}s
	 *
	 * @param	object  $criteria   {@link icms_core_CriteriaElement}
	 *
	 * @return	int     Count of {@link XoopsConfigOption}s matching $criteria
	 */
	public function getConfigOptionsCount($criteria = null) {
		return $this->_oHandler->getCount($criteria);
	}

	/**
	 * Get a list of configs
	 *
	 * @param	int $conf_modid ID of the modules
	 * @param	int $conf_catid ID of the category
	 *
	 * @return	array   Associative array of name=>value pairs.
	 */
	public function getConfigList($conf_modid, $conf_catid = 0) {
		if (!empty($this->_cachedConfigs[$conf_modid][$conf_catid])) {
			return $this->_cachedConfigs[$conf_modid][$conf_catid];
		} else {
			$criteria = new icms_core_CriteriaCompo(new icms_core_Criteria('conf_modid', $conf_modid));
			if (empty($conf_catid)) {
				$criteria->add(new icms_core_Criteria('conf_catid', $conf_catid));
			}
			$configs =& $this->_cHandler->getObjects($criteria);
			$confcount = count($configs);
			$ret = array();
			for ($i = 0; $i < $confcount; $i++) {
				$ret[$configs[$i]->getVar('conf_name')] = $configs[$i]->getConfValueForOutput();
			}
			$this->_cachedConfigs[$conf_modid][$conf_catid] =& $ret;
			return $ret;
		}
	}
}

/**
 * @deprecated	Use icms_config_Handler instead
 * @todo		Remove this in version 1.4
 */
class XoopsConfigHandler extends icms_config_Handler {
	public function __construct() {
		parent::__construct();
	}
}

