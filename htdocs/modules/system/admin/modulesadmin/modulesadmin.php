<?php
// $Id: modulesadmin.php 1029 2007-09-09 03:49:25Z phppp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit("Access Denied");
}

function xoops_module_list() {
    global $icmsAdminTpl,$xoopsUser,$xoopsConfig;
    
    $icmsAdminTpl->assign('lang_madmin',_MD_AM_MODADMIN);
    $icmsAdminTpl->assign('lang_module',_MD_AM_MODULE);
    $icmsAdminTpl->assign('lang_version',_MD_AM_VERSION);
    $icmsAdminTpl->assign('lang_modstatus',_MD_AM_MODULESADMIN_STATUS);
    $icmsAdminTpl->assign('lang_lastup',_MD_AM_LASTUP);
    $icmsAdminTpl->assign('lang_active',_MD_AM_ACTIVE);
    $icmsAdminTpl->assign('lang_order',_MD_AM_ORDER);
    $icmsAdminTpl->assign('lang_order0',_MD_AM_ORDER0);
    $icmsAdminTpl->assign('lang_action',_MD_AM_ACTION);
    $icmsAdminTpl->assign('lang_modulename',_MD_AM_MODULESADMIN_MODULENAME);
    $icmsAdminTpl->assign('lang_moduletitle',_MD_AM_MODULESADMIN_MODULETITLE);
    $icmsAdminTpl->assign('lang_info',_INFO);
    $icmsAdminTpl->assign('lang_update',_MD_AM_UPDATE);
    $icmsAdminTpl->assign('lang_unistall',_MD_AM_UNINSTALL);
    $icmsAdminTpl->assign('lang_support',_MD_AM_MODULESADMIN_SUPPORT);
    $icmsAdminTpl->assign('lang_submit',_MD_AM_SUBMIT);
    $icmsAdminTpl->assign('lang_install',_MD_AM_INSTALL);
    
    $module_handler =& xoops_gethandler('module');
    $installed_mods =& $module_handler->getObjects();
    $listed_mods = array();
    foreach ( $installed_mods as $module ) {
    	$module -> getInfo();
    	$mod = array();
    	$mod['mid'] = $module->getVar('mid');
    	$mod['dirname'] = $module->getVar('dirname');
    	$mod['name'] = $module -> getInfo('name');
    	$mod['title'] = $module -> getVar('name');
    	$mod['image'] = $module -> getInfo('image');
    	$mod['adminindex'] = $module->getInfo('adminindex');
    	$mod['hasadmin'] = $module->getVar('hasadmin');
    	$mod['hasmain'] = $module->getVar('hasmain');
    	$mod['isactive'] = $module->getVar('isactive');
    	$mod['version'] = round($module -> getVar('version') / 100, 2);
    	$mod['status'] = ($module->getInfo('status'))?$module->getInfo('status'):'&nbsp;';
    	$mod['last_update'] = ($module -> getVar('last_update') != 0)?formatTimestamp($module -> getVar('last_update'), 'm'):'&nbsp;';
    	$mod['weight'] = $module->getVar('weight');
    	$mod['support_site_url'] = $module->getInfo('support_site_url');
        $icmsAdminTpl->append('modules',$mod);
        $listed_mods[] = $module->getVar('dirname');
    }
    
    $dirlist = XoopsLists::getModulesList();
	foreach($dirlist as $file){
        clearstatcache();
        $file = trim($file);
        if ( !in_array($file, $listed_mods) ) {
            $module =& $module_handler->create();
            if (!$module->loadInfo($file, false)) {
                continue;
            }
            $mod = array();
            $mod['dirname'] = $module->getInfo('dirname');
            $mod['name'] = $module->getInfo('name');
            $mod['image'] = $module->getInfo('image');
            $mod['version'] = round($module->getInfo('version'), 2);
            $mod['status'] = $module->getInfo('status');
            $icmsAdminTpl->append('avmodules',$mod);
            unset($module);
        }
    }

    return $icmsAdminTpl->fetch('db:admin/modulesadmin/system_adm_modulesadmin.html');
}

