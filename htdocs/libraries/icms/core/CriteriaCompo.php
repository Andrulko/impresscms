<?php
/**
 * icms_core_CriteriaCompo
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.3
 * @author		marcan <marcan@impresscms.org>
 * @version		$Id: criteriacompo.php 19133 2010-04-17 14:28:40Z skenow $
 */

if( !defined( "ICMS_ROOT_PATH" ) ) die( "ImpressCMS root path not defined" );

/**
 * Collection of multiple {@link icms_core_CriteriaElement}s
 *
 * @package     kernel
 * @subpackage  database
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class icms_core_CriteriaCompo extends icms_core_CriteriaElement {

	/**
	 * The elements of the collection
	 * @var	array   Array of {@link icms_core_CriteriaElement} objects
	 */
	public $criteriaElements = array();

	/**
	 * Conditions
	 * @var	array
	 */
	public $conditions = array();

	/**
	 * Constructor
	 *
	 * @param   object  $ele
	 * @param   string  $condition
	 **/
	public function __construct($ele=null, $condition='AND') {
		if (isset($ele) && is_object($ele)) {
			$this->add($ele, $condition);
		}
	}

	/**
	 * Add an element
	 *
	 * @param   object  &$criteriaElement
	 * @param   string  $condition
	 *
	 * @return  object  reference to this collection
	 **/
	public function &add(&$criteriaElement, $condition='AND') {
		$this->criteriaElements[] =& $criteriaElement;
		$this->conditions[] = $condition;
		return $this;
	}

	/**
	 * Make the criteria into a query string
	 *
	 * @return	string
	 */
	public function render() {
		$ret = '';
		$count = count($this->criteriaElements);
		if ($count > 0) {
			$ret = '('. $this->criteriaElements[0]->render();
			for ($i = 1; $i < $count; $i++) {
				$ret .= ' '.$this->conditions[$i].' '.$this->criteriaElements[$i]->render();
			}
			$ret .= ')';
		}
		return $ret;
	}

	/**
	 * Make the criteria into a SQL "WHERE" clause
	 *
	 * @return	string
	 */
	public function renderWhere() {
		$ret = $this->render();
		$ret = ($ret != '') ? 'WHERE ' . $ret : $ret;
		return $ret;
	}

	/**
	 * Generate an LDAP filter from criteria
	 *
	 * @return string
	 * @author Nathan Dial ndial@trillion21.com
	 */
	public function renderLdap() {
		$retval = '';
		$count = count($this->criteriaElements);
		if ($count > 0) {
			$retval = $this->criteriaElements[0]->renderLdap();
			for ($i = 1; $i < $count; $i++) {
				$cond = $this->conditions[$i];
				if(strtoupper($cond) == 'AND'){
					$op = '&';
				} elseif (strtoupper($cond)=='OR'){
					$op = '|';
				}
				$retval = "($op$retval" . $this->criteriaElements[$i]->renderLdap().")";
			}
		}
		return $retval;
	}
}
