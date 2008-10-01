<?php
/**
* Upgrade script of ImpressCMS 1.0 to 1.0
*
* @copyright   The XOOPS project http://www.xoops.org/
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		upgrader
* @since		1.1
* @author	   Sina Asghari <pesian_stranger@users.sourceforge.net>
* @author      Taiwen Jiang <phppp@users.sourceforge.net>
* @version		$Id$
*/

class upgrade_impcms06 {
	
	var $usedFiles = array ();
    var $tasks = array(
    'table1', 'table2', 'table3', 'table4', 'conf', 
    'block1', 'block2', 'block3', 'block4', 
    'dbversion', 'db'
    , 'salt'
    );
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

	function cleaning_write_folders() {
		$dir = array();
		$dir['templates_c'] = XOOPS_ROOT_PATH."/templates_c/";
		$dir['cache'] = XOOPS_ROOT_PATH."/cache/";

		foreach ($dir as $d)
		{
			$dd = opendir($d);
			while($file = readdir($dd))
			{
		 		if(is_file($d.$file) && ($file != 'index.html' && $file != 'php.ini' && $file != '.htaccess'))
				{
		  			unlink($d.$file);
				}
			}
			closedir($dd);
		}
			return true;
	}

    function check_table1()
    {
		$table = new IcmsDatabasetable('modules');
	    return $table->fieldExists('dbversion');
            }

