<?php

namespace app\model;

use app\common\HttpClientUtil;
use app\common\WeChatProperties;
use think\Model;
use think\facade\Log;
class User extends Model
{


    protected $table = 'user';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';


    public function userLogin(string $code)
    {
        $openid = $this->getOpenid($code);
        if (!$openid) {
            throw new \Exception('微信登录失败');
        }

        $result = $this->where('openid', $openid)->select();
        if (!$result) {
            $result['openid'] = $openid;
            $this->save($result);
        }
        return $result;
    }


    public function getOpenid(string $code)
    {
//        $weChatProperties = new WeChatProperties();
//        $licence = [
//            'appid' => $weChatProperties->getAppId(),
//            'secret' => $weChatProperties->getSecret(),
//            'js_code' => $code,
//            'grant_type' => 'authorization_code'
//        ];
//        $json = (string)HttpClientUtil::doGet("https://api.weixin.qq.com/sns/jscode2session",$licence);
//        $array = json_decode($json,true);
//        return $array['openid'];


        $weChatProperties = new WeChatProperties();
        $params = [
            'appid' => $weChatProperties->getAppId(),
            'secret' => $weChatProperties->getSecret(),
            'js_code' => $code,
            'grant_type' => 'authorization_code'
        ];

        $response = HttpClientUtil::doGet("https://api.weixin.qq.com/sns/jscode2session", $params);

        if (empty($response)) {
            // 请求失败，记录日志或抛出异常
            Log::error('请求失败');
        }

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // JSON 解析失败
            Log::error('JSON 解析失败');
        }

        if (isset($data['errcode']) && $data['errcode'] != 0) {
            // 微信接口报错，例如 js_code 无效
            // 可记录日志：$data['errmsg']
            Log::error('微信接口报错');
        }

        return $data['openid'] ?? null;
    }


}