function xoops_module_install($dirname) {
    global $xoopsUser, $xoopsConfig;
    $dirname = trim($dirname);
    $db =& Database::getInstance();
    $reservedTables = array('avatar', 'avatar_users_link', 'block_module_link', 'xoopscomments', 'config', 'configcategory', 'configoption', 'image', 'imagebody', 'imagecategory', 'imgset', 'imgset_tplset_link', 'imgsetimg', 'groups','groups_users_link','group_permission', 'online', 'bannerclient', 'banner', 'bannerfinish', 'priv_msgs', 'ranks', 'session', 'smiles', 'users', 'newblocks', 'modules', 'tplfile', 'tplset', 'tplsource', 'xoopsnotifications', 'banner', 'bannerclient', 'bannerfinish');
    $module_handler =& xoops_gethandler('module');
    if ($module_handler->getCount(new Criteria('dirname', $dirname)) == 0) {
        $module =& $module_handler->create();
        $module->loadInfoAsVar($dirname);
        $module->setVar('weight', 1);
        $error = false;
        $errs = array();
        $sqlfile =& $module->getInfo('sqlfile');
        $msgs = array();
        $msgs[] = '<h4 style="text-align:'._GLOBAL_LEFT.';margin-bottom: 0px;border-bottom: dashed 1px #000000;">Installing '.$module->getInfo('name').'</h4>';
        if ($module->getInfo('image') != false && trim($module->getInfo('image')) != '') {
            $msgs[] ='<img src="'.XOOPS_URL.'/modules/'.$dirname.'/'.trim($module->getInfo('image')).'" alt="" />';
        }
        $msgs[] ='<b>Version:</b> '.$module->getInfo('version');
        if ($module->getInfo('author') != false && trim($module->getInfo('author')) != '') {
            $msgs[] ='<b>Author:</b> '.trim($module->getInfo('author'));
        }
        $msgs[] = '';
        $errs[] = '<h4 style="text-align:'._GLOBAL_LEFT.';margin-bottom: 0px;border-bottom: dashed 1px #000000;">Installing '.$module->getInfo('name').'</h4>';
        if ($sqlfile != false && is_array($sqlfile)) {

            $sql_file_path = XOOPS_ROOT_PATH."/modules/".$dirname."/".$sqlfile[XOOPS_DB_TYPE];
            if (!file_exists($sql_file_path)) {
                $errs[] = "SQL file not found at <b>$sql_file_path</b>";
                $error = true;
            } else {
                $msgs[] = "SQL file found at <b>$sql_file_path</b>.<br  /> Creating tables...";
                include_once XOOPS_ROOT_PATH.'/class/database/drivers/'.XOOPS_DB_TYPE.'/sqlutility.php';
                $sql_query = fread(fopen($sql_file_path, 'r'), filesize($sql_file_path));
                $sql_query = trim($sql_query);
                SqlUtility::splitSqlFile($pieces, $sql_query);
                $created_tables = array();
                foreach ($pieces as $piece) {
                    // [0] contains the prefixed query
                    // [4] contains unprefixed table name
                    $prefixed_query = SqlUtility::prefixQuery($piece, $db->prefix());
                    if (!$prefixed_query) {
                        $errs[] = "<b>$piece</b> is not a valid SQL!";
                        $error = true;
                        break;
                    }
                    // check if the table name is reserved
                    if (!in_array($prefixed_query[4], $reservedTables)) {
                        // not reserved, so try to create one
                        if (!$db->query($prefixed_query[0])) {
                            $errs[] = $db->error();
                            $error = true;
                            break;
                        } else {

                            if (!in_array($prefixed_query[4], $created_tables)) {
                                $msgs[] = '&nbsp;&nbsp;Table <b>'.$db->prefix($prefixed_query[4]).'</b> created.';
                                $created_tables[] = $prefixed_query[4];
                            } else {
                                $msgs[] = '&nbsp;&nbsp;Data inserted to table <b>'.$db->prefix($prefixed_query[4]).'</b>.';
                            }
                        }
                    } else {
                        // the table name is reserved, so halt the installation
                        $errs[] = '<b>'.$prefixed_query[4]."</b> is a reserved table!";
                        $error = true;
                        break;
                    }
                }
                // if there was an error, delete the tables created so far, so the next installation will not fail
                if ($error == true) {
                    foreach ($created_tables as $ct) {
                        //echo $ct;
                        $db->query("DROP TABLE ".$db->prefix($ct));
                    }
                }
            }
        }
        // if no error, save the module info and blocks info associated with it
        if ($error == false) {
            if (!$module_handler->insert($module)) {
                $errs[] = 'Could not insert <b>'.$module->getVar('name').'</b> to database.';
                foreach ($created_tables as $ct) {
                    $db->query("DROP TABLE ".$db->prefix($ct));
                }
                $ret = "<p>".sprintf(_MD_AM_FAILINS, "<b>".$module->name()."</b>")."&nbsp;"._MD_AM_ERRORSC."<br />";
                foreach ( $errs as $err ) {
                    $ret .= " - ".$err."<br />";
                }
                $ret .= "</p>";
                unset($module);
                unset($created_tables);
                unset($errs);
                unset($msgs);
                return $ret;
            } else {
                $newmid = $module->getVar('mid');
                unset($created_tables);
                $msgs[] = 'Module data inserted successfully. Module ID: <b>'.$newmid.'</b>';
                $tplfile_handler =& xoops_gethandler('tplfile');
                $templates = $module->getInfo('templates');
                if ($templates != false) {
                    $msgs[] = 'Adding templates...';
                    foreach ($templates as $tpl) {
                        $tplfile =& $tplfile_handler->create();
                        $tpldata =& xoops_module_gettemplate($dirname, $tpl['file']);
                        $tplfile->setVar('tpl_source', $tpldata, true);
                        $tplfile->setVar('tpl_refid', $newmid);

                        $tplfile->setVar('tpl_tplset', 'default');
                        $tplfile->setVar('tpl_file', $tpl['file']);
                        $tplfile->setVar('tpl_desc', $tpl['description'], true);
                        $tplfile->setVar('tpl_module', $dirname);
                        $tplfile->setVar('tpl_lastmodified', time());
                        $tplfile->setVar('tpl_lastimported', 0);
                        $tplfile->setVar('tpl_type', 'module');
                        if (!$tplfile_handler->insert($tplfile)) {
                            $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not insert template <b>'.$tpl['file'].'</b> to the database.</span>';
                        } else {
                            $newtplid = $tplfile->getVar('tpl_id');
                            $msgs[] = '&nbsp;&nbsp;Template <b>'.$tpl['file'].'</b> added to the database. (ID: <b>'.$newtplid.'</b>)';
                            // generate compiled file
                            include_once XOOPS_ROOT_PATH.'/class/template.php';
                            if (!xoops_template_touch($newtplid)) {
                                $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Failed compiling template <b>'.$tpl['file'].'</b>.</span>';
                            } else {
                                $msgs[] = '&nbsp;&nbsp;Template <b>'.$tpl['file'].'</b> compiled.</span>';
                            }
                        }
                        unset($tpldata);
                    }
                }
                include_once XOOPS_ROOT_PATH.'/class/template.php';
                xoops_template_clear_module_cache($newmid);
                $blocks = $module->getInfo('blocks');
                if ($blocks != false) {
                    $msgs[] = 'Adding blocks...';
                    foreach ($blocks as $blockkey => $block) {
                        // break the loop if missing block config
                        if (!isset($block['file']) || !isset($block['show_func'])) {
                            break;
                        }
                        $options = '';
                        if (!empty($block['options'])) {
                            $options = trim($block['options']);
                        }
                        $newbid = $db->genId($db->prefix('newblocks').'_bid_seq');
                        $edit_func = isset($block['edit_func']) ? trim($block['edit_func']) : '';
                        $template = '';
                        if ((isset($block['template']) && trim($block['template']) != '')) {
                            $content =& xoops_module_gettemplate($dirname, $block['template'], true);
                        }
                        if (empty($content)) {
                            $content = '';
                        } else {
                            $template = trim($block['template']);
                        }
                        $block_name = addslashes(trim($block['name']));
                        $sql = "INSERT INTO ".$db->prefix("newblocks")." (bid, mid, func_num, options, name, title, content, side, weight, visible, block_type, c_type, isactive, dirname, func_file, show_func, edit_func, template, bcachetime, last_modified) VALUES ('".intval($newbid)."', '".intval($newmid)."', '".intval($blockkey)."', '$options', '".$block_name."','".$block_name."', '', '1', '0', '0', 'M', 'H', '1', '".addslashes($dirname)."', '".addslashes(trim($block['file']))."', '".addslashes(trim($block['show_func']))."', '".addslashes($edit_func)."', '".$template."', '0', '".time()."')";
                        if (!$db->query($sql)) {
                            $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not add block <b>'.$block['name'].'</b> to the database! Database error: <b>'.$db->error().'</b></span>';
                        } else {
                            if (empty($newbid)) {
                                $newbid = $db->getInsertId();
                            }
                            $msgs[] = '&nbsp;&nbsp;Block <b>'.$block['name'].'</b> added. Block ID: <b>'.$newbid.'</b>';
                            $sql = 'INSERT INTO '.$db->prefix('block_module_link').' (block_id, module_id,page_id) VALUES ('.intval($newbid).', 0,1)';
                            $db->query($sql);
                            if ($template != '') {
                                $tplfile =& $tplfile_handler->create();
                                $tplfile->setVar('tpl_refid', $newbid);
                                $tplfile->setVar('tpl_source', $content, true);
                                $tplfile->setVar('tpl_tplset', 'default');
                                $tplfile->setVar('tpl_file', $block['template']);
                                $tplfile->setVar('tpl_module', $dirname);
                                $tplfile->setVar('tpl_type', 'block');
                                $tplfile->setVar('tpl_desc', $block['description'], true);
                                $tplfile->setVar('tpl_lastimported', 0);
                                $tplfile->setVar('tpl_lastmodified', time());
                                if (!$tplfile_handler->insert($tplfile)) {
                                    $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not insert template <b>'.$block['template'].'</b> to the database.</span>';
                                } else {
                                    $newtplid = $tplfile->getVar('tpl_id');
                                    $msgs[] = '&nbsp;&nbsp;Template <b>'.$block['template'].'</b> added to the database. (ID: <b>'.$newtplid.'</b>)';
                                    // generate compiled file
                                    include_once XOOPS_ROOT_PATH.'/class/template.php';
                                    if (!xoops_template_touch($newtplid)) {
                                        $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Failed compiling template <b>'.$block['template'].'</b>.</span>';
                                    } else {
                                        $msgs[] = '&nbsp;&nbsp;Template <b>'.$block['template'].'</b> compiled.</span>';
                                    }
                                }
                            }
                        }
                        unset($content);
                    }
                    unset($blocks);
                }
                $configs = $module->getInfo('config');
                if ($configs != false) {
                    if ($module->getVar('hascomments') != 0) {
                        include_once(XOOPS_ROOT_PATH.'/include/comment_constants.php');
                        array_push($configs, array('name' => 'com_rule', 'title' => '_CM_COMRULES', 'description' => '', 'formtype' => 'select', 'valuetype' => 'int', 'default' => 1, 'options' => array('_CM_COMNOCOM' => XOOPS_COMMENT_APPROVENONE, '_CM_COMAPPROVEALL' => XOOPS_COMMENT_APPROVEALL, '_CM_COMAPPROVEUSER' => XOOPS_COMMENT_APPROVEUSER, '_CM_COMAPPROVEADMIN' => XOOPS_COMMENT_APPROVEADMIN)));
                        array_push($configs, array('name' => 'com_anonpost', 'title' => '_CM_COMANONPOST', 'description' => '', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 0));
                    }
                } else {
                    if ($module->getVar('hascomments') != 0) {
                        $configs = array();
                        include_once(XOOPS_ROOT_PATH.'/include/comment_constants.php');
                        $configs[] = array('name' => 'com_rule', 'title' => '_CM_COMRULES', 'description' => '', 'formtype' => 'select', 'valuetype' => 'int', 'default' => 1, 'options' => array('_CM_COMNOCOM' => XOOPS_COMMENT_APPROVENONE, '_CM_COMAPPROVEALL' => XOOPS_COMMENT_APPROVEALL, '_CM_COMAPPROVEUSER' => XOOPS_COMMENT_APPROVEUSER, '_CM_COMAPPROVEADMIN' => XOOPS_COMMENT_APPROVEADMIN));
                        $configs[] = array('name' => 'com_anonpost', 'title' => '_CM_COMANONPOST', 'description' => '', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 0);
                    }
                }
                // RMV-NOTIFY
                if ($module->getVar('hasnotification') != 0) {
                    if (empty($configs)) {
                        $configs = array();
                    }
                    // Main notification options
                    include_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
                    include_once XOOPS_ROOT_PATH . '/include/notification_functions.php';
                    $options = array();
                    $options['_NOT_CONFIG_DISABLE'] = XOOPS_NOTIFICATION_DISABLE;
                    $options['_NOT_CONFIG_ENABLEBLOCK'] = XOOPS_NOTIFICATION_ENABLEBLOCK;
                    $options['_NOT_CONFIG_ENABLEINLINE'] = XOOPS_NOTIFICATION_ENABLEINLINE;
                    $options['_NOT_CONFIG_ENABLEBOTH'] = XOOPS_NOTIFICATION_ENABLEBOTH;

                    //$configs[] = array ('name' => 'notification_enabled', 'title' => '_NOT_CONFIG_ENABLED', 'description' => '_NOT_CONFIG_ENABLEDDSC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 1);
                    $configs[] = array ('name' => 'notification_enabled', 'title' => '_NOT_CONFIG_ENABLE', 'description' => '_NOT_CONFIG_ENABLEDSC', 'formtype' => 'select', 'valuetype' => 'int', 'default' => XOOPS_NOTIFICATION_ENABLEBOTH, 'options' => $options);
                    // Event-specific notification options
                    // FIXME: doesn't work when update module... can't read back the array of options properly...  " changing to &quot;
                    $options = array();
                    $categories =& notificationCategoryInfo('',$module->getVar('mid'));
                    foreach ($categories as $category) {
                        $events =& notificationEvents ($category['name'], false, $module->getVar('mid'));
                        foreach ($events as $event) {
                            if (!empty($event['invisible'])) {
                                continue;
                            }
                            $option_name = $category['title'] . ' : ' . $event['title'];
                            $option_value = $category['name'] . '-' . $event['name'];
                            $options[$option_name] = $option_value;
                        }
                    }
                    $configs[] = array ('name' => 'notification_events', 'title' => '_NOT_CONFIG_EVENTS', 'description' => '_NOT_CONFIG_EVENTSDSC', 'formtype' => 'select_multi', 'valuetype' => 'array', 'default' => array_values($options), 'options' => $options);
                }

                if ($configs != false) {
                    $msgs[] = 'Adding module config data...';
                    $config_handler =& xoops_gethandler('config');
                    $order = 0;
                    foreach ($configs as $config) {
                        $confobj =& $config_handler->createConfig();
                        $confobj->setVar('conf_modid', $newmid);
                        $confobj->setVar('conf_catid', 0);
                        $confobj->setVar('conf_name', $config['name']);
                        $confobj->setVar('conf_title', $config['title'], true);
                        $confobj->setVar('conf_desc', $config['description'], true);
                        $confobj->setVar('conf_formtype', $config['formtype']);
                        $confobj->setVar('conf_valuetype', $config['valuetype']);
                        $confobj->setConfValueForInput($config['default'], true);
                        //$confobj->setVar('conf_value', $config['default'], true);
                        $confobj->setVar('conf_order', $order);
                        $confop_msgs = '';
                        if (isset($config['options']) && is_array($config['options'])) {
                            foreach ($config['options'] as $key => $value) {
                                $confop =& $config_handler->createConfigOption();
                                $confop->setVar('confop_name', $key, true);
                                $confop->setVar('confop_value', $value, true);
                                $confobj->setConfOptions($confop);
                                $confop_msgs .= '<br />&nbsp;&nbsp;&nbsp;&nbsp;Config option added. Name: <b>'.$key.'</b> Value: <b>'.$value.'</b>';
                                unset($confop);
                            }
                        }
                        $order++;
                        if ($config_handler->insertConfig($confobj) != false) {
                            $msgs[] = '&nbsp;&nbsp;Config <b>'.$config['name'].'</b> added to the database.'.$confop_msgs;
                        } else {
                            $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not insert config <b>'.$config['name'].'</b> to the database.</span>';
                        }
                        unset($confobj);
                    }
                    unset($configs);
                }
            }
				if ($module->getInfo('hasMain')) {
					$groups = array(XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS, XOOPS_GROUP_ANONYMOUS);
				} else {
					$groups = array(XOOPS_GROUP_ADMIN);
				}
            // retrieve all block ids for this module
            $blocks =& XoopsBlock::getByModule($newmid, false);
            $msgs[] = 'Setting group rights...';
            $gperm_handler =& xoops_gethandler('groupperm');
            foreach ($groups as $mygroup) {
                if ($gperm_handler->checkRight('module_admin', 0, $mygroup)) {
                    $mperm =& $gperm_handler->create();
                    $mperm->setVar('gperm_groupid', $mygroup);
                    $mperm->setVar('gperm_itemid', $newmid);
                    $mperm->setVar('gperm_name', 'module_admin');
                    $mperm->setVar('gperm_modid', 1);
                    if (!$gperm_handler->insert($mperm)) {
                        $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not add admin access right for Group ID <b>'.$mygroup.'</b></span>';
                    } else {
                        $msgs[] = '&nbsp;&nbsp;Added admin access right for Group ID <b>'.$mygroup.'</b>';
                    }
                    unset($mperm);
                }
                $mperm =& $gperm_handler->create();
                $mperm->setVar('gperm_groupid', $mygroup);
                $mperm->setVar('gperm_itemid', $newmid);
                $mperm->setVar('gperm_name', 'module_read');
                $mperm->setVar('gperm_modid', 1);
                if (!$gperm_handler->insert($mperm)) {
                    $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not add user access right for Group ID: <b>'.$mygroup.'</b></span>';
                } else {
                    $msgs[] = '&nbsp;&nbsp;Added user access right for Group ID: <b>'.$mygroup.'</b>';
                }
                unset($mperm);
                foreach ($blocks as $blc) {
                    $bperm =& $gperm_handler->create();
                    $bperm->setVar('gperm_groupid', $mygroup);
                    $bperm->setVar('gperm_itemid', $blc);
                    $bperm->setVar('gperm_name', 'block_read');
                    $bperm->setVar('gperm_modid', 1);
                    if (!$gperm_handler->insert($bperm)) {
                        $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not add block access right. Block ID: <b>'.$blc.'</b> Group ID: <b>'.$mygroup.'</b></span>';
                    } else {
                        $msgs[] = '&nbsp;&nbsp;Added block access right. Block ID: <b>'.$blc.'</b> Group ID: <b>'.$mygroup.'</b>';
                    }
                    unset($bperm);
                }
            }
            unset($blocks);
            unset($groups);

            // execute module specific install script if any
            $install_script = $module->getInfo('onInstall');
            if (false != $install_script && trim($install_script) != '') {
                include_once XOOPS_ROOT_PATH.'/modules/'.$dirname.'/'.trim($install_script);
                if (function_exists('xoops_module_install_'.$dirname)) {
                    $func = 'xoops_module_install_'.$dirname;
                    if ( !( $lastmsg = $func($module) ) ) {
                        $msgs[] = 'Failed to execute '.$func;
                    } else {
                        $msgs[] = '<b>'.$func.'</b> executed successfully.';
						if ( is_string( $lastmsg ) ) {
							$msgs[] = $lastmsg;
						}
                    }
                }
            }

            $ret = '<p><code>';
            foreach ($msgs as $m) {
                $ret .= $m.'<br />';
            }
            unset($msgs);
            unset($errs);
            $ret .= '</code><br />'.sprintf(_MD_AM_OKINS, "<b>".$module->getVar('name')."</b>").'</p>';
            unset($module);
            return $ret;
        } else {
            $ret = '<p>';
            foreach ($errs as $er) {
                $ret .= '&nbsp;&nbsp;'.$er.'<br />';
            }
            unset($msgs);
            unset($errs);
            $ret .= '<br />'.sprintf(_MD_AM_FAILINS, '<b>'.$dirname.'</b>').'&nbsp;'._MD_AM_ERRORSC.'</p>';
            return $ret;
        }
    }
    else {
        return "<p>".sprintf(_MD_AM_FAILINS, "<b>".$dirname."</b>")."&nbsp;"._MD_AM_ERRORSC."<br />&nbsp;&nbsp;".sprintf(_MD_AM_ALEXISTS, $dirname)."</p>";
    }
}

