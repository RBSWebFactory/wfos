<?php
/**
 * @package modules.wfos.lib.services
*/
class wfos_SequenceService extends BaseService
{
	/**
	 * Singleton
	 * @var wfos_SequenceService
	 */
	private static $instance = null;

	/**
	 * @return wfos_SequenceService
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}
	
	/**
	 *
	 * @param string $name
	 * @param int $startValue
	 * @return wfos_Sequence
	 */
	function getSequence($name, $startValue = 1)
	{
		return new wfos_SequenceMysqlImpl($name, $startValue);
	}
}