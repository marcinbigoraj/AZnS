<?php

$system_path = 'system';
define('BASEPATH', str_replace("\\", "/", $system_path));
require_once('application/config/config.php');

file_get_contents($config['base_url'].'index.php/basicdataservice/removeEndedAuctions');
file_get_contents($config['base_url'].'index.php/basicdataservice/getNewVersion');
file_get_contents($config['base_url'].'index.php/searchservice/searchallnews');

?>