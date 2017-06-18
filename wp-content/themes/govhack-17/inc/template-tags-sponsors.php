<?php

/**
 * Check if a region page has sponsors allocated to it
 *
 * @author AY <alan@govhack.org>
 * @return bool
 */
function gh_has_regional_sponsors()
{
    global $post;
    $sponsors_query_args = [
        'post_type' => 'sponsor',
        'meta_query' => [['key' => 'sponsor_region_id', 'value' => $post->ID, 'compare' => '=']]
    ];
    return count(get_posts($sponsors_query_args)) > 0;
    // return false;
}

/**
 * Get the WP page ID of the page which is parent to
 * the individual region pages
 * Todo: Put this into a region provider
 *
 * @author AY <alan@govhack.org>
 * @return string
 */
function gh_get_region_parent_page_id()
{
    // Determine the parent page ID for the regions pages
    // Easiest way, if this is manually selected in a Sponsors settings option
    $region_parent_page_id = get_option('region_parent_page_id');
    if (empty($region_parent_page_id)) {
        // Fallback - guess based on slug name
        $region_parent_page = get_posts([
            'name' => 'regions',
            'post_type' => 'page',
            'post_status' => 'publish',
            'posts_per_page' => 1
        ]);
        $region_parent_page_id = !empty($region_parent_page) ? reset($region_parent_page)->ID : 0;
    }
    return $region_parent_page_id;
}


/**
 * Render those sponsors.
 * Implement a lookahead to "intelligently" optimise layouts e.g. if neighbouring
 * categories have a single logo each, then inline them, etc.
 *
 * @author AY <alan@govhack.org>
 *
 * @param string Taxonomy name
 * @param string[] Ordered list of sponsorship types for that taxonomy
 * @param string Region page ID, presense of which indicates we're doing state sponsors
 */
function gh_render_sponsors($tax_type, $sponsorship_types, $region_page_id = 0)
{

    // Blast away types without results
    $sponsorship_types = array_filter($sponsorship_types, function ($type) use ($tax_type, $region_page_id) {

        // Note tax_query is a double-nested array
        // for future reference: [ 'include_children' => true|false ]

        $sponsors_query_args = [
            'post_type' => 'sponsor',
            'orderby' => 'date',
            'order' => 'ASC',
            'posts_per_page' => -1,
            'tax_query' => [['taxonomy' => $tax_type, 'field' => 'slug', 'terms' => $type->slug]],
        ];

        // If need be, filter out based on the region post ID
        if (!empty($region_page_id)) {
            $sponsors_query_args['meta_query'] = [
                ['key' => 'sponsor_region_id', 'value' => "$region_page_id", 'compare' => '=']
            ];
        }

        return count(get_posts($sponsors_query_args)) > 0;
    });

    $sponsorship_types = array_values($sponsorship_types);

    // Lookahead optimisation
    foreach ($sponsorship_types as $index => &$type) {

        // Note tax_query is a double-nested array
        // for future reference: [ 'include_children' => true|false ]

        $sponsors_query_args = [
            'post_type' => 'sponsor',
            'orderby' => 'date',
            'order' => 'ASC',
            'posts_per_page' => -1,
            'tax_query' => [['taxonomy' => $tax_type, 'field' => 'slug', 'terms' => $type->slug]],
        ];

        // If need be, filter out based on the region post ID
        if (!empty($region_page_id)) {
            $sponsors_query_args['meta_query'] = [
                ['key' => 'sponsor_region_id', 'value' => "$region_page_id", 'compare' => '=']
            ];
        }

        $sponsors_query = get_posts($sponsors_query_args);

        $type->with_prev = false;
        $type->with_next = false;
        $type->num_prev_cols = $type->num_next_cols = 0;
        $type->columns = property_exists($type, 'columns') ? intval($type->columns) : 3;      // a sensible default
        $type->aggregate_count = count($sponsors_query);

        if (count($sponsors_query) > 0 && count($sponsors_query) < $type->columns) {
            // Check if there was a predecessor that has > 0
            // and if together they are less than the col limit
            // then combine forces!!
            if ($index > 0 && count($sponsorship_types[$index - 1]->fetched) > 0) {
                $column_count_equal = $sponsorship_types[$index - 1]->columns === $type->columns;
                // if ( $sponsorship_types[$index-1]->with_next ){
                $column_total_fits = $sponsorship_types[$index - 1]->aggregate_count + count($sponsors_query) <= $type->columns;
                // }
                // else {
                //     $column_total_fits = count($sponsorship_types[$index-1]->fetched) + count($sponsors_query) <= $type->columns;                    
                // }
                if ($column_count_equal && $column_total_fits) {
                    $sponsorship_types[$index - 1]->with_next = true;
                    $sponsorship_types[$index - 1]->num_next_cols = count($sponsors_query);
                    $type->with_prev = true;
                    $type->num_prev_cols = count($sponsorship_types[$index - 1]->fetched);
                    $type->aggregate_count = $sponsorship_types[$index - 1]->aggregate_count + count($sponsors_query);
                }
            }
        }

        // Augment class object with query results; 
        // This is how it'll be accessed in round #2
        $type->fetched = $sponsors_query;

    }
    unset($type);

    foreach ($sponsorship_types as $type) {

        if (!$type->fetched) {
            echo '<!-- No sponsors were returned for "' . $type->slug . '" -->';
            continue;       // don't dwell
        }

        $sponsorship_class_name = 'Sponsors';
        $sponsorship_class_query = get_terms(['taxonomy' => $tax_type, 'slug' => $type->slug]);
        if (count($sponsorship_class_query)) {
            if (!empty($sponsorship_class_query[0]->description)) {
                $sponsorship_class_name = $sponsorship_class_query[0]->description;
            } else {
                $sponsorship_class_name = $sponsorship_class_query[0]->name;
            }
        }

        $tile_wrapper_classnames = ['gh-sponsor-logo'];
        if (property_exists($type, 'classNames')) {
            foreach (explode(' ', $type->classNames) as $typeName) {
                $tile_wrapper_classnames[] = $typeName;
            }
        }

        $is_standalone = !($type->with_prev || $type->with_next);
        if ($is_standalone) {
            // Doesn't share this row with anyone else
            gh_row_open();
            echo '<section class="gh-sponsorship-class">';
            $tile_wrapper_classnames[] = gh_col_class(1, $type->columns);
        } else {
            // Calculate how to space out the rows
            if ($type->with_next && !$type->with_prev) {
                gh_row_open();
            }
            $shared_row_classnames = ['gh-sponsorship-class', 'shared-row'];
            $shared_row_classnames[] = $type->with_prev ? 'with-prev' : '';
            $shared_row_classnames[] = $type->with_next ? 'with-next' : '';
            $shared_row_classnames[] = gh_col_class(count($type->fetched), $type->columns);
            echo '<section class="' . implode(' ', $shared_row_classnames) . '">';
            // $tile_wrapper_classnames[] = gh_col_class(1, count($type->fetched));     // invalid assumption that shelves.css is same as BS grid system. wrong.
            $tile_wrapper_classnames[] = gh_col_class(1, count($type->fetched));
        }

        $sponsorship_heading_classnames = ['gh-sponsorship-class-heading', "{$type->slug}-heading"];
        echo '<h5 class="' . implode(' ', $sponsorship_heading_classnames) . '">' . $sponsorship_class_name . '</h5>';

        // NOTE NOTE NOTE
        // This is where the chunking is done
        $sponsors_chunked = array_chunk($type->fetched, $type->columns);

        foreach ($sponsors_chunked as $sponsors_row) {
            gh_row_open();
            foreach ($sponsors_row as $sponsor) {
                $sponsor_link = get_post_meta($sponsor->ID, 'link_sponsor', true);
                echo '<div class="' . implode(' ', $tile_wrapper_classnames) . '">';
                if ($sponsor_link) echo '<a href="' . $sponsor_link . '" target="_blank">';
                echo get_the_post_thumbnail($sponsor->ID, 'large', ['class' => "sponsorship-logo {$type->slug}-logo"]);
                if ($sponsor_link) echo '</a>';
                echo '</div>';
            }
            gh_row_close();
        }

        if ($is_standalone) {
            echo '</section><!-- .gh-sponsorship-class -->';
            gh_row_close();
        } else {
            echo '</section><!-- .gh-sponsorship-class.shared-row -->';
            if ($type->with_prev && !$type->with_next) {
                gh_row_close();
            }
        }

        wp_reset_postdata();

    }
}

