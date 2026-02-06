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

eval("var $ = window.jQuery;\n\n// Track React roots for cleanup - exposed globally for cross-module access\nwindow.wpAdminifyModalRoots = window.wpAdminifyModalRoots || new Map();\n\n// Initialize when DOM is ready\n$(function () {\n  initMediaModalSidebar();\n});\n\n/**\n * Initialize media modal folder sidebar\n * Extends wp.media.view.AttachmentsBrowser to inject folder sidebar in modal\n */\nvar initMediaModalSidebar = function initMediaModalSidebar() {\n  // Only run if wp.media is available (block editor or media modal context)\n  if (typeof wp === 'undefined' || !wp.media || !wp.media.view) {\n    return;\n  }\n  var initialData = window.wp_adminify__folder_data;\n  if (!initialData || !initialData.folders) {\n    return;\n  }\n\n  // Store reference to original AttachmentsBrowser\n  var AttachmentsBrowser = wp.media.view.AttachmentsBrowser;\n\n  // Extend AttachmentsBrowser to add folder sidebar\n  wp.media.view.AttachmentsBrowser = AttachmentsBrowser.extend({\n    createSidebar: function createSidebar() {\n      // Call original createSidebar\n      AttachmentsBrowser.prototype.createSidebar.apply(this, arguments);\n\n      // Only inject sidebar if we're in a modal context\n      var isInModal = this.controller && this.controller.$el && this.controller.$el.hasClass('wp-core-ui');\n      if (!isInModal) {\n        return;\n      }\n\n      // Listen for when media modals are opened\n      if (typeof wp !== 'undefined' && wp.media && wp.media.view) {\n        // Hook into the modal rendering\n        // wp.media.view.Modal.prototype.on('open', function() {\n\n        // Wait a bit for the modal to fully render\n        setTimeout(function () {\n          addCustomDivToMediaModal();\n        }, 300);\n        // });\n      }\n      function addCustomDivToMediaModal() {\n        // Look for the media frame menu in the current modal\n        var mediaFrameMenu = $('.media-modal .media-frame-menu .media-menu #menu-item-library');\n        if (mediaFrameMenu.length) {\n          // Check if we already added our div (check siblings since we use .before())\n          var existingContainer = mediaFrameMenu.siblings('.wp-adminify--modal-folder-container');\n          if (existingContainer.length === 0) {\n            var uniqueId = 'wp-adminify--modal-folder-container-' + Date.now();\n            var newDiv = $('<div>', {\n              id: uniqueId,\n              \"class\": 'wp-adminify--modal-folder-container'\n            });\n            var appDivEle = $('<div>', {\n              id: \"wp-adminify--folder-app\"\n            });\n\n            // Append the app div to container first\n            newDiv.append(appDivEle);\n            // Add container before the menu item\n            mediaFrameMenu.before(newDiv);\n            // console.log('Added custom div to media modal');\n\n            // Initialize React folder module after DOM is added\n            // Use setTimeout to ensure DOM is fully updated\n            setTimeout(function () {\n              if (typeof window.wpAdminifyInitFolderModule === 'function') {\n                window.wpAdminifyInitFolderModule(true);\n              }\n            }, 50);\n          }\n        }\n      }\n    }\n  });\n\n  // Listen for modal close to cleanup\n  $(document).on('click', '.media-modal-close', function () {\n    // Cleanup all React roots when modal is closed\n    var roots = window.wpAdminifyModalRoots;\n    if (roots && roots.size > 0) {\n      roots.forEach(function (root, id) {\n        try {\n          root.unmount();\n          // Remove the container element from DOM\n          var container = document.getElementById(id);\n          if (container) {\n            container.remove();\n          }\n        } catch (e) {\n          console.warn('Error unmounting React root:', e);\n        }\n      });\n      roots.clear();\n    }\n\n    // Also remove any orphaned containers\n    $('.wp-adminify--modal-folder-container').remove();\n  });\n};\n\n//# sourceURL=webpack://adminify/./dev/admin/modules/folder/modal-popup-folder.js?");

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