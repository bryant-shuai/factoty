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

	var _orders = __webpack_require__(23);

	var _orders2 = _interopRequireDefault(_orders);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	new Vue({
	  el: '#main',
	  replace: false,
	  template: '#tpl_main',
	  components: { App: _orders2.default }
	});

/***/ },

/***/ 23:
/***/ function(module, exports, __webpack_require__) {

	var __vue_script__, __vue_template__
	__vue_script__ = __webpack_require__(24)
	__vue_template__ = __webpack_require__(25)
	module.exports = __vue_script__ || {}
	if (module.exports.__esModule) module.exports = module.exports.default
	if (__vue_template__) { (typeof module.exports === "function" ? module.exports.options : module.exports).template = __vue_template__ }
	if (false) {(function () {  module.hot.accept()
	  var hotAPI = require("vue-hot-reload-api")
	  hotAPI.install(require("vue"), true)
	  if (!hotAPI.compatible) return
	  var id = "/Users/song/work/client_v2/njzs-wechat/node/src/orders.vue"
	  if (!module.hot.data) {
	    hotAPI.createRecord(id, module.exports)
	  } else {
	    hotAPI.update(id, module.exports, __vue_template__)
	  }
	})()}

/***/ },

/***/ 24:
/***/ function(module, exports) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	// <template lang="jade">
	// extends ./components/page.jade
	// block content
	//   div
	//     span 选择日期：
	//     select(v-model="date_selected")
	//       option(v-for="date in dates" v-bind:value="{{date}}") {{date}}
	//
	//
	//   h2 详情：{{date_selected}}
	//
	//   table(style="border:1px solid #000;width:100%;")
	//     tr
	//       th 序号
	//       th 商品
	//       th 订货
	//       th 发货
	//     tr(v-if="!date_order_info || date_order_info.length==0" style="border:1px solid #CCC;")
	//       td(colspan=100 style="border-top:1px solid #CCC;") {{{order_status}}}
	//     tr(v-if="date_order_info && date_order_info.length>0" v-for="order_info in date_order_info" style="border:1px solid #CCC;")
	//       td(style="border-top:1px solid #CCC;") {{$index+1}}
	//       td(style="border-top:1px solid #CCC;") {{order_info.product_name}}({{order_info.price}})
	//       td(style="border-top:1px solid #CCC;") {{order_info.need_amount}}
	//       td(style="border-top:1px solid #CCC;") {{order_info.send_amount}}
	//
	//   b 总金额: {{sum}}
	// </template>
	//
	//
	//
	// <script>

	exports.default = $$.Aaa({
	  NAME: 'orders',
	  APP: true,
	  TAG: 'orders',

	  data: function data() {
	    var dates = [];
	    var aDate = new Date();
	    dates.push("" + aDate.getFullYear() + "/" + $$.formatMonth(aDate.getMonth() + 1) + "/" + $$.formatMonth(aDate.getDate()));
	    for (var _i = 0; _i < 60; _i++) {
	      aDate.setDate(aDate.getDate() - 1);
	      dates.push("" + aDate.getFullYear() + "/" + $$.formatMonth(aDate.getMonth() + 1) + "/" + $$.formatMonth(aDate.getDate()));
	    }

	    return {
	      'date_selected': null,
	      'dates': dates,
	      'date_order_info': null,
	      'sum': 0,
	      'order_status': '加载中...'
	    };
	  },

	  methods: {
	    showOrder: function showOrder(date) {
	      var self = this;
	      // alert(date)
	      this.date_selected = date;
	      self.sum = 0;
	      this.setState({
	        date_order_info: null,
	        order_status: '加载中...'
	      });

	      $$.ajax({
	        type: 'get',
	        url: $$.BACKSERVER + '/order/orders',
	        data: {
	          thedate: date
	        },
	        success: function success(result, message, code) {
	          var orders = result.orders;
	          var ___sum = result.sum;
	          self.date_order_info = orders;
	          if (orders.length == 0) {
	            self.order_status = '<font color="red">没有数据</font>';
	          }
	          var _sum = 0;
	          for (var i in orders) {
	            _sum += parseFloat(orders[i].price) * parseFloat(orders[i].send_amount);
	          }

	          self.sum = ___sum.toFixed(2);
	        },
	        fail: function fail(err) {
	          alert(err);
	        }
	      });
	    }

	  },

	  _init: function _init() {
	    $$.utils.toggleNav('orders');

	    var aDate = new Date();
	    var date = "" + aDate.getFullYear() + "/" + $$.formatMonth(aDate.getMonth() + 1) + "/" + $$.formatMonth(aDate.getDate());
	    this.date_selected = date;
	    //this.date_selected = "2016/07/25"
	  },

	  // 这种方式可以监听事件
	  watch: {
	    'date_selected': function date_selected(val, oldVal) {
	      this.showOrder(val);
	    }
	  }
	});
	// </script>
	//
	//
	//

