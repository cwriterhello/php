<?php

return [
    'default' => 'local',  // 默认使用的存储磁盘（disk）
    'disks'   => [
        'local'  => [
            'type' => 'local',  // 存储类型：本地存储
            'root' => app()->getRootPath() . 'public',  // 存储根目录
        ],
        // 其他存储配置...
    ],
];
