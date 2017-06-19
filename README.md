# 数据库数据字典生成脚本 (Database Dictionary Generator)

> 本数据字典由PHP脚本自动导出,字典的备注来自数据库表及其字段的注释(`comment`).开发者在增改库表及其字段时,请在 `migration` 时写明注释,以备后来者查阅.


## 使用说明

请自行修改 `mysql_dict` 开始 `$config` 配置项：

```php
$config = [
    'host'     => '127.0.0.1',
    'user'     => 'root',
    'password' => 'root',
];
```

导出时，请指定需要生成字典的数据库名：

```php
$dbs = ['yascmf_app', 'test'];
foreach ($dbs as $db) {
    export_dict($db, $config);
}
```

## 导出预览

支持生成 `markdown` 和 `html` 两种格式。

页面样式预览：

![dict.png][1]

  [1]: http://douyasi.com/usr/uploads/2017/06/1954673305.png

## 授权协议

MIT LICENSE
