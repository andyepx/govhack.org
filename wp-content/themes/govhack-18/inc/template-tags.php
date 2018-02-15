<?php

//==================================================
// Template tags: good for sticking into templates
//==================================================

function gh_row_open($padded = '', $classNames = [])
{
    // $tag = 'pad' !== $padded ? '<div class="grid %s">' : '<div class="grid grid-pad %s">';
    // echo sprintf( $tag, implode(' ', $classNames));
    echo '<div class="row row-fluid">';
}

function gh_row_close()
{
    // echo '</div><!-- .grid -->';
    echo '</div><!-- .row -->';
}

function gh_col_class($width, $out_of)
{
    if (floatval($out_of) > 0) {
        $col_width = intval(floatval($width) / floatval($out_of) * 12);
        return "column-$col_width";
    } else {
        return "";
    }
}

function gh_col_open($width, $out_of, $classNames = [])
{
    echo sprintf('<div class="%s %s">', gh_col_class($width, $out_of), implode(' ', $classNames));
}

// Minimalistic display mode
function gh_is_minimalistic()
{
    $minimalistic_display_modes = ['content-only', 'content_only', 'no-header-footer', 'no_header_footer', 'bare', 'naked'];
    return in_array(get_query_var('display_mode'), $minimalistic_display_modes);
}


// Little logo tags
function gh_get_inline_logo()
{
    return '<span="govhack-inline-logo"></span>';
}

function gh_inline_logo()
{
    echo gh_get_inline_logo();
}


// Header config toggles 
function header_search()
{
    return apply_filters('header_search', true);
}

function header_tiles()
{
    return apply_filters('header_tiles', true);
}


// AddEvent bootstrapper
function addevent_button()
{ ?>
    <a title="Add to Calendar" class="addeventatc button-minimal">
        <span class="fa fa-calendar" aria-hidden="true"></span> Add to your Calendar
        <span class="start">29/07/2016 07:00 PM</span>
        <span class="end">31/07/2016 05:00 PM</span>
        <span class="timezone">Australia/Sydney</span>
        <span class="title">GovHack 2016</span>
        <span class="description">The almighty open data hackathon! Find out more at https://www.govhack.org</span>
        <span class="location">Check our event map https://www.govhack.org/locations</span>
        <span class="date_format">DD/MM/YYYY</span>
    </a>
    <script type="text/javascript">
        window.addeventasync = function () {
            addeventatc.settings({
                license: "00000000000000000000",
                css: false
            });
        };
    </script>
    <?php
}

/**
 * Home page "X sleeps left"
 * Checks if 15 sleeps or less, and if so, render something.
 *
 * @return string|null
 */
function gh_sleeps_left()
{
    if (defined('GH_EVENT_START_DATE') && strtotime(GH_EVENT_START_DATE)) {
        $sec_add_offset = 6 * 60 * 60;       // compare to 6am of target date rather than 12am
        $sec_diff = strtotime(GH_EVENT_START_DATE) - time() + $sec_add_offset;
        if ($sec_diff <= (15 * 24 * 60 * 60)) {
            $day_diff = ceil($sec_diff / (24 * 60 * 60));
            if ($day_diff > -3) {
                return $day_diff;
            }
        }
    }
}


/**
 * Home page "X sleeps left" for js
 * Checks if 15 sleeps or less, and if so, render a <script>
 *
 * @param string selector name in which to output the value
 * @return string|null
 */
function gh_sleeps_left_js($element_selector, $prior_template_string = null, $during_template_string = null)
{
    if (empty($prior_template_string)) {
        $prior_template_string = '{{time}} to go until GovHack begins';
    }
    if (empty($during_template_string)) {
        $during_template_string = 'GovHack is now LIVE across Australia and New Zealand';
    }
    if (defined('GH_EVENT_START_DATE') && strtotime(GH_EVENT_START_DATE)): ?>
        <script>
            document.querySelector('<?= $element_selector ?>').innerHTML = (function () {
                var secDiff = <?= strtotime(GH_EVENT_START_DATE) ?> -((+new Date) / 1000);
                var secOffset6am = -8 * 60 * 60;        // 6am AEST = 8pm GMT previous day
                var secOffset7pm = 9 * 60 * 60;        // 7pm AEST = 9am GMT same day
                if ((secDiff + secOffset7pm) <= 0) {
                    return '<?= $during_template_string ?>';
                }
                if ((secDiff + secOffset6am) <= (15 * 24 * 60 * 60)) {
                    var dayDiff = Math.ceil((secDiff + secOffset6am) / (24 * 60 * 60));
                    var hourDiff = Math.round((secDiff + secOffset7pm) / (60 * 60));
                    var timeString = dayDiff > 1 ? (dayDiff + (dayDiff === 1 ? ' sleep' : ' sleeps')) : (hourDiff + (hourDiff === 1 ? ' hour' : ' hours'));
                    return '<?= $prior_template_string ?>'.replace('{{time}}', timeString);
                }
            }());
        </script>
        <?php
    endif;
}

