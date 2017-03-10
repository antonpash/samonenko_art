<?php

/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

$wr = array(
    "/portfolio/89/page/2/",
    "/portfolio/33/page/2/",
    "/home/portfolio/925",
    "/home/portfolio/925/",
    "/home/portfolio/102/",
    "/home/portfolio/36",
    "/home/portfolio/46/",
    "/home/portfolio/52/",
    "/home/portfolio/74/",
    "/home/portfolio/512",
    "/home/portfolio/512/",
    "/home/portfolio/63/",
    "/home/portfolio/89",
    "/home/portfolio/89/",
    "/home/portfolio/18/",
    "/home/portfolio/27/",
    "/home/portfolio/41/",
    "/home/portfolio/30",
    "/home/",
    "/home/portfolio/30/",
    "/home/portfolio/46",
    "/home/portfolio/33/",
    "/home/portfolio/36/",
    "/home/portfolio/52",
    "/home/portfolio/93/",
    "/home/portfolio/56/",
    "/portfolio/44/page/2/"
);

if(in_array($_SERVER["REQUEST_URI"], $wr)){

    $link = "http://samonenkoart.com/";
    $parts = explode("/", $_SERVER["REQUEST_URI"]);

    if(preg_match("/^\/portfolio\/\d*\/page\/\d*/", $_SERVER["REQUEST_URI"])){
        $link .= $parts[1];
        $link .= "/";
        $link .= $parts[3];
        $link .= "/";
        $link .= $parts[4];
    }
    elseif(preg_match("/^\/home\/portfolio\/\d*/", $_SERVER["REQUEST_URI"])){
        $link .= str_replace("home", "archives", $_SERVER["REQUEST_URI"]);
    }

    header("Location: " . $link, true, 301);
    exit();
}

/** Loads the WordPress Environment and Template */
require(dirname(__FILE__) . '/wp-blog-header.php');
