<?php

/*******w******** 
    
    Name: Yi Siang Chang
    Date: 2023-10-06
    Description: To create the new post.

****************/

require('authenticate.php');
require('connect.php');


// Check if form is submitted via POST and validate
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING));
	$content = trim(filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING));

	if (empty($title) || empty($content)) {
		$error = "Both title and content are required!";
	} else {
		$query = "INSERT INTO blog (title, content) VALUES (?, ?)";
		$stmt = $db->prepare($query);
		$stmt->execute([$title, $content]);
		header('Location: index.php'); // Redirect to the index or a confirmation page after successful post
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Post a New Blog</title>
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

    <!--Display the error message if set-->
    <?php if(isset($error)): ?>
    <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form action="post.php" method="POST">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title"><br>
        <label for="content">Content:</label><br>
        <textarea id="content" name="content" rows="4" cols="50"></textarea><br><br>
        <input type="submit" value="Submit">
    </form>

    <p>If you click the "Submit" button, the form-data will be sent to a page
        called "/action_page.php".</p>
</body>
</html>