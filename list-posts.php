<?php 
require 'lib/common.php';
require 'lib/list-posts.php';

session_start();

if ($_POST)
{
    $deleteResponse = $_POST['delete-post'];
    if ($deleteResponse)
    {
        $keys = array_keys($deleteResponse);
        $deletePostId = $keys[0];
        if ($deletePostId)
        {
            deletePost(getPDO(), $deletePostId);
            redirectAndExit('list-posts.php');
        }
    }
}

$pdo = getPDO();
$posts = getAllPosts($pdo);

if(!isLoggedIn()){
    redirectAndExit('index.php');
}

?>

<DOCTYPE html>

<html>
    <head>
        <title> A blog application | Blog posts</title>
        <?php require 'templates/head.php' ?>
    </head>

    <body>
        <?php require 'templates/top-menu.php' ?>

        <h1> Post list </h1>
        <p> You have <?php echo count($posts)?> posts. </p>
        <form method="post">
            <table id="post-list">
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td>
                                <?php echo htmlEscape($post['title'])?>
                            </td>

                            <td>
                                <?php echo convertSqlDate($post['created_at']) ?>
                            </td>

                            <td>
                                <a href="edit-post.php?post_id=<?php echo $post['id']?>">Edit</a>
                            </td>

                            <td>
                                <input
                                    type="submit"
                                    name="delete-post[<?php echo $post['id']?>]"
                                    value="Delete"
                                />
                            </td>
                        </tr>
                        <?php endforeach ?>
                </tbody>
            </table>
        </form>
    </body>

</html>