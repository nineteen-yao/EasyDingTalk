<?php

/*
 * 考勤扩展
 * This file is part of the nineteenyao/dingtalk.
 *
 * (c) 姚飞 <nineteen.yao@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace YEasyDingTalk\Attendance;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param \Pimple\Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['attendance'] = function ($app) {
            return new Client($app);
        };
    }
}
