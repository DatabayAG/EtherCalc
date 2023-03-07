<?php declare(strict_types=1);

require_once './Customizing/global/plugins/Services/Repository/RepositoryObject/EtherCalc/classes/class.ilObjEtherCalcAccess.php';

/**
 * Class ilObjEtherCalcListGUI
 */
class ilObjEtherCalcListGUI extends ilObjectPluginListGUI
{

    function initType()
    {
        $this->setType('xetc');
    }

    /**
     * @return string
     */
    public function getGuiClass(): string
    {
        return 'ilObjEtherCalcGUI';
    }

    /**
     * @return array
     */
    public function initCommands(): array
    {
        return array
        (
            array(
                'permission' => 'read',
                'cmd' => 'showContent',
                'default' => true
            ),
            array(
                'permission' => 'write',
                'cmd' => 'editProperties',
                'txt' => $this->txt('edit'),
                'default' => false
            ),
        );
    }

    /**
     * @return array
     */
    public function getProperties() : array
    {
        $props = array();
        if (!ilObjEtherCalcAccess::checkOnline($this->obj_id)) {
            $props[] = array(
                'alert' => true,
                'property' => $this->txt('status'),
                'value' => $this->txt('offline')
            );
        }

        return $props;
    }
}
