<?php 
require_once 'lib/common.php';

session_start();

if ($_POST)
{
    $deleteResponse = $_POST['delete-user'];

    if($deleteResponse){
        $keys = array_keys($deleteResponse);
        $deleteUserUsername = $keys[0];
        if ($deleteUserUsername)
        {
            deleteUserWithCascade(getPDO(), $deleteUserUsername);
        }
    }
}

$pdo = getPDO();
$users = getAllUsers($pdo);

if(!isLoggedIn() and getAuthUser() !== 'admin'){
    redirectAndExit('index.php');
}

?>

<DOCTYPE html>

        <?php include 'templates/head.php' ?>
        <?php require_once 'templates/top-menu.php' ?>

        <h1> User list </h1>
        <p> You have <?php echo count($users)?> users. </p>
        <form method="post">
            <table id="user-list">
                <thead>
                    <tr>
                        <th> Username </th>
                        <th> Creation date </th>
                        <th> Number of posts </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <?php echo htmlEscape($user['username'])?></a>
                            </td>

                            <td>
                                <?php echo convertSqlDate($user['created_at']) ?>
                            </td>

                            <td>
                                <?php echo htmlEscape(getPostCounts($pdo, $user['id'])) ?>
                            </td>

                            <td>
                                <input
                                    type="submit"
                                    name="delete-user[<?php echo $user['username']?>]"
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