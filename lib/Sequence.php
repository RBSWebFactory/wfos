<?php
interface wfos_Sequence {
	/**
	 * Init sequence. Must be called before any getValue() invocation.
	 */
	public function init();
	/**
	 * return string
	 */
	public function getName();
	/**
	 * @return int
	 */
	public function getValue();
}