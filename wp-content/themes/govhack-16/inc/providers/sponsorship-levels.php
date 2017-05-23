<?php
/*===============================
 * Utility function for getting JSON-defined 
 * sponsorship classes
 *===============================*/

function gh_get_sponsorship_level( $level = 'national', $source = 'get_option' ){
    
    if ( $source == 'file' ){
        // Optionally get from file
        $config = gh_get_sponsorship_level_file();
    }
    else {
        // Default get from get_option
        $config = json_decode(get_option( 'gh_sponsor_renderopts' ));
    }
    
    if ( isset($config) && property_exists( $config, $level ) ){
        return $config->$level;
    }        
    return new StdClass();
    
}

function gh_get_sponsorship_level_file($file = 'sponsorship-classes.json'){
    // Assume folder structure is <theme>/inc/post-types/sponsor-post-type.php
    try {
        $f = __DIR__ . '/../../sponsorship-classes.json';
        if (file_exists($f)){
            $configFileContents = file_get_contents($f);
            $config = json_decode($configFileContents);
            return $config;
        }
        return null;
    }
    catch (Exception $e){
        error_log($e->getMessage());
        return null;
    }
}