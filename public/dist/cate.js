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

	var _cate = __webpack_require__(1);

	var _cate2 = _interopRequireDefault(_cate);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	new Vue({
	  el: '#main',
	  replace: false,
	  template: '#tpl_main',
	  components: { App: _cate2.default }
	});

/***/ },
/* 1 */
/***/ function(module, exports, __webpack_require__) {

	var __vue_script__, __vue_template__
	__vue_script__ = __webpack_require__(2)
	__vue_template__ = __webpack_require__(12)
	module.exports = __vue_script__ || {}
	if (module.exports.__esModule) module.exports = module.exports.default
	if (__vue_template__) { (typeof module.exports === "function" ? module.exports.options : module.exports).template = __vue_template__ }
	if (false) {(function () {  module.hot.accept()
	  var hotAPI = require("vue-hot-reload-api")
	  hotAPI.install(require("vue"), true)
	  if (!hotAPI.compatible) return
	  var id = "/private/var/www/njzs_vue/node/src/cate.vue"
	  if (!module.hot.data) {
	    hotAPI.createRecord(id, module.exports)
	  } else {
	    hotAPI.update(id, module.exports, __vue_template__)
	  }
	})()}

/***/ },
/* 2 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	// <template>
	//
	// <page-header></page-header>
	//
	// <div style="width:100%;background:#FF0000;clear:both;">
	// cate2
	// </div>
	//
	// <div v-cloak @click="clickAbc" class="message">{{ msg }}</div>
	//
	// <a href="#/dft">
	//   <button>
	//     cate.vue
	//   </button>
	// </a>
	//
	// <list-by-cate width="700" cate="1" :items="items"></list-by-cate>
	//
	// </template>
	//
	//
	//
	// <script>
	// var Test =

	exports.default = {
	  data: function data() {
	    return {
	      msg: 'Hello .............................. ' + new Date().getTime() + ' from vue-loader!',

	      items: [{ message: 'Foo' }, { message: 'Bar' }, { message: 'Bar2' }, { message: 'Bar3' }, { message: 'Bar4' }, { message: 'Bar5' }, { message: 'Bar6' }, { message: 'Bar7' }, { message: 'Foo' }, { message: 'Bar' }, { message: 'Bar2' }, { message: 'Bar3' }, { message: 'Bar4' }, { message: 'Bar5' }, { message: 'Bar6' }, { message: 'Bar7' }, { message: 'Bar5' }, { message: 'Bar6' }, { message: 'Bar7' }]
	    };
	  },


	  components: {
	    "PageHeader": __webpack_require__(3),
	    "ListByCate": __webpack_require__(8)
	  },

	  created: function created() {
	    // alert('ready')
	  },

	  methods: {
	    clickAbc: function clickAbc() {
	      alert('yes!');
	    }
	  }
	};
	// </script>
	//
	//
	//
	//

/***/ },
/* 3 */
/***/ function(module, exports, __webpack_require__) {

	var __vue_script__, __vue_template__
	__webpack_require__(4)
	__vue_script__ = __webpack_require__(6)
	__vue_template__ = __webpack_require__(7)
	module.exports = __vue_script__ || {}
	if (module.exports.__esModule) module.exports = module.exports.default
	if (__vue_template__) { (typeof module.exports === "function" ? module.exports.options : module.exports).template = __vue_template__ }
	if (false) {(function () {  module.hot.accept()
	  var hotAPI = require("vue-hot-reload-api")
	  hotAPI.install(require("vue"), true)
	  if (!hotAPI.compatible) return
	  var id = "/private/var/www/njzs_vue/node/src/components/pageHeader.vue"
	  if (!module.hot.data) {
	    hotAPI.createRecord(id, module.exports)
	  } else {
	    hotAPI.update(id, module.exports, __vue_template__)
	  }
	})()}

/***/ },
/* 4 */
/***/ function(module, exports) {

	// removed by extract-text-webpack-plugin

/***/ },
/* 5 */,
/* 6 */
/***/ function(module, exports) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	// <template lang="jade">
	// extends ./layout.jade
	//
	// block green_content
	//   div(style="width:100%;background:#FF9900;")
	//     h1
	//       a(href="#/home") 头部H2
	//     div(style="position:absolute;top:38px;left:200px;")
	//       a(href="#/home") home
	//       a(href="#/cate") cate
	//       a(href="#/user_home") user_home
	//   div(style="position:absolute;top:38px;left:400px;")
	//    | {{ user.name }} {{ msg }}
	//
	// block blue_content
	//   h1 Article Blue2 content
	//
	// </template>
	//
	//
	//
	//
	//
	//
	//
	//
	//
	// <script>
	exports.default = {
	  data: function data() {
	    console.log('.............................');
	    return {
	      msg: 'Hello ' + new Date().getTime() + ' !',
	      user: {
	        name: '小王'
	      }
	    };
	  },


	  created: function created() {
	    // alert('ready')
	  },

	  methods: {
	    clickAbc: function clickAbc() {
	      alert('yes!');
	    }
	  }
	};
	// </script>
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	// <style>
	//
	// .message2 {
	//   color: red;
	//   font-size: 14px;
	// }
	// a2 {
	//   margin: 0 20px 0 0;
	//   font-weight: bold;
	// }
	// </style>
	//
	//

