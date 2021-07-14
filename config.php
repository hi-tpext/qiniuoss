<?php

return [
    'access_key' => '',
    'secret_key' => '',
    'domain' => '',
    'region' => 'z0',
    'bucket_name' => 'tpext',
    //
    //配置描述
    '__config__' => [
        'access_key' => ['type' => 'text', 'label' => 'AccessKey', 'size' => [2, 8], 'help' => '云账号AccessKey'],
        'secret_key' => ['type' => 'text', 'label' => 'SecretKey', 'size' => [2, 8], 'help' => '云账号SecretKey'],
        'domain' => ['type' => 'text', 'label' => 'Domain', 'size' => [2, 8], 'help' => '域名，自定义域名（需要备案）或七牛的二级域名，如xxxx.hd-bkt.clouddn.com'],
        'region' => ['type' => 'text', 'label' => 'Region', 'size' => [2, 8], 'help' => '存储区域，如：z0(代表华东)，其他：z1:华北 | z2:华南 | cn-east-2:华东-浙江2'],
        'bucket_name' => ['type' => 'text', 'label' => 'BucketName', 'size' => [2, 8], 'help' => '空间名称，由3～63个字符组成，支持小写字母、短划线-和数字，且必须以小写字母或数字开头和结尾'],
    ],
];
