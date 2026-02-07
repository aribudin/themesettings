<?php
/**
 * The template for displaying comments
 *
 * @package starter 
 * @since 1.0.0
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            printf(
                /* translators: 1: Comment count */
                esc_html(_n('%d Comment', '%d Comments', $comment_count, 'nametheme')),
                number_format_i18n($comment_count)
            );
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments([
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 50,
                'callback'    => 'starter_comment_callback',
            ]);
            ?>
        </ol>

        <?php
        the_comments_navigation([
            'prev_text' => esc_html__('Older Comments', 'nametheme'),
            'next_text' => esc_html__('Newer Comments', 'nametheme'),
        ]);
        ?>

        <?php if (!comments_open()) : ?>
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'nametheme'); ?></p>
        <?php endif; ?>

    <?php endif; ?>

    <?php
    comment_form([
        'class_form'         => 'comment-form',
        'title_reply'        => esc_html__('Leave a Comment', 'nametheme'),
        'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after'  => '</h3>',
        'comment_field'      => '<p class="comment-form-comment"><label for="comment">' . esc_html__('Comment', 'nametheme') . ' <span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="6" required></textarea></p>',
        'submit_button'      => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />',
        'submit_field'       => '<p class="form-submit">%1$s %2$s</p>',
    ]);
    ?>
</div>
