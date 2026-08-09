<?php

namespace PXLBSAdminify\Inc\Admin\Frames\Templates;
use PXLBSAdminify\Inc\Admin\AdminSettings;

// no direct access allowed
if (!defined('ABSPATH')) {
    exit;
}

$pxlbsadminify_favicon = '';
if( function_exists('get_site_icon_url') ) {
    $pxlbsadminify_favicon = get_site_icon_url();
}
$pxlbsadminify_favicon = apply_filters('pxlbsadminify/frame/favicon', $pxlbsadminify_favicon); // Apply favicon filter

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='shortcut icon' href='<?php echo esc_url($pxlbsadminify_favicon) ?>' type='image/x-icon' />
    <script id="frame-adminify-app--guard">
    /*
     * Adminify serves the admin inside an iframe. The inner document is normally
     * recognised server-side through the `Sec-Fetch-Dest: iframe` request header
     * (Utils::is_iframe()). Setups that serve WordPress from a Service Worker -
     * WordPress Playground, offline/proxy installs - never hand that header to
     * PHP, because the browser adds it below the Service Worker layer. The inner
     * request is then treated as a top-level one and this whole shell (sidebar,
     * toolbar and yet another iframe) is printed a second time inside the frame:
     * the duplicated menu.
     *
     * Detect the situation on the client, where `window.frameElement` always
     * tells the truth, and turn this document back into a plain framed admin
     * page: drop the shell root before the bundle can mount into it and apply
     * the same rules `assets/admin/css/frame.css` would have applied.
     */
    (function () {
        var frame;

        try {
            frame = window.frameElement;
        } catch (e) {
            return; // Cross-origin parent - not an Adminify frame.
        }

        // Only the iframe Adminify itself creates. Any other embedding (the
        // Playground viewport hosting the whole admin, for one) must keep the
        // shell, so an id check - not `window.self !== window.top`.
        if (!frame || frame.id !== 'frame-adminify-app--iframe') {
            return;
        }

        window.PXLBSADMINIFY_FRAME_FALLBACK = true;
        document.documentElement.setAttribute('frame-adminify-iframe', 'true');

        // WordPress prints its own <html> tag further down the response and the
        // parser merges `frame-adminify-app="true"` onto this element, which
        // pulls in admin.css's "hide the real admin" rules. Two attribute
        // selectors outrank those single-attribute rules whatever the load order.
        var css = 'html[frame-adminify-iframe="true"] { padding-top: 0 !important; }'
            + 'html[frame-adminify-iframe="true"][frame-adminify-app="true"] #adminmenumain,'
            + 'html[frame-adminify-iframe="true"][frame-adminify-app="true"] #wpadminbar,'
            + 'html[frame-adminify-iframe="true"] #frame-adminify-app { display: none !important; }'
            + 'html[frame-adminify-iframe="true"][frame-adminify-app="true"] #wpcontent,'
            + 'html[frame-adminify-iframe="true"][frame-adminify-app="true"] #wpfooter {'
            + 'display: block !important; margin-left: 0 !important; margin-right: 0 !important; }';

        var style = document.createElement('style');
        style.id = 'frame-adminify-app--guard-style';
        style.appendChild(document.createTextNode(css));
        document.head.appendChild(style);

        // Remove the mount point as soon as the parser inserts it, so the shell
        // bundle - which loads in the footer, long before DOMContentLoaded -
        // finds nothing to mount and never creates a nested iframe.
        var dropShellRoot = function () {
            var root = document.getElementById('frame-adminify-app');
            if (!root) {
                return false;
            }
            root.parentNode.removeChild(root);
            return true;
        };

        if (window.MutationObserver) {
            var observer = new MutationObserver(function () {
                if (dropShellRoot()) {
                    observer.disconnect();
                }
            });
            observer.observe(document.documentElement, { childList: true, subtree: true });
        }

        document.addEventListener('DOMContentLoaded', dropShellRoot);
    })();
    </script>
</head>
<body>
    <div id="frame-adminify-app" class="frame-adminify-app"></div>
</body>
</html>