 	function apply_table1() {
		// Create table icmspage
		$table = new IcmsDatabasetable('icmspage');
		if (!$table->exists()) {
			$table->setStructure("page_id mediumint(8) unsigned NOT NULL auto_increment,
					  page_moduleid mediumint(8) unsigned NOT NULL default '1',
					  page_title varchar(255) NOT NULL default '',
					  page_url varchar(255) NOT NULL default '',
					  page_status tinyint(1) unsigned NOT NULL default '1',
					  PRIMARY KEY  (page_id)");
    	return $this->updater->updateTable($table, true);
		}
		unset ($table);

	}
    function check_table2()
    {
		$table = new IcmsDatabasetable('modules');
	    return $table->fieldExists('dbversion');
            }

 	function apply_table2() {
		// Create table icmscontent
		$table = new IcmsDatabasetable('icmscontent');
		if (!$table->exists()) {
			$table->setStructure("`content_id` mediumint(8) unsigned NOT NULL auto_increment,
  				`content_catid` mediumint(8) unsigned NOT NULL default '1',
				  `content_supid` mediumint(8) unsigned NOT NULL default '0',
				  `content_uid` mediumint(5) NOT NULL default '1',
				  `content_title` varchar(255) NOT NULL default '',
				  `content_menu` varchar(100) default NULL,
				  `content_body` text,
				  `content_css` text,
				  `content_visibility` int(10) NOT NULL default '3',
				  `content_created` int(10) NOT NULL default '0',
				  `content_updated` int(10) NOT NULL default '0',
				  `content_weight` smallint(5) unsigned NOT NULL default '0',
				  `content_reads` int(11) NOT NULL default '0',
				  `content_status` tinyint(1) unsigned NOT NULL default '0',
				  PRIMARY KEY  (`content_id`)
				");
    	return $this->updater->updateTable($table, true);
		}
		unset ($table);
	}
    function check_table3()
    {
		$table = new IcmsDatabasetable('modules');
	    return $table->fieldExists('dbversion');
            }

 	function apply_table3() {
		// Create table invites
		$table = new IcmsDatabasetable('invites');
		if (!$table->exists()) {
			$table->setStructure("`invite_id` mediumint(8) unsigned NOT NULL auto_increment,
						  `from_id` mediumint(8) unsigned NOT NULL default '0',
						  `invite_to` varchar(255) NOT NULL default '',
						  `invite_code` varchar(8) NOT NULL default '',
						  `invite_date` int(10) unsigned NOT NULL default '0',
						  `view_date` int(10) unsigned NOT NULL default '0',
						  `register_id` mediumint(8) unsigned NOT NULL default '0',
						  `extra_info` text NOT NULL,
						  PRIMARY KEY  (`invite_id`),
						  KEY `invite_code` (`invite_code`),
						  KEY `register_id` (`register_id`)
				");
    	return $this->updater->updateTable($table, true);
		}
		unset ($table);

	}
    function check_table4()
    {
		$table = new IcmsDatabasetable('modules');
	    return $table->fieldExists('dbversion');
            }

 	function apply_table4() {
		// Create table system_customtag
		$table = new IcmsDatabasetable('system_customtag');
		if (!$table->exists()) {
			$table->setStructure("`customtagid` int(11) unsigned NOT NULL auto_increment,
					  `name` varchar(255) NOT NULL default '',
					  `description` text NOT NULL default '',
					  `content` text NOT NULL default '',
					  `language` varchar(100) NOT NULL default '',
					  `customtag_type` tinyint(1) NOT NULL default 0,
					  PRIMARY KEY (`customtagid`)");
    	return $this->updater->updateTable($table, true);
		}
		unset ($table);
	}

    function check_block1()
    {
		$db = $GLOBALS['xoopsDB'];
		if (getDbValue($db, 'newblocks', 'bid', ' show_func="b_social_bookmarks"') != 0) {
			return true;
		}
            }

 	function apply_block1() {
		$db = $GLOBALS['xoopsDB'];
		$db->queryF(" INSERT INTO " . $db->prefix("newblocks") . " VALUES ('', 1, 0, '', 'Share this page!', 'Share this page!', '', 1, 0, 0, 'S', 'H', 1, 'system', 'social_bookmarks.php', 'b_social_bookmarks', '', 'system_block_socialbookmark.html', 0, " . time() . ")");
		$new_block_id = $db->getInsertId();
		$db->queryF(" UPDATE " . $db->prefix("newblocks") . " SET func_num = " . $new_block_id . " WHERE bid=" . $new_block_id);
		$db->queryF(" INSERT INTO " . $db->prefix("tplfile") . " VALUES ('', " . $new_block_id . ", 'system', 'default', 'system_block_socialbookmark.html', 'Displays image links to bookmark pages in sharing websites', " . time() . ", " . time() . ", 'block');");
		$new_tplfile_id = $db->getInsertId();
		$new_tpl_source = '<table cellspacing="0" class="outer">\n  <tr>\n    <td class="odd">\n		<{$block.bookmark}>\n	</td>\n  </tr>\n</table>';
		$db->queryF(" INSERT INTO " . $db->prefix("tplsource") . " VALUES (" . $new_tplfile_id . ", '" . $new_tpl_source . "');");
		$db->queryF(" INSERT INTO " . $db->prefix("block_module_link") . " VALUES (" . $new_block_id . ", 0);");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 1, " . $new_block_id . ", 1, 'block_read');");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 2, " . $new_block_id . ", 1, 'block_read');");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 3, " . $new_block_id . ", 1, 'block_read');");
		return true;
	}
    function check_block2()
    {
		$db = $GLOBALS['xoopsDB'];
		if (getDbValue($db, 'newblocks', 'bid', ' show_func="b_content_show"') != 0) {
			return true;
		}
            }

 	function apply_block2() {
		$db = $GLOBALS['xoopsDB'];
		$db->queryF(" INSERT INTO " . $db->prefix("newblocks") . " VALUES ('', 1, 0, '1|1|1|1', 'Content', 'Content', '', 1, 0, 0, 'S', 'H', 1, 'system', 'content_blocks.php', 'b_content_show', 'b_content_edits', 'system_block_content.html', 0, " . time() . ")");
		$new_block_id = $db->getInsertId();
		$db->queryF(" UPDATE " . $db->prefix("newblocks") . " SET func_num = " . $new_block_id . " WHERE bid=" . $new_block_id);
		$db->queryF(" INSERT INTO " . $db->prefix("tplfile") . " VALUES ('', " . $new_block_id . ", 'system', 'default', 'system_block_content.html', 'Show content page', " . time() . ", " . time() . ", 'block');");
		$new_tplfile_id = $db->getInsertId();
		$new_tpl_source = '<link rel="stylesheet" type="text/css" media="all" title="Style sheet" href="<{$xoops_url}>/modules/system/admin/content/style.css" />\r\n<style type="text/css">\r\n  <{$content_css}>\r\n</style>\r\n<div id="impress_content">\r\n  <{if $block.showNav}>\r\n    <div id="nav"><{$block.nav}></div>\r\n  <{/if}>  \r\n  <div id="title">\r\n    <{if $block.isAdmin}><div class="title_admlinks"><{$block.content_admlinks}></div><{/if}>\r\n    <h1 class="title_body"><{$block.content_title}></h1>\r\n    <{if $block.show_pinfo}><h2 class="title_info"><{$block.content_tinfo}></h2><{/if}>\r\n  </div>\r\n  <div class="content">\r\n    <div class="content_body"><{$block.content_body}></div>\r\n  </div>\r\n  <{if $block.showSubs}>\r\n    <hr style="margin:20px;" />\r\n    <div id="subs">\r\n      <div class="title_subs"><{$block.subs_label}></div>\r\n      <{foreach item=sub from=$block.content_subs}>\r\n        <div style="margin:5px;" class="<{cycle values="even,odd"}>">\r\n          <h3 style="margin:0;"><a href="<{$sub.link}>"><{$sub.titulo}></a></h3>\r\n          <em><{$sub.teaser}></em>\r\n        </div>\r\n      <{/foreach}>\r\n    </div>\r\n  <{/if}>  \r\n</div>';
		$db->queryF(" INSERT INTO " . $db->prefix("tplsource") . " VALUES (" . $new_tplfile_id . ", '" . $new_tpl_source . "');");
		$db->queryF(" INSERT INTO " . $db->prefix("block_module_link") . " VALUES (" . $new_block_id . ", 0);");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 1, " . $new_block_id . ", 1, 'block_read');");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 2, " . $new_block_id . ", 1, 'block_read');");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 3, " . $new_block_id . ", 1, 'block_read');");
		return true;
	}
    function check_block3()
    {
		$db = $GLOBALS['xoopsDB'];
		if (getDbValue($db, 'newblocks', 'bid', ' show_func="b_content_menu_show"') != 0) {
			return true;
		}
            }

 	function apply_block3() {
		$db = $GLOBALS['xoopsDB'];
		$db->queryF(" INSERT INTO " . $db->prefix("newblocks") . " VALUES ('', 1, 0, 'content_weight|ASC|1|#F2E2A0', 'Content Menu', 'Content Menu', '', 1, 0, 0, 'S', 'H', 1, 'system', 'content_blocks.php', 'b_content_menu_show', 'b_content_menu_edit', 'system_block_contentmenu.html', 0, " . time() . ")");
		$new_block_id = $db->getInsertId();
		$db->queryF(" UPDATE " . $db->prefix("newblocks") . " SET func_num = " . $new_block_id . " WHERE bid=" . $new_block_id);
		$db->queryF(" INSERT INTO " . $db->prefix("tplfile") . " VALUES ('', " . $new_block_id . ", 'system', 'default', 'system_block_contentmenu.html', 'Menu of content pages and categories', " . time() . ", " . time() . ", 'block');");
		$new_tplfile_id = $db->getInsertId();
		$new_tpl_source = '<script src="<{$xoops_url}>/modules/system/admin/content/menu.js" type="text/javascript"></script>\r\n<link rel="stylesheet" type="text/css" media="all" title="Style sheet" href="<{$xoops_url}>/modules/system/admin/content/menu.css" />\r\n<div id="mainmenu">\r\n  <ul id="primary-nav">\r\n    <{foreach from=$block.menu key=key item=menu}> \r\n      <li<{if $block.showsubs && $menu.hassubs}> class="menuparent"<{/if}>>\r\n        <a class="menuMain" href="<{$xoops_url}>/content.php?page=<{$menu.menu}>"><{$menu.title}></a>\r\n        <{if $block.showsubs && $menu.hassubs}><{includeq file="db:blocks/system_block_contentmenu_structure.html" menus=$menu.subs}><{/if}> \r\n      </li> \r\n    <{/foreach}>\r\n  </ul>\r\n</div>';
		$db->queryF(" INSERT INTO " . $db->prefix("tplsource") . " VALUES (" . $new_tplfile_id . ", '" . $new_tpl_source . "');");
		$db->queryF(" INSERT INTO " . $db->prefix("block_module_link") . " VALUES (" . $new_block_id . ", 0);");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 1, " . $new_block_id . ", 1, 'block_read');");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 2, " . $new_block_id . ", 1, 'block_read');");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 3, " . $new_block_id . ", 1, 'block_read');");
		return true;
	}
    function check_block4()
    {
		$db = $GLOBALS['xoopsDB'];
		if (getDbValue($db, 'newblocks', 'bid', ' show_func="b_content_relmenu_show"') != 0) {
			return true;
		}
            }

 	function apply_block4() {
		$db = $GLOBALS['xoopsDB'];
		$db->queryF(" INSERT INTO " . $db->prefix("newblocks") . " VALUES ('', 1, 0, 'content_weight|ASC|1', 'Related Content', 'Related Content', '', 1, 0, 0, 'S', 'H', 1, 'system', 'content_blocks.php', 'b_content_relmenu_show', 'b_content_relmenu_edit', 'system_block_contentmenu.html', 0, " . time() . ")");
		$new_block_id = $db->getInsertId();
		$db->queryF(" UPDATE " . $db->prefix("newblocks") . " SET func_num = " . $new_block_id . " WHERE bid=" . $new_block_id);
		$db->queryF(" INSERT INTO " . $db->prefix("tplfile") . " VALUES ('', " . $new_block_id . ", 'system', 'default', 'system_block_contentmenu.html', 'Menu of content pages and categories', " . time() . ", " . time() . ", 'block');");
		$new_tplfile_id = $db->getInsertId();
		$new_tpl_source = '<script src="<{$xoops_url}>/modules/system/admin/content/menu.js" type="text/javascript"></script>\r\n<link rel="stylesheet" type="text/css" media="all" title="Style sheet" href="<{$xoops_url}>/modules/system/admin/content/menu.css" />\r\n<div id="mainmenu">\r\n  <ul id="primary-nav">\r\n    <{foreach from=$block.menu key=key item=menu}> \r\n      <li<{if $block.showsubs && $menu.hassubs}> class="menuparent"<{/if}>>\r\n        <a class="menuMain" href="<{$xoops_url}>/content.php?page=<{$menu.menu}>"><{$menu.title}></a>\r\n        <{if $block.showsubs && $menu.hassubs}><{includeq file="db:blocks/system_block_contentmenu_structure.html" menus=$menu.subs}><{/if}> \r\n      </li> \r\n    <{/foreach}>\r\n  </ul>\r\n</div>';
		$db->queryF(" INSERT INTO " . $db->prefix("tplsource") . " VALUES (" . $new_tplfile_id . ", '" . $new_tpl_source . "');");
		$db->queryF(" INSERT INTO " . $db->prefix("block_module_link") . " VALUES (" . $new_block_id . ", 0);");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 1, " . $new_block_id . ", 1, 'block_read');");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 2, " . $new_block_id . ", 1, 'block_read');");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 3, " . $new_block_id . ", 1, 'block_read');");
		return ($this->cleaning_write_folders());
	}
    function check_conf()
    {
		$table = new IcmsDatabasetable('modules');
	    return $table->fieldExists('dbversion');
            }

 	function apply_conf() {
		$db = $GLOBALS['xoopsDB'];
		if (getDbValue($db,'configcategory','confcat_id',' confcat_name="_MD_AM_CONTMANAGER"') != 0){return true;}
		$db->queryF(" INSERT INTO " . $db->prefix("configcategory") . " (confcat_id,confcat_name) VALUES ('9','_MD_AM_CONTMANAGER')");
		if (getDbValue($db,'configcategory','confcat_id',' confcat_name="_MD_AM_PERSON"') != 0){return true;}
		$db->queryF(" INSERT INTO " . $db->prefix("configcategory") . " (confcat_id,confcat_name) VALUES ('10','_MD_AM_PERSON')");
    
    $db->queryF("UPDATE ". $db->prefix('config') . "SET conf_formtype = 'textsarea' WHERE conf_name IN ('meta_keywords', 'meta_description')" );
      
        $passwordmeter_installed = false;
        $sql = "SELECT COUNT(*) FROM `" . $GLOBALS['xoopsDB']->prefix('config') . "` WHERE `conf_name` = 'pass_level'";
        if ( $result = $GLOBALS['xoopsDB']->queryF( $sql ) ) {
            list($count) = $GLOBALS['xoopsDB']->fetchRow($result);
            if ($count == 1) {
                $passwordmeter_installed = true;
            }
        }
        if (!$passwordmeter_installed) {
            $sql = "INSERT INTO " . $GLOBALS['xoopsDB']->prefix('config') . 
                    " (conf_id, conf_modid, conf_catid, conf_name, conf_title, conf_value, conf_desc, conf_formtype, conf_valuetype, conf_order) " .
                    " VALUES " .
                    " (NULL, 0, 2, 'pass_level', '_MD_AM_PASSLEVEL', '20', '_MD_AM_PASSLEVEL_DESC', 'select', 'int', 2)";

            if (!$GLOBALS['xoopsDB']->queryF( $sql )) {
                return false;
            }
            $config_id = $GLOBALS['xoopsDB']->getInsertId();
            
            $sql = "INSERT INTO " . $GLOBALS['xoopsDB']->prefix('configoption') . 
                    " (confop_id, confop_name, confop_value, conf_id)" .
                    " VALUES" .
                    " (NULL, '_MD_AM_PASSLEVEL1', '20', {$config_id})," .
                    " (NULL, '_MD_AM_PASSLEVEL2', '40', {$config_id})," .
                    " (NULL, '_MD_AM_PASSLEVEL3', '60', {$config_id})," .
                    " (NULL, '_MD_AM_PASSLEVEL4', '80', {$config_id})," .
                    " (NULL, '_MD_AM_PASSLEVEL5', '95', {$config_id})";
            if ( !$result = $GLOBALS['xoopsDB']->queryF( $sql ) ) {
                return false;
            }
        }
        
		$db = $GLOBALS['xoopsDB'];
        $db->queryF("DELETE FROM `" . $db->prefix('configoption') . "` WHERE confop_name='_MD_AM_USERACTV'");
        $db->queryF("DELETE FROM `" . $db->prefix('configoption') . "` WHERE confop_name='_MD_AM_AUTOACTV'");
        $db->queryF("DELETE FROM `" . $db->prefix('configoption') . "` WHERE confop_name='_MD_AM_ADMINACTV'");
        $db->queryF("DELETE FROM `" . $db->prefix('config') . "` WHERE conf_name = 'activation_type'");
		// Now let's re-insert data
        $registration_type = false;
        $sql = "SELECT COUNT(*) FROM `" . $GLOBALS['xoopsDB']->prefix('config') . "` WHERE `conf_name` = 'activation_type'";
        if ( $result = $GLOBALS['xoopsDB']->queryF( $sql ) ) {
            list($count) = $GLOBALS['xoopsDB']->fetchRow($result);
            if ($count == 1) {
                $registration_type = true;
            }
        }
        if (!$registration_type) {
            $sql = "INSERT INTO " . $GLOBALS['xoopsDB']->prefix('config') . 
                    " (conf_id, conf_modid, conf_catid, conf_name, conf_title, conf_value, conf_desc, conf_formtype, conf_valuetype, conf_order) " .
                    " VALUES " .
                    " (NULL, 0, 2, 'activation_type', '_MD_AM_ACTVTYPE', '0', '_MD_AM_ACTVTYPEDSC', 'select', 'int', 9)";

            if (!$GLOBALS['xoopsDB']->queryF( $sql )) {
                return false;
            }
            $config_id = $GLOBALS['xoopsDB']->getInsertId();
            
            $sql = "INSERT INTO " . $GLOBALS['xoopsDB']->prefix('configoption') . 
                    " (confop_id, confop_name, confop_value, conf_id)" .
                    " VALUES" .
                    " (NULL, '_MD_AM_USERACTV', '0', {$config_id})," .
                    " (NULL, '_MD_AM_AUTOACTV', '1', {$config_id})," .
                    " (NULL, '_MD_AM_ADMINACTV', '2', {$config_id})," .
                    " (NULL, '_MD_AM_REGINVITE', '3', {$config_id})";
            if ( !$result = $GLOBALS['xoopsDB']->queryF( $sql ) ) {
                return false;
            }
        }

			$sql = "ALTER TABLE " . $GLOBALS['xoopsDB']->prefix('users') . " MODIFY pass VARCHAR(255)";
			if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
				icms_debug('An error occurred while executing "' . $sql . '" - ' . $GLOBALS['xoopsDB']->error());
				return false;
			}

			$sql = "ALTER TABLE " . $GLOBALS['xoopsDB']->prefix('users') . " MODIFY user_sig text NOT NULL";
			if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
				icms_debug('An error occurred while executing "' . $sql . '" - ' . $GLOBALS['xoopsDB']->error());
				return false;
			}

			$sql = "ALTER TABLE `" . $GLOBALS['xoopsDB']->prefix('users') . "` ADD language varchar(100) NOT NULL default ''";
			if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
				icms_debug('An error occurred while executing "' . $sql . '" - ' . $GLOBALS['xoopsDB']->error());
				return false;
			}

			$sql = "ALTER TABLE `" . $GLOBALS['xoopsDB']->prefix('users') . "` ADD openid varchar(255) NOT NULL default ''";
			if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
				icms_debug('An error occurred while executing "' . $sql . '" - ' . $GLOBALS['xoopsDB']->error());
				return false;
			}

			$sql = "ALTER TABLE `" . $GLOBALS['xoopsDB']->prefix('users') . "` ADD salt varchar(255) NOT NULL default ''";
			if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
				icms_debug('An error occurred while executing "' . $sql . '" - ' . $GLOBALS['xoopsDB']->error());
				return false;
			}

			$sql = "ALTER TABLE `" . $GLOBALS['xoopsDB']->prefix('users') . "` ADD user_viewoid tinyint(1) unsigned NOT NULL default '0'";
			if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
				icms_debug('An error occurred while executing "' . $sql . '" - ' . $GLOBALS['xoopsDB']->error());
				return false;
			}

			$sql = "ALTER TABLE `" . $GLOBALS['xoopsDB']->prefix('users') . "` ADD pass_expired tinyint(1) NOT NULL default '0'";
			if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
				icms_debug('An error occurred while executing "' . $sql . '" - ' . $GLOBALS['xoopsDB']->error());
				return false;
			}

			$sql = "ALTER TABLE `" . $GLOBALS['xoopsDB']->prefix('users') . "` ADD enc_type tinyint(2) NOT NULL default '0'";
			if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
				icms_debug('An error occurred while executing "' . $sql . '" - ' . $GLOBALS['xoopsDB']->error());
				return false;
			}

			$sql = "ALTER TABLE `" . $GLOBALS['xoopsDB']->prefix('users') . "` DROP INDEX unamepass, ADD INDEX unamepass (uname (10), pass (10))";
			if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
				icms_debug('An error occurred while executing "' . $sql . '" - ' . $GLOBALS['xoopsDB']->error());
				return false;
			}
			
		$pw_salt_installed = false;
		$sql = "SELECT COUNT(*) FROM `" . $GLOBALS ['xoopsDB']->prefix ( 'config' ) . "` WHERE `conf_name` = 'enc_type'";
		if ($result = $GLOBALS ['xoopsDB']->queryF ( $sql ))
		{
			list ( $count ) = $GLOBALS ['xoopsDB']->fetchRow ( $result );
			if ($count == 1)
			{
				$pw_salt_installed = true;
			}
		}
        	
		if (!$pw_salt_installed)
		{
            		$sql = "INSERT INTO " . $GLOBALS['xoopsDB']->prefix('config') . 
                    	" (conf_id, conf_modid, conf_catid, conf_name, conf_title, conf_value, conf_desc, conf_formtype, conf_valuetype, conf_order) " .
                    	" VALUES " .
                    	" (NULL, 0, 2, 'enc_type', '_MD_AM_ENC_TYPE', '0', '_MD_AM_ENC_TYPEDSC', 'select', 'int', 50)";

            		if (!$GLOBALS['xoopsDB']->queryF( $sql ))
			{
                		return false;
            		}
            		$config_id = $GLOBALS['xoopsDB']->getInsertId();
            
            		$sql = "INSERT INTO " . $GLOBALS['xoopsDB']->prefix('configoption') . 
                    	" (confop_id, confop_name, confop_value, conf_id)" .
                    	" VALUES" .
                    	" (NULL, '_MD_AM_ENC_MD5', '0', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_SHA256', '1', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_SHA384', '2', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_SHA512', '3', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_RIPEMD128', '4', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_RIPEMD160', '5', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_WHIRLPOOL', '6', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_HAVAL1284', '7', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_HAVAL1604', '8', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_HAVAL1924', '9', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_HAVAL2244', '10', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_HAVAL2564', '11', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_HAVAL1285', '12', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_HAVAL1605', '13', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_HAVAL1925', '14', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_HAVAL2245', '15', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_HAVAL2565', '16', {$config_id})";
            		if (!$result = $GLOBALS['xoopsDB']->queryF($sql))
			{
                		return false;
            		}
        	}
        
        return $result;

	}

       function check_dbversion()
    {
		$table = new IcmsDatabasetable('modules');
	    return $table->fieldExists('dbversion');
            }

    	function apply_dbversion() {
    	// First let's create the dbversion field in the modules table
    	$table = new IcmsDatabasetable('modules');
    	$table->addNewField('dbversion', 'INT(11) unsigned NOT NULL DEFAULT 1');
    	return $this->updater->updateTable($table, true);
	}
		function check_db()
    {
        $lines = file( XOOPS_ROOT_PATH . '/mainfile.php' );
        foreach ( $lines as $line ) {
            if( preg_match( "/(define\(\s*)([\"'])(XOOPS_DB_CHARSET)\\2,\s*([\"'])([^\"']*?)\\4\s*\);/", $line ) ) {
                return true;
            }
        }
        return false;
            }
		function check_salt()
    {
        $lines = file( XOOPS_ROOT_PATH . '/mainfile.php' );
        foreach ( $lines as $line ) {
            if( preg_match( "/(define\(\s*)([\"'])(XOOPS_DB_SALT)\\2,\s*([\"'])([^\"']*?)\\4\s*\);/", $line ) ) {
                return true;
            }
        }
        return false;
            }

    function apply_salt()
    {
        return $this->update_configs('salt');
    }

    function apply_db()
    {
        return $this->update_configs('db');
    }

    function update_configs($task)
    {
        if (!$vars = $this->set_configs($task) ) {
            return false;
        }
        if ($task == "db" && !empty($vars["XOOPS_DB_COLLATION"])) {
            if ($pos = strpos($vars["XOOPS_DB_COLLATION"], "_")) {
                $vars["XOOPS_DB_CHARSET"] = substr($vars["XOOPS_DB_COLLATION"], 0, $pos);
                $this->convert_db($vars["XOOPS_DB_CHARSET"], $vars["XOOPS_DB_COLLATION"]);
            }
        }
        
        return $this->write_mainfile($vars);
    }
    
    function convert_db($charset, $collation)
    {
	    $sql = "ALTER DATABASE `" . XOOPS_DB_NAME . "` DEFAULT CHARACTER SET " . $GLOBALS["xoopsDB"]->quote($charset) . " COLLATE " . $GLOBALS["xoopsDB"]->quote($collation);
	    if ( !$GLOBALS["xoopsDB"]->queryF($sql) ) {
		    return false;
	    }
	    if ( !$result = $GLOBALS["xoopsDB"]->queryF("SHOW TABLES LIKE '" . XOOPS_DB_PREFIX . "\_%'") ) {
		    return false;
	    }
	    $tables = array();
	    while (list($table) = $GLOBALS["xoopsDB"]->fetchRow($result)) {
    	    $tables[] = $table;
		    //$GLOBALS["xoopsDB"]->queryF( "ALTER TABLE `{$table}` DEFAULT CHARACTER SET " . $GLOBALS["xoopsDB"]->quote($charset) . " COLLATE " . $GLOBALS["xoopsDB"]->quote($collation) );
		    //$GLOBALS["xoopsDB"]->queryF( "ALTER TABLE `{$table}` CONVERT TO CHARACTER SET " . $GLOBALS["xoopsDB"]->quote($charset) . " COLLATE " . $GLOBALS["xoopsDB"]->quote($collation) );
	    }
	    $this->convert_table($tables, $charset, $collation);
    }
    
    // Some code not ready to use
    function convert_table($tables, $charset, $collation)
    {
    	// Initialize vars.
    	$string_querys = array();
    	$binary_querys = array();
    	$gen_index_querys = array();
    	$drop_index_querys = array();
    	$tables_querys = array();
    	$optimize_querys = array();
    	$final_querys = array();
    
    	// Begin Converter Core
    	if ( !empty($tables) ) {
    		foreach ( (array) $tables as $table ) {
    			// Analyze tables for string types columns and generate his binary and string correctness sql sentences.
    			$resource = $GLOBALS["xoopsDB"]->queryF("DESCRIBE $table");
    			while ( $result = $GLOBALS["xoopsDB"]->fetchArray($resource) ) {
    				if ( preg_match('/(char)|(text)|(enum)|(set)/', $result['Type']) ) {
    					// String Type SQL Sentence.
    					$string_querys[] = "ALTER TABLE `$table` MODIFY `" . $result['Field'] . '` ' . $result['Type'] . " CHARACTER SET $charset COLLATE $collation " . ( ( (!empty($result['Default'])) || ($result['Default'] === '0') || ($result['Default'] === 0) || ($result['Default'] ==='') ) ? "DEFAULT '". $result['Default'] ."' " : '' ) . ( 'YES' == $result['Null'] ? '' : 'NOT ' ) . 'NULL';
    
    					// Binary String Type SQL Sentence.
    					if ( preg_match('/(enum)|(set)/', $result['Type']) ) {
    						$binary_querys[] = "ALTER TABLE `$table` MODIFY `" . $result['Field'] . '` ' . $result['Type'] . ' CHARACTER SET binary ' . ( ( (!empty($result['Default'])) || ($result['Default'] === '0') || ($result['Default'] === 0) ) ? "DEFAULT '". $result['Default'] ."' " : '' ) . ( 'YES' == $result['Null'] ? '' : 'NOT ' ) . 'NULL';
    					} else {
    						$result['Type'] = preg_replace('/char/', 'binary', $result['Type']);
    						$result['Type'] = preg_replace('/text/', 'blob', $result['Type']);
    						$binary_querys[] = "ALTER TABLE `$table` MODIFY `" . $result['Field'] . '` ' . $result['Type'] . ' ' . ( ( (!empty($result['Default'])) || ($result['Default'] === '0') || ($result['Default'] === 0) ) ? "DEFAULT '". $result['Default'] ."' " : '' ) . ( 'YES' == $result['Null'] ? '' : 'NOT ' ) . 'NULL';
    					}
    				}
    			}
    
    			// Analyze table indexs for any FULLTEXT-Type of index in the table.
    			$fulltext_indexes = array();
    			$resource = $GLOBALS["xoopsDB"]->queryF("SHOW INDEX FROM `$table`");
    			while ( $result = $GLOBALS["xoopsDB"]->fetchArray($resource) ) {
    				if ( preg_match('/FULLTEXT/', $result['Index_type']) )
    					$fulltext_indexes[$result['Key_name']][$result['Column_name']] = 1;
    			}
    
    			// Generate the SQL Sentence for drop and add every FULLTEXT index we found previously.
    			if ( !empty($fulltext_indexes) ) {
    				foreach ( (array) $fulltext_indexes as $key_name => $column ) {
    					$drop_index_querys[] = "ALTER TABLE `$table` DROP INDEX `$key_name`";
    					$tmp_gen_index_query = "ALTER TABLE `$table` ADD FULLTEXT `$key_name`(";
    					$fields_names = array_keys($column);
    					for ($i = 1; $i <= count($column); $i++)
    						$tmp_gen_index_query .= $fields_names[$i - 1] . (($i == count($column)) ? '' : ', ');
    					$gen_index_querys[] = $tmp_gen_index_query . ')';
    				}
    			}

    			// Generate the SQL Sentence for change default table character set.
    			$tables_querys[] = "ALTER TABLE `$table` DEFAULT CHARACTER SET $charset COLLATE $collation";

    			// Generate the SQL Sentence for Optimize Table.
    			$optimize_querys[] = "OPTIMIZE TABLE `$table`";
    		}

    	}
    	// End Converter Core

    	// Merge all SQL Sentences that we temporary store in arrays.
    	$final_querys = array_merge( (array) $drop_index_querys, (array) $binary_querys, (array) $tables_querys, (array) $string_querys, (array) $gen_index_querys, (array) $optimize_querys );

    	foreach ($final_querys as $sql) {
        	$GLOBALS["xoopsDB"]->queryF($sql);
    	}
    	
    	// Time to return.
    	return $final_querys;
    }
    function write_mainfile($vars)
    {
        if (empty($vars)) {
            return false;
        }

        $file = dirname(__FILE__) . '/mainfile.dist.php';

        $lines = file($file);
        foreach (array_keys($lines) as $ln) {
            if ( preg_match("/(define\()([\"'])(XOOPS_[^\"']+)\\2,\s*([0-9]+)\s*\)/", $lines[$ln], $matches ) ) {
                $val = isset( $vars[$matches[3]] ) 
                        ? strval( constant($matches[3]) ) 
                        : ( defined($matches[3]) 
                            ? strval( constant($matches[3]) ) 
                            : "0"
                          );
                $lines[$ln] = preg_replace( "/(define\()([\"'])(XOOPS_[^\"']+)\\2,\s*([0-9]+)\s*\)/", 
                    "define( '" . $matches[3] . "', " . $val . " )", 
                    $lines[$ln] );
            } elseif( preg_match( "/(define\()([\"'])(XOOPS_[^\"']+)\\2,\s*([\"'])([^\"']*?)\\4\s*\)/", $lines[$ln], $matches ) ) {
                $val = isset( $vars[$matches[3]] )
                        ? strval( $vars[$matches[3]] )
                        : ( defined($matches[3])
                            ? strval( constant($matches[3]) ) 
                            : ""
                          );
                $lines[$ln] = preg_replace( "/(define\()([\"'])(XOOPS_[^\"']+)\\2,\s*([\"'])(.*?)\\4\s*\)/",
                    "define( '" . $matches[3] . "', '" . $val . "' )", 
                    $lines[$ln] );
            }
        }
        
        $fp = fopen( XOOPS_ROOT_PATH . '/mainfile.php', 'wt' );
        if ( !$fp ) {
            echo ERR_COULD_NOT_WRITE_MAINFILE;
            echo "<pre style='border: 1px solid black; width: 80%; overflow: auto;'><div style='color: #ff0000; font-weight: bold;'><div>" . implode("</div><div>", array_map("htmlspecialchars", $lines)) . "</div></div></pre>";
            return false;
        } else {
            $newline = defined( PHP_EOL ) ? PHP_EOL : ( strpos( php_uname(), 'Windows') ? "\r\n" : "\n" );
            $content = str_replace( array("\r\n", "\n"), $newline, implode('', $lines) );

            fwrite( $fp,  $content );
            fclose( $fp );
            return true;
        }
    }
    function set_configs($task)
    {
        $ret = array();
        $configs = include dirname(__FILE__) . "/settings_{$task}.php";
        if ( !$configs || !is_array($configs) ) {
            return $ret;
        }
        if (empty($_POST['action']) || $_POST['task'] != $task) {
            return false;
        }
        
        foreach ( $configs as $key => $val ) {
            $ret['XOOPS_' . $key] = $val;
        }
        return $ret;
        
    }
}

$upg = new upgrade_impcms06();
return $upg;
?>