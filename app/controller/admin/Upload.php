<?php
namespace app\controller\admin;

use app\BaseController;
use app\common\OssUtil;
use app\common\Result;
use think\facade\Request;
use think\exception\FileException;
use think\facade\Filesystem;
class Upload
{
    public function upload()
    {

        // 获取上传的文件
        $file = Request::file('file');

        // 验证文件是否存在
        if (empty($file)) {
            return json(['code' => 400, 'msg' => '请上传图片文件']);
        }

        // 验证文件类型和大小
        try {
            validate(['image' => [
                'fileSize' => 1024 * 1024 * 5, // 限制5M
                'fileExt'  => 'jpg,jpeg,png,gif',
                'fileMime' => 'image/jpeg,image/png,image/gif',
            ]])->check(['image' => $file]);
        } catch (\think\exception\ValidateException $e) {
            return json(['code' => 400, 'msg' => $e->getMessage()]);
        }

        try {
            // 初始化 OSS 工具类实例
            $oss = new OssUtil();

            // 获取上传文件的本地路径
            $filePath = $file->getRealPath();

            // 获取上传文件的原始文件名
            $originName = $file->getOriginalName();

            // 提取文件扩展名
            $extension = pathinfo($originName, PATHINFO_EXTENSION);

            // 生成唯一的文件名，避免文件名冲突
            $fileName = uniqid() . '.' . $extension;

            // 调用 OSS 工具类方法上传文件到远程存储
            $ossPath = $oss->uploadFile($fileName, $filePath);

            return json(['code' => 200, 'msg' => '上传成功', 'data' => $ossPath]);
        } catch (\Exception $e) {
            return Result::error('上传失败', $e->getMessage());
        }
//        // 保存文件到本地
//        $savePath = 'uploads/images/';
//        $saveName = \think\facade\Filesystem::putFile($savePath, $file);
//
//        // 返回完整路径
//        $fullPath = Request::domain() . '/' . str_replace('\\', '/', $saveName);


    }
}
