function tm_event_time() {
    function e(e) {
        return (e < 10 && "0") + e
    }
    var t, a, l, n, o, s = document.getElementsByClassName("tm-event_time"),
        i = new Date(Date.UTC(2017, 6, 3, 0, 0)),
        c = new Date;
    n = o = Math.floor((i.getTime() - c.getTime()) / 1e3), n -= 86400 * (t = e(Math.floor(n / 86400))), n -= 3600 * (a = e(Math.floor(n / 3600))), n -= 60 * (l = e(Math.floor(n / 60))), n = e(Math.floor(n)), o <= 0 ? t = a = l = n = "00" : setTimeout(tm_event_time, 1e3);
    for (var m = 0; m < s.length; ++m) s[m].innerHTML = '<div class="row"><div class="col-lg-3 col-md-3 col-sm-6 col-xs-6"><div class="tm-event_time-box"><span class="number">' + t + '</span><span class="title">day' + (t > 1 ? "s" : "") + '</span></div></div><div class="col-lg-3 col-md-3 col-sm-6 col-xs-6"><div class="tm-event_time-box"><span class="number">' + a + '</span><span class="title">hour' + (a > 1 ? "s" : "") + '</span></div></div><div class="col-lg-3 col-md-3 col-sm-6 col-xs-6"><div class="tm-event_time-box"><span class="number">' + l + '</span><span class="title">minute' + (l > 1 ? "s" : "") + '</span></div></div><div class="col-lg-3 col-md-3 col-sm-6 col-xs-6"><div class="tm-event_time-box"><span class="number">' + n + '</span><span class="title">second' + (n > 1 ? "s" : "") + "</span></div></div></div>\x3c!--//row--\x3e"
}
$(document).on("ready", function() {
    "use strict";
    setTimeout(function() {
        $(".tm-loader").addClass("active")
    }, 3e3), $(window).scroll(function() {
        $(this).scrollTop() > 100 ? $(".tm-header").addClass("active") : $(".tm-header").removeClass("active"), $(this).scrollTop() > 300 ? $("#tmScrollTop").addClass("active") : $("#tmScrollTop").removeClass("active")
    }), $("#tmScrollTop").on("click", function() {
        return $("html, body").animate({
            scrollTop: "0"
        }, 500), !1
    }), tm_event_time(), $(".tmTooltip").tooltip(), $(".tm-header_icon-nav .tm-header_icon-inner").on("click", function() {
        event.preventDefault(), $("#tm-resp-nav").addClass("active"), $(".tm-background-overlay").addClass("active"), document.body.style.overflow = "hidden"
    }), $(".tm-side-box_header-close").on("click", function() {
        event.preventDefault(), $(this).parent().parent().parent().removeClass("active"), $(".tm-background-overlay").removeClass("active"), document.body.style.overflow = "visible"
    }), $("#tm-resp-nav .nav-link").on("click", function() {
        event.preventDefault(), $("#tm-resp-nav").removeClass("active"), $(".tm-background-overlay").removeClass("active"), document.body.style.overflow = "visible"
    }), $(".tm-header_icon-lang .tm-header_icon-inner").on("click", function() {
        event.preventDefault(), $(".tm-language_bar").addClass("active"), $(".tm-background-overlay").addClass("active"), document.body.style.overflow = "hidden"
    }), $(".tm-language_bar a").on("click", function() {
        event.preventDefault(), $(".tm-language_bar").removeClass("active"), $(".tm-background-overlay").removeClass("active"), document.body.style.overflow = "visible"
    }), $(".btn-promo_detail").on("click", function() {
        event.preventDefault(), $("#tm-promo_detail").addClass("active"), $(".tm-background-overlay").addClass("active"), document.body.style.overflow = "hidden"
    }), $("*").on("click", function(e) {
        $(e.target).is(".tm-target") || $(e.target).is(".tm-target *") || ($(".tm-target").removeClass("active"), $(".tm-background-overlay").removeClass("active"), document.body.style.overflow = "visible")
    }), $("#tm-slider_carousel").owlCarousel({
        loop: !0,
        margin: 30,
        nav: !1,
        navText: ["<span class='glyphicon glyphicon-chevron-left'></span>", "<span class='glyphicon glyphicon-chevron-right'></span>"],
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: 2
            },
            1200: {
                items: 3
            }
        }
    }), $("#tm-slider_single").owlCarousel({
        loop: !0,
        margin: 0,
        nav: !1,
        items: 1,
        animateOut: "slideOutDown",
        animateIn: "slideInUp"
    }), $("#tm-slider_single-transparent").owlCarousel({
        loop: !0,
        margin: 0,
        nav: !1,
        items: 1,
        animateOut: "slideOutUp",
        animateIn: "slideInUp"
    }), $(".tm-schedule_title-resp").on("click", function() {
        event.preventDefault(), $(".tm-schedule_title .nav-tabs").toggleClass("active")
    }), $(".tm-schedule_title .nav-tabs li").on("click", function() {
        event.preventDefault();
        var e = $(this).find(".title").html(),
            t = $(this).find(".badge").html();
        $(".tm-schedule_title .nav-tabs").toggleClass("active"), $(".tm-schedule_title-resp").find(".title").html(e), $(".tm-schedule_title-resp").find(".badge").html(t)
    }), $("#tm-gallery_carousel").owlCarousel({
        loop: !0,
        margin: 10,
        nav: !1,
        responsive: {
            0: {
                items: 1,
                dots: !0
            },
            600: {
                items: 3,
                dots: !0
            },
            1e3: {
                items: 4,
                dots: !0
            }
        }
    }), $(".tm-popup_gallery").magnificPopup({
        delegate: "a",
        type: "image",
        tLoading: "Loading image #%curr%...",
        mainClass: "mfp-img-mobile",
        gallery: {
            enabled: !0,
            navigateByImgClick: !0,
            preload: [0, 1]
        }
    }), $(".tm-popup_videos").magnificPopup({
        disableOn: 700,
        type: "iframe",
        mainClass: "mfp-fade",
        removalDelay: 160,
        preloader: !1,
        fixedContentPos: !1
    }), $(".tm-popup_gallery-zoom").magnificPopup({
        delegate: "a",
        type: "image",
        closeOnContentClick: !1,
        closeBtnInside: !1,
        mainClass: "mfp-with-zoom mfp-img-mobile",
        image: {
            verticalFit: !0,
            titleSrc: function(e) {
                return e.el.attr("title") + ' &middot; <a class="image-source-link" href="' + e.el.attr("data-source") + '" target="_blank">image source</a>'
            }
        },
        gallery: {
            enabled: !0
        },
        zoom: {
            enabled: !0,
            duration: 300,
            opener: function(e) {
                return e.find("img")
            }
        }
    }), $(".tm-header_nav, .tm-resp-nav_nav").on("click", 'a[href^="#"]', function(e) {
        var t = $(this).attr("href");
        if (0 !== $(t).length) {
            e.preventDefault();
            var a = $(t).offset().top;
            $("body, html").animate({
                scrollTop: a
            }, 500)
        }
    })
}), tm_event_time();