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

/***/ "./dev/admin/icon-picker-init.js":
/*!***************************************!*\
  !*** ./dev/admin/icon-picker-init.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _shared_icon_libraries__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../shared/icon-libraries */ \"./dev/shared/icon-libraries.js\");\n// JS codes by WP Adminify\n\n\n\n/**\n * Boots the shared WP Adminify icon picker on screens outside the Menu Editor\n * that render an `adminify_icon` options-framework field — currently the\n * Dashboard Widget settings page and the Pro Admin Pages editor.\n *\n * One init covers every field on the page, including ones a repeater or group\n * clones later, because the picker binds its handlers to body and resolves the\n * owning field with .closest('.icon-picker-wrap').\n */\njQuery(function ($) {\n  \"use strict\";\n\n  if (typeof $.fn.ai_icon_picker !== \"function\") {\n    return;\n  }\n  var $wraps = $(\".icon-picker-wrap\");\n  if (!$wraps.length) {\n    return;\n  }\n  var settings = window.PXLBSADMINIFY_ICON_PICKER || {};\n\n  // Copy so the removal below cannot mutate the shared object.\n  var libraries = Object.assign({}, _shared_icon_libraries__WEBPACK_IMPORTED_MODULE_0__.ICON_LIBRARIES);\n\n  // Honour the Assets Manager: a library whose stylesheet the user disabled\n  // would render as empty boxes, so drop it from the picker as well. Same\n  // mapping the Menu Editor applies.\n  var libraryHandles = {\n    \"wp-adminify-simple-line-icons\": \"Simple Line Icons\"\n  };\n  var disabled = settings.assets_manager || [];\n  Object.keys(libraryHandles).forEach(function (handle) {\n    if (disabled && disabled.length && disabled.indexOf(handle) !== -1) {\n      delete libraries[libraryHandles[handle]];\n    }\n  });\n  $wraps.ai_icon_picker({\n    valueSelector: \"input.adminify-icon-value\",\n    iconLibrary: libraries\n  });\n});\n\n//# sourceURL=webpack://adminify/./dev/admin/icon-picker-init.js?");

/***/ }),

