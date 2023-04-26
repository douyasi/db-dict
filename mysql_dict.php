<?php

$i18n = [
    // default, en-US,en-GB
    'en' => [
        'name' => 'Database Dictionary',
        'description' => 'Exported by a PHP [script](https://github.com/douyasi/db-dict). You need make comments for database\'s tables and their fields before export. Comments for database(s) is a good habit.',
        'table_headers' => '|  Field  |  DataType  |  Default  |  Nullable  |  AutoIncr  |  Comment  |',
    ],
    // zh-CN
    'zh-CN' => [
        'name' => '数据库字典',
        'description' => '本数据库词典由 `PHP` [脚本](https://github.com/douyasi/db-dict) 导出的。在导出之前，您需要为数据库的表及其字段添加上注释。为数据库（表列）添加注释是一个好习惯。',
        'table_headers' => '|  字段名  |  数据类型  |  默认值  |  允许非空  |  自动递增  |  备注  |',
    ],
];

if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle) {
        return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}

/**
 * export_dict
 *
 * @param string $dbname
 * @param array $config
 * @return void
 */
function export_dict($dbname, $config, &$i18n) {
    $charset = isset($config['charset']) ? $config['charset'] : 'utf8mb4';
    $locale = isset($config['locale']) ? $config['locale'] : 'en';
    if (str_starts_with('zh', $locale)) {
        // set zh-* (such as zh-TW/zh-HK) to zh-CN
        $locale = 'zh-CN';
    }
    if (str_starts_with('en', $locale)) {
        // set en-* (such as en-GB/en-US) to en
        $locale = 'en';
    }
    if (!in_array($locale, array_keys($i18n))) {
        // not supported, set to default en
        $locale = 'en';
    }
    $title = $dbname.' '.$i18n[$locale]['name'];
    $dsn = 'mysql:dbname='.$dbname.';host='.$config['host'].';charset='.$charset;
    try {
        $con = new PDO($dsn, $config['user'], $config['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    } catch (PDOException $e) {
        die('连接失败[Connection failed]: ' . $e->getMessage());
    }

    $tables = $con->query('SHOW tables')->fetchAll(PDO::FETCH_COLUMN);

    // fetch all tables
    foreach ($tables as $table) {
        $_tables[]['TABLE_NAME'] = $table;
    }

    // fetch all comments for table(s) and their fields by loop
    foreach ($_tables as $k => $v) {
        $sql = 'SELECT * FROM ';
        $sql .= 'INFORMATION_SCHEMA.TABLES ';
        $sql .= 'WHERE ';
        $sql .= "table_name = '{$v['TABLE_NAME']}' AND table_schema = '{$dbname}'";
        $tr = $con->query($sql)->fetch(PDO::FETCH_ASSOC);
        $_tables[$k]['TABLE_COMMENT'] = $tr['TABLE_COMMENT'];

        $sql = 'SELECT * FROM ';
        $sql .= 'INFORMATION_SCHEMA.COLUMNS ';
        $sql .= 'WHERE ';
        $sql .= "table_name = '{$v['TABLE_NAME']}' AND table_schema = '{$dbname}'";
        $fields = [];
        $field_result = $con->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($field_result as $fr) {
            $fields[] = $fr;
        }
        $_tables[$k]['COLUMN'] = $fields;
    }
    unset($con);

    $mark = '';

    // loop all tables
    foreach ($_tables as $k => $v) {

        $mark .= '### '.$v['TABLE_NAME'].'  '.$v['TABLE_COMMENT'].PHP_EOL;
        $mark .= ' '.PHP_EOL;
        $mark .= $i18n[$locale]['table_headers'].PHP_EOL;
        $mark .= '| ------ | ------ | ------ | ------ | ------ | ------ |'.PHP_EOL;
        foreach ($v['COLUMN'] as $f) {
            $mark .= '| '.$f['COLUMN_NAME'].' | '.$f['COLUMN_TYPE'].' | '.$f['COLUMN_DEFAULT'].' | '.$f['IS_NULLABLE'].' | '.($f['EXTRA'] == 'auto_increment' ? 'YES' : '').' | '.(empty($f['COLUMN_COMMENT']) ? '-' : str_replace('|', '/', $f['COLUMN_COMMENT'])).' |'.PHP_EOL;
        }
        $mark .= ' '.PHP_EOL;

    }

    $description = $i18n[$locale]['description'];
    // markdown output
    $md_tplt = <<<EOT
## {$title}
>   {$description}
{$mark}
EOT;

    // html output
    $marked_text = htmlentities($md_tplt);
    $html_tplt = <<<EOT
<!DOCTYPE html>
<html lang="{$locale}">
<head>
    <meta charset="UTF-8">
    <title>{$title} - Powered By Markdown Viewer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="author" content="https://github.com/douyasi/db-dict">
    <link rel="stylesheet" type="text/css" href="https://raoyc.com/markdoc-viewer/css/github-markdown-light.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/4.2.12/marked.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/github.min.css" rel="stylesheet" />
</head>
<body>
<div class="markdown-body" id="content" style="margin:auto; width: 1024px;">

</div>
<div id="marked_text" style="display:none;">
{$marked_text}
</div>
<script>
var marked_text = document.getElementById('marked_text').innerText;
var renderer = new marked.Renderer();
renderer.table = function (header, body) {
    return (
      '<table class="table table-bordered table-striped">\\n' +
      '<thead>\\n' +
      header +
      '</thead>\\n' +
      '<tbody>\\n' +
      body +
      '</tbody>\\n' +
      '</table>\\n'
    );
};
// see: https://marked.js.org/using_advanced#options
marked.setOptions({
    renderer: renderer,
    gfm: true,
    breaks: false,
    pedantic: false,
    // sanitize: false, // deprecated
    smartLists: true,
    smartypants: false,
    langPrefix: "language-",
    // need highlight.js
    highlight: function (code) {
        return hljs.highlightAuto(code).value;
    },
});
document.getElementById('content').innerHTML = marked.parse(marked_text);
  </script>
</body>
</html>
EOT;

    file_put_contents($dbname.'.md', $md_tplt);
    file_put_contents($dbname.'.html', $html_tplt);
}

// config
$config = [
    'host'     => '127.0.0.1',
    'user'     => 'root',
    'password' => 'A123#456b',
    'charset'  => 'utf8mb4',
    'locale'   => 'zh',
];
# two databases: yascmf_app, test
$dbs = ['yascmf_app', 'test'];
foreach ($dbs as $db) {
    export_dict($db, $config, $i18n);
}

