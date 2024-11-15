<hr />

<?php if($errors): ?>
    <div style = "border 1px solid #ff6666; padding: 6px;">
        <ul>
            <?php foreach($errors as $error)       : ?>
                <?php echo $error; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<h3>Add comment</h3>

<form method="post">
    <p>
        <label for = "comment-name">
            Name:
        </label>
        <input type="text" id="comment-name" name="comment-name" value="<?php echo htmlEscape($commentData['_name']) ?>"/>
    </p>

    <p>
        <label for = "comment-website">
            Website:
        </label>
        <input type="text" id="comment-website" name="comment-website" value="<?php echo htmlEscape($commentData['website']) ?> "/>
    </p>

    <p>
        <label for = "comment-text">
            Comment:
        </label>
        <textarea id="comment-text" name="comment-text" rows="8" cols="70"> <?php echo htmlEscape($commentData['_text']) ?> </textarea>
    </p>

    <input type="submit" value="Submit comment" />  
</form>