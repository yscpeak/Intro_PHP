<?php

/*******w********

    Name: Yi Siang Chang
    Date: 2023-10-06
    Description: To edit the post.

****************/

require('authenticate.php');
require('connect.php');

$error = null;

if (isset($_GET['id'])) {
	$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
	if (!$id) {
		header('Location: index.php');
		exit();
	}
	$query = "SELECT * FROM blog WHERE id = ?";
	$stmt = $db->prepare($query);
	$stmt->execute([$id]);
	$post = $stmt->fetch();

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST['update'])) {
			if (!empty($_POST['title']) && !empty($_POST['content'])) {
				$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
				$content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

				$query = "UPDATE blog SET title = ?, content = ? WHERE id = ?";
				$stmt = $db->prepare($query);
				$stmt->execute([$title, $content, $id]);
				// Redirect to the full post
				header('Location: fullpost.php?id=' . $id);
				exit();
			}
			else {
				$error = "Both title and content are required!";
			}
		}
		elseif (isset($_POST['delete'])) {
			$query = "DELETE FROM blog WHERE id = ?";
			$stmt = $db->prepare($query);
			$stmt->execute([$id]);
			header('Location: index.php');
			exit();
		}
	}
}
else {
	header('Location: index.php');
	exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Editing <?= htmlspecialchars(html_entity_decode($post['title'])) ?></title>
</head>
<body>
    <!-- Remember that alternative syntax is good and html inside php is bad -->

    <h2>
        <a href="index.php">
            <img src="logo.png" alt="Blog Logo" class="blog-logo" style="width: 80px;
                 vertical-align: middle;">
        </a>
        <a href="index.php" style="vertical-align: middle;">My Amazing Blog</a>
    </h2>

    <?php if($error): ?>
        <p style="color: #ff0000;"><?= $error ?></p>
    <?php endif; ?>

    <form action="edit.php?id=<?= $id; ?>" method="post">
        <div>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title"
                   value="<?=htmlspecialchars(html_entity_decode($post['title'])); ?>" required>
        </div>
        <div>
            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="10" required>
                <?= htmlspecialchars(html_entity_decode($post['content'])); ?>
            </textarea>
        </div>
        <div>
            <input type="submit" name="update" value="Update Post">
            <input type="submit" name="delete" value="Delete Post"
                   onclick="return confirm('Are you sure you want to delete this post?');">
        </div>
    </form>

</body>
</html>