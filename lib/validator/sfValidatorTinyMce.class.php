<?php

class sfValidatorTinyMce extends sfValidatorString
{

	/**
	 * @see sfValidatorBase
	 */
	protected function doClean($value)
	{
		$clean = (string) $value;

		$clean = preg_replace('/<p[^>]*>/', '', $clean); // Remove the start <p> or <p attr="">
		$clean = preg_replace('/<\/p>/', '<br />', $clean); // Replace the end
		return parent::doClean($clean);
	}
}
