<?php

declare(strict_types=1);


/**
 * Class ilEtherCalcPlugin
 */
class ilEtherCalcPlugin extends ilRepositoryObjectPlugin
{
    public const ID = "xetc";

    /**
     * @var string
     */
    public const CTYPE = 'Services';

    /**
     * @var string
     */
    public const CNAME = 'Repository';

    /**
     * @var string
     */
    public const SLOT_ID = 'robj';

    /**
     * @var string
     */
    public const PNAME = 'EtherCalc';


    /**
     * @return null
     */


    public function getPluginName(): string
    {
        return self::PNAME;
    }
    protected function uninstallCustom(): void
    {
        /**
         * @var $ilDB ilDBInterface
         */
        global $ilDB;
        $ilDB->query('DROP TABLE rep_robj_xetc_data');
    }

}
