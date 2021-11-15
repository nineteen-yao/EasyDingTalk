<?php
/**
 * 考勤扩展
 *
 * @author: YaoFei<nineteen.yao@qq.com>
 * Datetime: 2021/11/12 17:16
 */

namespace YEasyDingTalk\Attendance;


class Client extends \EasyDingTalk\Attendance\Client
{
    /**
     * 更新考勤组信息
     *
     * @param string $operatorId
     * @param string $groupId
     * @param array  $data
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function modifyGroup(string $operatorId, string $groupId, $data = [])
    {
        $data = array_merge([
            'id' => $groupId,
//            'positions' => [
//                //考勤位置信息
//                'latitude' => '',
//                'longitude' => '',
//            ],
//            'offset' => 100,    //考勤范围 100米内
        ], $data);

        return $this->client->postJson('topapi/attendance/group/modify', [
            'op_user_id' => $operatorId,
            'top_group' => $data
        ]);
    }

    public function groupKey(string $groupId)
    {
        return $this->client->postJson('topapi/attendance/groups/idtokey', [
            'group_id' => $groupId
        ]);
    }

    public function positions(string $groupKey, $size = 50, $cursor = null)
    {
        $params = [
            'group_key' => $groupKey,
            'size' => min($size, 50),
        ];
        if (!empty($cursor)) {
            $params['cursor'] = $cursor;
        }

        return $this->client->postJson('topapi/attendance/group/positions/query', $params);
    }

    /**
     * 添加打卡地点
     *
     * @param string $groupKey
     * @param array  $addressConfig 地点配置 {address:'',foreign_id:'',longitude:'',latitude:''}
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function positionAdd(string $groupKey, array $addressConfig)
    {
        return $this->client->postJson('topapi/attendance/group/positions/add', [
            'group_key' => $groupKey,
            'position_list' => $addressConfig
        ]);
    }
}
