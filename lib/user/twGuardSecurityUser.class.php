<?php
class twGuardSecurityUser extends sfGuardSecurityUser
{
	public function __toString() {
		return $this->getAuthor();
	}

	public function getGuardUser() {
		if (!$this->user && $id = $this->getAttribute('user_id', null, 'sfGuardSecurityUser')) {
			$memcache = sfConfig::get('tw_memcache', false);
			if ($memcache != false) {
				$this->tryMemcacheGuardUser($memcache, $id);
			} else {
				$this->user = sfGuardUserPeer::retrieveByPk($id);
			}
			if (!$this->user) {
				// the user does not exist anymore in the database
				$this->signOut();
				throw new sfException('The user does not exist anymore in the database.');
			}
		}
		return $this->user;
	}

	protected function tryMemcacheGuardUser($memcache, $id) {
		$user_key = sfConfig::get('tw_memcache_kp').'.sfGuardSecurityUser.user_id.'.$id;
		$this->user = $memcache->get($user_key);
		if (!$this->user) {
			$this->user = sfGuardUserPeer::retrieveByPk($id);
			if ($this->user) {
				$this->user->getProfile();
				$memcache->set($user_key, $this->user);
			}
		}
	}

	public function getAuthor() {
		$user = $this->getGuardUser();
		if ($user->getProfile() && (mb_strlen($user->getProfile()->getFirstName()) > 0 || mb_strlen($user->getProfile()->getLastName()) > 0)) {
			return trim($user->getProfile()->getFirstName() . ' ' . $user->getProfile()->getLastName());
		}
		return $user->getUsername();
	}

	public function getId() {
		return $this->getGuardUser()->getId();
	}

}
?>