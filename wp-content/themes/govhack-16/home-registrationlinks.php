<?php
/**
 * Registration links quick overview
 * 
 * Grabs the location from Jabberwocky provider, and
 * dumps out a list of the states. We will manually
 * separate NZ during presentation.
 */
  
$nz_id = 'nz';

$loc_parser = gh_get_location_parser();
if ( $loc_parser->is_sourced_remotely() && $loc_parser->parse_locations() ){
    $locations_by_region = $loc_parser->get_locations_remotely( 'regions' );
    
    // Find NZ and chuck it at the end
    $nz_index = array_search( $nz_id, array_keys($locations_by_region) );
    if ( $nz_index !== false ){
        $nz_out = array_splice( $locations_by_region, $nz_index, 1 );
        if ( count($nz_out[$nz_id]) > 0 ){
            $nz_out[$nz_id][0]->eventbrite = 'http://govhack.org.nz/register-2016/';       // mad hardcode            
        }
        $locations_by_region = $locations_by_region + $nz_out;
    }
    
    // Map out a new array
    $location_quicklinks = array_map( function ($value, $key){
        if ( is_array($value) && count($value) > 0 && isset($value[0]->eventbrite) ){
            return ( object )[ 'region' => $key, 'link' => $value[0]->eventbrite ];
        }
    }, $locations_by_region, array_keys($locations_by_region) );
}

$things_to_say = [
    'Beat the queue!',
    'Hot off the press!',
    'Lock it in!',
    'Jump on board!'
];
$chosen_thing_to_say = $things_to_say[ floor( intval(date('H')) / (24 / count($things_to_say)) ) ];
  
if ( isset( $location_quicklinks ) ): ?>
<h3 class="gh-registration-quicklinks-title">
    <span class="fa fa-ticket" aria-hidden="true"></span> 
    <?= $chosen_thing_to_say ?> <small>Get tickets and more for your local GovHack event:</small>
</h3>
<div class="gh-registration-quicklinks">
<?php foreach ( $location_quicklinks as $tuple ): if ( !empty($tuple) ): ?>

    <?php if ( $tuple->region == $nz_id ): ?>
    <span class="divider-on-left"></span>
    <?php endif; ?>
    
    <a class="quicklink" href="<?php echo $tuple->link ?>">
        <?php echo strtoupper($tuple->region) ?>
        <span class="fa fa-caret-square-o-down quicklink-icon" aria-hidden="true"></span>
    </a>
    
<?php endif; endforeach; ?>
</div>
<?php endif;