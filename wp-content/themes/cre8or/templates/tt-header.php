<?php global $tt_theme; ?>
<header class="main-header" role="banner">

    <?php if (wp_is_mobile()): ?>
        <section class="header-menu">
            <a href="#" id="menu-toggle">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                     y="0px" width="36px" height="36px" viewBox="0 0 512 512">
                    <path d="M0 175v32h320v-32h-320zM0 271v32h320v-32h-320zM0 79v32h320v-32h-320z"/>
                </svg>
            </a>
            <div class="menu-box text-white">
                <section class="menu-content">
                    <?php echo balanceTags($navigation); ?>
                    <h5 style="opacity: 1;" class="uppercase text-white"><?php _e('Social networks', 'cre8or'); ?>:</h5>
                    <ul style="opacity: 1;" class="social-links inline-list">
                        <li><a href="https://vk.com/samonenko_art"><img width="32"
                                                                        src="<?php echo TT_THEME_URI; ?>/tesla_framework/static/images/social/vkontakte_32.png"/></a>
                        </li>
                        <li><a href="https://www.facebook.com/SamonenkoArt/"><img width="32"
                                                                        src="<?php echo TT_THEME_URI; ?>/tesla_framework/static/images/social/facebook_32.png"/></a>
                        </li>
                        <li><a href="http://instagram.com/samonenkoolya"><img width="32"
                                                                              src="<?php echo TT_THEME_URI; ?>/tesla_framework/static/images/social/instagram_32.png"/></a>
                        </li>
                        <li><a href="https://www.behance.net/samonenkoolga"><img width="32"
                                                                                 src="<?php echo TT_THEME_URI; ?>/tesla_framework/static/images/social/behance_32.png"/></a>
                        </li>
                    </ul>
                </section>

                <?php

                echo balanceTags($menu_bottom);

                ?>
            </div>

        </section> <!-- /.header-menu -->
    <?php endif; ?>
    <section class="header-bar">
        <div class="container">
            <?php echo balanceTags($logo); ?>

            <?php

            if (!wp_is_mobile())
                echo balanceTags($navigation_desktop);

            ?>

        </div>
    </section> <!-- /.header-bar -->
</header> <!-- /.main-header -->
<div class="main-content">


