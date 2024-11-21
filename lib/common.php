<?php

/**
 * Gets the root path of the project
 * 
 * @return string
 */
function getRootPath()
{
    return realpath(__DIR__ . '/..');
}

/**
 * Gets the full path for the database file
 * 
 * @return string
 */
function getDatabasePath()
{
    return getRootPath() . '/data/data.sqlite';
}

/**
 * Gets the DSN for the SQLite connection
 * 
 * @return string
 */
function getDsn()
{
    return 'sqlite:' . getDatabasePath();
}

/**
 * Gets the PDO object for database access
 * 
 * @return \PDO
 */
function getPDO()
{
    $pdo = new PDO(getDsn());

    $result = $pdo->query('PRAGMA foreign_keys=ON');
    if($result === false)
    {
        throw new Exception('Could not turn on foreign keys constraint');
    }

    return $pdo;
}

/**
 * Escapes HTML so it is safe to output
 * 
 * @param string $html
 * @return string
 */
function htmlEscape($html)
{
    return htmlspecialchars($html, ENT_HTML5, 'UTF-8');
}

function convertSqlDate($sqlDate)
{
    /* @var $date DateTime */
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $sqlDate);

    return $date->format('d M Y, H:i');
}

function getSqlDateForNow()
{
    return date('Y-m-d H:i:s');
}

/**
 * Converts unsafe text to safe, paragraphed, HTML
 * 
 * @param string $text
 * @return string
 */
function convertNewlinesToParagraphs($text)
{
    $escaped = htmlEscape($text);

    return '<p>' . str_replace("\n", "</p><p>", $escaped) . '</p>';
}

function redirectAndExit($script)
{
    // Get the domain-relative URL (e.g. /blog/whatever.php or /whatever.php) and work
    // out the folder (e.g. /blog/ or /).
    $relativeUrl = $_SERVER['PHP_SELF'];
    $urlFolder = substr($relativeUrl, 0, strrpos($relativeUrl, '/') + 1);

    // Redirect to the full URL (http://myhost/blog/script.php)
    $host = $_SERVER['HTTP_HOST'];
    $fullUrl = 'http://' . $host . $urlFolder . $script;
    header('Location: ' . $fullUrl);
    exit();
}

/**
 * Returns all the comments for the specified post
 * 
 * @param integer $postId
 */
function getCommentsForPost(PDO $pdo, $postId)
{
    $pdo = getPDO();
    $sql = "
        SELECT
            id, name, text, created_at, website
        FROM
            comment
        WHERE
            post_id = :post_id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array('post_id' => $postId, )
    );

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function tryLogin(PDO $pdo, $username, $password)
{
    $sql = "
        SELECT
            password
        FROM
            user
        WHERE
            username = :username
        AND is_enabled = 1
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array('username' => $username, )
    );

    // Get the hash from this row, and use the third-party hashing library to check it
    $hash = $stmt->fetchColumn();
    $success = password_verify($password, $hash);

    return $success;
}

/**
 * Logs the user in
 * 
 * For safety, we ask PHP to regenerate the cookie, so if a user logs onto a site that a cracker
 * has prepared for him/her (e.g. on a public computer) the cracker's copy of the cookie ID will be
 * useless.
 * 
 * @param string $username
 */
function login($username)
{
    session_regenerate_id();

    $_SESSION['logged_in_username'] = $username;
}

/**
 * Logs the user out
 */
function logout()
{
    unset($_SESSION['logged_in_username']);
}

function getAuthUser()
{
    return isLoggedIn() ? $_SESSION['logged_in_username'] : null;
}

function isLoggedIn()
{
    return isset($_SESSION['logged_in_username']);
}


function getAuthUserId(PDO $pdo)
{
    if(!isLoggedIn())
    {
        return null;
    }

    $sql = 'SELECT id from user WHERE username = :username AND is_enabled = 1';
    $stmt = $pdo->prepare($sql);
    $stmt -> execute(
        array('username' => getAuthUser())
    );

    return $stmt -> fetchColumn();
}

function getAllPosts(PDO $pdo)
{
    $stmt = $pdo -> query(
        'SELECT id, title, created_at, body, (SELECT COUNT(*) FROM comment WHERE comment.post_id = post.id) comment_count FROM post ORDER BY created_at DESC'
    );

    if($stmt === false){
        throw new Exception('Cant get all posts');
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}