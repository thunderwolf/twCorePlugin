<?php

/**
 * twMain actions.
 *
 * @package    subskrypcja
 * @subpackage homepage
 * @author     Arkadiusz Tułodziecki
 */
class twMainActions extends sfActions
{
	public function executeIndex($request)
	{
		//var_dump($this->getUser()->getCulture());
	}

	public function executeUsers($request)
	{
	}

	public function executeLanguage($request)
	{
		$this->form = new sfFormLanguage($this->getUser(), array('languages' => array('en', 'pl')), false);
		if ($this->form->process($request)) {
			$profile = sfGuardUserProfilePeer::retrieveByPK($this->getUser()->getProfile()->getUserId());
			$profile->setCulture($this->getUser()->getCulture());
			$profile->save();
			// culture has been changed
			return $this->redirect('@homepage');
		}
		echo 'NOT OK';
		exit;
		// the form is not valid (can't happen... but you never know)
		return $this->redirect('@homepage');
	}

}
