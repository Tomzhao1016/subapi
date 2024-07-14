<?php
// admin.php
$dataFile = 'api/free/data.json'; // JSON 文件路径

if (!file_exists($dataFile)) {
    die('错误: JSON 文件未找到。请检查文件路径.');
}

$data = json_decode(file_get_contents($dataFile), true);

if ($data === null) {
    die('错误: 无法解析 JSON 文件。请检查文件内容.');
}

// 处理还原请求
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'restore' && isset($_POST['id'])) {
        $idToRestore = intval($_POST['id']);
        foreach ($data as &$item) {
            if ($item['id'] === $idToRestore) {
                $item['status'] = 'new'; // 还原状态
            }
        }
        file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
        header('Location: admin.php'); // 还原后重定向
        exit;
    }
}

// 处理封禁请求
if (isset($_GET['id']) && $_GET['status'] === 'err') {
    $idToBan = intval($_GET['id']);
    foreach ($data as &$item) {
        if ($item['id'] === $idToBan) {
            $item['status'] = 'err'; // 封禁状态
        }
    }
    file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
    header('Location: admin.php'); // 封禁后重定向
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>订阅管理-后台</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #4CAF50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .button {
            padding: 5px 10px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            color: white;
            transition: background-color 0.3s;
        }
        .button-ban {
            background-color: #f44336;
        }
        .button-restore {
            background-color: #4CAF50;
        }
        .button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <h1>订阅管理</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>订阅</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['id']); ?></td>
                    <td><?php echo htmlspecialchars($item['sub']); ?></td>
                    <td><?php echo htmlspecialchars($item['status']); ?></td>
                    <td>
                        <?php if ($item['status'] === 'new'): ?>
                            <a href="?id=<?php echo $item['id']; ?>&status=err" class="button button-ban">封禁</a>
                        <?php else: ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                <input type="hidden" name="action" value="restore">
                                <button type="submit" class="button button-restore">还原</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
