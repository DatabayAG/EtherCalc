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

require_once './Customizing/global/plugins/Services/Repository/RepositoryObject/EtherCalc/classes/class.ilObjEtherCalcAccess.php';

/**
 * Class ilObjEtherCalcListGUI
 */
class ilObjEtherCalcListGUI extends ilObjectPluginListGUI
{
    public function initType()
    {
        $this->setType('xetc');
    }

    public function getGuiClass(): string
    {
        return 'ilObjEtherCalcGUI';
    }

    public function initCommands(): array
    {
        return
        [
            [
                'permission' => 'read',
                'cmd' => 'showContent',
                'default' => true
            ],
            [
                'permission' => 'write',
                'cmd' => 'editProperties',
                'txt' => $this->txt('edit'),
                'default' => false
            ],
        ];
    }

    public function getProperties(): array
    {
        $props = [];
        if (!ilObjEtherCalcAccess::checkOnline($this->obj_id)) {
            $props[] = [
                'alert' => true,
                'property' => $this->txt('status'),
                'value' => $this->txt('offline')
            ];
        }

        return $props;
    }
}
