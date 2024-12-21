<?php 
function updateBlog(PDO $pdo)
{
    // Get a couple of useful project paths
    $root = getRootPath();
    $database = getDatabasePath();

    $error = '';

    // Grab the SQL commands we want to run on the database
    if (!$error)
    {
        $sql = file_get_contents($root . '/data/update.sql');

        if ($sql === false)
        {
            $error = 'Cannot find SQL file';
        }
    }

    // Connect to the new database and try to run the SQL commands
    if (!$error)
    {
        $result = $pdo->exec($sql);
        if ($result === false)
        {
            $error = 'Could not run SQL: ' . print_r($pdo->errorInfo(), true);
        }
    }

    return $error;
}

?>