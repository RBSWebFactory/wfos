<?php
class wfos_SequenceMysqlImpl implements wfos_Sequence {
	/**
	 * 
	 * @var string
	 */
	protected $name;
	
	/**
	 * 
	 * @var string
	 */
	protected $startValue;
	
	/**
	 * 
	 * @param string $name
	 * @param int $startValue
	 */
	public function __construct($name, $startValue = 1)
	{
		$this->name = $name;
		$this->startValue = $startValue;
	}
	
	/**
	 * return string
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * @return int
	 */
	public function getValue()
	{
		$this->switchDatabaseProfileIfNeeded();
		$pp = f_persistentdocument_PersistentProvider::getInstance();
		$pdo = $pp->getDriver();
		$pp->executeSQLScript("INSERT INTO `".$this->getTableName()."` VALUES (NULL)");
		return $pdo->lastInsertId();
	}
	
	/**
	 * WARNING: do not call init inside a transaction !
	 * It will implicitely commit it !
	 * @see http://dev.mysql.com/doc/refman/5.1/en/implicit-commit.html
	 */
	public function init()
	{
		$this->createTable();
	}
	
	// PROTECTED
	
	/**
	 * return void
	 */
	protected function createTable()
	{
		$this->switchDatabaseProfileIfNeeded();
		$pp = f_persistentdocument_PersistentProvider::getInstance();
		$pp->executeSQLScript("CREATE TABLE IF NOT EXISTS `".$this->getTableName()."` (
			`value` INT(11) NOT NULL auto_increment,
			PRIMARY KEY  (`value`)
				) ENGINE=InnoDB AUTO_INCREMENT=".$this->startValue.";");
	}
	
	protected function switchDatabaseProfileIfNeeded()
	{
		if (ModuleService::getInstance()->moduleExists("clusterscale") &&
				clusterscale_PersistentProvider::useSeparateReadWrite() &&
				f_persistentdocument_PersistentProvider::getDatabaseProfileName() !== "default")
		{
			f_persistentdocument_PersistentProvider::setDatabaseProfileName("default");
		}
	}
	
	/**
	 * return string
	 */
	protected function getTableName()
	{
		return "m_wfos_sequence_".$this->name;
	}
}
