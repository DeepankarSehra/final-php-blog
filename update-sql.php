<?php
require_once 'lib/common.php';
require_once 'lib/update.php';

// We store stuff in the session, to survive the redirect to self
session_start();

// Only run the installer when we're responding to the form
if ($_POST)
{
    // Here's the install
    $pdo = getPDO();
    $error = updateBlog($pdo);

    $_SESSION['error'] = $error;
    $_SESSION['try-update'] = true;

    // ... and here we redirect from POST to GET
    redirectAndExit('index.php');
}

// Let's see if we've just installed
$attempted = false;
if (isset($_SESSION['try-update']))
{
    $attempted = true;
    $error = $_SESSION['error'];

    // Unset session variables, so we only report the install/failure once
    unset($_SESSION['error']);
    unset($_SESSION['try-install']);
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Blog updater</title>
        <?php require_once 'templates/head.php' ?>
    </head>
    <body>
        <?php if ($attempted): ?>

            <?php if ($error): ?>
                <div class="error box">
                    <?php echo $error ?>
                </div>
            <?php else: ?>
                <div class="success box">
                    Blog updated successfully.
                </div>

                <p>
                    <a href="index.php">View the blog</a>,
                    or <a href="update-sql.php">update again</a>.
                </p>
            <?php endif ?>

        <?php else: ?>

            <p>Click the update button to update the database.</p>

            <form method="post">
                <input
                    name="update"
                    type="submit"
                    value="Update"
                />
            </form>

        <?php endif ?>
    </body>
</html>
