<?php

require_once './Services/Repository/classes/class.ilRepositoryObjectPlugin.php';

/**
 * Class ilEtherCalcPlugin
 */
class ilEtherCalcPlugin extends ilRepositoryObjectPlugin
{
	/**
	 * @return string
	 */
	function getPluginName()
	{
		return 'EtherCalc';
	}

	protected function uninstallCustom()
	{
		/**
		 * @var $ilDB ilDB
		 */
		global $ilDB;
		$ilDB->query('DROP TABLE rep_robj_xetc_data');
	}

}
?>
