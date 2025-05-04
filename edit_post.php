<?php
include 'database.php';
session_start();

// Redirect nếu chưa đăng nhập
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Kiểm tra xem có truyền ID hay không
if (!isset($_GET['id'])) {
    header('Location: manage_posts.php');
    exit();
}

$id = $_GET['id'];

// Lấy dữ liệu bài viết
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// Nếu không tìm thấy bài viết
if (!$post) {
    header('Location: manage_posts.php');
    exit();
}

// Lấy danh sách module
$stmtModules = $pdo->query("SELECT * FROM modules");
$modules = $stmtModules->fetchAll(PDO::FETCH_ASSOC);

// Cập nhật bài viết
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $module_id = $_POST['module_id'];

    $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, module_id = ? WHERE id = ?");
    $stmt->execute([$title, $content, $module_id, $id]);

    header("Location: manage_posts.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>
    <link href="style/manage.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #111;
            color: white;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #1a1a1a;
            border-radius: 8px;
            padding: 40px;
            width: 90%;
            max-width: 800px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            margin: 50px auto;
        }

        h1 {
            color: #FFA500;
            text-align: center;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #f0f0f0;
        }

        input[type="text"], textarea, select {
            width: 100%;
            padding: 12px;
            background-color: #333;
            border: 1px solid #555;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            box-sizing: border-box;
        }

        textarea {
            height: 200px;
            resize: vertical;
        }

        button, a.btn {
            background-color: #FFA500;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            display: inline-block;
            margin-top: 20px;
            width: 100%;
            text-align: center;
            text-decoration: none;
        }

        button:hover, a.btn:hover {
            background-color: #e69500;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Post</h1>
        <form method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($post['id']) ?>">

            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
            </div>

            <div class="form-group">
                <label for="content">Content:</label>
                <textarea id="content" name="content" rows="6" required><?= htmlspecialchars($post['content']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="module_id">Module:</label>
                <select id="module_id" name="module_id" required>
                    <?php foreach ($modules as $module): ?>
                        <option value="<?= htmlspecialchars($module['id']) ?>"
                            <?= ($module['id'] == $post['module_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($module['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit">Update Post</button>
        </form>
        <a href="manage_posts.php" class="btn">Back</a>
    </div>
</body>
</html>


