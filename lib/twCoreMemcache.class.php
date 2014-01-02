<?php

class twCoreMemcache
{
    private $memcache = false;

    public function __construct($memcache_config)
    {
        if (class_exists('Memcache')) {
            $memcache = new Memcache();
            foreach ($memcache_config as $memcache_server) {
                $memcache->addServer($memcache_server['host'], $memcache_server['port']);
            }
            if ($memcache != false) {
                $this->memcache = $memcache;
                $strict_key_prefix = $key_prefix = sfConfig::get('tw_project_name', 'default') . '.' . sfConfig::get('tw_project_version', 0) . '.' . __FILE__;
                sfConfig::set('tw_memcache', $this);
                sfConfig::set('tw_memcache_skp', $strict_key_prefix);
                sfConfig::set('tw_memcache_kp', $key_prefix);
            } else {
                sfConfig::set('tw_memcache', false);
            }
        } else {
            sfConfig::set('tw_memcache', false);
        }
    }

    public function getValidatorsKey($validators, $strict = false)
    {
        if (!empty($validators) && is_array($validators)) {
            $validator = '';
            foreach ($validators as $validator) {
                $validator .= '|' . $this->get($this->createValidatorKey($validator, $strict), 0) . '|';
            }
            return $validator;
        } else {
            return null;
        }
    }

    public function setValidatorsKey($validators, $strict = false)
    {
        if (!empty($validators) && is_array($validators)) {
            $now = microtime();
            foreach ($validators as $validator) {
                $this->set($this->createValidatorKey($validator, $strict), $now);
            }
        }
    }

    public function createKey($partkey, $validators = array(), $strict = false)
    {
        return sha1($this->getPrefix($strict) . '||' . $this->getValidatorsKey($validators) . '||' . $partkey);
    }

    protected function createValidatorKey($validator, $strict)
    {
        return sha1($this->getPrefix($strict) . ':validator:' . $validator);
    }

    protected function getPrefix($strict)
    {
        if ($strict == false) {
            return sfConfig::get('tw_memcache_kp', null);
        } else {
            return sfConfig::get('tw_memcache_skp', null);
        }
    }

    static public function setStrictKey($app, $env)
    {
        $strict_key_prefix = sfConfig::get('tw_project', 'default') . '.' . $app . '.' . $env . '.' . sfConfig::get('tw_memcache_flush', 0) . '.' . __FILE__;
        sfConfig::set('tw_memcache_skp', $strict_key_prefix);
    }

    /**
     * Magic method for calling Memcache directly via twCoreMemcache
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if ($this->memcache != false) {
            return @call_user_func_array(array($this->memcache, $method), $arguments);
        }
        return false;
    }
}