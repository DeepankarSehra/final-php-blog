<?php

function deletePost(PDO $pdo, $postId)
{
    $sqls = array('DELETE FROM comment WHERE post_id = :id', 'DELETE FROM post WHERE id=:id',);
    
    foreach($sqls as $sql)
    {
        $stmt = $pdo -> prepare($sql);

        if($stmt === false){
            throw new Exception('Delete post query could not be initialised');
        }
    
        $result = $stmt -> execute(array('id' => $postId,));
        if($result === false){
            break; // dont continue if something went wrong
        }
    }

    

    return $result !== false;

}


?>