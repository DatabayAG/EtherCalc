<?php

require_once './Services/Repository/classes/class.ilObjectPluginAccess.php';

/**
 * Class ilObjEtherCalcAccess
 */
class ilObjEtherCalcAccess extends ilObjectPluginAccess
{

	/**
	 * @param string $a_cmd
	 * @param string $a_permission
	 * @param int    $a_ref_id
	 * @param int    $a_obj_id
	 * @param string $a_user_id
	 * @return bool
	 */
	function _checkAccess($a_cmd, $a_permission, $a_ref_id, $a_obj_id, $a_user_id = '')
	{
		/**
		 * @var ilObjUser $ilUser
		 * @var ilAccessHandler $ilAccess
		 */
		global $ilUser, $ilAccess;

		if ($a_user_id == '')
		{
			$a_user_id = $ilUser->getId();
		}

		switch ($a_permission)
		{
			case 'read':
				if (!ilObjEtherCalcAccess::checkOnline($a_obj_id) &&
					!$ilAccess->checkAccessOfUser($a_user_id, 'write', '', $a_ref_id))
				{
					return false;
				}
				break;
		}

		return true;
	}
	
	/**
	* Check online status of example object
	*/
	static function checkOnline($a_id)
	{
		/**
		 * @var ilDB $ilDB
		 */
		global $ilDB;
		
		$set = $ilDB->query('SELECT is_online FROM rep_robj_xetc_data  WHERE id = '.$ilDB->quote($a_id, 'integer'));
		$rec  = $ilDB->fetchAssoc($set);
		return (boolean) $rec['is_online'];
	}
	
}