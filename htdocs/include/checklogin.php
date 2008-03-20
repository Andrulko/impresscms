<?php
// $Id: checklogin.php 1083 2007-10-16 16:42:51Z phppp $
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
// URL: http://www.xoops.org/ http://jp.xoops.org/  http://www.myweb.ne.jp/  //
// Project: The XOOPS Project (http://www.xoops.org/)                        //
// ------------------------------------------------------------------------- //

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}
include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/user.php';
$uname = !isset($_POST['uname']) ? '' : trim($_POST['uname']);
$pass = !isset($_POST['pass']) ? '' : trim($_POST['pass']);
if ($uname == '' || $pass == '') {
    redirect_header(XOOPS_URL.'/user.php', 1, _US_INCORRECTLOGIN);
}
$member_handler =& xoops_gethandler('member');
$myts =& MyTextsanitizer::getInstance();

include_once XOOPS_ROOT_PATH.'/class/auth/authfactory.php';
include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/auth.php';
$xoopsAuth =& XoopsAuthFactory::getAuthConnection($myts->addSlashes($uname));
//$user = $xoopsAuth->authenticate($myts->addSlashes($uname), $myts->addSlashes($pass));
// uname&email hack GIJ
$uname4sql = addslashes( $myts->stripSlashesGPC($uname) ) ;
$pass4sql = addslashes( $myts->stripSlashesGPC($pass) ) ;
if( strstr( $uname , '@' ) ) {
	// check by email if uname includes '@'
	$criteria = new CriteriaCompo(new Criteria('email', $uname4sql ));
	$criteria->add(new Criteria('pass', md5( $pass4sql )));
	$user_handler =& xoops_gethandler('user');
	$users =& $user_handler->getObjects($criteria, false);
	if( empty( $users ) || count( $users ) != 1 ) $user = false ;
	else $user = $users[0] ;
	unset( $users ) ;
}
if( empty( $user ) || ! is_object( $user ) ) {
	$user =& $member_handler->loginUser($uname4sql,$pass4sql);
}
// end of uname&email hack GIJ

if (false != $user) {
    if (0 == $user->getVar('level')) {
        redirect_header(XOOPS_URL.'/index.php', 5, _US_NOACTTPADM);
    }
    if ($xoopsConfig['closesite'] == 1) {
        $allowed = false;
        foreach ($user->getGroups() as $group) {
            if (in_array($group, $xoopsConfig['closesite_okgrp']) || XOOPS_GROUP_ADMIN == $group) {
                $allowed = true;
                break;
            }
        }
        if (!$allowed) {
            redirect_header(XOOPS_URL.'/index.php', 1, _NOPERM);
        }
    }
    $user->setVar('last_login', time());
    if (!$member_handler->insertUser($user)) {
    }
    // Regenrate a new session id and destroy old session
    session_regenerate_id(true);
    $_SESSION = array();
    $_SESSION['xoopsUserId'] = $user->getVar('uid');
    $_SESSION['xoopsUserGroups'] = $user->getGroups();
    if ($xoopsConfig['use_mysession'] && $xoopsConfig['session_name'] != '') {
        setcookie($xoopsConfig['session_name'], session_id(), time()+(60 * $xoopsConfig['session_expire']), '/',  '', 0);
    }
    $user_theme = $user->getVar('theme');
    if (in_array($user_theme, $xoopsConfig['theme_set_allowed'])) {
        $_SESSION['xoopsUserTheme'] = $user_theme;
    }
    if (!empty($_POST['xoops_redirect']) && !strpos($_POST['xoops_redirect'], 'register')) {
		$_POST['xoops_redirect'] = trim( $_POST['xoops_redirect'] );
        $parsed = parse_url(XOOPS_URL);
        $url = isset($parsed['scheme']) ? $parsed['scheme'].'://' : 'http://';
        if ( isset( $parsed['host'] ) ) {
        	$url .= $parsed['host'];
			if ( isset( $parsed['port'] ) ) {
				$url .= ':' . $parsed['port'];
			}
        } else {
        	$url .= $_SERVER['HTTP_HOST'];
        }
        if ( @$parsed['path'] ) {
        	if ( strncmp( $parsed['path'], $_POST['xoops_redirect'], strlen( $parsed['path'] ) ) ) {
	        	$url .= $parsed['path'];
        	}
        }
		$url .= $_POST['xoops_redirect'];
    } else {
        $url = XOOPS_URL.'/index.php';
    }
	if ($pos = strpos( $url, '://' )) {
		$xoopsLocation = substr( XOOPS_URL, strpos( XOOPS_URL, '://' ) + 3 );
	    if ( substr($url, $pos + 3, strlen($xoopsLocation)) != $xoopsLocation)  {
			$url = XOOPS_URL;
	     }elseif(substr($url, $pos + 3, strlen($xoopsLocation)+1) == $xoopsLocation.'.') {
	        $url = XOOPS_URL;
	     }
	     if( substr($url, 0, strlen(XOOPS_URL)*2) ==  XOOPS_URL.XOOPS_URL){
	     	$url = substr($url, strlen(XOOPS_URL));

	     }
	}

	// autologin hack V3.1 GIJ (set cookie)
	$xoops_cookie_path = defined('XOOPS_COOKIE_PATH') ? XOOPS_COOKIE_PATH : preg_replace( '?http://[^/]+(/.*)$?' , "$1" , XOOPS_URL ) ;
	if( $xoops_cookie_path == XOOPS_URL ) $xoops_cookie_path = '/' ;
	if (!empty($_POST['rememberme'])) {
		$expire = time() + ( defined('XOOPS_AUTOLOGIN_LIFETIME') ? XOOPS_AUTOLOGIN_LIFETIME : 604800 ) ; // 1 week default
		setcookie('autologin_uname', $user->getVar('uname'), $expire, $xoops_cookie_path, '', 0);
		$Ynj = date( 'Y-n-j' ) ;
		setcookie('autologin_pass', $Ynj . ':' . md5( $user->getVar('pass') . XOOPS_DB_PASS . XOOPS_DB_PREFIX . $Ynj ) , $expire, $xoops_cookie_path, '', 0);
	}
	// end of autologin hack V3.1 GIJ

    // RMV-NOTIFY
    // Perform some maintenance of notification records
    $notification_handler =& xoops_gethandler('notification');
    $notification_handler->doLoginMaintenance($user->getVar('uid'));

    redirect_header($url, 1, sprintf(_US_LOGGINGU, $user->getVar('uname')), false);
}elseif(empty($_POST['xoops_redirect'])){
	redirect_header(XOOPS_URL.'/user.php', 5, $xoopsAuth->getHtmlErrors());
}else{
	redirect_header(XOOPS_URL.'/user.php?xoops_redirect='.urlencode(trim($_POST['xoops_redirect'])), 5, $xoopsAuth->getHtmlErrors(), false);
}
exit();

?>
