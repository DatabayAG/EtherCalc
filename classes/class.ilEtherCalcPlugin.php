<?php declare(strict_types=1);


/**
 * Class ilEtherCalcPlugin
 */
class ilEtherCalcPlugin extends ilRepositoryObjectPlugin
{
    public function __construct()
    {
        global $DIC;
        $this->db = $DIC->database();

    }
    protected function uninstallCustom() : void
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
    function getPluginName() : string
    {
        return 'EtherCalc';
    }

}
