<?php

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
