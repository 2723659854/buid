<?php


/** 加载助手函数 */
include_once __DIR__ . '/functions.php';

$docProjectPath = dirname(__DIR__) . '/app-api-doc';

/** 项目文档目录名称 */
$docName = 'in_rupiya_loan_ios';

/** 生成的h5的接口文档文件名称 */
$h5_dev_file = '1500.ph_peso_pocket_ios.md';

/** 7.map.md文件的名称 */
$my_7_map_md_file = '7.map.md';

$files = scan_dir($docProjectPath.'/docs/'.$docName);

$map_7_md_content  = file_get_contents($docProjectPath.'/docs/'.$docName.'/'.$my_7_map_md_file);

print_r($map_7_md_content);

