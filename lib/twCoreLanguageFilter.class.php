<?php

class twCoreLanguageFilter extends sfFilter
{
    public function execute($filterChain)
    {
        // Execute this filter only once
        if ($this->isFirstCall()) {
            $request = $this->getContext()->getRequest();
            $user = $this->getContext()->getUser();
            try {
                $profile = $user->getProfile();
                if (is_object($profile)) {
                    $language = $profile->getCulture();
                } else {
                    $language = $request->getPreferredCulture(array('en', 'pl'));
                }
            } catch (Exception $e) {
                $language = $request->getPreferredCulture(array('en', 'pl'));
            }
            $user->setCulture($language);
        }

        // Execute next filter
        $filterChain->execute();
    }
}
