<?php
/**
 * This file is part of the ynineteen/EasyDingTalkTalk.
 *
 * @author: YaoFei<nineteen.yao@qq.com>
 * Datetime: 2021/11/12 14:28
 */

namespace YEasyDingTalk;


/**
 * Class Application
 *
 * @property \YEasyDingTalk\UserV2\Client       $user_v2
 * @property \YEasyDingTalk\DepartmentV2\Client $department_v2
 * @property \YEasyDingTalk\Employee\Client     $employee
 * @property \YEasyDingTalk\Process\Client      $process
 * @property \YEasyDingTalk\Attendance\Client   $attendance
 * @package YEasyDingTalk
 */
class Application extends \EasyDingTalk\Application
{
    protected $providers_extend = [
        \YEasyDingTalk\DepartmentV2\ServiceProvider::class,
        \YEasyDingTalk\Employee\ServiceProvider::class,
        \YEasyDingTalk\UserV2\ServiceProvider::class,
        \YEasyDingTalk\Process\ServiceProvider::class,
        \YEasyDingTalk\Attendance\ServiceProvider::class,
    ];

    public function __construct($config = [], array $values = [])
    {
        foreach ($this->providers_extend as $provider) {
            array_push($this->providers, $provider);
        }

        parent::__construct($config, $values);
    }
}