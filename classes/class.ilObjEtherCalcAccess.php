<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 *
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 *
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *
 *********************************************************************/

declare(strict_types=1);

class ilObjEtherCalcAccess extends ilObjectPluginAccess
{
    public static function checkOnline(int $a_id): bool
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
