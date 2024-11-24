<div class="top-menu">
    <div class="menu-options">
        <?php if (isLoggedIn()): ?>
            <?php if(getAuthUser() === 'admin'): ?>
                <a href="add-users.php">Add user</a>
                <a href="delete-users.php">Delete users</a>
            <?php endif ?>
            <a href="index.php">Home</a>
            <a href="list-posts.php">All posts</a>
            <a href="edit-post.php">New post</a>
            Hello <?php echo htmlEscape(getAuthUser()) ?>.
            <a href="logout.php">Log out</a>
        <?php else: ?>
            <a href="login.php">Log in</a>
        <?php endif ?>
    </div>
</div>