<?php

namespace Cn\Xu42\Cet\Exception;

class CetSystemException extends BaseException
{
    public $message = 'CET查询系统异常';

    public $code = '42102000';
}
