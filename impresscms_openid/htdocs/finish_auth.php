<?php
define('ICMS_INCLUDE_OPENID', true);
$xoopsOption['pagetype'] = 'user';
include_once("mainfile.php");

$redirect_url = $_SESSION['frompage'];
$myts = MyTextSanitizer::getInstance();
$member_handler = xoops_gethandler('member');

include_once XOOPS_ROOT_PATH.'/class/auth/authfactory.php';
include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/auth.php';

$xoopsAuth =& XoopsAuthFactory::getAuthConnection($myts->addSlashes($uname));
$user = $xoopsAuth->authenticate();

switch($xoopsAuth->step) {
	case OPENID_STEP_NO_USER_FOUND:
		$xoopsOption['template_main'] = 'system_openid.html';
		include_once(XOOPS_ROOT_PATH . "/header.php");

		
		$sreg=$_SESSION['openid_sreg'];
		
		$xoopsTpl->assign('displayId', $xoopsAuth->displayid);
		$xoopsTpl->assign('cid', $xoopsAuth->openid);
		$xoopsTpl->assign('uname', isset($sreg['nickname']) ? $sreg['nickname'] : '');
		$xoopsTpl->assign('email', isset($sreg['email']) ? $sreg['email'] : '');
		$xoopsTpl->assign('existinguser',_OD_EXISTINGUSER);
		$xoopsTpl->assign('loginbelow',_OD_LOGINBELOW);
		$xoopsTpl->assign('xoopsuname', _OD_XOOPSUNAME);
		$xoopsTpl->assign('xoopspass', _OD_XOOPSPASS);
		$xoopsTpl->assign('nonmember', _OD_NONMEMBER);
		$xoopsTpl->assign('enterwantedname', _OD_ENTERWANTEDNAME);
		$xoopsTpl->assign('screenamelabel', _OD_SCREENNAMELABEL);
		$xoopsTpl->assign('youropenid', _OD_YOUR_OPENID);
		include_once XOOPS_ROOT_PATH.'/footer.php';
	break;
	
	case OPENID_STEP_REGISTER:
		
		/**
		 * setting the step to the previous one for if there is an error, user will be redirected
		 * a step behind
		 */
		$_SESSION['openid_step'] = OPENID_STEP_NO_USER_FOUND;
		
		$sreg=$_SESSION['openid_sreg'];
		include_once(XOOPS_ROOT_PATH.'/header.php');
	
		/**
		 * @todo this is only temporary and it needs to be included in the template as a javascript check
		 */
		if (empty($_POST['email']) || empty($_POST['uname'])) {
			redirect_header(XOOPS_URL . '/finish_auth.php', 3, 'email and username are mandatory');
		}
		
		$email = addslashes($_POST['email']) ;
		
		$uname = addslashes($_POST['uname']) ;
		/**
		 * @todo use the related UserConfigOption
		 */
		if (strlen($uname)<3 ){ // Username too short.
			redirect_header(XOOPS_URL . '/finish_auth.php', 3, _US_OPENID_NEW_USER_UNAME_TOO_SHORT);
		}
	
		// checking if this uname is available
		$criteria = new CriteriaCompo(new Criteria('uname', $uname ));
		$user_handler =& xoops_gethandler('user');
		$users =& $user_handler->getObjects($criteria, false);
		
		if (is_array($users) && count($users) > 0) {
			redirect_header(XOOPS_URL . '/finish_auth.php', 3, _US_OPENID_NEW_USER_UNAME_EXISTS);
		}
	
		$email = addslashes($myts->stripSlashesGPC($sreg['email']));
		$name = addslashes($myts->stripSlashesGPC(utf8_decode($sreg['fullname'])));
		//$tz = quote_smart($tzoffset[$sreg['timezone']]);
		$country = addslashes($myts->stripSlashesGPC(utf8_decode($sreg['country'])));
	
		/**
		 * @todo use proper core class, manage activation_type and send notifications
		 */
		
		/**
		 
		if ($xoopsConfigUser['activation_type'] == 1) {
			$newuser->setVar('level', 1, true);
		}
		if (!$user_handler->insert($newuser, true)) {
			$this->setErrors(106, 'The new user could not be created. ' . $newuser->getHtmlErrors());
			return false;
		}
		$newid = $newuser->getVar('uid');
		$mship_handler = new XoopsMembershipHandler($xoopsDB);
		$mship = & $mship_handler->create();
		$mship->setVar('groupid', XOOPS_GROUP_USERS);
		$mship->setVar('uid', $newid);
		if (!$mship_handler->insert($mship, true)) {
			$this->setErrors(107, 'The new user was created but could not be added to the Registered Users group');
			return false;
		}
		$this->setErrors(111, 'We have created an account for you on this site.');
	
		if ($xoopsConfigUser['activation_type'] == 1) {
	
			//Sending a confirmation email to the newly registered user
	
			$myts = & MyTextSanitizer :: getInstance();
			$xoopsMailer = & getMailer();
			$xoopsMailer->useMail();
			$xoopsMailer->setTemplate('welcome.tpl');
			$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
			$xoopsMailer->assign('NAME', $sreg['fullname']);
			$xoopsMailer->assign('UNAME', $sreg['nickname']);
			$xoopsMailer->assign('X_UEMAIL', $sreg['email']);
			$xoopsMailer->assign('SITEURL', XOOPS_URL . "/");
			$xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
			$xoopsMailer->setToEmails($sreg['email']);
			$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
			$xoopsMailer->setFromName($xoopsConfig['sitename']);
			$xoopsMailer->setSubject(sprintf(_US_YOURINSCRIPTION, $myts->oopsStripSlashesGPC($xoopsConfig['sitename'])));
			$xoopsMailer->send();
	
			// Sending a notification email to a selected group when a new user registers
	
						if ($xoopsConfigUser['new_user_notify'] == 1 && !empty ($xoopsConfigUser['new_user_notify_group'])) {
				$xoopsMailer = & getMailer();
				$xoopsMailer->useMail();
				$xoopsMailer->setTemplate('newuser_notify.tpl');
				$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
				$xoopsMailer->assign('NAME', $sreg['fullname']);
				$xoopsMailer->assign('UNAME', $sreg['nickname']);
				$xoopsMailer->assign('X_UEMAIL', $sreg['email']);
				$xoopsMailer->assign('SITEURL', XOOPS_URL . "/");
				$xoopsMailer->setToGroups($member_handler->getGroup($xoopsConfigUser['new_user_notify_group']));
				$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
				$xoopsMailer->setFromName($xoopsConfig['sitename']);
				$xoopsMailer->setSubject(sprintf(_US_NEWUSERREGAT, $xoopsConfig['sitename']));
				$xoopsMailer->send();
			}
		}
		if ($xoopsConfigUser['activation_type'] == 0) {
			$this->setErrors(112, 'A confirmation email was sent to your email adress. Please follow the instructions to activate your account.');
			$xoopsMailer = & getMailer();
			$xoopsMailer->useMail();
			$xoopsMailer->setTemplate('register.tpl');
			$xoopsMailer->assign('NAME', $sreg['fullname']);
			$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
			$xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
			$xoopsMailer->assign('SITEURL', XOOPS_URL . "/");
			$xoopsMailer->setToUsers(new XoopsUser($newid));
			$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
			$xoopsMailer->setFromName($xoopsConfig['sitename']);
			$xoopsMailer->setSubject(sprintf(_US_USERKEYFOR, $sreg['nickname']));
			$xoopsMailer->send();
			if ($xoopsConfigUser['new_user_notify'] == 1 && !empty ($xoopsConfigUser['new_user_notify_group'])) {
				$xoopsMailer = & getMailer();
				$xoopsMailer->useMail();
				$xoopsMailer->setTemplate('newuser_notify.tpl');
				$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
				$xoopsMailer->assign('NAME', $sreg['fullname']);
				$xoopsMailer->assign('UNAME', $sreg['nickname']);
				$xoopsMailer->assign('X_UEMAIL', $sreg['email']);
				$xoopsMailer->assign('SITEURL', XOOPS_URL . "/");
				$xoopsMailer->setToGroups($member_handler->getGroup($xoopsConfigUser['new_user_notify_group']));
				$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
				$xoopsMailer->setFromName($xoopsConfig['sitename']);
				$xoopsMailer->setSubject(sprintf(_US_NEWUSERREGAT, $xoopsConfig['sitename']));
				$xoopsMailer->send();
			}
		}
		elseif ($xoopsConfigUser['activation_type'] == 2) {
			$this->setErrors(113, 'Your account will need to be approved by an administrator. You will receive a notification when it\s done.');
			$xoopsMailer = & getMailer();
			$xoopsMailer->useMail();
			$xoopsMailer->setTemplate('adminactivate.tpl');
			$xoopsMailer->assign('NAME', $sreg['fullname']);
			$xoopsMailer->assign('USERNAME', $sreg['nickname']);
			$xoopsMailer->assign('USEREMAIL', $sreg['email']);
			$xoopsMailer->assign('USERACTLINK', XOOPS_URL . '/user.php?op=actv&id=' . $newid . '&actkey=' . $actkey);
			$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
			$xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
			$xoopsMailer->assign('SITEURL', XOOPS_URL . "/");
			$member_handler = & xoops_gethandler('member');
			$xoopsMailer->setToGroups($member_handler->getGroup($xoopsConfigUser['activation_group']));
			$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
			$xoopsMailer->setFromName($xoopsConfig['sitename']);
			$xoopsMailer->setSubject(sprintf(_US_USERKEYFOR, $sreg['nickname']));
			$xoopsMailer->send();
		}
		if ($xoopsConfigUser['new_user_notify'] == 1 && !empty ($xoopsConfigUser['new_user_notify_group'])) {
			 // Sending a notification email to a selected group when a new user registers
			if ($xoopsConfigUser['new_user_notify'] == 1 && !empty ($xoopsConfigUser['new_user_notify_group'])) {
				$xoopsMailer = & getMailer();
				$xoopsMailer->useMail();
				$xoopsMailer->setTemplate('newuser_notify.tpl');
				$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
				$xoopsMailer->assign('NAME', $sreg['fullname']);
				$xoopsMailer->assign('UNAME', $sreg['nickname']);
				$xoopsMailer->assign('X_UEMAIL', $sreg['email']);
				$xoopsMailer->assign('SITEURL', XOOPS_URL . "/");
				$xoopsMailer->setToGroups($member_handler->getGroup($xoopsConfigUser['new_user_notify_group']));
				$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
				$xoopsMailer->setFromName($xoopsConfig['sitename']);
				$xoopsMailer->setSubject(sprintf(_US_NEWUSERREGAT, $xoopsConfig['sitename']));
				$xoopsMailer->send();
			}
		}
		*/
		$newUser = $member_handler->createUser();
		$newUser->setVar('uname', $uname);
		$newUser->setVar('email', $email);
		$newUser->setVar('name', $name);
		$newUser->setVar('pass', '*');
		$newUser->setVar('user_regdate', time());
		$newUser->setVar('level', 1);
		$newUser->setVar('country', $country);
		$newUser->setVar('timesone_offset', $xoopsConfig['default_TZ']);
		$newUser->setVar('openid', $xoopsAuth->openid);
		if (!$member_handler->insertUser($newUser)) {
			redirect_header(XOOPS_URL . '/finish_auth.php', 3, _US_OPENID_NEW_USER_CANNOT_INSERT . ' ' . $newUser->getHtmlErrors());
		}
	
		// Now, add the user to the group.
		$newid = $newUser->getVar('uid');
		$mship_handler = xoops_getHandler('membership');
		$mship = & $mship_handler->create();
		$mship->setVar('groupid', XOOPS_GROUP_USERS);
		$mship->setVar('uid', $newid);
		if (!$mship_handler->insert($mship)) {
			redirect_header($redirect_url, 3, _US_OPENID_NEW_USER_CANNOT_INSERT_INGROUP);
		}
	
		// Login with this user.
	
		/**
		 * @todo use proper login process (include/checklogin.php)
		 */
		if ($newUser->getVar('level') == 0) {
			redirect_header($redirect_url, 3, _US_OPENID_NEW_USER_AUTH_NOT_ACTIVATED);
		}
		
		$_SESSION['xoopsUserId'] = $newUser->getVar('uid');
		$_SESSION['xoopsUserGroups'] = $newUser->getGroups();
		$user_theme = $newUser->getVar('theme');
		
		if (in_array($user_theme, $xoopsConfig['theme_set_allowed'])) {
			$_SESSION['xoopsUserTheme'] = $user_theme;
		}
	
		unset($_SESSION['openid_response']);
		unset($_SESSION['openid_sreg']);
		unset($_SESSION['frompage']);
		
		redirect_header($redirect_url, 3, sprintf(_US_OPENID_NEW_USER_CREATED, $newUser->getVar('uname')));		
		
	break;
	
	case OPENID_STEP_USER_FOUND:
		include_once("include/checklogin.php");
		exit;
	break;
	
	case OPENID_STEP_LINK:
		/**
		 * Linking an existing user with this openid
		 */
		include_once(XOOPS_ROOT_PATH.'/header.php');
		
		$uname4sql = addslashes($myts->stripSlashesGPC($_POST['uname'])) ;
		$pass4sql = addslashes( $myts->stripSlashesGPC($_POST['pass']) ) ;
		
		$thisUser = $member_handler->loginUser($uname4sql, $pass4sql);
	
		if (!$thisUser) {
			redirect_header($redirect_url, 3, _US_OPENID_LINKED_AUTH_FAILED);
		}
		
		if ($thisUser->getVar('level') == 0) {
			redirect_header($redirect_url, 3, _US_OPENID_LINKED_AUTH_NOT_ACTIVATED);
		}
		
		// This means the authentication succeeded.
	    $displayId = $xoopsAuth->response->getDisplayIdentifier();
	
		$thisUser->setVar('last_login', time());
		$thisUser->setVar('openid', $xoopsAuth->openid);
		
		if (!$member_handler->insertUser($thisUser)) {
			redirect_header($redirect_url, 3, _US_OPENID_LINKED_AUTH_CANNOT_SAVE);
		}
		
		$_SESSION['xoopsUserId'] = $thisUser->getVar('uid');
		$_SESSION['xoopsUserGroups'] = $thisUser->getGroups();
		$user_theme = $thisUser->getVar('theme');
		
		if (in_array($user_theme, $xoopsConfig['theme_set_allowed'])) {
			$_SESSION['xoopsUserTheme'] = $user_theme;
		}
	
		unset($_SESSION['openid_response']);
		unset($_SESSION['openid_sreg']);
		unset($_SESSION['frompage']);
		
		redirect_header($redirect_url, 3, sprintf(_US_OPENID_LINKED_DONE, $thisUser->getVar('uname')));		
	break;
		
}
?>