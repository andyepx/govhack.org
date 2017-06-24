"use strict";
var GH = GH || {};
jQuery ? GH.opts = jQuery.extend({
        useDashicons: !0,
        useFontAwesome: !1
    },
    GH.opts || {}) : GH.opts = GH.opts || {
        useDashicons: !0,
        useFontAwesome: !1
    },
    GH.siteSwitcher = function () {
        function s() {
            if (GH.opts.mobileHidden && document.body.classList.add("ghss-mobile-hidden"), GH.opts.layoutStrategy) switch (GH.opts.layoutStrategy) {
                case "block-padded":
                    document.body.classList.add("ghss-block-padded");
                    break;
                case "fixed":
                    document.body.classList.add("ghss-fixed")
            }
            document.body.classList.add("ghss-loaded"), document.body.insertAdjacentHTML("afterbegin", o)
        }

        document.write('<link rel="stylesheet" href="https://assets.govhack.org/css/site-switcher.css">'), GH.opts.useDashicons && document.write('<link rel="stylesheet" href="https://www.govhack.org/wp-includes/css/dashicons.min.css">'), GH.opts.useFontAwesome && document.write('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">');
        var a = [
            {
                name: "Slack",
                href: "http://slack.govhack.org",
                pattern: "/slack.govhack.org/",
                fa: "fa-slack",
                dashicons: "dashicons-admin-comments"
            },
            {
                name: "Website",
                href: "https://www.govhack.org",
                pattern: "/www.govhack.org/",
                fa: "fa-globe",
                dashicons: "dashicons-admin-site"
            },
            // {
            //     name: "News",
            //     href: "https://www.govhack.org/press/",
            //     pattern: "//",
            //     fa: "fa-rss-square",
            //     dashicons: "dashicons-rss"
            // },
            // {
            //     name: "Competition Portal",
            //     href: "http://portal.govhack.org",
            //     pattern: "/portal.govhack.org/",
            //     antipattern: "/portal.govhack.org/handbook/",
            //     fa: "fa-bar-chart",
            //     dashicons: "dashicons-chart-bar"
            // },
            // {
            //     name: "Handbook",
            //     href: "http://portal.govhack.org/handbook",
            //     pattern: "/portal.govhack.org/handbook/",
            //     fa: "fa-book",
            //     dashicons: "dashicons-clipboard"
            // },
            {
                name: "Hackerspace",
                href: "https://2016.hackerspace.govhack.org",
                fa: "fa-drupal",
                dashicons: "dashicons-megaphone"
            }
        ];
        var e = "gh-ss",
            o = "";
        o += '<nav class="' + e + '"><ul>', a.forEach(function (s) {
            if (s.pattern) {
                var a = new RegExp(s.pattern);
                if (a.test(document.location.href)) {
                    if (!s.antipattern) return !1;
                    var e = new RegExp(s.antipattern);
                    if (!e.test(document.location.href)) return !1
                }
            }
            o += '<li><a href="' + s.href + '">', GH.opts.useDashicons && (o += '<span class="dashicons ' + s.dashicons + '"></span>'), GH.opts.useFontAwesome && (o += '<span class="fa ' + s.fa + '"></span>'), o += s.name + "</a></li>"
        }), o += "</ul></nav>", window.addEventListener ? window.addEventListener("load", s, !1) : window.attachEvent && window.attachEvent("onload", s)
    }();