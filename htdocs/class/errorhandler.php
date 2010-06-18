<?php
/**
 * ErrorHandler Class
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @package	core
 * @subpackage ErrorHandler
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id$
 */

defined( 'ICMS_ROOT_PATH' ) or die();

require_once ICMS_ROOT_PATH . '/class/logger.php';

/**
 * Backward compatibility code, do not use this class directly
 */
class XoopsErrorHandler extends core_Logger {
	/**
	 * Activate the error handler
	 * @param   string  $showErrors
	 */
	function activate( $showErrors = false ) {
		$this->activated = $showErrors;
	}

	/**
	 * Render the list of errors
	 * @return   string  $list of errors
	 */
	function renderErrors() {
		return $this->dump( 'errors' );
	}
}

?>