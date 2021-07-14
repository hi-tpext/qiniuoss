# Aliyun-OSS

## Tpextbuilder的七牛云-OSS驱动

### 请使用composer安装**七牛云Qiniu Cloud SDK for PHP**后再安装本扩展

```bash
composer require qiniu/php-sdk
```

### 注意事项

- $accessKey和$secretKey 可以在我们的七牛云后台找到。

- 空间的命名，要求在对象存储系统范围内唯一，由3～63个字符组成，支持小写字母、短划线-和数字，且必须以小写字母或数字开头和结尾。

- 你可以事先在七牛云控制台创建好空间，然后填入配置里面。

- 空间限有两种[私有/公有]，建议设置为：**公有**。

- 填入的空间地域要和空间匹配，比如你在[z1:华北]创建的空间，空间地域不能填[z2:华南]。

- 如果没有事先创建，会按填写的[空间名称]和[空间地域]和[存储权公有]自动创建一个。

- 可选的空间地域：

    <https://developer.qiniu.com/kodo/1671/region-endpoint-fq>

### 使用

1. 全局设置

    在扩展`tpext ui生成(tpext.builder)`的配置里面选择本驱动：[七牛云OSS存储]，保存，设置后所有的文件上传都使用七牛云OSS。

2. 单独使用
    ckeditor,mdeditor,tinymce,ueditor,file,image,multipleFile,multipleImage等可使用`storageDriver($class)`方法单独设置。

```php

$form->image('logo', '封面图')->mediumSize()->storageDriver(\qiniuoss\common\OssStorage::class);//使用七牛云oss存储

$form->file('file', '附件')->mediumSize()->storageDriver(\tpext\builder\logic\LocalStorage::class);//服务器本地存储
```
