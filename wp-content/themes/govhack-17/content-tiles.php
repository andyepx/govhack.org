<?php
/**
 * Displays a bunch of tile-rows. Pretty useless unless called from a proper page template
 *
 * Currently using: (Simple Grid)[https://github.com/ThisIsDallas/Simple-Grid]
 *
 * @package Sequential
 */

$tiles_per_row = get_query_var('tiles_per_row', 3);                // a sensible default
$tiles_per_row = min(abs(intval($tiles_per_row)), 6);             // a sensible hard limit
$tiles_displayed_counter = 0;

// $tile_count = get_query_var('post_count');

$tile_wrapper_classnames = [gh_col_class(1, $tiles_per_row)];

gh_row_open('pad');

while (have_posts()) : the_post();

    $tile_classnames = [];
    $tile_attributes = [];
    $tile_styles = [];
    // echo '<pre>';
    // var_dump(get_post_custom_values('color'));
    // echo '</pre>';

    $tile_link = get_the_permalink();
    if ($tile_linked_page_id = get_post_meta(get_the_id(), 'linked_page_id', true)) {
        $tile_link = get_permalink($tile_linked_page_id);
    } elseif ($tile_linked_page_slug = get_post_meta(get_the_id(), 'linked_page_slug', true)) {
        $linked_page = get_page_by_path($tile_linked_page_slug, OBJECT);
        if (is_object($linked_page)) {
            $tile_link = get_permalink($linked_page->ID);
        }
    }

    if ($color = get_post_meta(get_the_id(), 'tile_color', true)) {
        $tile_classnames[] = $color;
    }

    if ($use_featherlight = get_post_meta(get_the_id(), 'use_featherlight_iframe', true)) {
        if ($use_featherlight_value = get_post_meta(get_the_id(), 'use_featherlight_value', true)) {
            $tile_attributes[] = 'data-featherlight="' . $use_featherlight_value . '"';
        } else {
            $tile_attributes[] = 'data-featherlight="iframe"';
        }
        $attrs[] = 'data-featherlight-persist="true"';
        $attrs[] = 'data-featherlight-variant="lightbox-white lightbox-fullscreen"';
        $attrs[] = 'data-featherlight-loading="Just a moment please"';
    }

    $tile_css_statements = get_post_meta(get_the_id(), 'tile_css', false);
    $tile_styles = array_merge($tile_styles, $tile_css_statements);

    if (has_post_thumbnail()) {
        $tile_styles[] = "background-image: url('" . get_the_post_thumbnail_url() . "')";
    }

    array_walk($tile_classnames, 'esc_html');
    array_walk($tile_attributes, 'esc_html');
    array_walk($tile_styles, 'esc_html');

    ?>
    <div class="<?php echo implode(' ', $tile_wrapper_classnames) ?>">
        <a class="tile <?= implode(' ', $tile_classnames) ?>"
           <?php if (strpos(implode(' ', $tile_classnames), 'no-link') === false) { ?>href="<?= $tile_link ?>"<?php } ?>
           style="<?= implode('; ', $tile_styles) ?>" <?= implode(' ', $tile_attributes) ?>>
            <div class="tile-caption">
                <h3><?php the_title() ?></h3>
                <?php the_excerpt() ?>
            </div>
        </a>
    </div>
    <?php

    if ($tiles_per_row > 0) {
        if (++$tiles_displayed_counter % $tiles_per_row === 0) {
            gh_row_close();
            gh_row_open('pad');
        }
    }

endwhile;

gh_row_close();