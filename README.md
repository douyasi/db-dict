# MySQL 数据库数据字典导出器

[ENGLISH README](README-en.md)

> MySQL 数据库字典（文档）是由 `PHP` 脚本自动导出的。在导出之前，您需要为数据库的表及其字段添加上注释。为数据库添加注释是一个（开发）好习惯。

### 衍生相关

- [ddl-to-object](https://github.com/ycrao/ddl-to-object): 根据 DDL 语句生成各种语言支持的对象类。

### 命令行版本使用说明

需要 [PHP](https://www.php.net/) 运行时和（安装） [PDO](https://www.php.net/manual/en/ref.pdo-mysql.php) 扩展。

```bash
# 如果 raw.githubusercontent.com 服务器无法连接连接上，可尝试科学上网并克隆本项目
wget https://raw.githubusercontent.com/douyasi/db-dict/master/mysql_dict
# 或者到项目 `release` 页面下载
# 当前最新版 1.1
wget https://github.com/douyasi/db-dict/releases/download/1.1/mysql_dict
chmod +x mysql_dict
# 示例
./mysql_dict -h=127.0.0.1 -u=root -p=root -d=test_app -c=utf8mb4
```

目前支持以下参数（带*为必传参数，如未传递则使用默认值）：

```
-h : MySQL服务主机名或 IP ，默认 `127.0.0.1`
-u : 登录的用户（名），默认 `root`
-p : 连接到服务器时使用的用户密码，默认 `root`
-d*: 指定要导出的数据库名称，多个名称之间用（英文）逗号分隔
-c : 数据库的字符集，默认 `utf8mb4`
-l : 导出文档的地区语言
     如果不被支持，则设置成默认 `en`

示例：
./mysql_dict -h=127.0.0.1 -u=root -p=root -d=test_app -c=utf8mb4
```

### 源码版本使用说明

请自行修改 `mysql_dict.php` 文件中 `$config` 配置项：

```php
$config = [
    'host'     => '127.0.0.1',
    'user'     => 'root',
    'password' => 'root',
    'charset'  => 'utf8mb4',  # 如果导出的文档出现乱码 ？，请指定该数据库对应的字符集
    'locale'   => 'zh-CN',  # 文档语言，目前仅支持简体中文（zh-CN）与英文（en）
];
```

导出时，请指定需要生成字典的数据库名：

```php
# 使用你自己的数据库（名）
$dbs = ['yascmf_app', 'test'];
foreach ($dbs as $db) {
    export_dict($db, $config);
}
```

### 导出的文件格式

支持导出 `markdown` 和 `html` 两种格式文件，HTML页面预览图如下：

![dict.png](https://douyasi.com/usr/uploads/2017/06/1954673305.png)

### 授权协议

MIT LICENSE

### 特别致谢

![JetBrains Logo (Main) logo](https://resources.jetbrains.com/storage/products/company/brand/logos/jb_beam.svg)
