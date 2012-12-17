<?php
define("WEBEDIT_HOME", realpath("."));
require_once WEBEDIT_HOME."/framework/Framework.php";

// Test we are compatible with clusterscale
RequestContext::getInstance()->setMode(RequestContext::FRONTOFFICE_MODE);

// This could be the content of your patch when migrating to wfos_OrderNumberDefaultStrategy
$strategy = new wfos_DefaultOrderNumberStrategy();
$prefix = date("Y");
for ($i = 0; $i < 20; $i++) // Init for next 20 years
{
	$strategy->init($prefix);
	$prefix++;
}
// End of content

$tm = f_persistentdocument_TransactionManager::getInstance();
try
{
	$tm->beginTransaction();
	echo $strategy->generate(null)."\n";
	//throw new Exception("Bla");
	$tm->commit();
}
catch (Exception $e)
{
	$tm->rollBack($e);
}

//echo "After rollback: ".$strategy->generate(null)."\n";
