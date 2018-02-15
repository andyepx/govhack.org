(function($){
    $(function(){
        
        // Create tooltippers for each dfn tag
        tooltipify('dfn.tooltip-now');
        tooltipify('dfn.tooltip-defer');            // Was formerly deferred. but nowadays not really.
        
        // Sliders
        // sliderHomeHero();
        sliderHomeHeroReveal();
        sliderHomeTiles();
        
        //=================

        function tooltipify(className, opts){
            $('#gh-defined ' + className).each(function(){
                Tipped.create(this, null, $.extend({ position: 'top', skin: 'light', size: 'large' }, opts || {}));
            });
        }
        
        function sliderHomeHero(){
            // Init the slider
            var $slider = $('#home-hero-slider');
            var hasArrows = $slider.hasClass('with-arrows');
            $slider.slick({
                autoplay: true,
                autoplaySpeed: 4000,
                arrows: hasArrows,
                dots: true
            });
        }
        
        function sliderHomeHeroReveal(){
            // There is an initial slider screen, which contains a link
            // to slide over to the next screen.
            var $slider = $('#home-hero-slider-reveal');
            var $sliderRevealCta = $('#slider-reveal-cta');
            var hasArrows = $slider.hasClass('with-arrows');
            $slider.slick({
                autoplay: false,
                adaptiveHeight: true,
                arrows: hasArrows,
                dots: true
            });
            $sliderRevealCta.on('click', function(event){
                event.preventDefault();
                var slideIndex = $(this).data('slideIndex') || 1;
                $slider.slick('slickGoTo', slideIndex);
                return false;
            });
        }
        
        function sliderHomeTiles(){
            var $slider = $('#gh-homepage-slider');
            var $sliderNav = $('#gh-homepage-slider-nav');
            var $sliderNavLinks = $sliderNav.find('a, [role=button]');
            
            // Init the slider
            $slider.slick({
                arrows: false,
                infinite: false,
                adaptiveHeight: true
            });

            // It would have been loaded as display: none; show it now
            $slider.show();
            $sliderNavLinks.length > 0 && $sliderNavLinks.eq(0).addClass('active').attr('aria-pressed', true);

            // Bind the slider nav 
            $sliderNavLinks.each(function(index){
                (function(index){
                    $(this).on('click', function(){
                        $sliderNavLinks.removeClass('active').attr('aria-pressed', false);
                        $(this).addClass('active').attr('aria-pressed', true);
                        $slider.slick('slickGoTo', index);
                        return false;
                    });
                }.call(this, index));
            });

            // Update which navlink is active
            $slider.on('afterChange', function(event, slick, currentSlide){
                if ($sliderNavLinks.length > currentSlide){
                    $sliderNavLinks.removeClass('active').attr('aria-pressed', false);
                    $sliderNavLinks.eq(currentSlide).addClass('active').attr('aria-pressed', true);
                }
            });
        }
        
    });
    
}(jQuery));