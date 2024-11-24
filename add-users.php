<?php require_once 'lib/common.php' ?>
<?php require_once 'lib/install.php' ?>

<?php 

session_start();

$errors = array();

if($_POST)
{
    $username = $_POST['new-username'];
    if(!$username){
        $errors[] = 'Give username';
        throw new Exception('gib username');
    }

    $password = $_POST['new-password'];
    if(!$password){
        $errors[] = 'Give password';
        throw new Exception('gib pw');
    }

    if(!$errors){
        $pdo = getPDO();

        list($password, $error) = createUser($pdo, $username, $password);
        redirectAndExit('index.php');
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Blogu title | New user</title>
        <?php require 'templates/head.php'?>
    </head>

    <body>
        <?php require 'templates/top-menu.php' ?>
        <form 
            method="post"
            class="add-user-form">
            <div>
                <label for="new-username">
                    Username:
                </label>
                <input 
                    type="text"
                    id="new-username"
                    name="new-username"/>
            </div>

            <div>
                <label for="new-password">
                    Password:
                </label>
                <input
                    type="text"
                    id="new-password"
                    name="new-password"/>
            </div>
        
            <div>
                <input
                    type="submit"
                    value="Add user"/>
            </div>
        </form>

    </body>
</html>