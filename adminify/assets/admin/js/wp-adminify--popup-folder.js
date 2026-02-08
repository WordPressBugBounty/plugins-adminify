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

/***/ "./dev/admin/modules/folder/modal-popup-folder.js":
/*!********************************************************!*\
  !*** ./dev/admin/modules/folder/modal-popup-folder.js ***!
  \********************************************************/
/***/ (() => {

eval("var $ = window.jQuery;\n\n// Track React roots for cleanup - exposed globally for cross-module access\nwindow.wpAdminifyModalRoots = window.wpAdminifyModalRoots || new Map();\n\n// Initialize when DOM is ready\n$(function () {\n  initMediaModalSidebar();\n});\n\n/**\n * Add folder container to the media modal menu\n */\nfunction addCustomDivToMediaModal() {\n  // Skip if already injected\n  if ($('.media-modal .wp-adminify--modal-folder-container').length) {\n    return;\n  }\n  var timestamp = Date.now();\n  var uniqueId = 'wp-adminify--modal-folder-container-' + timestamp;\n  var appId = 'wp-adminify--folder-app-modal-' + timestamp;\n  var newDiv = $('<div>', {\n    id: uniqueId,\n    \"class\": 'wp-adminify--modal-folder-container'\n  });\n  var appDivEle = $('<div>', {\n    id: appId\n  });\n  newDiv.append(appDivEle);\n\n  // Try #menu-item-library first (Elementor, block editor modals)\n  var menuItemLibrary = $('.media-modal .media-frame-menu .media-menu #menu-item-library');\n  if (menuItemLibrary.length) {\n    menuItemLibrary.before(newDiv);\n  } else {\n    // Fallback: append to .media-menu (Customizer modal)\n    var mediaMenu = $('.media-modal .media-frame-menu .media-menu');\n    if (mediaMenu.length) {\n      mediaMenu.append(newDiv);\n    } else {\n      return;\n    }\n  }\n\n  // Initialize React folder module after DOM is added\n  setTimeout(function () {\n    if (typeof window.wpAdminifyInitFolderModule === 'function') {\n      window.wpAdminifyInitFolderModule(true, appId);\n    }\n  }, 50);\n}\n\n/**\n * Initialize media modal folder sidebar\n * Extends wp.media.view.AttachmentsBrowser and Modal to inject folder sidebar\n */\nvar initMediaModalSidebar = function initMediaModalSidebar() {\n  // Only run if wp.media is available (block editor or media modal context)\n  if (typeof wp === 'undefined' || !wp.media || !wp.media.view) {\n    return;\n  }\n  var initialData = window.wp_adminify__folder_data;\n  if (!initialData || !initialData.folders) {\n    return;\n  }\n\n  // Store reference to original AttachmentsBrowser\n  var AttachmentsBrowser = wp.media.view.AttachmentsBrowser;\n\n  // Extend AttachmentsBrowser to add folder sidebar\n  wp.media.view.AttachmentsBrowser = AttachmentsBrowser.extend({\n    createSidebar: function createSidebar() {\n      // Call original createSidebar\n      AttachmentsBrowser.prototype.createSidebar.apply(this, arguments);\n\n      // Only inject sidebar if we're in a modal context\n      var isInModal = this.controller && this.controller.$el && this.controller.$el.hasClass('wp-core-ui');\n      if (!isInModal) {\n        return;\n      }\n      setTimeout(function () {\n        addCustomDivToMediaModal();\n      }, 300);\n    }\n  });\n\n  // Patch Modal.prototype.open directly to detect every modal open (including reopens)\n  var originalModalOpen = wp.media.view.Modal.prototype.open;\n  wp.media.view.Modal.prototype.open = function () {\n    var result = originalModalOpen.apply(this, arguments);\n    setTimeout(function () {\n      addCustomDivToMediaModal();\n    }, 300);\n    return result;\n  };\n\n  // Fallback: periodically check for visible modals missing the folder container\n  // Covers cases where modal reopen doesn't go through Modal.prototype.open\n  // (e.g. customizer reuses cached frames and toggles visibility directly)\n  setInterval(function () {\n    var modal = document.querySelector('.media-modal');\n    if (modal && modal.offsetParent !== null && !modal.querySelector('.wp-adminify--modal-folder-container')) {\n      addCustomDivToMediaModal();\n    }\n  }, 1000);\n\n  // Listen for modal close to cleanup\n  $(document).on('click', '.media-modal-close', function () {\n    // Cleanup all React roots when modal is closed\n    var roots = window.wpAdminifyModalRoots;\n    if (roots && roots.size > 0) {\n      roots.forEach(function (root, id) {\n        try {\n          root.unmount();\n          // Remove the container element from DOM\n          var container = document.getElementById(id);\n          if (container) {\n            container.remove();\n          }\n        } catch (e) {\n          console.warn('Error unmounting React root:', e);\n        }\n      });\n      roots.clear();\n    }\n\n    // Also remove any orphaned containers\n    $('.wp-adminify--modal-folder-container').remove();\n  });\n};\n\n//# sourceURL=webpack://adminify/./dev/admin/modules/folder/modal-popup-folder.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./dev/admin/modules/folder/modal-popup-folder.js"]();
/******/ 	
/******/ })()
;