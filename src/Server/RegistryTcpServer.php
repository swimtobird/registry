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
use Runner\Validator\Exceptions\ValidationException;
use Runner\Validator\Validator;
use Support\Registry\RegistryEntity;
use swoole_server;

class RegistryTcpServer extends TCP
{
    use OnWorkerStart;

    /**
     * @var RegistryEntity
     */
    protected $entity;

    public function doWork(swoole_server $server, $fd, $data, $from_id)
    {
        //校验格式
        $data = Json::decode($data, true);
        if (!$data || !is_array($data)) {
            return 0;
        }

        try{
            print_r($this->validate($data));
        }catch (ValidationException $exception){
            $server->send($fd,"error:{$exception->getMessage()}");
        }
        //生成注册数据
        $this->entity = (new RegistryEntity($data));

        //检查配置是否存在
        if (!config()->has('registry_server')) {
            return 0;
        }

        //注册配置
        registry()->register($this->entity);
        $server->send($fd, 'ok');
    }

    public function doClose(swoole_server $server, $fd, $fromId)
    {
        if ($this->entity){
            //服务断开连接，移除注册配置
            registry()->deRegister($this->entity);
        }
        print_r('服务断开' . PHP_EOL);
    }

    protected function validate($data)
    {
        $rules = [
            'service_host' => 'required|url',
            'service_name' => 'required|string',
            'service_pid' => 'required|int',
        ];

        $validator = new Validator($data, $rules);
        $validator->validate();
    }
}