<?php 
// 定义JSON文件路径
define('JSON_FILE', 'data.json');

// 检查请求方法
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // 检查是否存在id参数
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // 初始化数据数组
        $dataArray = [];

        // 读取现有数据
        if (file_exists(JSON_FILE)) {
            $json = file_get_contents(JSON_FILE);
            $dataArray = json_decode($json, true);
            if (!is_array($dataArray)) {
                $dataArray = [];
            }
        }

        // 查找并更新对应id的数据
        $found = false;
        foreach ($dataArray as &$item) {
            if ($item['id'] == $id) {
                $item['status'] = 'err';
                $found = true;
                break;
            }
        }

        if ($found) {
            // 将更新后的数据数组编码为JSON并保存到文件
            file_put_contents(JSON_FILE, json_encode($dataArray, JSON_PRETTY_PRINT));
            // 返回成功响应
            echo json_encode(['status' => 'success', 'message' => "ID $id has been banned"]);
        } else {
            // 返回错误响应
            echo json_encode(['status' => 'error', 'message' => "ID $id not found"]);
        }
    } else {
        // 返回错误响应
        echo json_encode(['status' => 'error', 'message' => 'Missing id parameter']);
    }
} else {
    // 返回错误响应
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