/**
 * Render sponsors as a carousel, using Slick
 *
 * @author AY <alan@govhack.org>
 *
 * @param string Taxonomy name
 * @param string[] Ordered list of sponsorship types for that taxonomy
 * @param string Region page ID, presense of which indicates we're doing state sponsors
 */
function gh_render_sponsors_carousel($tax_type, $sponsorship_types, $region_page_id = 0)
{

    // Todo

}

/**
 * Render locations on region page.
 *
 * @author AE <andy@govhack.org>
 *
 * @param string Region page ID
 */
function gh_render_locations_list($region_page_id = 0, $type = null)
{

    $r = strtoupper(get_the_title($region_page_id));
    $region = '';

    switch ($r) {
        case 'VICTORIA':
            $region = 'vic';
            break;
        case 'QUEENSLAND':
            $region = 'qld';
            break;
        case 'NEW SOUTH WALES':
            $region = 'nsw';
            break;
        case 'TASMANIA':
            $region = 'tas';
            break;
        case 'SOUTH AUSTRALIA':
            $region = 'sa';
            break;
        case 'WESTERN AUSTRALIA':
            $region = 'wa';
            break;
        case 'NORTHERN TERRITORIES':
            $region = 'nt';
            break;
        case 'NEW ZEALAND':
            $region = 'nz';
            break;
    }

    $locations_query = [
        'post_type' => 'locations',
        'posts_per_page' => -1,
        'meta_key' => 'region',
        'meta_value' => $region
    ];
    $locations = get_posts($locations_query);

    if ($type == 'buttons') {

        foreach ($locations as $x => &$loc) {
            echo "<a class='button gh-blue' href='/location/#'>$loc->post_title</a>";
        }

    } else if ($type == 'sidebar') {

        foreach ($locations as $x => &$loc) {
            echo '<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="/locations/' . $loc->post_name . '">' . $loc->post_title . '</a></li>';
        }

    } else {

        echo "<h3>Local events</h3>";

        foreach ($locations as $x => &$loc) {

            echo "<h5>" . $loc->post_title . "</h5>";
            echo "<p>" . $loc->post_content . "</p>";

        }

    }

}