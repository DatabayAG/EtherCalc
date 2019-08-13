<?php declare(strict_types=1);

require_once './Services/Repository/classes/class.ilRepositoryObjectPlugin.php';

/**
 * Class ilEtherCalcPlugin
 */
class ilEtherCalcPlugin extends ilRepositoryObjectPlugin
{
    protected function uninstallCustom()
    {
        /**
         * @var $ilDB ilDBInterface
         */
        global $ilDB;
        $ilDB->query('DROP TABLE rep_robj_xetc_data');
    }

    /**
     * @return string
     */
    function getPluginName()
    {
        return 'EtherCalc';
    }

}
