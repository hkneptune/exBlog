<?php
require_once("./upgrade.php");
$required_version_s = "01050020050710";
$required_version = "V 1.5.0 alpha D1";
$destination_version_s = "01050020050905";
$destination_version = "V 1.5.0 alpha D2";

$sql_query_string[] = "update %%%PREFIX%%%keyword change content content text not null";
$sql_query_string[] = "alter table %%%PREFIX%%%admin change uid uid tinyint(1)";
$sql_query_string[] = "alter table %%%PREFIX%%%upload type=MYISAM";
$sql_query_string[] = "alter table %%%PREFIX%%%upload add fulltext(destination_folder), add max_width tinyint(4) default 1, add max_height tinyint(4) default 1, add watermark_type enum('string','image') default 'string', add watermark text, modify destination_folder text";
// 2005-08-16
$sql_query_string[] = "alter table %%%PREFIX%%%upload add url text default ''";
$new_config_items[] = "\$exblog['charset'] = 'gb2312';";

$upgrade_op = new Upgrade();
$upgrade_op->setRequireVersion($required_version_s, $required_version);
$upgrade_op->setDestinationVersion($destination_version_s, $destination_version);
$upgrade_op->_addSqlSentences($sql_query_string);
$upgrade_op->_addConfigItems($new_config_items);

$upgrade_op->startUpgrade();

unset($upgrade_op);
?>
