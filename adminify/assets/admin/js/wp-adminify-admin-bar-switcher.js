/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./dev/admin/admin-bar-switcher.js":
/*!*****************************************!*\
  !*** ./dev/admin/admin-bar-switcher.js ***!
  \*****************************************/
/***/ (() => {

eval("/**\n * Admin bar Light/Dark/System switcher - standalone build.\n *\n * `wp-adminify.js` drives this widget everywhere Adminify's main bundle loads,\n * but that bundle is skipped on customize.php by design\n * (Assets::should_skip_adminify_scripts). WordPress 7.1 renders the admin bar\n * there, so the switcher node arrives without the code behind it. This is the\n * same widget with no dependencies: no jQuery, and no Adminify globals beyond\n * the dark-mode bundle and the localized ajax data.\n */\n(function () {\n  var MODES = [\"light\", \"dark\", \"system\"];\n  var prefersDark = function prefersDark() {\n    return !!window.matchMedia && window.matchMedia(\"(prefers-color-scheme: dark)\").matches;\n  };\n\n  /**\n   * The dark-mode bundle only ships up front to users already in dark mode, so\n   * switching to dark from a light page may have to fetch it first.\n   * `PXLBSADMINIFYDarkMode` (printed by Assets::header_scripts) handles that\n   * and is a no-op once the bundle is loaded; `AdminifyDarkMode` is the bundle\n   * itself, which needs the brightness passed in.\n   */\n  var paintDarkMode = function paintDarkMode(enable) {\n    document.body.classList.toggle(\"adminify-dark-mode\", enable);\n    document.body.classList.toggle(\"adminify-light-mode\", !enable);\n    if (window.PXLBSADMINIFYDarkMode) {\n      if (enable) {\n        window.PXLBSADMINIFYDarkMode.enable();\n      } else {\n        window.PXLBSADMINIFYDarkMode.disable();\n      }\n      return;\n    }\n    if (window.AdminifyDarkMode) {\n      if (enable) {\n        window.AdminifyDarkMode.enable({\n          brightness: 120\n        });\n      } else {\n        window.AdminifyDarkMode.disable();\n      }\n    }\n  };\n\n  // Same endpoint and payload as WP_Adminify.ToggleSwitcher(), over fetch so\n  // the widget does not drag jQuery onto a screen that may not have it.\n  var persistMode = function persistMode(mode) {\n    var data = window.PXLBSADMINIFY_ADMINBAR;\n    if (!data || !data.ajax_url) return;\n    window.fetch(data.ajax_url, {\n      method: \"POST\",\n      credentials: \"same-origin\",\n      body: new URLSearchParams({\n        action: \"pxlbsadminify_color_mode\",\n        security: data.security_nonce || \"\",\n        key: \"color_mode\",\n        value: mode\n      })\n    });\n  };\n  var init = function init() {\n    var wrapper = document.getElementById(\"wp-adminify-color-mode-wrapper\");\n    if (!wrapper) return;\n    var modeIcon = wrapper.querySelector(\".mode-icon\");\n    var dropdown = wrapper.querySelector(\".light-dark-dropdown\");\n    if (!modeIcon || !dropdown) return;\n    var openDropdown = function openDropdown() {\n      dropdown.style.visibility = \"visible\";\n      dropdown.style.opacity = \"0.9999\";\n      dropdown.style.transform = \"none\";\n    };\n\n    // Back to the stylesheet's hidden state - the rules carry the transition.\n    var closeDropdown = function closeDropdown() {\n      return dropdown.removeAttribute(\"style\");\n    };\n    var setMode = function setMode(mode) {\n      persistMode(mode);\n      paintDarkMode(mode === \"system\" ? prefersDark() : mode === \"dark\");\n\n      // The active-mode class is what decides which of the three glyphs\n      // shows, exactly as AdminBar::dark_mode_switcher_dom() prints it.\n      modeIcon.className = \"mode-icon adminify-color-mode-\" + mode + \"-active\";\n      closeDropdown();\n    };\n    modeIcon.addEventListener(\"click\", function (event) {\n      event.stopPropagation();\n      openDropdown();\n    });\n    MODES.forEach(function (mode) {\n      var button = dropdown.querySelector(\".\" + mode);\n      if (button) button.addEventListener(\"click\", function () {\n        return setMode(mode);\n      });\n    });\n    document.addEventListener(\"click\", function (event) {\n      if (!wrapper.contains(event.target)) closeDropdown();\n    });\n  };\n  if (document.readyState === \"loading\") {\n    document.addEventListener(\"DOMContentLoaded\", init);\n  } else {\n    init();\n  }\n})();\n\n//# sourceURL=webpack://adminify/./dev/admin/admin-bar-switcher.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./dev/admin/admin-bar-switcher.js"]();
/******/ 	
/******/ })()
;