<?php 

function addPost(PDO $pdo, $title, $body, $userId, $imagePath = null)
{
    $sql = 'INSERT INTO post (title, body, user_id, created_at, image_path) VALUES (:title, :body, :user_id, :created_at, :image_path)';

    $stmt = $pdo->prepare($sql);
    if($stmt === false){
        throw new Exception('Could not prepare post insert query.');
    }

    $result = $stmt -> execute(
        array('title' => $title, 'body' => $body, 'user_id' => $userId, 'created_at' => getSqlDateForNow(), 'image_path' => $imagePath)
    );

    if($result === false){
        throw new Exception('Could not run post insert query.');
    }

    return $pdo -> lastInsertId();
}


function editPost(PDO $pdo, $title, $body, $postId, $imagePath)
{
    $sql = 'UPDATE post SET title = :title, body = :body, image_path = :image_path WHERE id = :post_id';
    $stmt = $pdo->prepare($sql);
    if($stmt === false){
        throw new Exception('Could not prepare update query.');
    }

    $result = $stmt -> execute(
        array('title' => $title, 'body' => $body, 'post_id' => $postId, 'image_path' => $imagePath)
    );

    if($result === false){
        throw new Exception('Could not run update query.');
    }

    // redirectAndExit('index.php');

    return true;    

}

