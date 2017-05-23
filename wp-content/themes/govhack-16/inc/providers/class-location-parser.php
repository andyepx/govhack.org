<?php
/**
 * GovHack '16 location parser
 * A decoupled provider which spits out locations.
 * It can be configured for either JSON or 
 */
 
class GH_Location_Parser {
    
    public static $display_columns = 3;
    public static $region_name_map = [
        'nsw' => 'New South Wales',
        'qld' => 'Queensland',
        'vic' => 'Victoria',
        'tas' => 'Tasmania',
        'nt' => 'Northern Territory',
        'act' => 'Australian Capital Territory',
        'sa' => 'South Australia',
        'wa' => 'Western Australia',
        'nz' => 'New Zealand',
    ];
    
    private $provider_method;       // a simple 2-letter value
    private $is_parsed = false;
    private $jabberwocky_url;
    private $jabberwocky_api_endpoint; 
    
    private $locations_column_cache;
    private $location_cards;
    private $region_cache;
    
    public $total_count = 0;
    public $node_count = 0;
    public $official_count = 0;
    
    public static function decode_region_name( $name ){
        // Lookup the proper state name
        if ( array_key_exists($name, self::$region_name_map)){
            return self::$region_name_map[$name];
        }
        return $name;
    }
    
    public function __construct(){
        $this->jabberwocky_url = defined('GH_PORTAL_URL') ? GH_PORTAL_URL : 'http://portal.govhack.org';        // a sensible default
        $this->jabberwocky_api_endpoint = '/feed/locations/all.json';
        
        $jabberwocky_defined = defined('GH_LOCATION_PROVIDER_METHOD') && in_array(GH_LOCATION_PROVIDER_METHOD, [ 'portal', 'data', 'jabberwocky', 'jb' ]);
        $this->provider_method = $jabberwocky_defined ? 'jb' : 'wp';
        
        $this->location_cards = [];    
        $this->locations_column_cache = [];
        $this->region_cache = [];
        
        //
        // Todo: Make the display_columns value dynamically set via WP admin options page
        //
    }
    
    
    /** From this wordpress instance **/
    public function is_sourced_locally(){
        return $this->provider_method == 'wp';
    }
    
    /** From le Jabberwocky **/
    public function is_sourced_remotely(){
        return $this->provider_method == 'jb';
    }
    
    
    /**
     * Wrapper method for source-specific parsing techniques
     * Must always be called, before accessing data
     */
    public function parse_locations(){
        
        if ( $this->is_sourced_locally() ){
            return $this->parse_locations_locally();
        }
        if ( $this->is_sourced_remotely() ){
            return $this->parse_locations_remotely();
        }
        
    }
    
    /**
     * Gets locations from the WordPress cpt / taxonomy
     * @return Boolean status of parsing result
     */
    private function parse_locations_locally(){
        
        try {
            for ($i = 0; $i < self::$display_columns; $i++ ){
                $this->locations_column_cache[] = [];      // dimension a new array
            }
            
            $locations_query_args = [ 'post_type' => 'locations', 'posts_per_page' => 999, 'post_status' => ['publish'], 'orderby' => 'date', 'order' => 'DESC' ];
            $locations_query = get_posts($locations_query_args);
            
            // Parse it asap, then call wp_reset_postdata()
            foreach ($locations_query as $location){
                $loc = [];
                $loc['obj'] = $location;
                
                $id = $loc['id'] = $location->ID;
                $loc['title'] = !empty($location->post_title) ? $location->post_title : '<Location>';
                
                $location_types = wp_get_post_terms($id, 'location_type');       // is array
                $loc['type'] = $location_types[0]->name;
                $loc['type_clean'] = addslashes(strtolower($location_types[0]->name));
                $loc['slug'] = addslashes(strtolower($location_types[0]->slug));
                
                // Prefer map_latlong
                if ($lat_long = get_post_meta( $id , 'map_latlong', true)){
                    $lat_long_parts = explode(',', $lat_long);
                    $loc['lat'] = trim($lat_long_parts[0]);
                    $loc['long'] = trim($lat_long_parts[1]);
                }
                else {
                    // Look for individiual map_lat and map_long
                    $loc['lat'] = get_post_meta( $id , 'map_lat', true);
                    $loc['long'] = get_post_meta( $id , 'map_long', true);
                }
                
                $this->total_count++;
                if (strpos($loc['slug'], 'official') !== false) $this->official_count++;
                elseif (strpos($loc['slug'], 'node') !== false) $this->node_count++;
                
                $cache_slot = (++$cache_slot > 2) ? 0 : $cache_slot;
                $this->locations_column_cache[$cache_slot][] = $loc;
                $this->location_cards[] = $loc;
            }
            
            // shuffle($this->locations_column_cache);
            wp_reset_postdata();
            
            $this->is_parsed = true;
            return true;
        }
        catch (Exception $ex){
            error_log($ex);
            return false;
        }
        
    }
    
