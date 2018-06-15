<?php
/**
 * Created by PhpStorm.
 * User: yong
 * Date: 2018/6/15
 * Time: 12:32
 */

namespace Support\Registry\Adapter;


use Support\Registry\RegistryEntity;

interface RegistryInterface
{
    /**
     * 注册服务
     */
    public function register(RegistryEntity $entity);


    /**
     * 移除服务
     */
    public function unRegister(RegistryEntity $entity);
}