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
 * @deprecated	Use icms_core_Logger, instead
 * @todo		Remove in version 1.4
 */
class XoopsErrorHandler extends icms_core_Logger {
	private $_deprecated;

	public function __construct() {
		parent::instance();
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_core_Logger', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

?>