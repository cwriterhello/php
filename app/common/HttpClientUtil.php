<?php

namespace app\common;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use think\facade\Log;
class HttpClientUtil
{
    const TIMEOUT_MSEC = 5.0; // 秒

    /**
     * 发送 GET 请求
     *
     * @param string $url
     * @param array $queryParams 查询参数
     * @return string 响应内容
     */
//    public static function doGet(string $url, array $queryParams = []): string
//    {
//        $client = new Client();
//
//        try {
//            $response = $client->get($url, [
//                RequestOptions::QUERY => $queryParams,
//                RequestOptions::TIMEOUT => self::TIMEOUT_MSEC,
//            ]);
//
//            return (string)$response->getBody();
//        } catch (GuzzleException $e) {
//            echo "GET Error: " . $e->getMessage() . "\n";
//            return '';
//        }
//    }

    public static function doGet(string $url, array $queryParams = []): string
    {
        $client = new Client();

        try {
            $response = $client->get($url, [
                RequestOptions::QUERY => $queryParams,
                RequestOptions::TIMEOUT => self::TIMEOUT_MSEC,
                RequestOptions::HEADERS => [
                    'User-Agent' => 'ThinkPHP HttpClientUtil'
                ]
            ]);

            return (string)$response->getBody();

        } catch (GuzzleException $e) {
            Log::error("HTTP GET 请求失败: {$url}, 错误: " . $e->getMessage());
            return '';
        }
    }
    /**
     * 发送 POST 表单请求（application/x-www-form-urlencoded）
     *
     * @param string $url
     * @param array $formData 表单数据
     * @return string 响应内容
     */
    public static function doPost(string $url, array $formData = []): string
    {
        $client = new Client();

        try {
            $response = $client->post($url, [
                RequestOptions::FORM_PARAMS => $formData,
                RequestOptions::TIMEOUT => self::TIMEOUT_MSEC,
            ]);

            return (string)$response->getBody();
        } catch (GuzzleException $e) {
            echo "POST Form Error: " . $e->getMessage() . "\n";
            return '';
        }
    }

    /**
     * 发送 POST JSON 请求（application/json）
     *
     * @param string $url
     * @param array $jsonData JSON 数据
     * @return string 响应内容
     */
    public static function doPostJson(string $url, array $jsonData = []): string
    {
        $client = new Client();

        try {
            $response = $client->post($url, [
                RequestOptions::JSON => $jsonData,
                RequestOptions::TIMEOUT => self::TIMEOUT_MSEC,
            ]);

            return (string)$response->getBody();
        } catch (GuzzleException $e) {
            echo "POST JSON Error: " . $e->getMessage() . "\n";
            return '';
        }
    }
}
