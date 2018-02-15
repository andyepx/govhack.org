<?php

date_default_timezone_set('Australia/Brisbane');

if (time() > strtotime('2017-10-14 21:05')) { // AFTER
    ?>
    <div class="hero home-hero-pre2017 rca" role="banner">
        <div id="home-hero-slider-reveal" class="home-hero-slider">
            <div>
                <div class="wrapper">
                    <?php gh_row_open() ?>
                    <div class="<?= gh_col_class(5, 6) ?>">
                        <div class="hero-inner">
                            <h1 class="">
                                Red Carpet Awards, 2017. Tick.
                            </h1>
                            <p>
                                The lights shone, the cameras popped, speeches were speeched, and awards were awarded.
                                The 2017 GovHack Red Carpet Awards were held in Brisbane, and we celebrated the cream of
                                this year’s GovHacking crop. See all the winners, and find out how your state or
                                territory went.
                                <br>
                                <br>
                                <a class="button gh-blue first-button" style="color: white;"
                                   href="/national-international-winners">
                                    VIEW THE WINNERS
                                </a>
                            </p>
                        </div><!-- gh_col_close -->
                    </div>
                    <?php gh_row_close() ?>
                </div>
            </div>
        </div>
    </div>
    <?php
} else if (time() > strtotime('2017-10-14 18:15')) { //DURING
    ?>
    <div class="hero home-hero-pre2017 rca" role="banner">
        <div id="home-hero-slider-reveal" class="home-hero-slider">
            <div>
                <div class="wrapper">
                    <?php gh_row_open() ?>
                    <div class="<?= gh_col_class(5, 6) ?>">
                        <div class="hero-inner">
                            <h1 class="">
                                Airhorn! Red carpet Awards are a go!
                            </h1>
                            <p>
                                The lights are shining in Brisbane, the City Hall looks a picture, and our special
                                guests and award nominees have all arrived. The 2017 GovHack Red Carpet Awards have
                                kicked off, and you can be part of the action!
                                <br>
                                Tune in now to the live stream.
                                <br>
                                <br>
                                <a class="button gh-blue first-button" style="color: white;"
                                   href="/live">
                                    Watch live
                                </a>
                            </p>
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
    <div class="hero home-hero-pre2017 rca" role="banner">
        <div id="home-hero-slider-reveal" class="home-hero-slider">
            <div>
                <div class="wrapper">
                    <?php gh_row_open() ?>
                    <div class="<?= gh_col_class(5, 6) ?>">
                        <div class="hero-inner">
                            <h1 class="">
                                Lights, camera, action!
                            </h1>
                            <p>
                                It's time to celebrate the best of the best, at the 2017 GovHack International Red
                                Carpet Awards.
                                <br>
                                This year's awards will be held at the Brisbane City Hall, on Saturday 14 October.
                                <br>
                                <br>
                                <a class="button gh-blue first-button" style="color: white;"
                                   href="/about-us/red-carpet-awards/">
                                    find out more
                                </a>
                                <a class="button gh-pink" style="color: white;"
                                   target="_blank"
                                   href="https://www.eventbrite.com.au/e/govhack-2017-international-red-carpet-awards-tickets-38075264140">
                                    book tickets
                                </a>
                            </p>
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

