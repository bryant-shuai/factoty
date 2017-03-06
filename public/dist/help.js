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
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _help = __webpack_require__(1);

	var _help2 = _interopRequireDefault(_help);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	new Vue({
	  el: '#main',
	  replace: false,
	  template: '#tpl_main',
	  components: { App: _help2.default }
	});

/***/ },
/* 1 */
/***/ function(module, exports, __webpack_require__) {

	var __vue_script__, __vue_template__
	__vue_script__ = __webpack_require__(2)
	__vue_template__ = __webpack_require__(3)
	module.exports = __vue_script__ || {}
	if (module.exports.__esModule) module.exports = module.exports.default
	if (__vue_template__) { (typeof module.exports === "function" ? module.exports.options : module.exports).template = __vue_template__ }
	if (false) {(function () {  module.hot.accept()
	  var hotAPI = require("vue-hot-reload-api")
	  hotAPI.install(require("vue"), true)
	  if (!hotAPI.compatible) return
	  var id = "/Users/song/work/client_v2/njzs-wechat/node/src/help.vue"
	  if (!module.hot.data) {
	    hotAPI.createRecord(id, module.exports)
	  } else {
	    hotAPI.update(id, module.exports, __vue_template__)
	  }
	})()}

/***/ },
/* 2 */
/***/ function(module, exports) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	// <template lang="jade">
	// extends ./components/page.jade
	//
	// block content
	//   div(v-cloak class="message")
	//     div(style="margin-top:30px;")
	//       b 订货颜色含义
	//       div(style="margin-left:20px;")
	//         p 绿色，订货成功；
	//         p 白色，未订货；
	//         p 灰色，提交中。
	//
	// </template>
	//
	//
	//
	// <script>

	var tag = 'help';
	exports.default = $$.Aaa({
	  NAME: tag,
	  APP: true,
	  TAG: tag,
	  EVENT: ['A'],
	  components: {},
	  data: function data() {
	    return {
	      'msg': 'Hello  ' + new Date().getTime() + ' from vue-loader!',
	      'a': 'va',
	      'b': 'vb'
	    };
	  },

	  methods: {},

	  ready: function ready() {
	    $$.utils.toggleNav('help');
	  }

	});
	// </script>
	//
	//
	//
	//
	//

/***/ },
/* 3 */
/***/ function(module, exports) {

	module.exports = "<div id=\"header\" style=\"position:fixed;width:100%;z-index:99;\" onclick=\"$$.utils.showNav();\"><div style=\"text-align:center;background:#FFF;border-bottom:1px solid #F1F1F1;\"><img src=\"/img/logo.png\" height=\"40\" style=\"\"/></div><div id=\"nav\" style=\"z-order:1;width:100%;;height:3000px;\"><div style=\"position:absolute;top:0;z-index:-1;width:100%;background:#000;height:3000px;opacity:0.5;;\"></div><div><a href=\"#/order\"> <div id=\"order\" style=\"background:#64BD77\" class=\"menu-item\">下订单</div></a><a href=\"#/orders\"><div id=\"orders\" style=\"background:#4BC0C1\" class=\"menu-item\">订单历史</div></a><a href=\"#/log_account\"><div id=\"log_account\" style=\"background:#FFC332\" class=\"menu-item\"> 余额变动</div></a><a href=\"#/log_returnback\"><div id=\"log_account\" style=\"background:#64BD77\" class=\"menu-item\"> 返款记录</div></a><a href=\"#/help\"> <div id=\"help\" style=\"background:#FB6B5B\" class=\"menu-item\">帮助</div></a><a href=\"#/login\"><div id=\"login\" style=\"background:#41586E\" class=\"menu-item\"> 登录/注销</div></a></div></div></div><div style=\"position:relative;clear:both;border:0px solid #000000;margin:0px 10px 0 10px;padding-top:20px;\"><div style=\"width:100%;clear:both;height:40px;\"></div><div v-cloak=\"v-cloak\" class=\"message\"><div style=\"margin-top:30px;\"><b>订货颜色含义</b><div style=\"margin-left:20px;\"> <p>绿色，订货成功；</p><p>白色，未订货；</p><p>灰色，提交中。</p></div></div></div><div style=\"width:100%;clear:both;height:50px;\"></div></div>";

/***/ }
/******/ ]);