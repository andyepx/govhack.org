<?php 

?><div class="hero home-hero-rca" role="banner">
    <div class="wrapper">
        <?php gh_row_open() ?>
        <?php gh_col_open(1, 2) ?>
            <div class="banner-alt-inner">
                <h1><span>GovHack</span> 2016 Red Carpet Awards</h1>
                <p>The Red Carpet Awards recognise innovative developers and digital creatives and their transformation of government open data into amazingly useful apps, visualisations and websites at GovHack 2016 - Australia and New Zealand’s premier hackathon.</p> 
            </div>
        </div><!-- gh_col_close -->
        <?php gh_col_open(1, 2) ?>
            <div class="date-panel">
                <header>
                    Adelaide
                </header>
                <div>                    
                    <span class="line">Saturday</span>
                    <span class="line">22 October</span>                    
                </div>
            </div>
        </div><!-- gh_col_close -->
        <?php gh_row_close() ?>
        <?php if ( $gh_facebook_url = get_theme_mod('gh_social_facebook', false) ): ?>
        <div class="tune-in-livestream">
            <a href="<?php echo esc_url( $gh_facebook_url ) ?>">
            
            <span class="dashicons dashicons-format-video"></span> 
            <span class="dashicons dashicons-facebook"></span>
            Click to tune in for the the live stream on the awards night 
            <br>
            <small>6:30pm SA &middot; 7:00pm NSW, VIC, ACT, TAS &middot; 6:00pm QLD &middot; 4:00pm WA</small>
            </a>
        </div>
        <?php endif; // gh_social_facebook ?>
    </div>
</div>