<?php 
// 定义JSON文件路径
define('JSON_FILE', 'data.json');

// 检查请求方法
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
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

    // 过滤出状态为new的数据
    $newDataArray = array_filter($dataArray, function ($item) {
        return $item['status'] === 'new';
    });

    // 检查是否存在link参数
    if (isset($_GET['link'])) {
        $link = $_GET['link'];
        $filteredData = array_filter($newDataArray, function ($item) use ($link) {
            return strpos($item['sub'], $link) !== false;
        });

        if (!empty($filteredData)) {
            $selectedItem = array_values($filteredData)[0];
            echo json_encode(['status' => 'success', 'id' => $selectedItem['id'], 'sub' => $selectedItem['sub']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No matching sub found']);
        }
    } else {
        if (!empty($newDataArray)) {
            $randomItem = $newDataArray[array_rand($newDataArray)];
            echo json_encode(['status' => 'success', 'id' => $randomItem['id'], 'sub' => $randomItem['sub']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No new sub found']);
        }
    }
} else {
    // 返回错误响应
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
