/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./dev/admin/wp-adminify.js":
/*!**********************************!*\
  !*** ./dev/admin/wp-adminify.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   waitForElm: () => (/* binding */ waitForElm)\n/* harmony export */ });\n// JS codes by WP Adminify\n\n(function ($) {\n  \"use strict\";\n\n  // $('.auto-fold .interface-interface-skeleton').css('left', '244px');\n  // $('.wp-adminify.block-editor-page .interface-interface-skeleton').css('top', '90px');\n  // $('.wp-adminify .editor-document-tools__left button.editor-document-tools__inserter-toggle').css('padding', '0px');\n  // $('.wp-adminify.is-fullscreen-mode #wpadminbar').css('display', 'none');\n\n  // var $body = $('body'),\n  //     isFullScreen = $body.hasClass('is-fullscreen-mode');\n  // if (isFullScreen) {\n  //     console.log(\"yes its it\");\n  //     // $step5.css({\n  //     //     'top': '93px',\n  //     //     'left': '0',\n  //     // });\n  //     // $step5Arrow.css('left', '91px');\n  // }\n\n  // $(document).ready(function () {\n  //     if ($(\"body.js.wp-adminify\").hasClass(\"is-fullscreen-mode\")) {\n  //     //   console.log(\"yes its it\");\n  //         $(\".wp-adminify.adminify-top_bar\").css('display', 'none');\n  //         $(\".wp-adminify.block-editor-page .interface-interface-skeleton\").css('left', '0px !important');\n  //     } else {\n  //       console.log(\"non ono \");\n  //     }\n  //     // console.log(\"ready!\");\n  // });\n\n  // pro code start\n  var isPro = WP_ADMINIFY_ADMIN.is_pro;\n  var isAdminifyUI = WP_ADMINIFY_ADMIN.settings.adminify_ui;\n\n  // pro code end\n\n  // adminbar sticky class add/remove\n  $(window).scroll(function () {\n    var scroll = $(window).scrollTop();\n    if (scroll >= 1) {\n      $(\".adminify-top_bar\").addClass(\"is-sticky\");\n    } else {\n      $(\".adminify-top_bar\").removeClass(\"is-sticky\");\n    }\n  });\n\n  // wp adminify adminbar menu displaying issue fixed START\n  if (!$(\"#wp-admin-bar-top-secondary\").length) {\n    $(\"#wp-toolbar.quicklinks\").append(\" <ul id = \\\"wp-adminify-default-top-secondary\\\" > </ul> \");\n  }\n  // wp adminify adminbar menu displaying issue fixed END\n\n  // Icon Class replaced when adminify_ui is disabled\n  $(\"body:not(.adminify-ui) div[class*=' dashicons-adminify']\").each(function () {\n    var el_class = $(this).attr(\"class\").replace(\"dashicons-before dashicons-adminify-\", \"adminify-menu-icon \");\n    $(this).attr(\"class\", el_class);\n  });\n  $('body:not(.adminify-ui) #adminmenuwrap #adminmenu a').each(function () {\n    $(this).find('img').each(function () {\n      var imgSrc = $(this).attr('src');\n      if (imgSrc && imgSrc.includes('http://fas%20fa-')) {\n        // Extract the icon class dynamically\n        var iconClassMatch = imgSrc.match(/http:\\/\\/fas%20fa-([a-zA-Z0-9-]+)/);\n        if (iconClassMatch && iconClassMatch[1]) {\n          var iconClass = 'fas fa-' + iconClassMatch[1];\n\n          // Add the class to the parent div\n          $(this).parent().addClass(iconClass);\n\n          // Remove the image\n          $(this).remove();\n        }\n      }\n    });\n  });\n\n  // scroll to top class added to body / its currently working for gravity form header\n  window.addEventListener(\"scroll\", function (e) {\n    var distanceY = window.pageYOffset || document.documentElement.scrollTop,\n      scrollTo = 40,\n      header = document.querySelector(\"body\");\n    if (distanceY > scrollTo) {\n      header.classList.add(\"adminify-scrollto-sticky\");\n    } else {\n      if (header.classList.contains(\"adminify-scrollto-sticky\")) {\n        header.classList.remove(\"adminify-scrollto-sticky\");\n      }\n    }\n  });\n\n  // Folder div made full width and content placed to bottom\n  if (window.matchMedia(\"(max-width: 500px)\").matches) {\n    setTimeout(function () {\n      var folder_height = $(\".wp-adminify--folder-app\").height();\n      $(\"#wpbody-content\").css(\"margin-top\", folder_height + 180);\n    }, 1500);\n  }\n  var adminHeight = $(\".wp-adminify.adminify-top_bar\").height();\n  $(\".wp-adminify-admin-bar.position-bottom\").css(\"padding-bottom\", adminHeight * 1.25);\n\n  // Widget #dashboard_right_now count style\n\n  jQuery(\"#dashboard_right_now li a\").html(function () {\n    var text = jQuery(this).text().trim().split(\" \");\n    var first = text.shift();\n    return (text.length > 0 ? \"<span class='counter'>\" + first + \"</span> \" : first) + text.join(\" \");\n  });\n\n  // Accordion\n\n  jQuery(\".accordion .accordion-body\").css(\"display\", \"none\");\n  jQuery(\"body\").on(\"click\", \".accordion-button, .accordion-opener\", function (e // jQuery(\".accordion-button, .accordion-opener\").on('click', function (e)\n  ) {\n    e.preventDefault();\n    jQuery(this).toggleClass(\"show\");\n    var jQuerythis = jQuery(this);\n    if (jQuerythis.next().hasClass(\"show\")) {\n      jQuerythis.next().removeClass(\"show\");\n      jQuerythis.next().slideUp(100);\n    } else {\n      jQuerythis.parent().parent().find(\".accordion-body\").removeClass(\"show\");\n      jQuerythis.parent().parent().find(\".accordion-body\").slideUp(100);\n      jQuerythis.prev(\".accordion-title\").toggleClass(\"show\");\n      jQuerythis.next().toggleClass(\"show\");\n      jQuerythis.next().slideToggle(100);\n    }\n  });\n\n  // Admin Columns Accordions\n\n  $(\".accordion-opener\").on(\"click\", function (e) {\n    e.preventDefault();\n    var $this = $(this);\n    if ($this.next().hasClass(\"show\")) {\n      $this.next().removeClass(\"show\");\n      $this.next().slideUp(100);\n    } else {\n      $this.parent().parent().find(\".accordion-body\").removeClass(\"show\");\n      $this.parent().parent().find(\".accordion-body\").slideUp(100);\n      $this.prev(\".accordion-title\").toggleClass(\"show\");\n      $this.next().toggleClass(\"show\");\n      $this.next().slideToggle(100);\n    }\n  });\n  $(window).on(\"load\", function () {\n    // WP_Adminify.animateCSS('body.wp-adminify', 'fadeIn');\n    // WP_Adminify.animateCSS('.my-element', 'fadeIn').then((message) => {\n    // // Do something after the animation\n    // });\n\n    $(\".wp-adminify-loader\").delay(300).fadeOut(\"slow\");\n\n    // Adminbar Loader\n    $(\".wp-adminify-topbar-loader\").delay(100).fadeOut(\"fast\");\n    setTimeout(function () {\n      $(\".wp-adminify.adminify-top_bar\").fadeIn(\"fast\");\n    }, 100);\n\n    // Menu Editor Preloader\n    setTimeout(function () {\n      $(\".wp-adminify-menu-editor-loader\").css({\n        display: \"none\"\n      });\n    }, 700);\n    setTimeout(function () {\n      $(\".wp-adminify--menu--editor--settings\").addClass(\"loaded\");\n    }, 700);\n  });\n\n  // Google page speed origin on / off\n\n  jQuery(\".origin-summery-trigger button\").on(\"click\", function () {\n    alert(\"clicked!\");\n    jQuery(\".result-body\").toggleClass(\"show-origin\");\n  });\n\n  // Wrap content get extra margin if folder options exist\n  jQuery(\"body\").has(\"#wp-adminify--folder-app\").addClass(\"has-folder-options\");\n\n  // tippy('[data-tippy-content]');\n\n  // Admin Topbar Search\n  function admin_top_search_hide_result() {\n    $(\"#top-header-search-results\").hide();\n  }\n  function admin_top_search_show_result() {\n    $(\"#top-header-search-results\").show();\n  }\n  $(\"#top-header-search-input\").on(\"input\", function () {\n    var search_val = $(\"#top-header-search-input\").val();\n    admin_top_bar_search(search_val);\n    if (!search_val.length) {\n      admin_top_search_hide_result();\n    }\n  });\n  var cansearch;\n  function admin_top_bar_search(searchTerm) {\n    // Admin Bar Search\n    if (cansearch == false) {\n      return;\n    }\n    if (searchTerm == \"\") {\n      return;\n    }\n\n    // var count_rows = $('#top-header-search-results .top-header-result-table > tbody > tr').length;\n    // console.log(count_rows);\n    // $(\"#top-header-search-results\").css('display','block');\n\n    $.ajax({\n      url: WPAdminify.ajax_url,\n      type: \"post\",\n      data: {\n        action: \"adminify_all_search\",\n        security: WPAdminify.security_nonce,\n        search: searchTerm\n      },\n      beforeSend: function beforeSend(xhr) {\n        cansearch = false;\n      },\n      success: function success(response) {\n        if (response) {\n          var data = JSON.parse(response);\n\n          // if (data.error) {\n          // Toastr Code here\n          // } else {\n          admin_top_search_show_result();\n          $(\"#top-header-search-results .top-header-results-wrapper\").html(data);\n\n          // $(\"#top-header-search-results\").show();\n          cansearch = true;\n          // }\n        }\n      }\n    });\n  }\n  var WP_Adminify = {\n    // Pro Notice\n    ProNotice: function ProNotice() {\n      // Notice Hide to Outside overlay\n      $(\".wp-adminify-popup-overlay\").on(\"click\", function (evt) {\n        evt.preventDefault();\n        $(this).closest(\".wp-adminify-upgrade-popup\").fadeOut(200);\n      });\n\n      // Notice Hide to close button\n      $(\"body\").on(\"click\", \".wp-adminify-upgrade-popup .popup-dismiss\", function (evt) {\n        evt.preventDefault();\n        $(this).closest(\".wp-adminify-upgrade-popup\").fadeOut(200);\n      });\n\n      // Notice Show\n      $(\"body\").on(\"click\", \".adminify-pro-notice\", function (evt) {\n        evt.preventDefault();\n        $(\".wp-adminify-upgrade-popup\").fadeIn(200);\n      });\n\n      /**\n       * Fields Notice Class Add\n       */\n      // Checkbox\n      var checkboxLabel = $(\".adminify-pro-checkbox\").parent().parent();\n      checkboxLabel.addClass(\"adminify-pro-notice\");\n\n      // Color Picker\n      var colorPickerLabel = $(\".adminify-field-color_group.adminify-pro-fieldset > .adminify-fieldset\");\n      colorPickerLabel.addClass(\"adminify-pro-notice\");\n\n      // Gradient Color Picker\n      var gradientLolorPickerLabel = $(\".adminify-field-background[data-value='gradient|true'].adminify-pro-feature > .adminify-fieldset\");\n      gradientLolorPickerLabel.css(\"pointer-events\", \"none\");\n    },\n    // Pro Notice With Iframe\n    ProNoticeWithIframe: function ProNoticeWithIframe(__iFrameDOM) {\n      // Notice Hide to Outside overlay\n      __iFrameDOM.find(\"body\").on(\"click\", \".wp-adminify-popup-overlay\", function (evt) {\n        evt.preventDefault();\n        $(this).closest(\".wp-adminify-upgrade-popup\").fadeOut(200);\n      });\n\n      // Notice Hide to close button\n      __iFrameDOM.find(\"body\").on(\"click\", \".wp-adminify-upgrade-popup .popup-dismiss\", function (evt) {\n        evt.preventDefault();\n        $(this).closest(\".wp-adminify-upgrade-popup\").fadeOut(200);\n      });\n\n      // Notice Show\n      __iFrameDOM.find(\"body\").on(\"click\", \".adminify-pro-notice\", function (evt) {\n        evt.preventDefault();\n        __iFrameDOM.find(\".wp-adminify-upgrade-popup\").fadeIn(200);\n      });\n\n      /**\n       * Fields Notice Class Add\n       */\n      // Checkbox\n      var checkboxLabel = __iFrameDOM.find(\".adminify-pro-checkbox\").parent().parent();\n      checkboxLabel.addClass(\"adminify-pro-notice\");\n\n      // Color Picker\n      var colorPickerLabel = __iFrameDOM.find(\".adminify-field-color_group.adminify-pro-fieldset > .adminify-fieldset\");\n      colorPickerLabel.addClass(\"adminify-pro-notice\");\n\n      // Gradient Color Picker\n      var gradientLolorPickerLabel = __iFrameDOM.find(\".adminify-field-background[data-value='gradient|true'].adminify-pro-feature > .adminify-fieldset\");\n      gradientLolorPickerLabel.css(\"pointer-events\", \"none\");\n    },\n    // Preset Pro Notice\n    PresetProNotice: function PresetProNotice(presets) {\n      // Presets\n      presets.forEach(function (item) {\n        var preset_item = document.querySelector(\".adminify--image-group .adminify--image figure input[value=\\\"\".concat(item, \"\\\"]\"));\n        if (!preset_item) return;\n        var preset = preset_item.parentNode.parentNode;\n        preset.classList.add(\"adminify-pro-notice\");\n        var proBatch = document.createElement(\"span\");\n        proBatch.classList.add(\"adminify-pro-tag\");\n        var proText = document.createTextNode(\"Pro\");\n        proBatch.appendChild(proText);\n        preset.appendChild(proBatch);\n      });\n    },\n    // Preset Pro Notice With Iframe\n    PresetProNoticeWithIframe: function PresetProNoticeWithIframe(__iFrameDOM, presets) {\n      // Presets\n      presets.forEach(function (item) {\n        // With Iframe\n        var iframe_preset_item = __iFrameDOM.find(\".adminify--image-group .adminify--image figure input[value=\\\"\".concat(item, \"\\\"]\"));\n        var iframe_preset = iframe_preset_item.parent().parent();\n        iframe_preset.addClass(\"adminify-pro-notice\");\n        var iframe_proBatch = document.createElement(\"span\");\n        iframe_proBatch.classList.add(\"adminify-pro-tag\");\n        var iframe_proText = document.createTextNode(\"Pro\");\n        iframe_proBatch.appendChild(iframe_proText);\n        iframe_preset.append(iframe_proBatch);\n      });\n    },\n    ToggleSwitcher: function ToggleSwitcher(key, value) {\n      if (key == \"\") {\n        return;\n      }\n      jQuery.ajax({\n        url: WPAdminify.ajax_url,\n        type: \"post\",\n        data: {\n          action: \"wp_adminify_color_mode\",\n          security: WPAdminify.security_nonce,\n          key: key,\n          value: value\n        }\n      });\n    },\n    SetColorMode: function SetColorMode(color_mode) {\n      WP_Adminify.ToggleSwitcher(\"color_mode\", color_mode);\n      if (color_mode === \"dark\") {\n        window.AdminifyDarkMode.enable({\n          brightness: 120\n        });\n        $(\"body\").removeClass(\"adminify-light-mode\");\n        $(\"body\").addClass(\"adminify-dark-mode\");\n      } else if (color_mode === \"light\") {\n        window.AdminifyDarkMode.disable();\n        $(\"body\").removeClass(\"adminify-dark-mode\");\n        $(\"body\").addClass(\"adminify-light-mode\");\n      } else if (color_mode === \"system\") {\n        var isDark = window.matchMedia(\"(prefers-color-scheme: dark)\").matches;\n        if (!!isDark) {\n          window.AdminifyDarkMode.enable({\n            brightness: 120\n          });\n          $(\"body\").removeClass(\"adminify-light-mode\");\n          $(\"body\").addClass(\"adminify-dark-mode\");\n        } else {\n          window.AdminifyDarkMode.disable();\n          $(\"body\").removeClass(\"adminify-dark-mode\");\n          $(\"body\").addClass(\"adminify-light-mode\");\n        }\n      }\n    },\n    // Light/Dark Mode\n    Color_Mode_Switcher: function Color_Mode_Switcher() {\n      var lightBtn = document.querySelector(\".light-dark-dropdown .light\");\n      var darkBtn = document.querySelector(\".light-dark-dropdown .dark\");\n      var systemBtn = document.querySelector(\".light-dark-dropdown .system\");\n      var dropdown = document.querySelector(\".light-dark-dropdown\");\n      var modeIcon = document.querySelector(\".mode-icon\");\n      var lightIcon = document.querySelector(\".mode-icon .lightIcon\");\n      var darkIcon = document.querySelector(\".mode-icon .darkIcon\");\n      var systemIcon = document.querySelector(\".mode-icon .systemIcon\");\n      if (!lightBtn || !darkBtn || !systemBtn) return;\n      document.addEventListener(\"click\", function (event) {\n        var isVisible = getComputedStyle(dropdown).visibility === \"visible\";\n        if (!dropdown.contains(event.target)) {\n          if (!!isVisible) {\n            dropdown.removeAttribute(\"style\");\n          }\n        }\n      });\n      handleClick(modeIcon, function () {\n        setTimeout(function () {\n          dropdown.style.visibility = \"visible\";\n          dropdown.style.opacity = \"0.9999\";\n          dropdown.style.transform = \"none\";\n        }, 10);\n      });\n      handleClick(lightBtn, function () {\n        WP_Adminify.SetColorMode(\"light\");\n        lightIcon.style.display = \"block\";\n        darkIcon.style.display = \"none\";\n        systemIcon.style.display = \"none\";\n      });\n      handleClick(darkBtn, function () {\n        WP_Adminify.SetColorMode(\"dark\");\n        lightIcon.style.display = \"none\";\n        systemIcon.style.display = \"none\";\n        darkIcon.style.display = \"block\";\n      });\n      handleClick(systemBtn, function () {\n        WP_Adminify.SetColorMode(\"system\");\n        systemIcon.style.display = \"block\";\n        lightIcon.style.display = \"none\";\n        darkIcon.style.display = \"none\";\n      });\n\n      /**\n       * Handle click\n       * @param {css selector} el\n       * @param {function} callback\n       * @returns\n       */\n      function handleClick(el, callback) {\n        return el.addEventListener(\"click\", callback);\n      }\n    },\n    // Screens Tab\n    Screen_Option_Switcher: function Screen_Option_Switcher() {\n      $(\"#screen-option-switcher-btn\").on(\"click\", function () {\n        var screen_options_tab = $(\"#screen-option-switcher-btn\").is(\":checked\") ? 1 : 0;\n        WP_Adminify.ToggleSwitcher(\"screen_options_tab\", screen_options_tab);\n        if (screen_options_tab) {\n          $(\"#screen-options-link-wrap\").css(\"display\", \"none\");\n        }\n      });\n    },\n    // Help Tab\n    Help_Tab: function Help_Tab() {\n      $(\"#help-option-switcher-btn\").on(\"click\", function () {\n        var adminify_help_tab = $(\"#help-option-switcher-btn\").is(\":checked\") ? 1 : 0;\n        WP_Adminify.ToggleSwitcher(\"adminify_help_tab\", adminify_help_tab);\n        if (adminify_help_tab) {\n          $(\"#contextual-help-link-wrap\").css(\"display\", \"none\");\n        }\n      });\n    },\n    // Hide WP Links\n    Hide_WP_Links: function Hide_WP_Links() {\n      $(\"#hide-wp-links-switcher-btn\").on(\"click\", function () {\n        var hide_wp_links = $(\"#hide-wp-links-switcher-btn\").is(\":checked\") ? 1 : 0;\n        WP_Adminify.ToggleSwitcher(\"hide_wp_links\", hide_wp_links);\n      });\n    },\n    // Copy Active Plugins\n    Copy_Active_Plugins: function Copy_Active_Plugins(e) {\n      e.preventDefault();\n      $(\".adminify-copy-btn\").copyToClipboard({\n        parent: \".adminify-server-info\",\n        content: \".adminify-active-plugins-data\",\n        onSuccess: function onSuccess($element, source, selection) {\n          $(\"span\", $element).text($element.attr(\"data-text-copied\"));\n          setTimeout(function () {\n            $(\"span\", $element).text($element.attr(\"data-text\"));\n          }, 200000);\n        }\n      });\n    },\n    Dismiss_Notice: function Dismiss_Notice() {\n      $(\"div[data-dismissible] .notice-dismiss,div[data-dismissible] .adminify-notice-dismiss, div[data-dismissible] .dismiss-this\").on(\"click\", function (event) {\n        event.preventDefault();\n        var $this = $(this);\n        var attr_value, option_name, dismissible_length, data;\n        attr_value = $this.closest(\"div[data-dismissible]\").attr(\"data-dismissible\").split(\"-\");\n\n        // remove the dismissible length from the attribute value and rejoin the array.\n        dismissible_length = attr_value.pop();\n        option_name = attr_value.join(\"-\");\n        data = {\n          action: \"adminify_dismiss_admin_notice\",\n          option_name: option_name,\n          dismissible_length: dismissible_length,\n          notice_nonce: WPAdminify.notice_nonce\n        };\n\n        // We can also pass the url value separately from ajaxurl for front end AJAX implementations\n        $.post(WPAdminify.ajax_url, data);\n        $this.closest(\"div[data-dismissible]\").hide(\"slow\");\n      });\n    },\n    animateCSS: function animateCSS(element, animation) {\n      var prefix = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : \"animate__\";\n      // We create a Promise and return it\n      new Promise(function (resolve, reject) {\n        var animationName = \"\".concat(prefix).concat(animation);\n        var node = document.querySelector(element);\n        node.classList.add(\"\".concat(prefix, \"animated\"), animationName);\n\n        // When the animation ends, we clean the classes and resolve the Promise\n        function handleAnimationEnd(event) {\n          event.stopPropagation();\n          node.classList.remove(\"\".concat(prefix, \"animated\"), animationName);\n          resolve(\"Animation ended\");\n        }\n        node.addEventListener(\"animationend\", handleAnimationEnd, {\n          once: true\n        });\n      });\n    },\n    VersionRollback: function VersionRollback() {\n      $(\"select.wp-adminify-rollback-select\").on(\"change\", function () {\n        var $this = $(this),\n          $rollbackButton = $this.next(\".wp-adminify-rollback-button\"),\n          placeholderText = $rollbackButton.data(\"placeholder-text\"),\n          placeholderUrl = $rollbackButton.data(\"placeholder-url\");\n        $rollbackButton.html(placeholderText.replace(\"{VERSION}\", $this.val()));\n        $rollbackButton.attr(\"href\", placeholderUrl.replace(\"VERSION\", $this.val()));\n      }).trigger(\"change\");\n      $(\"body\").removeClass(\"wp-adminify--popup-show\");\n      $(\".wp-adminify-rollback-button\").on(\"click\", function (event) {\n        event.preventDefault();\n        var $this = $(this);\n        $(\"body\").addClass(\"wp-adminify--popup-show\");\n        $(\".wp-adminify-dialog-ok\").on(\"click\", function (event) {\n          event.preventDefault();\n          location.href = $this.attr(\"href\");\n        });\n      });\n    }\n  };\n\n  // Documents Loaded\n  $(function () {\n    // Extra space appears on Folder Widget in Horizontal Menu mode\n    var hmenuHeight = $(\".wp-adminify-horizontal-menu\").height();\n    $(\".wp-adminify.horizontal-menu.has-folder-options .wp-adminify--folder-widget\").css(\"top\", hmenuHeight * 1.05);\n    function fixClasses() {\n      var width = $(window).innerWidth();\n      if (width <= 767) {\n        if ($(\"body\").hasClass(\"adminify-ui\")) {\n          $(\"body\").removeClass(\"folded auto-fold\");\n        } else {\n          $(\"body\").removeClass(\"folded\");\n        }\n      }\n      if (width <= 1023 && width > 767) {\n        $(\"body\").addClass(\"folded\");\n      }\n    }\n    fixClasses();\n    $(window).on(\"resize\", function () {\n      fixClasses();\n    });\n\n    // Presets\n    var presets = [\"preset3\", \"preset4\", \"preset5\", \"preset6\", \"preset7\", \"preset8\", \"preset9\", \"custom\"];\n\n    // Not PRO and With Adminify UI\n    if (!isPro && !!isAdminifyUI) {\n      waitForElm(\"#frame-adminify-app--iframe\").then(function (elm) {\n        elm.contentWindow.onload = function (event) {\n          var __iFrameDOM = $(\"#frame-adminify-app--iframe\").contents();\n          WP_Adminify.PresetProNoticeWithIframe(__iFrameDOM, presets);\n          WP_Adminify.ProNoticeWithIframe(__iFrameDOM);\n        };\n      });\n    }\n\n    // Not PRO and Without Adminify UI\n    if (!isPro && !isAdminifyUI) {\n      WP_Adminify.PresetProNotice(presets);\n      WP_Adminify.ProNotice();\n    }\n\n    // Without Adminify UI\n    if (!isAdminifyUI) {\n      WP_Adminify.Color_Mode_Switcher();\n    }\n\n    // WP_Adminify.ToggleSwitcher();\n\n    WP_Adminify.Screen_Option_Switcher();\n    WP_Adminify.Help_Tab();\n    WP_Adminify.Hide_WP_Links();\n    WP_Adminify.Dismiss_Notice();\n    WP_Adminify.VersionRollback();\n    // WP_Adminify.Copy_Active_Plugins();\n\n    // Copy to Clipboard Section\n    (function (n) {\n      n.fn.copyToClipboard = function (e) {\n        var t = n.extend({\n          parent: \"body\",\n          content: \"\",\n          onSuccess: function onSuccess() {},\n          onError: function onError() {}\n        }, e);\n        return this.each(function () {\n          var e = n(this);\n          e.on(\"click\", function () {\n            var n = e.parents(t.parent).find(t.content);\n            var o = document.createRange();\n            var c = window.getSelection();\n            o.selectNodeContents(n[0]);\n            c.removeAllRanges();\n            c.addRange(o);\n            try {\n              var r = document.execCommand(\"copy\");\n              var a = r ? \"onSuccess\" : \"onError\";\n              t[a](e, n, c.toString());\n            } catch (i) {}\n            c.removeAllRanges();\n          });\n        });\n      };\n    })(jQuery);\n\n    // $(\".adminify-copy-btn\").on(\"click\", function (e) {\n    //     e.preventDefault();\n    //     $(\".adminify-copy-btn\").copyToClipboard({\n    //         parent: \".adminify-server-info\",\n    //         content: \".adminify-active-plugins-data\",\n    //         onSuccess: function ($element, source, selection) {\n    //             $(\"span\", $element).text($element.attr(\"data-text-copied\"));\n    //             setTimeout(function () {\n    //                 $(\"span\", $element).text($element.attr(\"data-text\"));\n    //             }, 2000);\n    //         },\n    //     });\n    // });\n\n    if ($(window).innerWidth() <= 1200) {\n      $(\".adminify-search-expand\").on(\"click\", function () {\n        $(\".top-header--search--form\").toggleClass(\"adminify-form-expand\");\n      });\n    }\n    if (WPAdminify_ThirdParty !== undefined || WPAdminify_ThirdParty != null) {\n      // betterlinks menu settings\n      if (WPAdminify_ThirdParty.better_links.active === true) {\n        var _WPAdminify_ThirdPart = WPAdminify_ThirdParty.better_links,\n          menu_name = _WPAdminify_ThirdPart.menu_name,\n          submenu_manage = _WPAdminify_ThirdPart.submenu_manage,\n          submenu_name = _WPAdminify_ThirdPart.submenu_name,\n          submenu_settings = _WPAdminify_ThirdPart.submenu_settings;\n        if ($(\"body\").hasClass(\"toplevel_page_betterlinks\")) {\n          if (menu_name) {\n            $(\".toplevel_page_betterlinks #toplevel_page_betterlinks .wp-menu-name\").text(menu_name);\n          }\n          setTimeout(function () {\n            if (menu_name) {\n              $(\".toplevel_page_betterlinks #toplevel_page_betterlinks .wp-submenu .wp-submenu-head\").text(menu_name);\n            }\n            if (submenu_manage) {\n              $(\".toplevel_page_betterlinks #toplevel_page_betterlinks .wp-submenu li:nth-child(2) a\").text(submenu_manage);\n            }\n            if (submenu_name) {\n              $(\".toplevel_page_betterlinks #toplevel_page_betterlinks .wp-submenu li:nth-child(3) a\").text(submenu_name);\n            }\n            if (submenu_settings) {\n              $(\".toplevel_page_betterlinks #toplevel_page_betterlinks .wp-submenu li:nth-child(4) a\").text(submenu_settings);\n            }\n          }, 500);\n        }\n      }\n    }\n  });\n\n  // Click with Reload\n  $(\".adminify-toolbar .adminify-top-menu-my-sites a\").on(\"click\", function (event) {\n    setTimeout(function () {\n      window.location.reload();\n    }, 100);\n  });\n})(jQuery);\nfunction waitForElm(selector) {\n  return new Promise(function (resolve) {\n    if (document.querySelector(selector)) {\n      return resolve(document.querySelector(selector));\n    }\n    var observer = new MutationObserver(function (mutations) {\n      if (document.querySelector(selector)) {\n        observer.disconnect();\n        resolve(document.querySelector(selector));\n      }\n    });\n\n    // If you get \"parameter 1 is not of type 'Node'\" error, see https://stackoverflow.com/a/77855838/492336\n    observer.observe(document.body, {\n      childList: true,\n      subtree: true\n    });\n  });\n}\n\n//# sourceURL=webpack://adminify/./dev/admin/wp-adminify.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./dev/admin/wp-adminify.js"](0, __webpack_exports__, __webpack_require__);
/******/ 	
/******/ })()
;