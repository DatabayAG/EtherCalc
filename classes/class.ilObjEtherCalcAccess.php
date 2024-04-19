<?php

declare(strict_types=1);


/**
 * Class ilObjEtherCalcAccess
 */
class ilObjEtherCalcAccess extends ilObjectPluginAccess
{
    /**
     * @return bool
     */
    public static function checkOnline($a_id)
    {
        /**
         * @var $ilDB ilDBInterface
         */
        global $ilDB;

        $set = $ilDB->query('SELECT is_online FROM rep_robj_xetc_data  WHERE id = ' . $ilDB->quote($a_id, 'integer'));
        $rec = $ilDB->fetchAssoc($set);
        return (bool) $rec['is_online'];
    }

    public function _checkAccess(string $cmd, string $permission, int $ref_id, int $obj_id, ?int $user_id = null): bool
    {
        /**
         * @var ilObjUser       $ilUser
         * @var ilAccessHandler $ilAccess
         */
        global $ilUser, $ilAccess;

        if ($user_id == '') {
            $user_id = $ilUser->getId();
        }

        switch ($permission) {
            case 'read':
                if (!ilObjEtherCalcAccess::checkOnline($obj_id) &&
                    !$ilAccess->checkAccessOfUser($user_id, 'write', '', $ref_id)) {
                    return false;
                }
                break;
        }

        return true;
    }

}
