<?php
if (!function_exists('scan_dir')) {
    /**
     * 扫描目录的文件
     * @param string $path 目录
     * @param bool $force 首次调用：true返回本目录的文件，false返回包含上一次的数据
     * @return array
     */
    function scan_dir(string $path = '.', bool $force = false)
    {
        /** 这里必须使用静态变量 ，否则递归调用的时候不能正确保存数据 */
        static $filePath = [];
        if ($force) {
            $filePath = [];
        }
        $current_dir = opendir($path);
        while (($file = readdir($current_dir)) !== false) {
            $sub_dir = $path . DIRECTORY_SEPARATOR . $file;
            if ($file == '.' || $file == '..') {
                continue;
            } else if (is_dir($sub_dir)) {
                scan_dir($sub_dir);
            } else {
                $filePath[$path . '/' . $file] = $path . '/' . $file;
            }
        }
        return $filePath;
    }
}

if (!function_exists('replaceFile')) {
    /**
     * @param string $targetProject project项目根目录
     * @param string $targetFile 需要修改的文件
     * @param string $startContent 需要修改的文件内容开始
     * @param string $endContent 需要修改的文件内容结束
     * @param string $newReplaceContent 替换的新数据
     */
    function replaceFile(string $targetProject, string $targetFile, string $startContent, string $endContent, string $newReplaceContent)
    {
        /** 需要修改的文件 */
        $urlServiceFile = $targetProject . '/' . $targetFile;
        /** 读取需要修改的文件的内容 */
        $content = file_get_contents($urlServiceFile);
        /** 确定起始位置 */
        $start_index = strpos($content, $startContent) + strlen($startContent);
        /** 确定结束位置 */
        $end_index = strpos($content, $endContent);
        /** 获取需要替换的内容 */
        $word = substr($content, $start_index, $end_index - $start_index);

        /** 生成新的content内容 */
        $newContent = str_replace($word, $newReplaceContent, $content);
        /** 写入文件 */
        if (file_put_contents($urlServiceFile, $newContent)) {
            echo "替换{$targetFile}成功\r\n";
        } else {
            echo "替换{$targetFile}失败\r\n";
        }

    }
}