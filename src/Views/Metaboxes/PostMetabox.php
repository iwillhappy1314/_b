<?php

namespace WenpriseSpaceName\Views\Metaboxes;

class PostMetabox
{
    public function __construct()
    {
        if (is_admin()) {
            add_action('load-post.php', [$this, 'init_metabox']);
            add_action('load-post-new.php', [$this, 'init_metabox']);
        }


    }

    /**
     * Meta box initialization.
     */
    public function init_metabox()
    {
        add_action('add_meta_boxes', [$this, 'add_meta_box']);
        add_action('save_post', [$this, 'save']);
    }

    /**
     * Adds the meta box container.
     */
    public function add_meta_box($post_type)
    {
        // Limit meta box to certain post types.
        $post_types = ['post'];

        if (in_array($post_type, $post_types)) {
            add_meta_box('_b_metabox', __('Post Meta Box', '_b'), [$this, 'render'], $post_type, 'advanced', 'high');
        }
    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save(int $post_id)
    {

        /*
         * We need to verify this came from our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */

        // Check if our nonce is set.
        if ( ! isset($_POST[ '_b_metabox_nonce' ])) {
            return $post_id;
        }

        $nonce = $_POST[ '_b_metabox_nonce' ];

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce($nonce, '_b_metabox_nonce')) {
            return $post_id;
        }

        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // Check the user's permissions.
        if ('page' == $_POST[ 'post_type' ]) {
            if ( ! current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can('edit_post', $post_id)) {
                return $post_id;
            }
        }

        /* OK, it's safe for us to save the data now. */

        // Sanitize the user input.
        $mydata = sanitize_text_field($_POST[ '_b_new_field' ]);

        // Update the meta field.
        update_post_meta($post_id, '_b_new_field', $mydata);
    }


    /**
     * Render Meta Box content.
     *
     * @param \WP_Post $post The post object.
     */
    public function render(\WP_Post $post)
    {

        // Add a nonce field, so we can check for it later.
        wp_nonce_field('_bbox', '_b_metabox_nonce');

        // Use get_post_meta to retrieve an existing value from the database.
        $value = get_post_meta($post->ID, '_b_new_field', true);

        // Display the form, using the current value.
        ?>
        <label for="_b_new_field">
            <?php _e('Description for this field', '_b'); ?>
        </label>

        <textarea style="width:100%;min-height:200px;" type="textarea" class="form-control" name="_b_new_field"><?php echo esc_attr($value); ?></textarea>

        <?php
    }
}