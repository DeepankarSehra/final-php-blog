<?php require_once 'lib/common.php' ?>
<?php // Report any errors in a bullet-point list ?>
<?php if ($errors): ?>
    <div class="error box comment margin">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>

<h3>Add your comments</h3>


<form method="post" 
    class="comment-form user-form"
    action="view-post.php?action=add-comment&amp;post_id=<?php echo $postId?>">
    <div>
        <label for="comment-name">
            Name:
        </label>
        <input
            type="text"
            id="comment-name"
            name="comment-name"
            value="<?php echo htmlEscape($commentData['name']) ?>"
        />
    </div>
    <div>
        <label for="comment-text">
            Comment:
        </label>
        <textarea
            id="comment-text"
            name="comment-text"
            rows="8"
            cols="70"
        ><?php echo htmlEscape($commentData['text']) ?></textarea>
    </div>

    <div>
        <input type="submit" value="Submit comment" />
    </div>
</form>