function &xoops_module_gettemplate($dirname, $template, $block=false) {
    global $xoopsConfig;
    $ret = '';
    if ($block) {
        $path = XOOPS_ROOT_PATH.'/modules/'.$dirname.'/templates/blocks/'.$template;
    } else {
        $path = XOOPS_ROOT_PATH.'/modules/'.$dirname.'/templates/'.$template;
    }
    if (!file_exists($path)) {
        return $ret;
    } else {
        $lines = file($path);
    }
    if (!$lines) {
        return $ret;
    }
    $count = count($lines);
    for ($i = 0; $i < $count; $i++) {
        $ret .= str_replace("\n", "\r\n", str_replace("\r\n", "\n", $lines[$i]));
    }
    return $ret;
}

function xoops_module_uninstall($dirname) {
    global $xoopsConfig;
    $reservedTables = array('avatar', 'avatar_users_link', 'block_module_link', 'xoopscomments', 'config', 'configcategory', 'configoption', 'image', 'imagebody', 'imagecategory', 'imgset', 'imgset_tplset_link', 'imgsetimg', 'groups','groups_users_link','group_permission', 'online', 'bannerclient', 'banner', 'bannerfinish', 'priv_msgs', 'ranks', 'session', 'smiles', 'users', 'newblocks', 'modules', 'tplfile', 'tplset', 'tplsource', 'xoopsnotifications', 'banner', 'bannerclient', 'bannerfinish');
    $db =& Database::getInstance();
    $module_handler =& xoops_gethandler('module');
    $module =& $module_handler->getByDirname($dirname);
    include_once XOOPS_ROOT_PATH.'/class/template.php';
    xoops_template_clear_module_cache($module->getVar('mid'));
    if ($module->getVar('dirname') == 'system') {
        return "<p>".sprintf(_MD_AM_FAILUNINS, "<b>".$module->getVar('name')."</b>")."&nbsp;"._MD_AM_ERRORSC."<br /> - "._MD_AM_SYSNO."</p>";
    } elseif ($module->getVar('dirname') == $xoopsConfig['startpage']) {
        return "<p>".sprintf(_MD_AM_FAILUNINS, "<b>".$module->getVar('name')."</b>")."&nbsp;"._MD_AM_ERRORSC."<br /> - "._MD_AM_STRTNO."</p>";
    } else {	
        $msgs = array();
        
    	$stararr = explode('-',$xoopsConfig['startpage']);
    	if (count($stararr) > 0){
    		if ($module->getVar('mid') == $stararr[0]){
    			return "<p>".sprintf(_MD_AM_FAILDEACT, "<b>".$module->getVar('name')."</b>")."&nbsp;"._MD_AM_ERRORSC."<br /> - "._MD_AM_STRTNO."</p>";
    		}
    	}

    	$page_handler = xoops_gethandler('page');
    	$criteria = new CriteriaCompo(new Criteria('page_moduleid', $module->getVar('mid')));
    	$pages = $page_handler->getCount($criteria);

    	if ($pages > 0){
    		$pages = $page_handler->getObjects($criteria);
    		$msgs[] = 'Deleting links fom Symlink Manager...';
    		foreach ($pages as $page){
    			if (!$page_handler->delete($page)) {
    				$msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not delete link '.$page->getVar('page_title').' from the database. Link ID: <b>'.$page->getVar('page_id').'</b></span>';
    			} else {
    				$msgs[] = '&nbsp;&nbsp;Link <b>'.$page->getVar('page_title').'</b> deleted from the database. Link ID: <b>'.$page->getVar('page_id').'</b>';
    			}
    		}
    	}
        
        if (!$module_handler->delete($module)) {
            $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not delete '.$module->getVar('name').'</span>';
        } else {

            // delete template files
            $tplfile_handler = xoops_gethandler('tplfile');
            $templates =& $tplfile_handler->find(null, 'module', $module->getVar('mid'));
            $tcount = count($templates);
            if ($tcount > 0) {
                $msgs[] = 'Deleting templates...';
                for ($i = 0; $i < $tcount; $i++) {
                    if (!$tplfile_handler->delete($templates[$i])) {
                        $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not delete template '.$templates[$i]->getVar('tpl_file').' from the database. Template ID: <b>'.$templates[$i]->getVar('tpl_id').'</b></span>';
                    } else {
                        $msgs[] = '&nbsp;&nbsp;Template <b>'.$templates[$i]->getVar('tpl_file').'</b> deleted from the database. Template ID: <b>'.$templates[$i]->getVar('tpl_id').'</b>';
                    }
                }
            }
            unset($templates);

            // delete blocks and block tempalte files
            $block_arr =& XoopsBlock::getByModule($module->getVar('mid'));
            if (is_array($block_arr)) {
                $bcount = count($block_arr);
                $msgs[] = 'Deleting block...';
                for ($i = 0; $i < $bcount; $i++) {
                    if (!$block_arr[$i]->delete()) {
                        $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not delete block <b>'.$block_arr[$i]->getVar('name').'</b> Block ID: <b>'.$block_arr[$i]->getVar('bid').'</b></span>';
                    } else {
                        $msgs[] = '&nbsp;&nbsp;Block <b>'.$block_arr[$i]->getVar('name').'</b> deleted. Block ID: <b>'.$block_arr[$i]->getVar('bid').'</b>';
                    }
                    if ($block_arr[$i]->getVar('template') != ''){
                        $templates =& $tplfile_handler->find(null, 'block', $block_arr[$i]->getVar('bid'));
                        $btcount = count($templates);
                        if ($btcount > 0) {
                            for ($j = 0; $j < $btcount; $j++) {
                                if (!$tplfile_handler->delete($templates[$j])) {
                                $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not delete block template '.$templates[$j]->getVar('tpl_file').' from the database. Template ID: <b>'.$templates[$j]->getVar('tpl_id').'</b></span>';
                                } else {
                                $msgs[] = '&nbsp;&nbsp;Block template <b>'.$templates[$j]->getVar('tpl_file').'</b> deleted from the database. Template ID: <b>'.$templates[$j]->getVar('tpl_id').'</b>';
                                }
                            }
                        }
                        unset($templates);
                    }
                }
            }

            // delete tables used by this module
            $modtables = $module->getInfo('tables');
            if ($modtables != false && is_array($modtables)) {
                $msgs[] = 'Deleting module tables...';
                foreach ($modtables as $table) {
                    // prevent deletion of reserved core tables!
                    if (!in_array($table, $reservedTables)) {
                        $sql = 'DROP TABLE '.$db->prefix($table);
                        if (!$db->query($sql)) {
                            $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not drop table <b>'.$db->prefix($table).'<b>.</span>';
                        } else {
                            $msgs[] = '&nbsp;&nbsp;Table <b>'.$db->prefix($table).'</b> dropped.</span>';
                        }
                    } else {
                        $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Not allowed to drop table <b>'.$db->prefix($table).'</b>!</span>';
                    }
                }
            }

            // delete comments if any
            if ($module->getVar('hascomments') != 0) {
                $msgs[] = 'Deleting comments...';
                $comment_handler =& xoops_gethandler('comment');
                if (!$comment_handler->deleteByModule($module->getVar('mid'))) {
                    $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not delete comments</span>';
                } else {
                    $msgs[] = '&nbsp;&nbsp;Comments deleted';
                }
            }

            // RMV-NOTIFY
            // delete notifications if any
            if ($module->getVar('hasnotification') != 0) {
                $msgs[] = 'Deleting notifications...';
                if (!xoops_notification_deletebymodule($module->getVar('mid'))) {
                    $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not delete notifications</span>';
                } else {
                    $msgs[] = '&nbsp;&nbsp;Notifications deleted';
                }
            }

            // delete permissions if any
            $gperm_handler =& xoops_gethandler('groupperm');
            if (!$gperm_handler->deleteByModule($module->getVar('mid'))) {
                $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not delete group permissions</span>';
            } else {
                $msgs[] = '&nbsp;&nbsp;Group permissions deleted';
            }

            // delete module config options if any
            if ($module->getVar('hasconfig') != 0 || $module->getVar('hascomments') != 0) {
                $config_handler =& xoops_gethandler('config');
                $configs =& $config_handler->getConfigs(new Criteria('conf_modid', $module->getVar('mid')));
                $confcount = count($configs);
                if ($confcount > 0) {
                    $msgs[] = 'Deleting module config options...';
                    for ($i = 0; $i < $confcount; $i++) {
                        if (!$config_handler->deleteConfig($configs[$i])) {
                            $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not delete config data from the database. Config ID: <b>'.$configs[$i]->getvar('conf_id').'</b></span>';
                        } else {
                            $msgs[] = '&nbsp;&nbsp;Config data deleted from the database. Config ID: <b>'.$configs[$i]->getVar('conf_id').'</b>';
                        }
                    }
                }
            }

            // execute module specific install script if any
            $uninstall_script = $module->getInfo('onUninstall');
            if (false != $uninstall_script && trim($uninstall_script) != '') {
                include_once XOOPS_ROOT_PATH.'/modules/'.$dirname.'/'.trim($uninstall_script);
                if (function_exists('xoops_module_uninstall_'.$dirname)) {
                    $func = 'xoops_module_uninstall_'.$dirname;
                    if (!$func($module)) {
                        $msgs[] = 'Failed to execute <b>'.$func.'</b>';
                    } else {
                        $msgs[] = '<b>'.$func.'</b> executed successfully.';
                    }
                }
            }

            $msgs[] = '</code><p>'.sprintf(_MD_AM_OKUNINS, "<b>".$module->getVar('name')."</b>").'</p>';
        }
        $ret = '<code>';
        foreach ($msgs as $msg) {
            $ret .= $msg.'<br />';
        }
        return $ret;
    }
}

