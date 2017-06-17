(function ($) {
    $(function () {

        $(window).on('scroll', function (x) {
            if (window.scrollY > 10) {
                $('#landingHeader').addClass('fixed');
            } else {
                $('#landingHeader').removeClass('fixed');
            }
        });

        $('#subscribe').on('submit', function (x) {
            x.preventDefault();
            // do something?

            var l = $('#subscribe button');
            l.find('span').hide();
            l.find('.loading').show();

            setTimeout(function (y) {
                l.find('span').html('Done!');
                l.find('.loading').hide();
                l.find('span').show();
                $('#subscribe input').val('');
            }, 2000);

        });

        $('#goToSponsor').click(function(x) {
            document.querySelector('#sponsor').scrollIntoView({ behavior: 'smooth' });
        });
        $('#goToVolunteer').click(function(x) {
            document.querySelector('#mentor').scrollIntoView({ behavior: 'smooth' });
        });
        $('#goToTop').click(function(x) {
            window.scroll({ top: 0, behavior: 'smooth' });
        });

    });
}(jQuery));