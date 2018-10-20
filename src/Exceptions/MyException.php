<?php

/*
 * This file is part of the smallnews/laravel-shopcore.
 *
 * (c) smallnews <1371606921@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Wsmallnews\Shopcore\Exceptions;

use Exception;

class MyException extends Exception
{
    /**
     * 多样化错误信息组合，非必须使用
     * throw (new MyException)->setMessage("错了");.
     */
    public function setMessage($message = '', $error = 500)
    {
        // 错误信息组合模式
        $this->message = $message;
        $this->errorCode = $error;

        return $this;
    }
}
