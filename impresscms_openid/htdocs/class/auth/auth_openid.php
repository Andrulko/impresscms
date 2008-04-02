<?php
/**
* Authentification class for OpenID protocol
*
* This class handles the authentication of a user with its openid. If the the authenticate openid
* is not found in the users database, the user will be able to create his account on this site or
* associate its openid with is already registered account. This process is also taking into
* consideration $xoopsConfigUser['activation_type'].
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		openid
* @since		1.1
* @author		malanciault <marcan@impresscms.org)
* @credits		Sakimura <http://www.sakimura.org/> Evan Prodromou <http://evan.prodromou.name/>
* @version		$Id$
*/

define('OPENID_STEP_BACK_FROM_SERVER', 1);
define('OPENID_STEP_REGISTER', 2);
define('OPENID_STEP_LINK', 3);
define('OPENID_STEP_NO_USER_FOUND', 4);
define('OPENID_STEP_USER_FOUND', 5);

class XoopsAuthOpenid extends XoopsAuth {

	/**
	 * @var string $displayid $displayid fetch from the openid authentication
	 */
	var $displayid;

	/**
	 * @var string $openid openid used for this authentication
	 */
	var $openid;
	
	/**
	 * OpenID response
	 *
	 * @var OpenIDResponse object
	 */
	var $response;	
	
	/**
	 * Where are we in the process
	 * Possible options are
	 *   - OPENID_STEP_BACK_FROM_SERVER
	 *   - OPENID_STEP_REGISTER
	 *   - OPENID_STEP_LINK
	 *   - OPENID_STEP_NO_USER_FOUND
	 * @var int
	 */
	var $step = OPENID_STEP_BACK_FROM_SERVER;
	
	/**
	 * Authentication Service constructor
	 */
	function XoopsAuthOpenid (&$dao) {
		$this->_dao = $dao;
		$this->auth_method = 'openid';
	}

	/**
	 * Authenticate using the OpenID protocol
	 *
     * @return bool successful?
	 */
	function authenticate() {
		require_once ICMS_LIBRARIES_ROOT_PATH . "/phpopenid/occommon.php";

		// session_start();
		
		// check to see if we alredy have an OpenID response in SESSION
		if (isset($_SESSION['openid_response'])) {
			$this->response = $_SESSION['openid_response'];
		} else {
			// Complete the authentication process using the server's response.
			$consumer = getConsumer();//1123
			$return_to = getReturnTo();//1123
			//$this->response = $consumer->complete($_GET);
			$this->response = $consumer->complete($return_to);//1123
			$_SESSION['openid_response']=$this->response;
		}
		
		if ($this->response->status == Auth_OpenID_CANCEL) {
		    // This means the authentication was cancelled.
		    $this->setErrors('100', 'Verification cancelled.');
		} else if ($this->response->status == Auth_OpenID_FAILURE) {
		    $this->setErrors('101', "OpenID authentication failed: " . $this->response->message);
			/**
			 * This can be uncommented to display the $_REQUEST array. This is usefull for
			 * troubleshooting purposes
			 */
			 //$this->setErrors('102', "REQUEST info: <pre>" . var_export($_REQUEST, true) . "</pre>");
			 return false;
		} else if ($this->response->status == Auth_OpenID_SUCCESS) {
		    // This means the authentication succeeded.
			$this->displayid = $this->response->getDisplayIdentifier();
			$this->openid = $this->response->identity_url;
			$sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($this->response);
			$sreg = $sreg_resp->contents();
			$_SESSION['openid_sreg']=$sreg;

		    // $openid = $this->response->identity_url;
		    $esc_identity = htmlspecialchars($this->openid, ENT_QUOTES);

		    $success = "You have successfully verified $esc_identity (" . $this->displayid . ") as your identity.";

		    if ($this->response->endpoint->canonicalID) {
		        $success .= '  (XRI CanonicalID: '.$this->response->endpoint->canonicalID.') ';
		    }

			/**
			 * This can be uncommented to display the $success info for troubleshooting purposes
			 */
		    //$this->setErrors('103', $success);

		    /**
		     * Now, where are we in the process, just back from OpenID server or trying to register or 
		     * trying to link to an existing account
		     */
		    if (isset($_POST['openid_register'])) {
		    	$this->step = OPENID_STEP_REGISTER;
		    } elseif (isset($_POST['openid_link'])) {
		    	$this->step = OPENID_STEP_LINK;
		    } elseif(isset($_SESSION['openid_step'])) {
		    	$this->step = $_SESSION['openid_step'];
		    } else {
				// Do we already have a user with this openid
				$member_handler = & xoops_gethandler('member');
				$criteria = new CriteriaCompo();
				$criteria->add(new Criteria('openid', $this->openid));
				$users =& $member_handler->getUsers($criteria);
				if ($users && count($users) > 0) {
					$this->step = OPENID_STEP_USER_FOUND;
					return $users[0];
			    } else {
			    	/*
			    	 * This openid was not found in the users table.Let's ask the user if he wants
			    	 * to create a new user account on the site or else login with his already registered
			    	 * account
			    	 */
					$this->step = OPENID_STEP_NO_USER_FOUND;
					return false;
			    }
		    }
		}
	}
	
	function errorOccured() {
		return count($this->getErrors);
	}
}

?>