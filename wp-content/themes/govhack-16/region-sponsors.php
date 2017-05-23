<?php

$regional_sponsorship_classes = function_exists('gh_get_sponsorship_level') ? gh_get_sponsorship_level('state')->classes : [];
$regional_sponsorship_tax_type = 'state_sponsor_type';
$region_page_id = get_the_ID();

gh_render_sponsors( $regional_sponsorship_tax_type, $regional_sponsorship_classes, $region_page_id );
