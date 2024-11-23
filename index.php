<?php
require_once 'lib/common.php';

session_start();

// Connect to the database, run a query, handle errors
$pdo = getPDO();
$posts = getAllPosts($pdo);
$stmt = $pdo->query(
    'SELECT
        id, title, created_at, body
    FROM
        post
    ORDER BY
        created_at DESC'
);
if ($stmt === false)
{
    throw new Exception('There was a problem running this query');
}

$notFound = isset($_GET['not-found']);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Blogu title</title>
        <?php require_once 'templates/head.php' ?>
    </head>
    <body>
        <?php require_once 'templates/title.php' ?>

        <?php if ($notFound): ?>
            <div class="error box">
                Error: cannot find the requested blog post
            </div>
        <?php endif ?>

        <div class="post-list">
            <?php foreach ($posts as $post): ?>
                <div class="post-synopsis"> 
                    <h2>
                        <?php echo htmlEscape($post['title']) ?>
                    </h2>   
                    <div class="meta">
                        <?php echo convertSqlDate($post['created_at']) ?>

                        (<?php echo $post['comment_count'] ?> comments)
                    </div>
                    <p>
                        <?php echo htmlEscape($post['body']) ?>
                    </p>
                    <div class="post-controls">
                        <a
                            href="view-post.php?post_id=<?php echo $post['id'] ?>"
                        >Read more...</a>
                        <?php if (isLoggedIn()): ?>
                            |
                            <a
                                href="edit-post.php?post_id=<?php echo $post['id'] ?>"
                            >Edit</a>
                        <?php endif ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

    </body>
</html>
