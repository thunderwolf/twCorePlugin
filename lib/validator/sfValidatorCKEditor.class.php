<?php

class sfValidatorCKEditor extends sfValidatorString
{
	protected function configure($options = array(), $messages = array()) {
		parent::configure($options, $messages);

		$this->addOption('phptal', false);
	}

	/**
	 * @see sfValidatorBase
	 */
	protected function doClean($value) {
		$clean = (string) $value;
		if ($this->getOption('phptal')) {
			$clean = str_replace('tal:omittag', 'tal:omit-tag', $clean);
		}

//		$clean = preg_replace('/<p[^>]*>/', '', $clean); // Remove the start <p> or <p attr="">
//		$clean = preg_replace('/<\/p>/', '<br />', $clean); // Replace the end
//		$clean = preg_replace('/<\/p>/', '', $clean); // Replace the end

		return parent::doClean($clean);
	}
}
