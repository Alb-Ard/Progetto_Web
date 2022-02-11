<?php if (!has_accepted_cookies()) { ?>
    <script type="text/javascript">
        function setAcceptedCookies() {
            document.cookie = `<?php echo HAS_ACCEPTED_COOKIES; ?>=yes; expires=${new Date(Date.now() + <?php echo COOKIE_DURATION; ?>).toUTCString()}; path=/; samesite=Lax`;
            $("#cks-banner").fadeOut("fast");
        }
    </script>
    <aside id="cks-banner" class="fixed-bottom rounded shadow m-3 p-3 border bg-light">
        <p>This website uses cookies for technical purposes only, and no personal data is collected or stored for use.</p>
        <p>By closing this panel or continuing on the site you agree to the use of cookies.</p>
        <nav>
            <ul class="p-0">
                <li class="bottom-bar-item d-inline-block me-3"><button class="btn button-primary" onclick="setAcceptedCookies();">I understand, close this panel</button></li>
                <li class="bottom-bar-item d-inline-block"><a class="btn button-secondary black-link" href="./policy/cookies.html">Learn more</a></li>
            </ul>
        </nav>
    </aside>
<?php } ?>