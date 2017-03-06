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

	var _login = __webpack_require__(10);

	var _login2 = _interopRequireDefault(_login);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	// setTimeout(()=>{
	new Vue({
	  el: '#main',
	  replace: false,
	  template: '#tpl_main',
	  components: { App: _login2.default }
	});
	// },0)

/***/ },
/* 1 */,
/* 2 */,
/* 3 */,
/* 4 */,
/* 5 */,
/* 6 */,
/* 7 */,
/* 8 */,
/* 9 */,
/* 10 */
/***/ function(module, exports, __webpack_require__) {

	var __vue_script__, __vue_template__
	__vue_script__ = __webpack_require__(11)
	__vue_template__ = __webpack_require__(13)
	module.exports = __vue_script__ || {}
	if (module.exports.__esModule) module.exports = module.exports.default
	if (__vue_template__) { (typeof module.exports === "function" ? module.exports.options : module.exports).template = __vue_template__ }
	if (false) {(function () {  module.hot.accept()
	  var hotAPI = require("vue-hot-reload-api")
	  hotAPI.install(require("vue"), true)
	  if (!hotAPI.compatible) return
	  var id = "/Users/song/work/client_v2/njzs-wechat/node/src/login.vue"
	  if (!module.hot.data) {
	    hotAPI.createRecord(id, module.exports)
	  } else {
	    hotAPI.update(id, module.exports, __vue_template__)
	  }
	})()}

/***/ },
/* 11 */
/***/ function(module, exports, __webpack_require__) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});

	var _md5Min = __webpack_require__(12);

	var _md5Min2 = _interopRequireDefault(_md5Min);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	var tag = 'login'; // <template lang="jade">
	// extends ./components/page.jade
	//
	// block content
	//   div(v-if="hascookie")
	//     p 店铺：{{client["storename"]}}
	//     p 地址：{{client["address"]}}
	//     p 手机：{{client["phone"]}}
	//
	//     input#submit(type="submit" value="注销" @click="clickLogout" style="border:1px solid #CCC;background:#DDD;font-size:18px;")
	//
	//     br
	//     br
	//     br
	//
	//     p 修改密码:
	//     input#passwd.input(type="password" v-model="oldpwd" placeholder="请输入当前密码" style="margin:0 0 10px 0;")
	//     input#passwd.input(type="password" v-model="newpwd" placeholder="请输入新密码" style="margin:0 0 10px 0;")
	//     input#passwd.input(type="password" v-model="newpwd2" placeholder="再次输入新密码" style="margin:0 0 10px 0;")
	//     input#submit(type="submit" value="修改密码" @click="clickResetPwd" style="border:1px solid #CCC;background:#DDD;font-size:18px;")
	//
	//
	//   div(v-else)
	//     h1 登录
	//     div
	//       input#userid.input(type="text" v-model="username" placeholder="用户ID" style="margin:0 0 10px 0;")
	//       input#passwd.input(type="password" v-model="password" placeholder="用户密码" style="margin:0 0 10px 0;")
	//       input#submit(type="submit" value="登录" @click="clickLogin" style="border:1px solid #CCC;background:#DDD;font-size:18px;")
	//
	// </template>
	//
	//
	//
	//
	// <script>

	exports.default = $$.Aaa({
	  NAME: tag,
	  APP: true,
	  TAG: tag,
	  components: {
	    // ComLogin,
	  },
	  data: function data() {
	    var hascookie = $.cookie("client") ? true : false;
	    return {
	      "hascookie": hascookie,
	      "client": $$.str2js($.cookie("client")) || "",
	      "username": "",
	      "password": "",
	      "oldpwd": "",
	      "newpwd": "",
	      "newpwd2": ""
	    };
	  },

	  methods: {
	    clickLogin: function clickLogin() {
	      var self = this;
	      $$.ajax({
	        type: 'post',
	        url: $$.BACKSERVER + '/order/login',
	        data: {
	          username: self.username,
	          password: (0, _md5Min2.default)(self.password)
	        },
	        success: function success(data) {
	          //客户信息存储到cookie
	          $.cookie("client", $$.js2str(data), { expires: 20 });
	          // window.location.reload();
	          page('/order');
	        },
	        fail: function fail(error) {
	          alert(error);
	        }
	      });
	    },

	    clickLogout: function clickLogout() {
	      $$.ajax({
	        type: 'get',
	        url: $$.BACKSERVER + '/order/logout',
	        success: function success() {
	          window.location.reload();
	          $.removeCookie("client");
	          $$.data.myorder_list = null;
	        },
	        fail: function fail(error) {
	          window.location.reload();
	          $.removeCookie("client");
	        }
	      });
	    },

	    clickResetPwd: function clickResetPwd() {
	      var self = this;

	      if (!self.oldpwd || !self.newpwd || !self.newpwd2) {
	        alert("请完整输入当前密码和新密码，并重复新密码!");
	        return;
	      }
	      if (self.newpwd != self.newpwd2) {
	        alert("确认与新密码密码不一致!");
	        return;
	      }

	      $$.ajax({
	        type: 'POST',
	        url: $$.BACKSERVER + '/order/resetpwd',
	        data: {
	          oldpwd: (0, _md5Min2.default)(self.oldpwd),
	          newpwd: (0, _md5Min2.default)(self.newpwd)
	        },
	        success: function success(data) {
	          if (data = "true") {
	            alert("修改成功!");
	          } else {
	            alert("修改失败!");
	          }
	        },
	        fail: function fail(error) {
	          alert("修改失败!");
	        }
	      });
	    }

	  },

	  ready: function ready() {
	    $$.utils.toggleNav('login');
	  }

	});
	// </script>
	//
	//
	//
	//
	//

