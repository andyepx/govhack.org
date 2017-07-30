<?php

date_default_timezone_set('Australia/Sydney');

if (time() > strtotime('2017-07-30 21:00')) { // AFTER
    ?>

    <div class="hero home-hero-pre2017 after" role="banner">
        <div id="home-hero-slider-reveal" class="home-hero-slider">
            <div>
                <div class="wrapper">
                    <?php gh_row_open() ?>
                    <div class="<?= gh_col_class(4, 6) ?>">
                        <div class="hero-inner">
                            <h1 class="">
                                That's a wrap
                            </h1>
                            <p>
                                Congratulations to all participants at GovHack 2017, and huge thanks to all the hosts,
                                mentors, coaches and volunteers who made it so great!
                                <br>
                                Watch for details of our state and territory awards night... Coming soon.
                            </p>
                        </div><!-- gh_col_close -->
                    </div>
                    <?php gh_row_close() ?>
                </div>
            </div>
        </div>
    </div>
    <?php
} else if (time() > strtotime('2017-07-28 19:00')) { //DURING
    ?>

    <div class="hero home-hero-pre2017 during" role="banner">
        <div id="home-hero-slider-reveal" class="home-hero-slider">
            <div>
                <div class="wrapper">
                    <?php gh_row_open() ?>
                    <div class="<?= gh_col_class(4, 6) ?>">
                        <div class="hero-inner">
                            <h1 class="">
                                And... we have liftoff!
                            </h1>
                            <p>
                                GovHack 2017 is on this weekend. As in, <b>right now</b>!
                                <br>
                                Remember all entries must be complete by 5pm, Sunday 30 July.
                            </p>
                            <a class="button gh-pink fix-width" style="color: white;" href="/handbook">
                                Read the handbook
                            </a>
                            <br>
                            <br>
                            <a class="button gh-blue fix-width" style="color: white;" href="//2017.hackerspace.govhack.org">
                                Get started on hackerspace
                            </a>
                            <br>
                            <br>
                        </div><!-- gh_col_close -->
                    </div>
                    <?php gh_row_close() ?>
                </div>
            </div>
        </div>
    </div>
    <?php
} else { // BEFORE
    ?>
    <div class="hero home-hero-pre2017" role="banner">
        <div id="home-hero-slider-reveal" class="home-hero-slider">
            <div>
                <div class="wrapper">
                    <?php gh_row_open() ?>
                    <div class="<?= gh_col_class(2, 6) ?>">
                        <img class="gh-pre2017-pin-map"
                             src="<?= esc_url(home_url('/wp-content/uploads/2017/07/tickets.png')) ?>" alt="Tickets">
                    </div>
                    <div class="<?= gh_col_class(4, 6) ?>">
                        <div class="hero-inner">
                            <h1 class="">
                                GovHackers start your engines <br>
                                Registrations are now open
                            </h1>
                            <a class="button gh-pink" style="color: white;" href="/register">
                                Get your tickets here
                            </a>
                        </div><!-- gh_col_close -->
                    </div>
                    <?php gh_row_close() ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