function xoops_module_activate($mid) {
    $module_handler =& xoops_gethandler('module');
    $module =& $module_handler->get($mid);
    include_once XOOPS_ROOT_PATH.'/class/template.php';
    xoops_template_clear_module_cache($module->getVar('mid'));
    $module->setVar('isactive', 1);
    if (!$module_handler->insert($module)) {
        $ret = "<p>".sprintf(_MD_AM_FAILACT, "<b>".$module->getVar('name')."</b>")."&nbsp;"._MD_AM_ERRORSC."<br />".$module->getHtmlErrors();
        return $ret."</p>";
    }
    $blocks =& XoopsBlock::getByModule($module->getVar('mid'));
    $bcount = count($blocks);
    for ($i = 0; $i < $bcount; $i++) {
        $blocks[$i]->setVar('isactive', 1);
        $blocks[$i]->store();
    }
    return "<p>".sprintf(_MD_AM_OKACT, "<b>".$module->getVar('name')."</b>")."</p>";
}

function xoops_module_deactivate($mid) {
    global $xoopsConfig;
    $module_handler =& xoops_gethandler('module');
    $module =& $module_handler->get($mid);
    include_once XOOPS_ROOT_PATH.'/class/template.php';
    xoops_template_clear_module_cache($mid);
    $module->setVar('isactive', 0);
    if ($module->getVar('dirname') == "system") {
        return "<p>".sprintf(_MD_AM_FAILDEACT, "<b>".$module->getVar('name')."</b>")."&nbsp;"._MD_AM_ERRORSC."<br /> - "._MD_AM_SYSNO."</p>";
    } elseif ($module->getVar('dirname') == $xoopsConfig['startpage']) {
        return "<p>".sprintf(_MD_AM_FAILDEACT, "<b>".$module->getVar('name')."</b>")."&nbsp;"._MD_AM_ERRORSC."<br /> - "._MD_AM_STRTNO."</p>";
    } else {
    	$stararr = explode('-',$xoopsConfig['startpage']);
    	if (count($stararr) > 0){
    		if ($module->getVar('mid') == $stararr[0]){
    			return "<p>".sprintf(_MD_AM_FAILDEACT, "<b>".$module->getVar('name')."</b>")."&nbsp;"._MD_AM_ERRORSC."<br /> - "._MD_AM_STRTNO."</p>";
    		}
    	}

    	$page_handler = xoops_gethandler('page');
    	$criteria = new CriteriaCompo(new Criteria('page_moduleid', $module->getVar('mid')));
    	$pages = $page_handler->getCount($criteria);

    	if ($pages > 0){
    		$pages = $page_handler->getObjects($criteria);
    		foreach ($pages as $page){
    			$page_handler->changestatus($page);
    		}
    	}
    	
    	if (!$module_handler->insert($module)) {
            $ret = "<p>".sprintf(_MD_AM_FAILDEACT, "<b>".$module->getVar('name')."</b>")."&nbsp;"._MD_AM_ERRORSC."<br />".$module->getHtmlErrors();
            return $ret."</p>";
        }
        $blocks =& XoopsBlock::getByModule($module->getVar('mid'));
        $bcount = count($blocks);
        for ($i = 0; $i < $bcount; $i++) {
            $blocks[$i]->setVar('isactive', 0);
            $blocks[$i]->store();
        }
        return "<p>".sprintf(_MD_AM_OKDEACT, "<b>".$module->getVar('name')."</b>")."</p>";
    }
}

function xoops_module_change($mid, $weight, $name) {
    $module_handler =& xoops_gethandler('module');
    $module =& $module_handler->get($mid);
    $module->setVar('weight', $weight);
    $module->setVar('name', $name);
    $myts =& MyTextSanitizer::getInstance();
    if (!$module_handler->insert($module)) {
        $ret = "<p>".sprintf(_MD_AM_FAILORDER, "<b>".$myts->stripSlashesGPC($name)."</b>")."&nbsp;"._MD_AM_ERRORSC."<br />";
        $ret .= $module->getHtmlErrors()."</p>";
        return $ret;
    }
    return "<p>".sprintf(_MD_AM_OKORDER, "<b>".$myts->stripSlashesGPC($name)."</b>")."</p>";
}

?>