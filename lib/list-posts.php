<?php

function deletePost(PDO $pdo, $postId)
{
    $sql = 'DELETE FROM post WHERE id=:id';
    $stmt = $pdo -> prepare($sql);
    if($stmt === false){
        throw new Exception('Delete post query could not be initialised');
    }

    $result = $stmt -> execute(array('id' => $postId,));
    if($result === false){
        throw new Exception('Post cant be deleted');
    }

    return $result !== false;

}


?>