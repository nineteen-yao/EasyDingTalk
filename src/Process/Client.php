<?php
/**
 * 只能工作流扩展
 *
 * @author: YaoFei<nineteen.yao@qq.com>
 * Datetime: 2021/11/12 17:16
 */

namespace YEasyDingTalk\Process;


class Client extends \EasyDingTalk\Process\Client
{

    /**
     * 获取审批实例，将实例ID加入到结果中返回
     *
     * @param string $id
     * @return mixed
     */
    public function get($id)
    {
        $res = parent::get($id);

        if ($res['errcode'] === 0) {
            $res['instance_id'] = $id;
        }

        return $res;
    }

    /**
     * 查询用户待办任务
     *
     * @param string $userId
     * @param int    $status
     * @param int    $offset
     * @param int    $size
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function userTask(string $userId, $status = 0, $offset = 0, $size = 20)
    {
        return $this->client->postJson('topapi/process/workrecord/task/query', [
            'userid' => $userId,
            'offset' => max($offset, 0),
            'count' => min($size, 50),
            'status' => $status
        ]);
    }

    /**
     * 获取用户能管理的所有的审批模板，需要有权限的管理才能访问
     *
     * @param string $userId
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function templateManage(string $userId)
    {
        return $this->client->postJson('topapi/process/template/manage/get', [
            'userid' => $userId,
        ]);
    }

    /**
     * 获取所有指定审批模板下的实例
     *
     * @param string $templateId
     * @param array  $timeRanage
     * @param array  $userList
     * @param int    $size
     * @param int    $offset
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function instances(string $templateId, $timeRanage = [], $userList = [], $size = 10, $offset = 0)
    {
        $params = array_merge([
            'process_code' => $templateId,
            'start_time' => (time() - 86400) * 1000,    //一天内
            'end_time' => time() * 1000,
            'size' => min($size, 20),
            'cursor' => max(0, $offset)
        ], $timeRanage);
        if (!empty($userList)) {
            $params['userid_list'] = implode(',', array_slice($userList, 0, 10));
        }

        $response = $this->client->postJson('topapi/processinstance/listids', $params);
        if ($response['errcode'] !== 0) {
            return $response;
        }

        $details = [];
        foreach ($response['result']['list'] as $instanceId) {
            try {
                $instanceResp = $this->get($instanceId);

                if ($instanceResp['errcode'] !== 0) {
                    throw new \Exception($instanceResp['errmsg'], $instanceResp['errcode']);
                }

                $details[] = array_merge($instanceResp['process_instance'], [
                    'instance_id' => $instanceId
                ]);
            } catch (\Throwable $throwable) {

            }
        }

        $response['result']['list'] = $details;

        return $response;
    }
}