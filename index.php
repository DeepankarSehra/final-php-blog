<?php
require_once 'lib/common.php';
session_start();
require_once 'lib/protected.php';

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
        <?php include 'templates/head.php' ?>
        <?php require_once 'templates/title.php' ?>
    <body>
        <?php if ($notFound): ?>
            <div class="error box">
                Error: cannot find the requested blog post
            </div>
        <?php endif ?>

        <div class="post-list">
            <?php foreach ($posts as $post): ?>
                <div class="post-synopsis"> 

                    <h2>
                        <!-- <?php echo htmlEscape($post['title']) ?> -->
                        <a href="view-post.php?post_id=<?php echo $post['id'] ?>"> <?php echo htmlEscape($post['title']) ?> </a>
                    </h2>   

                    <div class="meta">
                        <?php echo convertSqlDate($post['created_at']) ?>

                        (<?php echo $post['comment_count'] ?> comments)
                    </div>

                    <?php if(!empty($post['image_path'])):?>
                        <div class="post-image">
                            <img src="<?php echo htmlEscape($post['image_path']); ?>" alt="Post Image" style="max-width: 400px; height: 200px;">
                        </div>
                        <?php else:?>
                        <div class="post-image">
                            link nono.
                        </div>
                    <?php endif; ?>    

                    <p>
                        <?php if(strlen($post['body']) > 200)
                        {
                            echo htmlEscape(substr($post['body'], 0, 200) . '.....');
                        }
                        else
                        {
                            echo htmlEscape($post['body']);
                        }
                        ?>
                    </p>
                    <div class="post-controls">
                        <a
                            href="view-post.php?post_id=<?php echo $post['id'] ?>"
                        >Read more...</a>
                        <?php if (isLoggedIn()): ?>
                            <?php if(checkUser($pdo, $post['id'], getAuthUser())): ?>
                                <a
                                    href="edit-post.php?post_id=<?php echo $post['id'] ?>"
                                >Edit</a>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

    </body>
</html>
