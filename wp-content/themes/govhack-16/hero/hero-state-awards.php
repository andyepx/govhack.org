<?php 
    
$states = json_decode(get_option( 'gh_homepage_stateawards_config', '{}' ));

?><div class="hero home-hero-state-awards" role="banner">
    <div class="wrapper">
        <?php gh_row_open() ?>
        <?php gh_col_open(1, 3) ?>
            <div class="banner-alt-inner">
                <h1><span>GovHack</span> State Awards</h1>
                <p>Winners of state and local prizes have been invited to attend the State Awards ceremonies.</p>
            </div>
        </div>
        <?php gh_col_open(2, 3) ?>
            <div class="state-awards-container clear">
            <?php foreach ( $states as $state ): ?>
                <div class="date-panel">
                    <header>
                        <?php echo $state->state ?>
                    </header>
                    <div>
                        <span class="day"><?php echo $state->day ?></span>
                        <span class="month"><?php echo $state->month ?></span>
                    </div>
                    <footer>
                    <?php if ( !empty($state->link) ): ?>
                        <a href="<?php echo $state->link ?>"<?php echo !empty($state->blank) ? ' target="_blank"' : '' ?>><?php echo $state->desc ?></a>
                    <?php else: ?>
                        <?php echo $state->desc ?>
                    <?php endif; ?>
                    </footer>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
        <?php gh_row_close() ?>
    </div>
</div>