    private function parse_locations_remotely(){
        
        try {
            $endpoint  = $this->jabberwocky_url . $this->jabberwocky_api_endpoint;
            $raw = file_get_contents($endpoint);
            $locations = json_decode($raw);
            for ($i = 0; $i < self::$display_columns; $i++ ){
                $this->locations_column_cache[] = [];      // dimension a new array
            }
            // First pass - data formatting
            foreach ($locations as &$loc){
                //
                // Todo: extract this weighting formula and put it elsewhere
                // We should probably bake this into the API
                //
                
                $cap_cities = ['sydney', 'canberra', 'adelaide', 'melbourne'];
                if (strpos(strtolower($loc->type), 'official') !== false){
                    // $loc->weighting = 400;
                    $loc->type_clean = 'official';
                    foreach ($cap_cities as $city){
                        if (strpos(strtolower($loc->name), $city) !== false){
                            // $loc->weighting = 200;
                            break;
                        }
                    }
                }
                elseif (strpos(strtolower($loc->jurisdiction), 'nz') !== false){
                    // $loc->weighting = 600;
                    $loc->type_clean = 'official';
                }
                elseif (strpos(strtolower($loc->type), 'node') !== false){
                    // $loc->weighting = 1000;
                    $loc->type_clean = 'node';
                    foreach ($cap_cities as $city){
                        if (strpos(strtolower($loc->name), $city) !== false){
                            // $loc->weighting = 800;
                            break;
                        }
                    }
                }
                
                // Override the weight with the API-defined weight
                if (!empty($loc->display_weight)){
                    $loc->weighting = $loc->display_weight;
                }
                elseif (!empty($loc->display_rank)){
                    $loc->weighting = $loc->display_rank;
                }
                
                // Side effect, check the region
                if ( ! array_key_exists($loc->jurisdiction, $this->region_cache)){
                    $this->region_cache[$loc->jurisdiction] = 1;
                }
                else {
                    $this->region_cache[$loc->jurisdiction]++;
                }
                
                // Alias, play nice
                $loc->region = $loc->jurisdiction;
                
                // Clean all string properties, trust nobody.
                foreach ( $loc as &$loc_field ){
                    if ( is_string($loc_field) ){
                        $loc_field = sanitize_text_field($loc_field);
                    }
                    elseif ( is_array($loc_field) || is_object($loc_field) ){
                        foreach ( $loc_field as &$loc_field_2 ){
                            if ( is_string($loc_field_2) ){
                                $loc_field_2 = sanitize_text_field($loc_field_2);                                
                            }
                        }    
                        unset($loc_field_2);
                    } 
                }
                unset($loc_field);
                
            }
            unset($loc);
            
            // Pass one-and-a-half: Sort
            usort($locations, [$this, 'location_weighting_comparator']);
            
            // Second pass - columnization
            $cache_slot = 0;
            foreach ($locations as $loc){
                
                $this->total_count++;
                if (strpos(strtolower($loc->type), 'official') !== false) $this->official_count++;
                elseif (strpos(strtolower($loc->type), 'node') !== false) $this->node_count++;
                
                $cache_slot = (++$cache_slot > 2) ? 0 : $cache_slot;
                $this->locations_column_cache[$cache_slot][] = $loc;
                $this->location_cards[] = $loc;
            }
            
            $this->is_parsed = true;
            return true;
            
        }
        catch (Exception $ex){
            error_log($ex);
            return false;
        }
        
    }
    
    
    //==================
    // Location getters 
    //==================
    
