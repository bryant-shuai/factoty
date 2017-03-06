/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "./dist/";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports) {

	'use strict';

	// page('/', '/photos/0');       //指向另一个url
	// page('/photos/:page', photos) //传参
	page.base('/main.php#');
	page('/main.php', createRoute('order'));
	page('/', createRoute('order'));
	page('/order', createRoute('order'));
	page('/orders', createRoute('orders'));
	page('/log_account', createRoute('log_account'));
	page('/log_returnback', createRoute('log_returnback'));
	page('/help', createRoute('help'));
	page('/login', createRoute('login'));
	page('*', notfoundPage);
	page();

	function createRoute(name) {
	  return function () {
	    $$.loadJs(name, function () {
	      $$.data.loading = true;
	      // $$.data.args = arguments
	      // alert($$.data.loading)
	    });
	  };
	}

	function defaultPage() {
	  // $$.data.loading = true
	  // $('#main').html($('#tpl_main').html())
	  loadJs('test', function () {
	    $$.data.loading = false;
	  });
	}

	function notfoundPage() {
	  loadJs('app', function () {
	    $$.data.loading = true;
	  });

	  // setTimeout(function(){
	  //   $('#main').html($('#tpl_main').html())
	  //   loadJs('app',function(){
	  //     $$.data.loading = false
	  //   })

	  // },300)
	}

/***/ }
/******/ ]);