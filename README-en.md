# MySQL Database Dictionary Exporter

> MySQL database dictionary (document) is automatically exported by a PHP script. You need make comments for database's tables and their fields before export. Comments for database(s) is a good habit.

### CLI Version Usage

Requires [PDO](https://www.php.net/manual/en/ref.pdo-mysql.php) extension with [PHP](https://www.php.net/) runtime.

```bash
# download newest version from raw.githubusercontent.com 
wget https://raw.githubusercontent.com/douyasi/db-dict/master/mysql_dict
# or download it from project `release` page
# current newest version 1.1
wget https://github.com/douyasi/db-dict/releases/download/1.1/mysql_dict
chmod +x mysql_dict
# an example
./mysql_dict -h=127.0.0.1 -u=root -p=root -d=test_app -c=utf8mb4 -l=zh-CN
```

Currently, supports the following options (parameters with * are required, and will use default values if not passed).

```
-h : MySQL server host name or ip, default `127.0.0.1`
-u : user for login, default `root`
-p : user password to use when connecting to server, default `root`
-d*: specify the name(s) of database(s)
     separate multiple names with commas
-c : the charset for database(s), default `utf8mb4`
-l : locale language for markdown and html document
     if locale not supported, will set it to default `en`

example:
./mysql_dict -h=127.0.0.1 -u=root -p=root -d=test_app -c=utf8mb4 -l=zh-CN
```

### Source Code Version Usage

Modify `$config` array values in `mysql_dict.php` file.

```php
$config = [
    'host'     => '127.0.0.1',
    'user'     => 'root',
    'password' => 'root',
    'charset'  => 'utf8mb4',  # if 
];
```

Specify the name(s) of database(s) when handle export.

```php
# two databases: yascmf_app, test
$dbs = ['yascmf_app', 'test'];
foreach ($dbs as $db) {
    export_dict($db, $config);
}
```

### File Formats

Supports two file formats `markdown` and `html`.

See screenshot for `HTML` below.

![dict.png](https://douyasi.com/usr/uploads/2017/06/1954673305.png)

### License

MIT LICENSE

### Special Thanks

![JetBrains Logo (Main) logo](https://resources.jetbrains.com/storage/products/company/brand/logos/jb_beam.svg)