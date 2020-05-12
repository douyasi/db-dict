# 数据库数据字典生成脚本 (Database Dictionary Generator)

> 本数据字典由PHP脚本自动导出,字典的备注来自数据库表及其字段的注释(`comment`).开发者在增改库表及其字段时,请在 `migration` 时写明注释,以备后来者查阅.



## 命令行版本使用说明

依赖于 `PHP` 运行时，且需安装 `PDO` 扩展。

```bash
# raw.githubusercontent.com 如果无法连接，可克隆本项目
wget https://raw.githubusercontent.com/douyasi/db-dict/master/mysql_dict
# 或者到项目 `release` 页面下载
# or
wget https://github.com/douyasi/db-dict/releases/download/1.0/mysql_dict
chmod +x mysql_dict
./mysql_dict -h=127.0.0.1 -u=root -p=root -d=test,yascmf_app -c=utf8mb4
```

目前支持以下参数（带*为必传参数，未传递则使用默认值）：

```
-h 指定MySQL主机名，默认 `127.0.0.1`
-u 指定MySQL用户名，默认 `root`
-p 指定MySQL用户密码，默认 `root`
-d * 指定MySQL数据库名，多个库名请以英文逗号分隔
-c 指定MySQL数据库字符集，默认 `utf8mb4`
如：
./mysql_dict -h=127.0.0.1 -u=root -p=root -d=test,yascmf_app -c=utf8mb4
```


## 使用说明

请自行修改 `mysql_dict.php` 文件中 `$config` 配置项：

```php
$config = [
    'host'     => '127.0.0.1',
    'user'     => 'root',
    'password' => 'root',
    'charset'  => 'utf8mb4',  # 如果导出的文档出现乱码 ？，请指定该数据库对应的字符集
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
