<?php

class twSessionStorage extends sfSessionStorage
{
    public function initialize($options = null)
    {
        $cookieDefaults = session_get_cookie_params();

        $options = array_merge(array(
            'session_name' => 'symfony',
            'session_id' => null,
            'auto_start' => true,
            'session_cookie_lifetime' => $cookieDefaults['lifetime'],
            'session_cookie_path' => $cookieDefaults['path'],
            'session_cookie_domain' => $cookieDefaults['domain'],
            'session_cookie_secure' => $cookieDefaults['secure'],
            'session_cookie_httponly' => isset($cookieDefaults['httponly']) ? $cookieDefaults['httponly'] : false,
            'session_cache_limiter' => 'none',
        ), $options);

        // initialize parent
        parent::initialize($options);

        // set session name
        $sessionName = $this->options['session_name'];

        session_name($sessionName);

        $sessionId = $this->options['session_id'];
        if ($sessionId) {
            session_id($sessionId);
        }

        $lifetime = $this->options['session_cookie_lifetime'];
        $path = $this->options['session_cookie_path'];
        $domain = $this->options['session_cookie_domain'];
        $secure = $this->options['session_cookie_secure'];
        $httpOnly = $this->options['session_cookie_httponly'];
        session_set_cookie_params($lifetime, $path, $domain, $secure, $httpOnly);

        if (!is_null($this->options['session_cache_limiter'])) {
            session_cache_limiter($this->options['session_cache_limiter']);
        }

        if ($this->options['auto_start'] && !self::$sessionStarted) {
            session_start();
            self::$sessionStarted = true;
        }
    }
}
