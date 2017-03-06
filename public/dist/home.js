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
/******/ ({

/***/ 0:
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _home = __webpack_require__(16);

	var _home2 = _interopRequireDefault(_home);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	new Vue({
	  el: '#main',
	  replace: false,
	  template: '#tpl_main',
	  components: { App: _home2.default }
	});

/***/ },

/***/ 16:
/***/ function(module, exports, __webpack_require__) {

	var __vue_script__, __vue_template__
	__vue_script__ = __webpack_require__(17)
	__vue_template__ = __webpack_require__(18)
	module.exports = __vue_script__ || {}
	if (module.exports.__esModule) module.exports = module.exports.default
	if (__vue_template__) { (typeof module.exports === "function" ? module.exports.options : module.exports).template = __vue_template__ }
	if (false) {(function () {  module.hot.accept()
	  var hotAPI = require("vue-hot-reload-api")
	  hotAPI.install(require("vue"), true)
	  if (!hotAPI.compatible) return
	  var id = "/private/var/www/njzs_vue/node/src/home.vue"
	  if (!module.hot.data) {
	    hotAPI.createRecord(id, module.exports)
	  } else {
	    hotAPI.update(id, module.exports, __vue_template__)
	  }
	})()}

/***/ },

/***/ 17:
/***/ function(module, exports) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	// <template lang="jade">
	// extends ./components/page.jade
	//
	// block header
	//   div(style="width:100%;background:#-FF9900;")
	//     div(style="position:absolute;top:38px;left:20px;")
	//       a(href="#/order" style="margin:0 5px 0 5px;") 下订单
	//       a(href="#/orders" style="margin:0 5px 0 5px;") 订单历史
	//       a(href="#/help" style="margin:0 5px 0 5px;") 帮助
	//
	//       a(href="#/home" style="margin:0 5px 0 5px;") home
	//       a(href="#/cate" style="margin:0 5px 0 5px;") cate
	//       a(href="#/user_home" style="margin:0 5px 0 5px;") user_home
	//   page-header-submit
	//
	// block content
	//   h1 Article Blue2 content
	//   div(v-cloak @click="clickAbc" class="message")
	//     span {{{ msg }}}
	//
	// </template>
	//
	//
	//
	// <script>

	exports.default = $$.Aaa({
	  NAME: 'name',
	  TAG: 'tag',
	  EVENT: ['A'],
	  components: {},
	  data: function data() {
	    return {
	      'msg': 'Hello  ' + new Date().getTime() + ' from vue-loader!',
	      'a': 'va',
	      'b': 'vb'
	    };
	  },

	  methods: {
	    clickAbc: function clickAbc() {
	      $$.event.pub('A', 'arg:a');
	      this.msg = '<h1>AAA</h1><br>' + new Date().getTime();
	      console.dir(this);
	    },

	    hd_A: function hd_A(args) {
	      var self = this;
	      setTimeout(function () {
	        self.msg = '<h1>BBB</h1><br>' + new Date().getTime();
	      }, 1000);
	    }

	  }

	});
	// </script>
	//
	//
	//
	//

/***/ },

/***/ 18:
/***/ function(module, exports) {

	module.exports = "<div style=\"background:green;position:fixed;top:0;left:0;width:100%;height:70px;\"><div style=\"width:100%;background:#-FF9900;\"><div style=\"position:absolute;top:38px;left:20px;\"><a href=\"#/order\" style=\"margin:0 5px 0 5px;\">下订单</a><a href=\"#/orders\" style=\"margin:0 5px 0 5px;\">订单历史</a><a href=\"#/help\" style=\"margin:0 5px 0 5px;\">帮助</a></div></div><div style=\"width:100%;background:#-FF9900;\"><div style=\"position:absolute;top:38px;left:20px;\"><a href=\"#/order\" style=\"margin:0 5px 0 5px;\">下订单</a><a href=\"#/orders\" style=\"margin:0 5px 0 5px;\">订单历史</a><a href=\"#/help\" style=\"margin:0 5px 0 5px;\">帮助</a><a href=\"#/home\" style=\"margin:0 5px 0 5px;\">home</a><a href=\"#/cate\" style=\"margin:0 5px 0 5px;\">cate</a><a href=\"#/user_home\" style=\"margin:0 5px 0 5px;\">user_home</a></div></div><page-header-submit></page-header-submit></div><div style=\"margin-top:70px;border:1px solid #000000;\"><h1>Article Blue2 content</h1><div v-cloak=\"v-cloak\" @click=\"clickAbc\" class=\"message\"> <span>{{{ msg }}}</span></div></div>";

/***/ }

/******/ });