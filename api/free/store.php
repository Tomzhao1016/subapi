<?php 
// 定义JSON文件路径
define('JSON_FILE', 'data.json');

// 检查请求方法
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // 检查是否存在sub参数
    if (isset($_GET['sub'])) {
        // 获取sub参数的值
        $sub = $_GET['sub'];

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

        // 确定新的ID
        $id = 1;
        if (count($dataArray) > 0) {
            $lastElement = end($dataArray);
            $id = $lastElement['id'] + 1;
        }

        // 定义状态
        $status = 'new';

        // 创建数据数组
        $data = [
            'id' => $id,
            'sub' => $sub,
            'status' => $status
        ];

        // 将新数据添加到数据数组
        $dataArray[] = $data;

        // 将数据数组编码为JSON并保存到文件
        file_put_contents(JSON_FILE, json_encode($dataArray, JSON_PRETTY_PRINT));

        // 返回成功响应
        echo json_encode(['status' => 'success', 'id' => $id]);
    } else {
        // 返回错误响应
        echo json_encode(['status' => 'error', 'message' => 'Missing sub parameter']);
    }
} else {
    // 返回错误响应
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
