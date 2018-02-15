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
    <div class="form-field term-group-wrap">
        <th scope="row">
            <label for="position_title"><?php _e('Position title', 'sequential'); ?></label>
        </th>
        <td>
            <input type="text" id="position_title" name="position_title"/>
        </td>
    </div>
    <div class="form-field term-group-wrap">
        <th scope="row">
            <label for="organisation"><?php _e('Organisation', 'sequential'); ?></label>
        </th>
        <td>
            <input type="text" id="organisation" name="organisation"/>
        </td>
    </div>
    <div class="form-field term-group-wrap">
        <th scope="row">
            <label for="type"><?php _e('Type', 'sequential'); ?></label>
        </th>
        <td>
            <input type="text" id="type" name="type"/>
        </td>
    </div>
    <div class="form-field term-group-wrap">
        <th scope="row">
            <label for="ask_me_about"><?php _e('Ask me about', 'sequential'); ?></label>
        </th>
        <td>
            <input type="text" id="ask_me_about" name="ask_me_about"/>
        </td>
    </div>
    <div class="form-field term-group-wrap">
        <th scope="row">
            <label for="email"><?php _e('Email', 'sequential'); ?></label>
        </th>
        <td>
            <input type="text" id="email" name="email"/>
        </td>
    </div>
    <div class="form-field term-group-wrap">
        <th scope="row">
            <label for="twitter"><?php _e('Twitter', 'sequential'); ?></label>
        </th>
        <td>
            <input type="text" id="twitter" name="twitter"/>
        </td>
    </div>
    <div class="form-field term-group-wrap">
        <th scope="row">
            <label for="linkedin"><?php _e('LinkedIn', 'sequential'); ?></label>
        </th>
        <td>
            <input type="text" id="linkedin" name="linkedin"/>
        </td>
    </div>

    <style>
        .term-slug-wrap {
            display: none;
        }
    </style>
    <?php
}

function mentor_edit_meta_fields($term, $taxonomy)
{
    $type = get_term_meta($term->term_id, 'type', true);
    $position_title = get_term_meta($term->term_id, 'position_title', true);
    $ask_me_about = get_term_meta($term->term_id, 'ask_me_about', true);
    $organisation = get_term_meta($term->term_id, 'organisation', true);
    $email = get_term_meta($term->term_id, 'email', true);
    $twitter = get_term_meta($term->term_id, 'twitter', true);
    $linkedin = get_term_meta($term->term_id, 'linkedin', true);
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row">
            <label for="position_title"><?php _e('Position title', 'sequential'); ?></label>
        </th>
        <td>
            <input type="text" id="position_title" name="position_title" value="<?php echo $position_title; ?>"/>
        </td>
    </tr>
    <tr class="form-field term-group-wrap">
        <th scope="row">
            <label for="organisation"><?php _e('Organisation', 'sequential'); ?></label>
        </th>
        <td>
            <input type="text" id="organisation" name="organisation" value="<?php echo $organisation; ?>"/>
        </td>
    </tr>
    <tr class="form-field term-group-wrap">
        <th scope="row">
            <label for="type"><?php _e('Type', 'sequential'); ?></label>
        </th>
        <td>
            <input type="text" id="type" name="type" value="<?php echo $type; ?>"/>
        </td>
    </tr>
    <tr class="form-field term-group-wrap">
        <th scope="row">
            <label for="ask_me_about"><?php _e('Ask me about', 'sequential'); ?></label>
        </th>
        <td>
            <input type="text" id="ask_me_about" name="ask_me_about" value="<?php echo $ask_me_about; ?>"/>
        </td>
    </tr>
    <tr class="form-field term-group-wrap">
        <th scope="row">
            <label for="email"><?php _e('Email', 'sequential'); ?></label>
        </th>
        <td>
            <input type="text" id="email" name="email" value="<?php echo $email; ?>"/>
        </td>
    </tr>
    <tr class="form-field term-group-wrap">
        <th scope="row">
            <label for="twitter"><?php _e('Twitter', 'sequential'); ?></label>
        </th>
        <td>
            <input type="text" id="twitter" name="twitter" value="<?php echo $twitter; ?>"/>
        </td>
    </tr>
    <tr class="form-field term-group-wrap">
        <th scope="row">
            <label for="linkedin"><?php _e('LinkedIn', 'sequential'); ?></label>
        </th>
        <td>
            <input type="text" id="linkedin" name="linkedin" value="<?php echo $linkedin; ?>"/>
        </td>
    </tr>
    <?php
}

function mentor_save_taxonomy_meta($term_id, $tag_id)
{
    if (isset($_POST['name'])) {
        update_term_meta($term_id, 'type', esc_attr($_POST['type']));
        update_term_meta($term_id, 'position_title', esc_attr($_POST['position_title']));
        update_term_meta($term_id, 'ask_me_about', esc_attr($_POST['ask_me_about']));
        update_term_meta($term_id, 'organisation', esc_attr($_POST['organisation']));
        update_term_meta($term_id, 'email', esc_attr($_POST['email']));
        update_term_meta($term_id, 'twitter', esc_attr($_POST['twitter']));
        update_term_meta($term_id, 'linkedin', esc_attr($_POST['linkedin']));
    }
}

add_action('created_mentor', 'mentor_save_taxonomy_meta', 10, 2);
add_action('edited_mentor', 'mentor_save_taxonomy_meta', 10, 2);

add_action('mentor_edit_form_fields', 'mentor_edit_meta_fields', 10, 2);
add_action('mentor_add_form_fields', 'mentor_add_new_meta_field', 10, 2);

add_action('init', 'register_mentor_post_type');
