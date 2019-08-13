<?php declare(strict_types=1);

require_once './Services/Repository/classes/class.ilObjectPluginListGUI.php';
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
    function getGuiClass()
    {
        return 'ilObjEtherCalcGUI';
    }

    /**
     * @return array
     */
    function initCommands()
    {
        return array
        (
            array(
                'permission' => 'read',
                'cmd'        => 'showContent',
                'default'    => true
            ),
            array(
                'permission' => 'write',
                'cmd'        => 'editProperties',
                'txt'        => $this->txt('edit'),
                'default'    => false
            ),
        );
    }

    /**
     * @param string $a_item
     * @return array
     */
    function getProperties($a_item = '')
    {
        $props = array();
        if (!ilObjEtherCalcAccess::checkOnline($this->obj_id)) {
            $props[] = array(
                'alert'    => true,
                'property' => $this->txt('status'),
                'value'    => $this->txt('offline')
            );
        }

        return $props;
    }
}
