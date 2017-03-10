<?php global $tt_theme; ?>

</div>
<footer class="main-footer">
    <?php do_action('tt_footer'); ?>
    <?php $theme_meta = wp_get_theme(); ?>
    <div class="container">
        <div class="copyright uppercase align-center text-center">
            Авторские картины и магазин атрибутики.</br>Художник Ольга Самоненко.</br>
            <?php echo _go('footer_info') ? _go('footer_info') : esc_html__('&copy;', 'cre8or'); ?>
            <a href="<?php echo get_site_url(); ?>">SamonenkoART</a>
            <?php echo date('Y'); ?>
        </div>
    </div>
</footer>
</div> <!-- /#main-wrap -->
<?php wp_footer(); ?>

<?php if (/*get_locale() != 'ru_RU'*/ false): ?>
    <script type="text/javascript">
        function googleTranslateElementInit2() {
            new google.translate.TranslateElement({
                pageLanguage: 'ru',
                autoDisplay: false
            }, 'google_translate_element2');
        }
    </script>
    <script type="text/javascript"
            src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit2"></script>


    <script type="text/javascript">
        function GTranslateFireEvent(element, event) {
            try {
                if (document.createEventObject) {
                    var evt = document.createEventObject();
                    element.fireEvent('on' + event, evt)
                } else {
                    var evt = document.createEvent('HTMLEvents');
                    evt.initEvent(event, true, true);
                    element.dispatchEvent(evt)
                }
            } catch (e) {
            }
        }
        function doGTranslate(lang_pair) {
            if (lang_pair.value)lang_pair = lang_pair.value;
            if (lang_pair == '')return;
            var lang = lang_pair.split('|')[1];
            var teCombo;
            var sel = document.getElementsByTagName('select');
            for (var i = 0; i < sel.length; i++)if (sel[i].className == 'goog-te-combo')teCombo = sel[i];
            if (document.getElementById('google_translate_element2') == null || document.getElementById('google_translate_element2').innerHTML.length == 0 || teCombo.length == 0 || teCombo.innerHTML.length == 0) {
                setTimeout(function () {
                    doGTranslate(lang_pair)
                }, 500)
            } else {
                teCombo.value = lang;
                GTranslateFireEvent(teCombo, 'change');
                GTranslateFireEvent(teCombo, 'change')
            }
        }

        doGTranslate('ru|en');
    </script>
<?php endif; ?>
</body>
</html>