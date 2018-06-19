<?php
/**
 * Created by PhpStorm.
 * User: yong
 * Date: 2018/6/19
 * Time: 12:31
 */

namespace Support\Registry\Exception;


use Throwable;

class RegisteryValidatorExction extends RegistryException
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}