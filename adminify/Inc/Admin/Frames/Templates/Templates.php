<?php

namespace WPAdminify\Inc\Admin\Frames\Templates;

use WPAdminify\Inc\Admin\AdminSettings;
// no direct access allowed
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$favicon = '';
if ( function_exists( 'get_site_icon_url' ) ) {
    $favicon = get_site_icon_url();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='shortcut icon' href='<?php 
echo esc_url( $favicon );
?>' type='image/x-icon'></link>
</head>
<body>
    <div id="frame-adminify-app" class="frame-adminify-app"></div>
</body>
</html>
<?php 