/***/ },
/* 12 */
/***/ function(module, exports, __webpack_require__) {

	var __WEBPACK_AMD_DEFINE_RESULT__;"use strict";

	!function (a) {
	  "use strict";
	  function b(a, b) {
	    var c = (65535 & a) + (65535 & b),
	        d = (a >> 16) + (b >> 16) + (c >> 16);return d << 16 | 65535 & c;
	  }function c(a, b) {
	    return a << b | a >>> 32 - b;
	  }function d(a, d, e, f, g, h) {
	    return b(c(b(b(d, a), b(f, h)), g), e);
	  }function e(a, b, c, e, f, g, h) {
	    return d(b & c | ~b & e, a, b, f, g, h);
	  }function f(a, b, c, e, f, g, h) {
	    return d(b & e | c & ~e, a, b, f, g, h);
	  }function g(a, b, c, e, f, g, h) {
	    return d(b ^ c ^ e, a, b, f, g, h);
	  }function h(a, b, c, e, f, g, h) {
	    return d(c ^ (b | ~e), a, b, f, g, h);
	  }function i(a, c) {
	    a[c >> 5] |= 128 << c % 32, a[(c + 64 >>> 9 << 4) + 14] = c;var d,
	        i,
	        j,
	        k,
	        l,
	        m = 1732584193,
	        n = -271733879,
	        o = -1732584194,
	        p = 271733878;for (d = 0; d < a.length; d += 16) {
	      i = m, j = n, k = o, l = p, m = e(m, n, o, p, a[d], 7, -680876936), p = e(p, m, n, o, a[d + 1], 12, -389564586), o = e(o, p, m, n, a[d + 2], 17, 606105819), n = e(n, o, p, m, a[d + 3], 22, -1044525330), m = e(m, n, o, p, a[d + 4], 7, -176418897), p = e(p, m, n, o, a[d + 5], 12, 1200080426), o = e(o, p, m, n, a[d + 6], 17, -1473231341), n = e(n, o, p, m, a[d + 7], 22, -45705983), m = e(m, n, o, p, a[d + 8], 7, 1770035416), p = e(p, m, n, o, a[d + 9], 12, -1958414417), o = e(o, p, m, n, a[d + 10], 17, -42063), n = e(n, o, p, m, a[d + 11], 22, -1990404162), m = e(m, n, o, p, a[d + 12], 7, 1804603682), p = e(p, m, n, o, a[d + 13], 12, -40341101), o = e(o, p, m, n, a[d + 14], 17, -1502002290), n = e(n, o, p, m, a[d + 15], 22, 1236535329), m = f(m, n, o, p, a[d + 1], 5, -165796510), p = f(p, m, n, o, a[d + 6], 9, -1069501632), o = f(o, p, m, n, a[d + 11], 14, 643717713), n = f(n, o, p, m, a[d], 20, -373897302), m = f(m, n, o, p, a[d + 5], 5, -701558691), p = f(p, m, n, o, a[d + 10], 9, 38016083), o = f(o, p, m, n, a[d + 15], 14, -660478335), n = f(n, o, p, m, a[d + 4], 20, -405537848), m = f(m, n, o, p, a[d + 9], 5, 568446438), p = f(p, m, n, o, a[d + 14], 9, -1019803690), o = f(o, p, m, n, a[d + 3], 14, -187363961), n = f(n, o, p, m, a[d + 8], 20, 1163531501), m = f(m, n, o, p, a[d + 13], 5, -1444681467), p = f(p, m, n, o, a[d + 2], 9, -51403784), o = f(o, p, m, n, a[d + 7], 14, 1735328473), n = f(n, o, p, m, a[d + 12], 20, -1926607734), m = g(m, n, o, p, a[d + 5], 4, -378558), p = g(p, m, n, o, a[d + 8], 11, -2022574463), o = g(o, p, m, n, a[d + 11], 16, 1839030562), n = g(n, o, p, m, a[d + 14], 23, -35309556), m = g(m, n, o, p, a[d + 1], 4, -1530992060), p = g(p, m, n, o, a[d + 4], 11, 1272893353), o = g(o, p, m, n, a[d + 7], 16, -155497632), n = g(n, o, p, m, a[d + 10], 23, -1094730640), m = g(m, n, o, p, a[d + 13], 4, 681279174), p = g(p, m, n, o, a[d], 11, -358537222), o = g(o, p, m, n, a[d + 3], 16, -722521979), n = g(n, o, p, m, a[d + 6], 23, 76029189), m = g(m, n, o, p, a[d + 9], 4, -640364487), p = g(p, m, n, o, a[d + 12], 11, -421815835), o = g(o, p, m, n, a[d + 15], 16, 530742520), n = g(n, o, p, m, a[d + 2], 23, -995338651), m = h(m, n, o, p, a[d], 6, -198630844), p = h(p, m, n, o, a[d + 7], 10, 1126891415), o = h(o, p, m, n, a[d + 14], 15, -1416354905), n = h(n, o, p, m, a[d + 5], 21, -57434055), m = h(m, n, o, p, a[d + 12], 6, 1700485571), p = h(p, m, n, o, a[d + 3], 10, -1894986606), o = h(o, p, m, n, a[d + 10], 15, -1051523), n = h(n, o, p, m, a[d + 1], 21, -2054922799), m = h(m, n, o, p, a[d + 8], 6, 1873313359), p = h(p, m, n, o, a[d + 15], 10, -30611744), o = h(o, p, m, n, a[d + 6], 15, -1560198380), n = h(n, o, p, m, a[d + 13], 21, 1309151649), m = h(m, n, o, p, a[d + 4], 6, -145523070), p = h(p, m, n, o, a[d + 11], 10, -1120210379), o = h(o, p, m, n, a[d + 2], 15, 718787259), n = h(n, o, p, m, a[d + 9], 21, -343485551), m = b(m, i), n = b(n, j), o = b(o, k), p = b(p, l);
	    }return [m, n, o, p];
	  }function j(a) {
	    var b,
	        c = "";for (b = 0; b < 32 * a.length; b += 8) {
	      c += String.fromCharCode(a[b >> 5] >>> b % 32 & 255);
	    }return c;
	  }function k(a) {
	    var b,
	        c = [];for (c[(a.length >> 2) - 1] = void 0, b = 0; b < c.length; b += 1) {
	      c[b] = 0;
	    }for (b = 0; b < 8 * a.length; b += 8) {
	      c[b >> 5] |= (255 & a.charCodeAt(b / 8)) << b % 32;
	    }return c;
	  }function l(a) {
	    return j(i(k(a), 8 * a.length));
	  }function m(a, b) {
	    var c,
	        d,
	        e = k(a),
	        f = [],
	        g = [];for (f[15] = g[15] = void 0, e.length > 16 && (e = i(e, 8 * a.length)), c = 0; 16 > c; c += 1) {
	      f[c] = 909522486 ^ e[c], g[c] = 1549556828 ^ e[c];
	    }return d = i(f.concat(k(b)), 512 + 8 * b.length), j(i(g.concat(d), 640));
	  }function n(a) {
	    var b,
	        c,
	        d = "0123456789abcdef",
	        e = "";for (c = 0; c < a.length; c += 1) {
	      b = a.charCodeAt(c), e += d.charAt(b >>> 4 & 15) + d.charAt(15 & b);
	    }return e;
	  }function o(a) {
	    return unescape(encodeURIComponent(a));
	  }function p(a) {
	    return l(o(a));
	  }function q(a) {
	    return n(p(a));
	  }function r(a, b) {
	    return m(o(a), o(b));
	  }function s(a, b) {
	    return n(r(a, b));
	  }function t(a, b, c) {
	    return b ? c ? r(b, a) : s(b, a) : c ? p(a) : q(a);
	  } true ? !(__WEBPACK_AMD_DEFINE_RESULT__ = function () {
	    return t;
	  }.call(exports, __webpack_require__, exports, module), __WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)) : a.md5 = t;
	}(undefined);

