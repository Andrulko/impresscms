<?php
/**
 * Manage groups
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Member
 * @subpackage	Group
 * @author		Kazumi Ono (aka onokazo)
 * @version		SVN: $Id$
 */

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

/**
 * Group handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of group class objects.
 *
 * @author Kazumi Ono <onokazu@xoops.org>
 * @category	ICMS
 * @package		Member
 * @subpackage	Group
 */
class icms_member_group_Handler extends icms_core_ObjectHandler {

	/**
	 * create a new {@link icms_member_group_Object} object
	 *
	 * @param bool $isNew mark the new object as "new"?
	 * @return object icms_member_group_Object {@link icms_member_group_Object} reference to the new object
	 * @see icms_core_ObjectHandler#create()
	 */
	public function &create($isNew = true) {
		$group = new icms_member_group_Object();
		if ( $isNew ) {
			$group->setNew();
		}
		return $group;
	}

	/**
	 * retrieve a specific group
	 *
	 * @param int $id ID of the group to get
	 * @return object icms_member_group_Object {@link icms_member_group_Object} reference to the group object, FALSE if failed
	 * @see icms_core_ObjectHandler#get($int_id)
	 */
	public function &get($id) {
		$id = (int) $id;
		$group = false;
		if ( $id > 0 ) {
			$sql = "SELECT * FROM " . $this->db->prefix('groups') . " WHERE groupid='" . $id . "'";
			if ( !$result = $this->db->query($sql) ) {
				return $group;
			}
			$numrows = $this->db->getRowsNum($result);
			if ( $numrows == 1 ) {
				$group = new icms_member_group_Object();
				$group->assignVars($this->db->fetchArray($result));
			}
		}
		return $group;
	}

	/**
	 * insert a group into the database
	 *
	 * @param object reference to the group object
	 * @return mixed ID of the group if inserted, FALSE if failed, TRUE if already present and unchanged.
	 * @see icms_core_ObjectHandler#insert($object)
	 */
	public function insert(&$group) {
		/* As of PHP5.3.0, is_a()is no longer deprecated, so there is no reason to replace it */
		if ( !is_a($group, 'icms_member_group_Object') ) {
			return false;
		}
		if ( !$group->isDirty() ) {
			return true;
		}
		if ( !$group->cleanVars() ) {
			return false;
		}
		foreach ( $group->cleanVars as $k => $v ) {
			${$k} = $v;
		}
		if ( $group->isNew() ) {
			$groupid = $this->db->genId('group_groupid_seq');
			$sql = sprintf("INSERT INTO %s (groupid, name, description, group_type)
				VALUES ('%u', %s, %s, %s)",
				$this->db->prefix('groups'),
				(int) $groupid,
				$this->db->quoteString($name),
				$this->db->quoteString($description),
				$this->db->quoteString($group_type)
			);
		} else {
			$sql = sprintf(
				"UPDATE %s SET name = %s, description = %s, group_type = %s WHERE groupid = '%u'",
				$this->db->prefix('groups'),
				$this->db->quoteString($name),
				$this->db->quoteString($description),
				$this->db->quoteString($group_type),
				(int) $groupid
			);
		}
		if ( !$result = $this->db->query($sql) ) {
			return false;
		}
		if ( empty($groupid) ) {
			$groupid = $this->db->getInsertId();
		}
		$group->assignVar('groupid', $groupid);
		return true;
	}

	/**
	 * remove a group from the database
	 *
	 * @param object $group reference to the group to be removed
	 * @return bool FALSE if failed
	 * @see icms_core_ObjectHandler#delete($object)
	 */
	public function delete(&$group) {
		/* As of PHP5.3.0, is_a() is no longer deprecated and there is no need to replace it */
		if ( !is_a($group, 'icms_member_group_Object') ) {
			return false;
		}
		$sql = sprintf(
			"DELETE FROM %s WHERE groupid = '%u'",
			$this->db->prefix('groups'),
			(int) $group->getVar('groupid')
		);
		if ( !$result = $this->db->query($sql) ) {
			return false;
		}
		return true;
	}

	/**
	 * retrieve groups from the database
	 *
	 * @param object $criteria {@link icms_criteria_Element} with conditions for the groups
	 * @param bool $id_as_key should the groups' IDs be used as keys for the associative array?
	 * @return mixed Array of groups
	 */
	public function getObjects($criteria = null, $id_as_key = false) {
		$ret = array();
		$limit = $start = 0;
		$sql = "SELECT * FROM " . $this->db->prefix('groups');
		if ( isset($criteria) && is_subclass_of($criteria, 'icms_criteria_Element') ) {
			$sql .= " " . $criteria->renderWhere();
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$result = $this->db->query($sql, $limit, $start);
		if ( !$result ) {
			return $ret;
		}
		while ( $myrow = $this->db->fetchArray($result) ) {
			$group = new icms_member_group_Object();
			$group->assignVars($myrow);
			if ( !$id_as_key ) {
				$ret[] =& $group;
			} else {
				$ret[$myrow['groupid']] =& $group;
			}
			unset($group);
		}
		return $ret;
	}
}
