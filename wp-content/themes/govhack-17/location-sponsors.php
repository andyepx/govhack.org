<?php

$local_sponsorship_classes = function_exists('gh_get_sponsorship_level') ? gh_get_sponsorship_level('local')->classes : [];
$local_sponsorship_tax_type = 'local_sponsor_type';
$region_location_id = get_the_ID();

gh_render_sponsors( $local_sponsorship_tax_type, $local_sponsorship_classes, 0, $region_location_id );