/***/ },
/* 13 */
/***/ function(module, exports) {

	module.exports = "<div id=\"header\" style=\"position:fixed;width:100%;z-index:99;\" onclick=\"$$.utils.showNav();\"><div style=\"text-align:center;background:#FFF;border-bottom:1px solid #F1F1F1;\"><img src=\"/img/logo.png\" height=\"40\" style=\"\"/></div><div id=\"nav\" style=\"z-order:1;width:100%;;height:3000px;\"><div style=\"position:absolute;top:0;z-index:-1;width:100%;background:#000;height:3000px;opacity:0.5;;\"></div><div><a href=\"#/order\"> <div id=\"order\" style=\"background:#64BD77\" class=\"menu-item\">下订单</div></a><a href=\"#/orders\"><div id=\"orders\" style=\"background:#4BC0C1\" class=\"menu-item\">订单历史</div></a><a href=\"#/log_account\"><div id=\"log_account\" style=\"background:#FFC332\" class=\"menu-item\"> 余额变动</div></a><a href=\"#/log_returnback\"><div id=\"log_account\" style=\"background:#64BD77\" class=\"menu-item\"> 返款记录</div></a><a href=\"#/help\"> <div id=\"help\" style=\"background:#FB6B5B\" class=\"menu-item\">帮助</div></a><a href=\"#/login\"><div id=\"login\" style=\"background:#41586E\" class=\"menu-item\"> 登录/注销</div></a></div></div></div><div style=\"position:relative;clear:both;border:0px solid #000000;margin:0px 10px 0 10px;padding-top:20px;\"><div style=\"width:100%;clear:both;height:40px;\"></div><div v-if=\"hascookie\"><p>店铺：{{client[\"storename\"]}}</p><p>地址：{{client[\"address\"]}}</p><p>手机：{{client[\"phone\"]}}</p><input id=\"submit\" type=\"submit\" value=\"注销\" @click=\"clickLogout\" style=\"border:1px solid #CCC;background:#DDD;font-size:18px;\"/><br/><br/><br/><p>修改密码:</p><input id=\"passwd\" type=\"password\" v-model=\"oldpwd\" placeholder=\"请输入当前密码\" style=\"margin:0 0 10px 0;\" class=\"input\"/><input id=\"passwd\" type=\"password\" v-model=\"newpwd\" placeholder=\"请输入新密码\" style=\"margin:0 0 10px 0;\" class=\"input\"/><input id=\"passwd\" type=\"password\" v-model=\"newpwd2\" placeholder=\"再次输入新密码\" style=\"margin:0 0 10px 0;\" class=\"input\"/><input id=\"submit\" type=\"submit\" value=\"修改密码\" @click=\"clickResetPwd\" style=\"border:1px solid #CCC;background:#DDD;font-size:18px;\"/></div><div v-else=\"v-else\"><h1>登录</h1><div><input id=\"userid\" type=\"text\" v-model=\"username\" placeholder=\"用户ID\" style=\"margin:0 0 10px 0;\" class=\"input\"/><input id=\"passwd\" type=\"password\" v-model=\"password\" placeholder=\"用户密码\" style=\"margin:0 0 10px 0;\" class=\"input\"/><input id=\"submit\" type=\"submit\" value=\"登录\" @click=\"clickLogin\" style=\"border:1px solid #CCC;background:#DDD;font-size:18px;\"/></div></div><div style=\"width:100%;clear:both;height:50px;\"></div></div>";

/***/ }
/******/ ]);