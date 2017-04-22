<?php

namespace Cn\Xu42\Cet\Service;

use Cn\Xu42\Cet\BizImpl\CetBizImpl;
use Cn\Xu42\Cet\Exception\ArgumentException;
use Cn\Xu42\Cet\Exception\CetSystemException;

class CetService
{
    private $bizImpl = null;

    public function __construct()
    {
        $this->bizImpl = new CetBizImpl();
    }

    public function query($name, $number)
    {
        try {
            return $this->bizImpl->query($name, $number);
        } catch (\Throwable $throwable) {
            throw new $throwable;
        }
    }
}
