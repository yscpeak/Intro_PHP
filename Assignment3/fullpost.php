<?php

/*******w********

Name: Yi Siang Chang
Date: 2023-10-06
Description: To show full contents of the post.

 ****************/

require('connect.php');
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
	header('Location: index.php');
	exit;
}

$query = "SELECT * FROM blog WHERE id = :id";
$statement = $db->prepare($query);
$statement->bindValue(':id', $id, PDO::PARAM_INT);
$statement->execute();
$post = $statement->fetch();

if (!$post) {
	header('Location: index.php');
	exit;
}
?>

<!-- ... Your HTML Structure ... -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <!--<title><?/*= htmlspecialchars($post['title']) */?></title>-->
    <title><?= htmlspecialchars(html_entity_decode($post['title'])) ?></title>
</head>

<body>

<div id="content">
    <!--<h2><?/*= htmlspecialchars($post['title']) */?></h2>-->
    <h2>
        <a href="index.php">
            <img src="logo.png" alt="Blog Logo" class="blog-logo" style="width: 80px; vertical-align: middle;">
        </a>
        <a href="index.php" style="vertical-align: middle;">My Amazing Blog</a>
    </h2>
    <h3><?= htmlspecialchars(html_entity_decode($post['title'])) ?>
        <!--<div class="topnav">
            <a href="index.php">Return Home</a>
        </div>-->
    </h3>

    <p><?= date("F d, Y, h:i A", strtotime($post['datepost'])) ?>
        <a href="edit.php?id=<?= $post['id'] ?>">edit</a>
    </p><br>

    <p><?= htmlspecialchars(html_entity_decode($post['content'])) ?></p>

</div>
</body>

</html>