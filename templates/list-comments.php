<form 
    method="post"
    class="comment-list"
    action="view-post.php?action=delete-comment&amp;post_id=<?php echo $postId ?>&amp;"
>

    <h3><?php echo $commentCount ?> comments</h3>

    <?php foreach (getCommentsForPost($pdo, $postId) as $comment): ?>
    <div class="comment">
        <div class="comment-meta">
            Comment from
            <?php echo htmlEscape($comment['name']) ?> on <?php echo convertSqlDate($comment['created_at']) ?>
            <?php if(isLoggedIn()): ?>
                <?php if(getAuthUser() === 'admin'): ?>
                    <input
                        type='submit'
                        name="delete-comment[<?php echo $comment['id'] ?>]"
                        value="delete"
                    />
                <?php endif ?>
        </div>
        <?php endif ?>

        <div class="comment-body">
            <?php // This is already escaped ?>
            <?php echo convertNewlinesToParagraphs($comment['text']) ?>
        </div>
    </div>
    <?php endforeach ?>
</form>