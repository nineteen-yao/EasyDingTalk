<?php

/*
 * This file is part of the ninteenyao/dingtalk.
 *
 * (c) 姚飞 <nineteen.yao@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace YEasyDingTalk\Employee;

use EasyDingTalk\Kernel\BaseClient;

class Client extends BaseClient
{
    /**
     * 获取员工列表
     * @param int $offset
     * @param int $size
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function list($offset = 0, $size = 50)
    {
        return $this->client->postJson('topapi/smartwork/hrm/employee/queryonjob', [
            'offset' => $offset,
            'size' => $size,
            'status_list' => '-1,2,3,5' //2：试用期 3：正式 5：待离职 -1：无状态
        ]);
    }

    /**
     * 获取待入职员工列表
     * @param int $offset
     * @param int $size
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function preentry($offset = 0, $size = 50)
    {
        return $this->client->postJson('topapi/smartwork/hrm/employee/querypreentry', [
            'offset' => $offset,
            'size' => $size,
        ]);
    }

    /**
     * 添加企业待入职员工
     * @param array $param
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addPreentry($param)
    {
        return $this->client->postJson('topapi/smartwork/hrm/employee/addpreentry', $param);
    }

    /**
     * 获取员工离职信息
     * @param string $userIds 要查询的离职员工userid，多个员工用逗号分隔，最大长度50
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function dimission($userIds)
    {
        if (is_array($userIds)) {
            $userIds = implode(',', $userIds);
        }
        return $this->client->postJson('topapi/smartwork/hrm/employee/listdimission', [
            'userid_list' => $userIds,
        ]);
    }

    /**
     * 获取离职员工列表
     * @param int $offset 偏移值
     * @param int $size 分页大小，最大50
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function listDimission($offset = 0, $size = 50)
    {
        return $this->client->postJson('topapi/smartwork/hrm/employee/querydimission', [
            'offset' => $offset,
            'size' => $size,
        ]);
    }

    ////////////////////////////// 花名册部分 /////////////////////////////////

    /**
     * 获取员工花名册字段信息
     * @param string $agentid 应用的AgentID
     * @param string|array $userIds 员工的userid列表，多个userid之间使用逗号分隔。一次最多支持传100个值
     * @param null|string|array $field_filter_list 需要获取的花名册字段信息。查询字段越少，RT越低，建议按需查询，多个字段之间使用逗号分隔。一次最多支持传100个值
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function listDetails($agentid, $userIds, $field_filter_list = null)
    {
        if (is_array($userIds)) {
            $userIds = implode(',', $userIds);
        }

        if (is_array($field_filter_list)) {
            $field_filter_list = implode(',', $field_filter_list);
        }

        return $this->client->postJson('topapi/smartwork/hrm/employee/v2/list', [
            'userid_list' => $userIds,
            'agentid' => $agentid,
            'field_filter_list' => $field_filter_list
        ]);
    }

    /**
     * 更新员工花名册信息
     * @param string $agentid 应用的AgentID
     * @param string $userid 员工的userid
     * @param array $groups 花名册分组详情
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update($agentid, $userid, $groups)
    {
        return $this->client->postJson('topapi/smartwork/hrm/employee/v2/update', [
            'agentid' => $agentid,
            'param' => [
                'userid' => $userid,
                'groups' => $groups
            ]
        ]);
    }

    /**
     * 获取花名册元数据
     * @param string $agentid
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function meta($agentid)
    {
        return $this->client->postJson('topapi/smartwork/hrm/roster/meta/get', [
            'agentid' => $agentid,
        ]);
    }


    /////////////////////////////////////组织部分 /////////

    /**
     * 更新部门扩展信息
     * @param string $deptId
     * @param array $attributes
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orgDeptUpdate($deptId, $attributes = [])
    {
        return $this->client->postJson('topapi/smartwork/hrm/organization/dept/update', [
            'dept_id' => $deptId,
            'attributeVOS' => $attributes
        ]);
    }

    /**
     * 获取部门扩展信息
     * @param array $field_code_list
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orgDept($field_code_list)
    {
        return $this->client->postJson('topapi/smartwork/hrm/organization/dept/get', [
            'field_code_list' => $field_code_list,
        ]);
    }

    /**
     * 获取部门的扩展字段定义
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orgMeta()
    {
        return $this->client->postJson('topapi/smartwork/hrm/organization/dept/meta/get');
    }
}
