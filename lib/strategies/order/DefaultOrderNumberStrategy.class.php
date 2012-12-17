<?php
/**
 * An order number generator strategy that concatenates year (on 4 digits)
 * with a 8 character left formatted integer sequence.
 * 
 * "8" is configurable:
 * <modules>
 * 	<wfos>
 * 		<entry name="defaultOrderNumberStrategyPad">8</entry>
 * 	</wfos>
 * </modules>
 * 
 * @example 201200000001, 201200000002, ... 
 * @author intsimoa
 * @see order_OrderNumberStrategy
 * @see wfos_SequenceService
 */
class wfos_DefaultOrderNumberStrategy implements order_OrderNumberStrategy
{
	/**
	 * @param order_persistentdocument_order $order
	 * @return string
	 */
	public function generate($order)
	{
		$prefix = date("Y");
		$newCount = wfos_SequenceService::getInstance()->getSequence("order$prefix")->getValue();
		$newCount = str_pad($newCount, Framework::getConfigurationValue("modules/wfos/defaultOrderNumberStrategyPad", 8), "0", STR_PAD_LEFT);
		return $prefix.$newCount;
	}
	
	/**
	 * 
	 * @param string $prefix
	 */
	public function init($prefix = null)
	{
		if ($prefix === null)
		{
			$prefix = date("Y");
		}
		$startValue = intval(substr(f_util_ArrayUtils::firstElement(order_OrderService::getInstance()
				->createQuery()
				->add(Restrictions::like("orderNumber", $prefix, MatchMode::START()))
				->setProjection(Projections::max("orderNumber", "n"))
				->findColumn("n")), strlen($prefix)))+1;
		wfos_SequenceService::getInstance()->getSequence("order$prefix", $startValue)->init();
	}
}
