<?php
require_once 'lib/common.php';
require_once 'lib/view-post.php';

session_start();

// Get the post ID
if (isset($_GET['post_id']))
{
    $postId = $_GET['post_id'];
}
else
{
    // So we always have a post ID var defined
    $postId = 0;
}

$pdo = getPDO();
$row = getPostRow($pdo, $postId);
$commentCount = $row['comment_count'];

if (!$row)
{
    redirectAndExit('index.php?not-found=1');
}

$errors = null;
if ($_POST)
{
    switch($_GET['action'])
    {
        case 'add-comment':
            $commentData = array(
                'name' => $_POST['comment-name'],
                'website' => $_POST['comment-website'],
                'text' => $_POST['comment-text'],
            );
            $errors = handleAddComment($pdo, $postId, $commentData);
            break;
        case 'delete-comment':
            $deleteResponse = $_POST['delete-comment'];
            handleDeleteComment($pdo, $postId, $deleteResponse);
    }
    
}
else
{
    $commentData = array(
        'name' => '',
        'website' => '',
        'text' => '',
    );
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>
            Blogu title |
            <?php echo htmlEscape($row['title']) ?>
        </title>
        <?php require_once 'templates/head.php' ?>
    </head>
    <body>
        <?php require_once 'templates/title.php' ?>

        <div class="post">
            <h2>
                <?php echo htmlEscape($row['title']) ?>
            </h2>
            <div class="date">
                <?php echo convertSqlDate($row['created_at']) ?>
            </div>

            <?php // This is already escaped, so doesn't need further escaping ?>
            <?php echo convertNewlinesToParagraphs($row['body']) ?>
        </div>

        <?php require_once 'templates/list-comments.php'?>

        <?php require_once 'templates/comment-form.php' ?>
    </body>
</html>
