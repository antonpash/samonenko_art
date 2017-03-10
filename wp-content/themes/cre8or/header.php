<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="yandex-verification" content="60d77d04ba9a605c"/>
    <!--[if IE]>
    <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="interkassa-verification" content="06a8a7fcf1310f45e8d38318f746cf73"/>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <link
        href='https://fonts.googleapis.com/css?family=Open+Sans:500,400,300,300italic,400italic,600,600italic,700,700italic,800,800italic&subset=latin,cyrillic-ext'
        rel='stylesheet' type='text/css'>
    <?php wp_head(); ?>
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-73030361-2', 'auto');
        ga('send', 'pageview');

    </script>
</head>
<body <?php body_class(); ?> <?php echo apply_filters('body_gradient', 'data-animated-bg="#f8f8f8:20-#eeeeee"'); ?>>

<script type="text/javascript">
    VK.init({
        apiId: "5805653",
        onlyWidgets: true
    });
</script>


<div id="fb-root"></div>
<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.8";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

<!--[if lt IE 9]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->

<div id="main-wrap">
    <?php do_action('tt_theme_header'); ?>