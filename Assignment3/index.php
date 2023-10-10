<?php

/*******w******** 
    
    Name: Yi Siang Chang
    Date: 2023-10-06
    Description: Homepage of the blog.

****************/

// Include the connection file to connect to the database
require('connect.php');

//  Five most recent blog posts displayed in reverse chronological order.
$query = "SELECT * FROM blog ORDER BY id DESC LIMIT 5";

// Prepare the query to be executed
$statement = $db->prepare($query);

// Execute the query
$statement->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Home Page of My Blog</title>
</head>
<body>
    <!-- Remember that alternative syntax is good and html inside php is bad -->

    <div id="content">

        <h2>
            <a href="index.php">
                <img src="logo.png" alt="Blog Logo" class="blog-logo" style="width: 80px;
                 vertical-align: middle;">
            </a>
            <a href="index.php" style="vertical-align: middle;">My Amazing Blog</a>
        </h2>

        <div class="topnav">
            <!--<a href="index.php">Home</a>-->
            <a href="post.php">New Blog Post</a>
        </div>

	    <?php if ($statement->rowCount() == 0) : ?>
            <p>Error: No Posts Detected</p>
	    <?php else : ?>

		    <?php while ($row = $statement->fetch()) : ?>
                <h3>
                    <a href="fullpost.php?id=<?= $row['id'] ?>"><?= $row['title'] ?></a>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="edit-button">edit</a>
                </h3>

                <p><?= date("F d, Y, h:i a", strtotime($row['datepost'])) ?></p>
                <p><?= substr($row['content'], 0, 200) ?>...
                    <a href="fullpost.php?id=<?= $row['id'] ?>">Read Full Post</a>
                </p>

		    <?php endwhile; ?>

	    <?php endif; ?>

    </div>

</body>
</html>