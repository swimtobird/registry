<?php
/**
 * Created by PhpStorm.
 * User: yong
 * Date: 2018/6/15
 * Time: 11:59
 */

namespace Server;

use FastD\Servitization\OnWorkerStart;
use FastD\Swoole\Server\TCP;
use FastD\Packet\Json;
use Support\Registry\Registry;
use Support\Registry\RegistryEntity;
use swoole_server;

class RegistryTcpServer extends TCP
{
    use OnWorkerStart;

    protected $fd = [];

    public function doWork(swoole_server $server, $fd, $data, $from_id)
    {
        //校验格式
        $data = Json::decode($data, true);
        if (!$data || !is_array($data)) {
            return 0;
        }
        //检查配置是否存在
        if (!config()->has('registry_server')) {
            return 0;
        }

        $entity = (new RegistryEntity($data));

        //注册配置
        $registry = new Registry();

        $registry->register($entity);

        //$this->bind($fd, $entity);

        $server->send($fd, 'ok');
    }

    public function doClose(swoole_server $server, $fd, $fromId)
    {
//        $registry = new Registry();
//
//        $entity = $this->getBind($fd);
//
//        $registry->unRegister($entity);

        //服务断开连接，移除注册配置
        print_r('服务断开' . PHP_EOL);
    }

    protected function bind($fd, RegistryEntity $entity)
    {
        $this->fd[$fd] = $entity;
    }

    protected function getBind($fd)
    {
        if (!isset($this->fd[$fd])) {
            return false;
        }
        return $this->fd[$fd];
    }
}