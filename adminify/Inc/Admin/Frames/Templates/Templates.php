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
    <script>
    // Tag every admin URL set on any iframe with ?adminify-iframe=1 so the server
    // can identify the inner request as Adminify's iframe even when Sec-Fetch-Dest
    // and Referer headers are stripped (e.g. WordPress Playground service worker).
    // Without this the inner request renders Templates.php again -> nested chrome.
    (function () {
        function tag(url) {
            try {
                var u = new URL(url, location.origin);
                if (u.pathname.indexOf('/wp-admin') !== -1 && !u.searchParams.has('adminify-iframe')) {
                    u.searchParams.set('adminify-iframe', '1');
                    return u.toString();
                }
            } catch (e) {}
            return url;
        }
        var origPropDesc = Object.getOwnPropertyDescriptor(HTMLIFrameElement.prototype, 'src')
            || Object.getOwnPropertyDescriptor(HTMLFrameElement.prototype, 'src');
        if (origPropDesc && origPropDesc.set) {
            Object.defineProperty(HTMLIFrameElement.prototype, 'src', {
                set: function (v) { origPropDesc.set.call(this, tag(v)); },
                get: origPropDesc.get,
                configurable: true
            });
        }
        var origSetAttr = Element.prototype.setAttribute;
        Element.prototype.setAttribute = function (name, value) {
            if (this.tagName === 'IFRAME' && name === 'src') {
                value = tag(value);
            }
            return origSetAttr.call(this, name, value);
        };
    })();
    </script>
</head>
<body>
    <div id="frame-adminify-app" class="frame-adminify-app"></div>
</body>
</html>
