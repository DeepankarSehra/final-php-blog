<?php 
require_once 'lib/common.php';
require_once 'lib/edit-post.php';
require_once 'lib/view-post.php';   

session_start();

// to not let non-auth users see this page
if(!isLoggedIn())
{
    redirectAndExit('index.php');
}

// empty default variables
$title = $body = $imagePath = '';

// initialise database and get handle
$pdo = getPDO();

$postId = null;
if(isset($_GET['post_id'])){
    $post = getPostRow($pdo, $_GET['post_id']);
    if($post){
        $postId = $_GET['post_id'];
        $title = $post['title'];
        $body = $post['body'];
        $imagePath = $post['image_path'];
    }
}

// new post feature here
$errors = array();
if($_POST)
{
    $title = $_POST['post-title'];
    if(!$title)
    {   
        $errors[] = 'The post must have a title';
    }   

    $body = $_POST['post-body'];
    if(!$body)
    {
        $errors[] = 'The post must have a body';
    }

    // Handle image upload
    $uploadedImagePath = null;
    if(isset($_FILES['post-image']) && $_FILES['post-image']['error'] === UPLOAD_ERR_OK)
    {
        $uploadDir = 'uploads/';
        $imageName = basename($_FILES['post-image']['name']);
        $targetFile = $uploadDir . $imageName;

        // check if dir exists
        if(!is_dir($uploadDir)){
            mkdir($uploadDir, 0777, true);
        }

        if(move_uploaded_file($_FILES['post-image']['tmp_name'], $targetFile)){
            $uploadedImagePath = $targetFile;

            if($imagePath && file_exists($imagePath)){
                unlink($imagePath);
            }
        }
        else{
            $errors[] = 'error uploading the image';
        }
    }

    if(!$errors)
    {
        $pdo = getPDO();
        // decide if editing or adding post
        if($postId){
            $imagePathToUse = $uploadedImagePath ?? $imagePath;         // use the new image path if uploaded, otherwise retain the prev path
            editPost($pdo, $title, $body, $postId, $imagePathToUse);
        }
        else{
            $userId = getAuthUserId($pdo);
            $postId = addPost($pdo, $title, $body, $userId, $uploadedImagePath);

            if($postId === false){
                $errors[] = 'Post operation failed.';
            }
        }
    }

    if(!$errors){
        // redirectAndExit('edit-post.php?post_id='.$postId);
        redirectAndExit('index.php');
    }   

}

?>

<DOCTYPE html>
        <?php include 'templates/head.php' ?>
        <?php require 'templates/top-menu.php' ?>

        <?php if(isset($_GET['post_id'])): ?>
            <h1>Edit post</h1>
        <?php else: ?>
            <h1>New post</h1>
        <?php endif ?>


        <?php if($errors): ?>
            <div class='error box'>
                <ul>
                    <?php foreach ($errors as $error) ?>
                    <li><?php echo $error ?></li>
            </div>
        <?php endif ?>


        <form method="post" class="post-form user-form" enctype="multipart/form-data">
            <div>
                <label for="post-title">Title:</label>
                <textarea
                    id="post-title"
                    name="post-title"
                    placeholder="Write your title"
                ><?php echo htmlEscape($title) ?></textarea>
            </div>

            <div>
                <label for="post-body">Body:</label>
                <textarea
                    id="post-body"
                    name="post-body"
                    rows="12"
                    cols="70"
                    placeholder="Write your post"
                ><?php echo htmlEscape($body) ?></textarea>
            </div>

            <div>
                <label for="post-image">Post Image:</label>
                <input type="file" name="post-image" accept="image/*">
                <?php if($imagePath): ?>
                    <p>Current Image:</p>
                    <img src="<?php echo htmlEscape($imagePath); ?>" alt="Post Image" style="max-width:50%;">
                <?php endif ?>
            </div>

            <div>
                <input
                    type="submit"
                    value="Save post"
                />
                <a href="index.php">Cancel</a>
            </div>
        </form>
    </body>

</html>


</DOCTYPE>
