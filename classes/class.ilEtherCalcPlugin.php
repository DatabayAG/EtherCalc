<?php declare(strict_types=1);


/**
 * Class ilEtherCalcPlugin
 */
class ilEtherCalcPlugin extends ilRepositoryObjectPlugin
{

    public const ID = "xetc";

    /**
     * @var string
     */
    const CTYPE = 'Services';

    /**
     * @var string
     */
    const CNAME = 'Repository';

    /**
     * @var string
     */
    const SLOT_ID = 'robj';

    /**
     * @var string
     */
    const PNAME = 'EtherCalc';


    /**
     * @return null
     */


    /**
     * @return string
     */
    public function getPluginName() : string
    {
        return self::PNAME;
    }
    protected function uninstallCustom() : void
    {
        /**
         * @var $ilDB ilDBInterface
         */
        global $ilDB;
        $ilDB->query('DROP TABLE rep_robj_xetc_data');
    }

}
