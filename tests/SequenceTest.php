<?php
define("WEBEDIT_HOME", realpath("."));
require_once WEBEDIT_HOME."/framework/Framework.php";

$sequence = wfos_SequenceService::getInstance()->getSequence("test", 10);
$sequence->init();
for ($i = 0; $i < 100; $i++)
{
	echo $sequence->getValue()."\n";
}