    public function get_locations_locally( $format ){
        if ( ! $this->is_sourced_locally() ){
            return new WP_Error('Location provider error', __('Attempted to retrieve locations locally but is configured otherwise. Check GH_LOCATION_PROVIDER_METHOD.', 'sequential'));      // fail silently
        }
        if ( ! $this->is_parsed ){
            return new WP_Error('Location provider error', __('Location information not retrieved yet. Call GH_Location_Parser::parse_locations() first.', 'sequential'));      // fail silently
        }
        return $this->get_locations( $format );
    }
    
    public function get_locations_remotely( $format ){
        if ( ! $this->is_sourced_remotely() ){
            return new WP_Error('Location provider error', __('Attempted to retrieve locations remotely but is configured otherwise. Check GH_LOCATION_PROVIDER_METHOD.', 'sequential'));      // fail silently
        }
        if ( ! $this->is_parsed ){
            return new WP_Error('Location provider error', __('Location information not retrieved yet. Call GH_Location_Parser::parse_locations() first.', 'sequential'));      // fail silently
        }
        return $this->get_locations( $format );
    }
    
    public function get_region_counts( $region_slugs = [] ){
        if ( ! $this->is_parsed ){
            return new WP_Error('Location provider error', __('Location information not retrieved yet. Call GH_Location_Parser::parse_locations() first.', 'sequential'));      // fail silently
        }
        // Use locations_by_region and pick from that
        $locs_by_region = $this->locations_by_region();
        $counts_by_region = [];
        foreach ($locs_by_region as $region => $locs){
            if ( in_array($region, $region_slugs) ){
                $counts_by_region[$region] = count($locs);
            }
        }
        return $counts_by_region;
    }
    
    public function get_regions(){
        if ( ! $this->is_parsed ){
            return new WP_Error('Location provider error', __('Location information not retrieved yet. Call GH_Location_Parser::parse_locations() first.', 'sequential'));      // fail silently
        }
        return $this->region_cache;
    }

    private function get_locations( $format ){
        if ( in_array($format, ['card', 'cards']) ){
            return $this->location_cards;
        }
        if ( in_array($format, ['column', 'columns']) ){
            return $this->locations_column_cache;
        }
        if ( in_array($format, ['region', 'regions', 'jurisdiction', 'jurisdictions']) ){
            return $this->locations_by_region();
        }
    }
    
    private function locations_by_region( $keyRemapType = null ){
        $regioned = [];
        foreach ($this->location_cards as $card){
            $regionedKey = $card->region;
            if ( 'remap' == $keyRemapType ){
                // Lookup the proper state name
                $regionedKey = self::decode_region_name( $card->region );
            }
            // Facilitate a smooth add
            if ( ! array_key_exists($regionedKey, $regioned) ){
                $regioned[$regionedKey] = [];
            }
            $regioned[$regionedKey][] = $card;
        }
        return $regioned;
    }

    // The spaceship operator can't come soon enough for simple crap like this
    private function location_weighting_comparator($a, $b){
        if (empty($a->weighting) || $a->region == 'nz'){
            return 1;      // B always comes earlier
        }
        if (empty($b->weighting) || $b->region == 'nz'){
            return -1;       // A always comes earlier
        }
        if ($a->weighting == $b->weighting){
            return 0;
        }
        // Inverse weighting; lower number comes earlier
        return ($a->weighting > $b->weighting) ? 1 : -1;
    }
    
}