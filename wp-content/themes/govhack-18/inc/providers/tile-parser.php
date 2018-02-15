<?php
// Grab it from a json file
// DEPREC this was discontinued after tiles were built into a custom post type/taxonomy

$f = __DIR__ . '/../../tiles.json';
if (file_exists($f)){
    
    // Tile definitions, retrieved by parsing a json object
    // First-level array items represent rows
    // Second-level represent tiles
    $configFileContents = file_get_contents($f);
    $config = json_decode($configFileContents);
    
    foreach ($config as &$row){
        
        // Figure out the tile classname
        $numTiles = count($row->tiles);
        if ($numTiles == 2){
            $row->tileClassNames = "one-half column";
        }
        elseif ($numTiles == 3){
            $row->tileClassNames = "one-third column";
        }
        elseif ($numTiles == 4){
            $row->tileClassNames = "three columns";
        }
         
        foreach ($row->tiles as &$tile){

            $tile->classNames = '';
            if ($tile->colour){
                $tile->classNames .= $tile->colour;
            }
            elseif ($tile->color){
                $tile->classNames .= $tile->color;
            }

            $args = [];
            // Note: Assume that the referenced ID is always a page ID NOT a post ID
            if (isset($tile->pageId)){
                $args['page_id'] = $tile->pageId;
            }
            elseif (isset($tile->pageName)){
                $args['pagename'] = $tile->pageName;
            }
            $query = new WP_Query($args);
            if($query->have_posts()) {
                $query->the_post(); 
                $tile->href = get_the_permalink();
            }

        }
        unset($tile);
    }
    unset($row);
    
    // error_log(print_r($config, true));
    
    return $config;
 
}
return [];