/***/ },

/***/ 25:
/***/ function(module, exports) {

	module.exports = "<div id=\"header\" style=\"position:fixed;width:100%;z-index:99;\" onclick=\"$$.utils.showNav();\"><div style=\"text-align:center;background:#FFF;border-bottom:1px solid #F1F1F1;\"><img src=\"/img/logo.png\" height=\"40\" style=\"\"/></div><div id=\"nav\" style=\"z-order:1;width:100%;;height:3000px;\"><div style=\"position:absolute;top:0;z-index:-1;width:100%;background:#000;height:3000px;opacity:0.5;;\"></div><div><a href=\"#/order\"> <div id=\"order\" style=\"background:#64BD77\" class=\"menu-item\">下订单</div></a><a href=\"#/orders\"><div id=\"orders\" style=\"background:#4BC0C1\" class=\"menu-item\">订单历史</div></a><a href=\"#/log_account\"><div id=\"log_account\" style=\"background:#FFC332\" class=\"menu-item\"> 余额变动</div></a><a href=\"#/log_returnback\"><div id=\"log_account\" style=\"background:#64BD77\" class=\"menu-item\"> 返款记录</div></a><a href=\"#/help\"> <div id=\"help\" style=\"background:#FB6B5B\" class=\"menu-item\">帮助</div></a><a href=\"#/login\"><div id=\"login\" style=\"background:#41586E\" class=\"menu-item\"> 登录/注销</div></a></div></div></div><div style=\"position:relative;clear:both;border:0px solid #000000;margin:0px 10px 0 10px;padding-top:20px;\"><div style=\"width:100%;clear:both;height:40px;\"></div><div><span>选择日期：</span><select v-model=\"date_selected\"><option v-for=\"date in dates\" v-bind:value=\"{{date}}\">{{date}}</option></select></div><h2>详情：{{date_selected}}</h2><table style=\"border:1px solid #000;width:100%;\"><tr><th>序号</th><th>商品</th><th>订货</th><th>发货</th></tr><tr v-if=\"!date_order_info || date_order_info.length==0\" style=\"border:1px solid #CCC;\"><td colspan=\"100\" style=\"border-top:1px solid #CCC;\">{{{order_status}}}</td></tr><tr v-if=\"date_order_info &amp;&amp; date_order_info.length&gt;0\" v-for=\"order_info in date_order_info\" style=\"border:1px solid #CCC;\"><td style=\"border-top:1px solid #CCC;\">{{$index+1}}</td><td style=\"border-top:1px solid #CCC;\">{{order_info.product_name}}({{order_info.price}})</td><td style=\"border-top:1px solid #CCC;\">{{order_info.need_amount}}</td><td style=\"border-top:1px solid #CCC;\">{{order_info.send_amount}}</td></tr></table><b>总金额: {{sum}}</b><div style=\"width:100%;clear:both;height:50px;\"></div></div>";

/***/ }

/******/ });