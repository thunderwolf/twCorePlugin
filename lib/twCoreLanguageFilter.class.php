<?php
class twCoreLanguageFilter extends sfFilter
{
	public function execute($filterChain)
	{
		// Execute this filter only once
		if ($this->isFirstCall()) {
			$request = $this->getContext()->getRequest();
			$user    = $this->getContext()->getUser();
			if ($user->getProfile() != null) {
				$language = $user->getProfile()->getCulture();
			} else {
				$language = $request->getPreferredCulture(array('en', 'pl'));
			}
			$user->setCulture($language);
		}

		// Execute next filter
		$filterChain->execute();
	}
}

?>