<?php

$national_sponsorship_classes = function_exists('gh_get_sponsorship_level') ? gh_get_sponsorship_level( 'national' )->classes : [];
$national_sponsorship_tax_type = 'national_sponsor_type';

gh_render_sponsors( $national_sponsorship_tax_type, $national_sponsorship_classes );
