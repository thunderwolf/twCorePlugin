<?php

class sfValidatorSlugPropelUnique extends sfValidatorPropelUnique
{
	protected function configure($options = array(), $messages = array()) {
		$this->addRequiredOption('slugify');
		parent::configure($options, $messages);
	}

	protected function doClean($values) {
		if (!is_array($this->getOption('column'))) {
			$this->setOption('column', array($this->getOption('column')));
		}
		if (count($this->getOption('column')) > 1) {
			return parent::doClean($values);
		}
		// TODO: na razie zakładamy że nie ustyawiamy opcji field
		$column = $this->getOption('column');
		$field = $column[0];
		if (mb_strlen($values[$field]) == 0) {
			$values[$field] = $values[$this->getOption('slugify')];
		}
		$values[$field] = twCoreSlugify::slugify($values[$field]);
		return parent::doClean($values);
	}
}