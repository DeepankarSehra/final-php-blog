<?php 
require_once 'lib/common.php';
require_once 'lib/list-posts.php';

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
        <title> Blogu title | Blog posts</title>
        <?php require_once 'templates/head.php' ?>
    </head>

    <body>
        <?php require_once 'templates/top-menu.php' ?>

        <h1> Post list </h1>
        <p> You have <?php echo count($posts)?> posts. </p>
        <form method="post">
            <table id="post-list">
                <thead>
                    <tr>
                        <th> Title </th>
                        <th> Creation date </th>
                        <th> Comments </th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td>
                                <a href="view-post.php?post_id=<?php echo $post['id'] ?>"><?php echo htmlEscape($post['title'])?></a>
                            </td>

                            <td>
                                <?php echo convertSqlDate($post['created_at']) ?>
                            </td>

                            <td>
                                <?php echo htmlEscape($post['comment_count']); ?> comments 
                            </td>

                            <td>
                                <?php if(checkUser($pdo, $post['id'], getAuthUser())): ?>
                                    <a href="edit-post.php?post_id=<?php echo $post['id']?>">Edit</a>
                                <?php endif ?>
                            </td>

                            <td>
                                <?php if(checkUser($pdo, $post['id'], getAuthUser())): ?>
                                    <input
                                        type="submit"
                                        name="delete-post[<?php echo $post['id']?>]"
                                        value="Delete"
                                    />
                                <?php endif ?>
                            </td>
                        </tr>
                        <?php endforeach ?>
                </tbody>
            </table>
        </form>
    </body>

</html>