/***/ "./dev/shared/icon-libraries.js":
/*!**************************************!*\
  !*** ./dev/shared/icon-libraries.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   ICON_LIBRARIES: () => (/* binding */ ICON_LIBRARIES)\n/* harmony export */ });\n/**\n * Icon libraries shared by every WP Adminify icon picker.\n *\n * The picker itself (assets/vendors/adminify-icon-picker) only ships Dashicons.\n * Anything else is handed to it through the `iconLibrary` option, so the list\n * lives here rather than inside a single consumer's bundle.\n */\nvar ICON_LIBRARIES = {\n  \"Simple Line Icons\": {\n    \"\": {\n      prefix: \"icon-\",\n      \"list-icon\": \"icon-heart\",\n      \"icon-style\": \"simple-line-icons\",\n      icons: [\"icon-user\", \"icon-people\", \"icon-user-female\", \"icon-user-follow\", \"icon-user-following\", \"icon-user-unfollow\", \"icon-login\", \"icon-logout\", \"icon-emotsmile\", \"icon-phone\", \"icon-call-end\", \"icon-call-in\", \"icon-call-out\", \"icon-map\", \"icon-location-pin\", \"icon-direction\", \"icon-directions\", \"icon-compass\", \"icon-layers\", \"icon-menu\", \"icon-list\", \"icon-options-vertical\", \"icon-options\", \"icon-arrow-down\", \"icon-arrow-left\", \"icon-arrow-right\", \"icon-arrow-up\", \"icon-arrow-up-circle\", \"icon-arrow-left-circle\", \"icon-arrow-right-circle\", \"icon-arrow-down-circle\", \"icon-check\", \"icon-clock\", \"icon-plus\", \"icon-minus\", \"icon-close\", \"icon-event\", \"icon-exclamation\", \"icon-organization\", \"icon-trophy\", \"icon-screen-smartphone\", \"icon-screen-desktop\", \"icon-plane\", \"icon-notebook\", \"icon-mustache\", \"icon-mouse\", \"icon-magnet\", \"icon-energy\", \"icon-disc\", \"icon-cursor\", \"icon-cursor-move\", \"icon-crop\", \"icon-chemistry\", \"icon-speedometer\", \"icon-shield\", \"icon-screen-tablet\", \"icon-magic-wand\", \"icon-hourglass\", \"icon-graduation\", \"icon-ghost\", \"icon-game-controller\", \"icon-fire\", \"icon-eyeglass\", \"icon-envelope-open\", \"icon-envelope-letter\", \"icon-bell\", \"icon-badge\", \"icon-anchor\", \"icon-wallet\", \"icon-vector\", \"icon-speech\", \"icon-puzzle\", \"icon-printer\", \"icon-present\", \"icon-playlist\", \"icon-pin\", \"icon-picture\", \"icon-handbag\", \"icon-globe-alt\", \"icon-globe\", \"icon-folder-alt\", \"icon-folder\", \"icon-film\", \"icon-feed\", \"icon-drop\", \"icon-drawer\", \"icon-docs\", \"icon-doc\", \"icon-diamond\", \"icon-cup\", \"icon-calculator\", \"icon-bubbles\", \"icon-briefcase\", \"icon-book-open\", \"icon-basket-loaded\", \"icon-basket\", \"icon-bag\", \"icon-action-undo\", \"icon-action-redo\", \"icon-wrench\", \"icon-umbrella\", \"icon-trash\", \"icon-tag\", \"icon-support\", \"icon-frame\", \"icon-size-fullscreen\", \"icon-size-actual\", \"icon-shuffle\", \"icon-share-alt\", \"icon-share\", \"icon-rocket\", \"icon-question\", \"icon-pie-chart\", \"icon-pencil\", \"icon-note\", \"icon-loop\", \"icon-home\", \"icon-grid\", \"icon-graph\", \"icon-microphone\", \"icon-music-tone-alt\", \"icon-music-tone\", \"icon-earphones-alt\", \"icon-earphones\", \"icon-equalizer\", \"icon-like\", \"icon-dislike\", \"icon-control-start\", \"icon-control-rewind\", \"icon-control-play\", \"icon-control-pause\", \"icon-control-forward\", \"icon-control-end\", \"icon-volume-1\", \"icon-volume-2\", \"icon-volume-off\", \"icon-calendar\", \"icon-bulb\", \"icon-chart\", \"icon-ban\", \"icon-bubble\", \"icon-camrecorder\", \"icon-camera\", \"icon-cloud-download\", \"icon-cloud-upload\", \"icon-envelope\", \"icon-eye\", \"icon-flag\", \"icon-heart\", \"icon-info\", \"icon-key\", \"icon-link\", \"icon-lock\", \"icon-lock-open\", \"icon-magnifier\", \"icon-magnifier-add\", \"icon-magnifier-remove\", \"icon-paper-clip\", \"icon-paper-plane\", \"icon-power\", \"icon-refresh\", \"icon-reload\", \"icon-settings\", \"icon-star\", \"icon-symbol-female\", \"icon-symbol-male\", \"icon-target\", \"icon-credit-card\", \"icon-paypal\", \"icon-social-tumblr\", \"icon-social-twitter\", \"icon-social-facebook\", \"icon-social-instagram\", \"icon-social-linkedin\", \"icon-social-pinterest\", \"icon-social-github\", \"icon-social-google\", \"icon-social-reddit\", \"icon-social-skype\", \"icon-social-dribbble\", \"icon-social-behance\", \"icon-social-foursqare\", \"icon-social-soundcloud\", \"icon-social-spotify\", \"icon-social-stumbleupon\", \"icon-social-youtube\", \"icon-social-dropbox\", \"icon-social-vkontakte\", \"icon-social-steam\"]\n    }\n  }\n};\n\n//# sourceURL=webpack://adminify/./dev/shared/icon-libraries.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
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
/******/ 	var __webpack_exports__ = __webpack_require__("./dev/admin/icon-picker-init.js");
/******/ 	
/******/ })()
;