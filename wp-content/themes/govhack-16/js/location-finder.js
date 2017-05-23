(function($){
    'use strict';
        
    /**
     * Ye olde GovHack location finder
     * Since 2016
     * @author AY
     */
    $(function(){

        //==================================
        // Leggo:
        //==================================
        
        var theMap = renderMap();   // Map is special. Map is used a lot. // Returns an instance of locationFinder
        var defaultFilters = window.location.hash.substring(1).split(/[,;:]/).map(function(value){
            return value.toLowerCase();
        });      // The hash provided may be the name of a state or csv with multiple states
        
        renderFilterButtons(theMap, {
            target: $('#location-finder-nav .location-area-button-wrapper'), 
            cloneSource: $('#location-toggle-button-clone-source'),
            cardWrapper: $('.content-area'),
            defaultFilters: defaultFilters
        });
        
        bindMapActions(theMap);
        
    });
    
    //=========================== 

    function renderFilterButtons(locationFinder, opts){
        
        opts = opts || {};
        var $targetContainer = opts.target; 
        var $cloneSource = opts.cloneSource;
        var $cardWrapper = opts.cardWrapper;
        var defaultFilters = opts.defaultFilters || [];
        var locationCardSelector = opts.locationCardSelector || opts.locationCard || '.location-card';
        var regionContainerSelector = opts.regionContainerSelector || opts.regionContainer || '.location-region';
        var regionHeadingSelector = opts.regionHeadingSelector || opts.regionHeading || '.location-region-name';
        var buttonLabelSelector = opts.buttonLabelSelector || opts.buttonLabel || '.button-label';
        var buttonLabelSecondarySelector = opts.buttonLabelSecondarySelector || opts.buttonLabelSecondary || '.button-label-secondary';

        if ( ! locationFinder){
            console.warn('%clocationFinder object must be first argument', 'color: fuchsia');
            return;
        }
        
        // Read from API what all the different regions are
        var regions = locationFinder.regions;
        
        // now do some buttongen
        for (var regionName in regions){
            if (regions.hasOwnProperty(regionName)){
                (function(regionName){
                    var regionCount = regions[regionName];
                    // regions.forEach(function(regionCount, regionName){
                    var $ourButton = $cloneSource.clone();
                    $ourButton.removeAttr('id').addClass('location-area').addClass('location-unchecked').data('checked', false);
                    $ourButton.find(buttonLabelSelector).text(regionName);
                    // $ourButton.find(buttonLabelSecondarySelector).text(regionCount);
                    $ourButton.click(buttonClick.bind($ourButton, regionName));
                    $targetContainer.append($ourButton);
                    $ourButton.show();
                    if (defaultFilters){
                        defaultFilters.forEach(function(filterName){
                            if (filterName === regionName){
                                buttonClick.call($ourButton, regionName);                                
                            }
                        });
                    }
                }(regionName));
            }
        }
        
        function anyLocationsChecked(){
            return !!$('.location-area:not(.location-unchecked)').length;
        }
        function oneLocationChecked(){
            return $('.location-area:not(.location-unchecked)').length === 1;
        }
        function buttonClick(regionName){
            // Check the data flag, see how this button's going
            var currentCheckState = $(this).data('checked');
            $(this).data('checked', !currentCheckState).toggleClass('location-unchecked');
            
            // Update the checkbox icon
            var checkboxIcon = $(this).find('.fa');
            checkboxIcon.removeClass().addClass('fa').addClass(function(){
                return currentCheckState ? 'fa-square' : 'fa-check-square';      // if currently checked, then invert and display empty, and vice versa
            });
            
            // Invisibilize the needful
            // $cardWrapper.find(locationCardSelector + '[data-region=' + regionName + ']').toggleClass('filter-hidden');
            $cardWrapper.find(regionContainerSelector + '[data-region=' + regionName + ']').toggleClass('filter-hidden', currentCheckState);
            $cardWrapper.find(regionContainerSelector).toggleClass('filter-default-display', !anyLocationsChecked());
            
            // Muck around with the map's icon layer
            if (anyLocationsChecked()){
                if (currentCheckState){     // then hide it
                    locationFinder.clearMarkers([regionName.toLowerCase()]);
                }
                else {
                    if (oneLocationChecked()){
                        locationFinder.clearMarkers();
                    }
                    locationFinder.addMarkers([regionName.toLowerCase()]);                                
                }
            }
            else {
                locationFinder.clearMarkers();
                locationFinder.addAllMarkers();
            }
        }

    }
    
    
    function renderMap(){
        if ( ! GH.locationFinder){
            console.warn('%cNo GH.locationFinder definition object was found', 'color: fuchsia');
            return;
        }
        
        if ( ! L){
            console.warn('%cNo Leaflet map object was found... http://leafletjs.com/examples/quick-start.html', 'color: dodgerblue');
            return;
        }
        
        var map = L.map( ( GH.locationFinder.bootstrapElementId || 'gh-location-map' ), {
            attributeControl: false,        // to be added later
            minZoom: 4, 
            maxBounds: new L.LatLngBounds([-1,100], [-60, 190]) }
        ).setView([-34.88, 147.129], 3);
        
        var markers = GH.locationFinder.registerMarkers(L);
        
        var markerUtility = Object.create(GH.locationFinder, {
            /**
             * Adds markers to the map, assuming the `markers` object is a hash that's based on
             * the names of the regions and each hash value is an array of event marker objects
             */
            clearMarkers: {
                value: changeMarkers.bind(this, -1)
            },
            addAllMarkers: {
                value: changeMarkers.bind(this, 1)
            },
            addMarkers: {
                value: function(filterOnly){
                    changeMarkers(1, filterOnly);
                }
            },
            removeMarkers: {
                value: function(filterOnly){
                    changeMarkers(-1, filterOnly);
                }
            }
        });

        L.tileLayer('https://{s}.tiles.mapbox.com/v3/{id}/{z}/{x}/{y}.png', {
            maxZoom: 18,
            id: 'maxious.gdb67hbh'
        }).addTo(map);

        markerUtility.clearMarkers();
        markerUtility.addAllMarkers();
        
        //export script
        var geojson_data = {
          "type": "FeatureCollection",
          "features": []
        };
        for (var layerId in map._layers) { 
            if (typeof layerId === 'string' && typeof map._layers[layerId]._popup === 'object') { 
                // console.log('"'+map._layers[layerId]._popup._content + '",' + map._layers[layerId]._latlng.lat + ',' + map._layers[layerId]._latlng.lng);  
                geojson_data['features'].push({
                      "type": "Feature",
                      "properties": {
                          'name': map._layers[layerId]._popup._content,         
                          "marker-color": (map._layers[layerId]._icon.src.indexOf("red") > -1 ? "#ff0000" : "#0000ff"),        
                          "marker-size": "medium",
                          "marker-symbol": "building"
                          },
                        "geometry": {
                        "type": "Point",
                        "coordinates": [
                            map._layers[layerId]._latlng.lng,
                            map._layers[layerId]._latlng.lat
                        ]
                      }
                });
            }
        }
        // console.log("http://geojson.io/#data=data:application/json," + encodeURIComponent(JSON.stringify(geojson_data)));
        var topRightAttrib = L.control.attribution({
            position: 'topright'
        }).addAttribution('Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery &copy; <a href="http://mapbox.com">Mapbox</a>'
        ).setPrefix('<a href="http://geojson.io/#data=data:application/json,' + encodeURIComponent(JSON.stringify(geojson_data)) + '">Export Map</a>');
        map.addControl(topRightAttrib);
        
        return markerUtility;
        
        //=========================
        
        function changeMarkers(addRemove, filterOnly){
    
            addRemove = addRemove || 0;
    
            if ( ! markers) throw 'Must provide argument or global `markers` to LocationFinder.clearMarkers()';
            if ( ! map) throw 'Must provide argument or global `map` to LocationFinder.addMarkers()';
            filterOnly = filterOnly instanceof Array ? filterOnly : [];      // must be array
            
            // The pyramid of doom
            for (var region in markers){
                if (filterOnly.length == 0 || ~filterOnly.indexOf(region.toLowerCase())){
                    if (markers.hasOwnProperty(region)){
                        if (markers[region] instanceof Array){
                            markers[region].forEach(function(marker){
                                if (addRemove > 0){
                                    marker.addTo(map);                                            
                                }
                                if (addRemove < 0){
                                    map.removeLayer(marker);
                                }
                            });
                        }
                    }
                }
            }
            
        }
    }
    
    function bindMapActions(map){
        
        // Add gh-map-visible if our viewport appears to be larger than mobile
        $('body').toggleClass('gh-map-visible', $(window).width() > 600);
        
        // CSS should have transition that pivots based on this class
        $('#map-toggle').click(function(){
            $('body').toggleClass('gh-map-visible');
            return false;
        });
        
    }
    
}(jQuery));