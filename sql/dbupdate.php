<#1>
<?php
$fields = array(
	'id' => array(
		'type' => 'integer',
		'length' => 4,
		'notnull' => true
	),
	'is_online' => array(
		'type' => 'integer',
		'length' => 1,
		'notnull' => true,
		'default' => 0
	)
);

$ilDB->createTable("rep_robj_xetc_data", $fields);
$ilDB->addPrimaryKey("rep_robj_xetc_data", array("id"));
?>
<#2>
<?php
?>
<#3>
<?php
if(!$ilDB->tableColumnExists('rep_robj_xetc_data', 'page_id'))
{
	$ilDB->addTableColumn('rep_robj_xetc_data', 'page_id',
		array(
			'notnull'=> false,
			'type'   => 'text',
			'length' => '500'
		)
	);
}
?>
<#4>
<?php
if(!$ilDB->tableColumnExists('rep_robj_xetc_data', 'fullscreen'))
{
	$ilDB->addTableColumn('rep_robj_xetc_data', 'fullscreen',
		array(
			'type' => 'integer',
			'length' => 1,
			'notnull' => false
		)
	);
}
?>
