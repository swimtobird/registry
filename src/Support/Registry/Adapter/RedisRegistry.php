<?php
/**
 * Created by PhpStorm.
 * User: yong
 * Date: 2018/6/15
 * Time: 14:03
 */

namespace Support\Registry\Adapter;


use Support\Registry\RegistryEntity;

class RedisRegistry implements RegistryInterface
{
    /**
     * @var \Redis
     */
    protected $redis;

    protected $config;

    public function __construct($config)
    {
        $this->redis = new \Redis();

        $this->redis->connect($config['host'], $config['port']);

        if (isset($config['auth']) && $config['auth']) {
            $this->redis->auth($config['auth']);
        }
    }

    public function register(RegistryEntity $entity)
    {
        $registry = $entity->toArray();

        $this->redis->hSet("registry.{$registry['service']}", "{$registry['node']}", $entity->toJson());
    }

    public function unRegister(RegistryEntity $entity)
    {
        $registry = $entity->toArray();

        $this->redis->hDel("registry.{$registry['service']}", "{$registry['node']}");
    }
}