/***/ },
/* 7 */
/***/ function(module, exports) {

	module.exports = "<div style=\"background:green;\"><div style=\"width:100%;background:#FF9900;\"><h1> <a href=\"#/home\">头部H2</a></h1><div style=\"position:absolute;top:38px;left:200px;\"><a href=\"#/home\">home</a><a href=\"#/cate\">cate</a><a href=\"#/user_home\">user_home</a></div></div><div style=\"position:absolute;top:38px;left:400px;\"> \n{{ user.name }} {{ msg }}</div></div><div style=\"background:blue;\"><h1>Article Blue2 content</h1></div>";

/***/ },
/* 8 */
/***/ function(module, exports, __webpack_require__) {

	var __vue_script__, __vue_template__
	__webpack_require__(9)
	__vue_script__ = __webpack_require__(10)
	__vue_template__ = __webpack_require__(11)
	module.exports = __vue_script__ || {}
	if (module.exports.__esModule) module.exports = module.exports.default
	if (__vue_template__) { (typeof module.exports === "function" ? module.exports.options : module.exports).template = __vue_template__ }
	if (false) {(function () {  module.hot.accept()
	  var hotAPI = require("vue-hot-reload-api")
	  hotAPI.install(require("vue"), true)
	  if (!hotAPI.compatible) return
	  var id = "/private/var/www/njzs_vue/node/src/components/list_by_cate.vue"
	  if (!module.hot.data) {
	    hotAPI.createRecord(id, module.exports)
	  } else {
	    hotAPI.update(id, module.exports, __vue_template__)
	  }
	})()}

/***/ },
/* 9 */
/***/ function(module, exports) {

	// removed by extract-text-webpack-plugin

/***/ },
/* 10 */
/***/ function(module, exports) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	// <template lang="jade">
	// div(style="width:800px;background:#F2E6B3;margin:5px 0 0 0;padding:10px 0 30px 0;")
	//   | 列表
	//   | {{ cate }}
	//   div(v-bind:style="{width: width + 'px'}")
	//     ul#example-1
	//       li.li(v-for="item in items")
	//         {{ item.message }}
	// </template>
	//
	//
	//
	//
	//
	//
	//
	//
	//
	// <script>
	exports.default = {
	  data: function data() {
	    return {
	      width: 200
	    };
	  },


	  props: ['cate', 'items', 'width'],

	  components: {
	    // "Test":require('./test.vue'),
	  },

	  created: function created() {
	    // alert('ready')
	  },

	  methods: {
	    clickAbc: function clickAbc() {
	      alert('yes!');
	    }
	  }
	};
	// </script>
	//
	//
	//
	//
	//
	//
	//
	//
	// <style>
	// .message {
	//   color: red;
	// }
	// li.li {
	//   display: inline-block;
	//   background: #F1F1F1;
	//   width: 60px;
	//   height: 60px;
	//   margin: 5px 0 0 10px;
	// }
	// </style>
	//
	//

/***/ },
/* 11 */
/***/ function(module, exports) {

	module.exports = "<div style=\"width:800px;background:#F2E6B3;margin:5px 0 0 0;padding:10px 0 30px 0;\">列表\n{{ cate }}<div v-bind:style=\"{width: width + 'px'}\"><ul id=\"example-1\"><li v-for=\"item in items\" class=\"li\">{{ item.message }}</li></ul></div></div>";

/***/ },
/* 12 */
/***/ function(module, exports) {

	module.exports = "\n\n<page-header></page-header>\n\n<div style=\"width:100%;background:#FF0000;clear:both;\">\ncate2\n</div>\n\n<div v-cloak @click=\"clickAbc\" class=\"message\">{{ msg }}</div>\n\n<a href=\"#/dft\">\n  <button>\n    cate.vue\n  </button>\n</a>\n\n<list-by-cate width=\"700\" cate=\"1\" :items=\"items\"></list-by-cate>\n\n";

/***/ }
/******/ ]);