<?php
// Custom post type for Location
// To be included in functions.php

// Creates new post type for Locations
if (!function_exists('register_mentor_post_type')):
    function register_mentor_post_type()
    {

        register_taxonomy('mentor', 'product', array(
            'hierarchical' => false,
            'labels' => array(
                'name' => __('Mentors'),
                'singular_name' => __('Mentor'),
                'add_new_item' => __('Add new Mentor'),
                'edit_item' => __('Edit Mentor'),
                'new_item' => __('New Mentor'),
                'view_item' => __('View Mentor'),
                'all_items' => __('All Mentors'),
                'search_items' => __('Search Mentors'),
                'not_found' => __('No mentors found'),
                'not_found_in_trash' => __('No mentors found in trash'),
                'parent_item' => __('Parent Mentor'),
            ),
            'rewrite' => array(
                'slug' => 'mentors',
                'with_front' => false
            )
        ));

    }
endif;

// Add term page
function mentor_add_new_meta_field()
{
    ?>
    <div class="form-field">
        <label for="phone"><?php _e('Phone number', 'sequential'); ?></label>
        <input type="text" name="phone" id="phone" value="">
    </div>
    <?php
}

function mentor_edit_meta_fields($term, $taxonomy)
{
    $phone = get_term_meta($term->term_id, 'phone', true);
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row">
            <label for="phone"><?php _e('Phone number', 'sequential'); ?></label>
        </th>
        <td>
            <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>"/>
        </td>
    </tr>
    <?php
}

function mentor_save_taxonomy_meta($term_id, $tag_id)
{
    if (isset($_POST['phone'])) {
        update_term_meta($term_id, 'phone', esc_attr($_POST['phone']));
    }
}

add_action('created_mentor', 'mentor_save_taxonomy_meta', 10, 2);
add_action('edited_mentor', 'mentor_save_taxonomy_meta', 10, 2);

add_action('mentor_edit_form_fields', 'mentor_edit_meta_fields', 10, 2);
add_action('mentor_add_form_fields', 'mentor_add_new_meta_field', 10, 2);

add_action('init', 'register_mentor_post_type');
