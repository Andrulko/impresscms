<?php
/**
 * Upgrader from 2.3 to 2.0.x
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package     upgrader
 * @since       1.1
 * @author		Sina Asghari <pesian_stranger@users.sourceforge.net>
 * @version     $Id$
 */

class upgrade_230
{
	var $usedFiles = array ();
    var $tasks = array('config');
	var $updater;
	
	function __construct() {
		$this->updater = XoopsDatabaseFactory::getDatabaseUpdater();
	}

    function isApplied()
    {
        if (!isset($_SESSION[__CLASS__]) || !is_array($_SESSION[__CLASS__])) {
            $_SESSION[__CLASS__] = array();
        }
        foreach ($this->tasks as $task) {
            if (!in_array($task, $_SESSION[__CLASS__])) {
                if (!$res = $this->{"check_{$task}"}()) {
                    $_SESSION[__CLASS__][] = $task;
                }
            }
        }
        return empty($_SESSION[__CLASS__]) ? true : false;
    }
    function apply()
    {
        $tasks = $_SESSION[__CLASS__];
        foreach ($tasks as $task) {
            $res = $this->{"apply_{$task}"}();
            if (!$res) return false;
            array_shift($_SESSION[__CLASS__]);
        }
        return true;
    }
    
    
    /**
     * Check if cpanel config already exists
     *
     */
    function check_config()
    {
        $sql = "SHOW TABLES LIKE '" . $GLOBALS['xoopsDB']->prefix("cache_model") . "'";
        $result = $GLOBALS['xoopsDB']->queryF($sql);
        if (!$result) return true;
        if ($GLOBALS['xoopsDB']->getRowsNum($result) > 0) return false;
        return true;
    }
/*    function check_config()
    {
		$db = $GLOBALS['xoopsDB'];
		if (getDbValue($db, 'config', 'conf_name', ' conf_name="cpanel"') != 1) {
			return true;
		}
    }

    /**
     * Check if cache_model table already exists
     *
     * SHOW TABLES requires specific privilege thus the tricky SELECT COUNT query is used
     */
    function apply_config()
    {
		$db = $GLOBALS['xoopsDB'];
        $result = true;
        // remove profile_search configuration item
        $db->queryF("DELETE FROM `" . $db->prefix('config') . "` WHERE conf_name='cpanel'");
        $db->queryF("DELETE FROM `" . $db->prefix('config') . "` WHERE conf_name='welcome_type'");
        $db->queryF("DELETE FROM `" . $db->prefix('configoption') . "` WHERE confop_name='_NO'");
        $db->queryF("DELETE FROM `" . $db->prefix('configoption') . "` WHERE confop_name='_MD_AM_WELCOMETYPE_EMAIL'");
        $db->queryF("DELETE FROM `" . $db->prefix('configoption') . "` WHERE confop_name='_MD_AM_WELCOMETYPE_PM'");
        $db->queryF("DELETE FROM `" . $db->prefix('configoption') . "` WHERE confop_name='_MD_AM_WELCOMETYPE_BOTH'");
         $db->queryF("DROP TABLE " . $db->prefix("cache_model"));
   }
}

$upg = new upgrade_230();
return $upg;

?>
