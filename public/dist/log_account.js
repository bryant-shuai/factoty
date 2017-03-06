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

	var _log_account = __webpack_require__(4);

	var _log_account2 = _interopRequireDefault(_log_account);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	new Vue({
	  el: '#main',
	  replace: false,
	  template: '#tpl_main',
	  components: { App: _log_account2.default }
	});

/***/ },
/* 1 */,
/* 2 */,
/* 3 */,
/* 4 */
/***/ function(module, exports, __webpack_require__) {

	var __vue_script__, __vue_template__
	__vue_script__ = __webpack_require__(5)
	__vue_template__ = __webpack_require__(6)
	module.exports = __vue_script__ || {}
	if (module.exports.__esModule) module.exports = module.exports.default
	if (__vue_template__) { (typeof module.exports === "function" ? module.exports.options : module.exports).template = __vue_template__ }
	if (false) {(function () {  module.hot.accept()
	  var hotAPI = require("vue-hot-reload-api")
	  hotAPI.install(require("vue"), true)
	  if (!hotAPI.compatible) return
	  var id = "/Users/song/work/client_v2/njzs-wechat/node/src/log_account.vue"
	  if (!module.hot.data) {
	    hotAPI.createRecord(id, module.exports)
	  } else {
	    hotAPI.update(id, module.exports, __vue_template__)
	  }
	})()}

/***/ },
/* 5 */
/***/ function(module, exports) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	// <template lang="jade">
	// extends ./components/page.jade
	//
	// block content
	//   div
	//     span 日期区间：
	//     select(v-model="fromdate")
	//       option(v-for="date in dates" v-bind:value="{{date}}") {{date}}
	//
	//     span {{' 至 '}}
	//     select(v-model="todate")
	//       option(v-for="date in dates" v-bind:value="{{date}}") {{date}}
	//
	//   h2 详情：{{fromdate + " ～ " + todate}}
	//
	//   table(style="border:1px solid #000;width:100%;")
	//     tr
	//       th 变动前余额
	//       th 变动金额
	//       th 变动原因
	//       //- th 变动后余额
	//       th 时间
	//     tr(v-if="!log_account_data || log_account_data.length==0" style="border:1px solid #CCC;")
	//       td(colspan=100 style="border-top:1px solid #CCC;") 没有数据
	//     tr(v-if="log_account_data && log_account_data.length>0" v-for="log_info in log_account_data" style="border:1px solid #CCC;")
	//       td(style="border-top:1px solid #CCC;") {{log_info["extra_obj"]["变动前余额"]}}
	//       td(style="border-top:1px solid #CCC;") {{log_info["extra_obj"]["变动金额"]}}
	//       td(style="border-top:1px solid #CCC;") {{log_info["extra_obj"]["变动原因"]}}
	//       //- td(style="border-top:1px solid #CCC;") {{log_info["extra_obj"]["变动后余额"]}}
	//       td(style="border-top:1px solid #CCC;") {{log_info.create_date}}
	//
	// </template>
	//
	//
	// <script>
	var tag = 'log_account';
	exports.default = $$.Aaa({
	  NAME: tag,
	  APP: true,
	  TAG: tag,

	  data: function data() {
	    var dates = [];
	    var aDate = new Date();
	    dates.push("" + aDate.getFullYear() + "/" + $$.formatMonth(aDate.getMonth() + 1) + "/" + $$.formatMonth(aDate.getDate()));
	    for (var _i = 0; _i < 60; _i++) {
	      aDate.setDate(aDate.getDate() - 1);
	      dates.push("" + aDate.getFullYear() + "/" + $$.formatMonth(aDate.getMonth() + 1) + "/" + $$.formatMonth(aDate.getDate()));
	    }

	    return {
	      'log_account_data': [],
	      'dates': dates,
	      'fromdate': null,
	      'todate': null,
	      'pageno': 1,
	      'pagesize': 1000
	    };
	  },

	  methods: {
	    showLogAccount: function showLogAccount() {
	      var self = this;

	      $$.ajax({
	        type: 'get',
	        url: $$.BACKSERVER + '/order/log_account',
	        data: {
	          fromdate: self.fromdate,
	          todate: self.todate,
	          pageno: self.pageno,
	          pagesize: self.pagesize
	        },
	        success: function success(result, message, code) {
	          var deductByDate = {};
	          var removeIdx = [];
	          for (var _index in result) {
	            var date = new Date(parseInt(result[_index]["create_at"]) * 1000);
	            var datestr = "" + date.getFullYear() + "/" + $$.formatMonth(date.getMonth() + 1) + "/" + $$.formatMonth(date.getDate());
	            result[_index]["create_date"] = datestr || "-";

	            result[_index]["extra_obj"] = $$.str2js(result[_index]["extra"]);

	            deductByDate['' + datestr] = deductByDate['' + datestr] || {
	              '金额': 0,
	              '前': 0,
	              '后': null
	            };

	            var extra_obj = result[_index]["extra_obj"];
	            try {

	              extra_obj['变动后余额'] = parseFloat(extra_obj["变动前余额"]) + parseFloat(extra_obj["变动金额"]);

	              extra_obj["变动后余额"] = parseFloat(extra_obj["变动后余额"]).toFixed(2);
	            } catch (e) {}
	            console.dir('extra_obj');
	            console.dir(extra_obj);
	            extra_obj['变动金额'] = parseFloat(extra_obj['变动金额']); // .toFixed(1)

	            if (extra_obj["变动原因"].indexOf('货物分拣扣款') >= 0) {
	              deductByDate['' + datestr]['金额'] += extra_obj['变动金额'];
	              if (parseFloat(extra_obj["变动前余额"]) >= deductByDate['' + datestr]['前']) {
	                deductByDate['' + datestr]['前'] = parseFloat(extra_obj["变动前余额"]);
	                if (null === deductByDate['' + datestr]['后']) {
	                  deductByDate['' + datestr]['后'] = parseFloat(extra_obj["变动前余额"]);
	                }
	              }
	              if (parseFloat(extra_obj["变动后余额"]) <= deductByDate['' + datestr]['后']) {
	                deductByDate['' + datestr]['后'] = parseFloat(extra_obj["变动后余额"]);
	              }

	              removeIdx.unshift(parseInt(_index));
	            }
	          }

	          // alert($$.js2str(removeIdx))

	          for (var idx in removeIdx) {
	            var _index = removeIdx[idx];
	            console.log(_index);
	            result.splice(_index, 1);
	          }

	          // alert($$.js2str(deductByDate))
	          var dist = [];

	          for (var _date in deductByDate) {
	            if (deductByDate[_date]['金额'] != 0) {
	              dist.unshift({
	                create_date: _date,
	                extra_obj: {
	                  '变动前余额': deductByDate[_date]['前'],
	                  '变动后余额': (deductByDate[_date]['前'] + deductByDate[_date]['金额']).toFixed(2),
	                  '变动原因': '发货扣款',
	                  '变动金额': deductByDate[_date]['金额'].toFixed(2)
	                }
	              });
	            }
	          }

	          for (var _idx in dist) {
	            result.unshift(dist[_idx]);
	          }

	          // alert($$.js2str(result))

	          self.log_account_data = result;
	        },
	        fail: function fail(error) {
	          alert(error);
	        }
	      });
	    }

	  },

	  _init: function _init() {
	    $$.utils.toggleNav('log_account');

	    var aDate = new Date();
	    var date = "" + aDate.getFullYear() + "/" + $$.formatMonth(aDate.getMonth() + 1) + "/" + $$.formatMonth(aDate.getDate());
	    this.fromdate = date;
	    this.todate = date;
	    //this.date_selected = "2016/07/25"
	  },

	  // 这种方式可以监听事件
	  watch: {
	    'fromdate': function fromdate(val, oldVal) {
	      this.fromdate = val;
	      this.showLogAccount();
	    },
	    'todate': function todate(val, oldVal) {
	      this.todate = val;
	      this.showLogAccount();
	    }
	  }

	});
	// </script>
	//
	//
	//
	//
	//

/***/ },
/* 6 */
/***/ function(module, exports) {

	module.exports = "<div id=\"header\" style=\"position:fixed;width:100%;z-index:99;\" onclick=\"$$.utils.showNav();\"><div style=\"text-align:center;background:#FFF;border-bottom:1px solid #F1F1F1;\"><img src=\"/img/logo.png\" height=\"40\" style=\"\"/></div><div id=\"nav\" style=\"z-order:1;width:100%;;height:3000px;\"><div style=\"position:absolute;top:0;z-index:-1;width:100%;background:#000;height:3000px;opacity:0.5;;\"></div><div><a href=\"#/order\"> <div id=\"order\" style=\"background:#64BD77\" class=\"menu-item\">下订单</div></a><a href=\"#/orders\"><div id=\"orders\" style=\"background:#4BC0C1\" class=\"menu-item\">订单历史</div></a><a href=\"#/log_account\"><div id=\"log_account\" style=\"background:#FFC332\" class=\"menu-item\"> 余额变动</div></a><a href=\"#/log_returnback\"><div id=\"log_account\" style=\"background:#64BD77\" class=\"menu-item\"> 返款记录</div></a><a href=\"#/help\"> <div id=\"help\" style=\"background:#FB6B5B\" class=\"menu-item\">帮助</div></a><a href=\"#/login\"><div id=\"login\" style=\"background:#41586E\" class=\"menu-item\"> 登录/注销</div></a></div></div></div><div style=\"position:relative;clear:both;border:0px solid #000000;margin:0px 10px 0 10px;padding-top:20px;\"><div style=\"width:100%;clear:both;height:40px;\"></div><div><span>日期区间：</span><select v-model=\"fromdate\"><option v-for=\"date in dates\" v-bind:value=\"{{date}}\">{{date}}</option></select><span>{{' 至 '}}</span><select v-model=\"todate\"><option v-for=\"date in dates\" v-bind:value=\"{{date}}\">{{date}}</option></select></div><h2>详情：{{fromdate + \" ～ \" + todate}}</h2><table style=\"border:1px solid #000;width:100%;\"><tr><th>变动前余额</th><th>变动金额</th><th>变动原因</th><th>时间</th></tr><tr v-if=\"!log_account_data || log_account_data.length==0\" style=\"border:1px solid #CCC;\"><td colspan=\"100\" style=\"border-top:1px solid #CCC;\">没有数据</td></tr><tr v-if=\"log_account_data &amp;&amp; log_account_data.length&gt;0\" v-for=\"log_info in log_account_data\" style=\"border:1px solid #CCC;\"><td style=\"border-top:1px solid #CCC;\">{{log_info[\"extra_obj\"][\"变动前余额\"]}}</td><td style=\"border-top:1px solid #CCC;\">{{log_info[\"extra_obj\"][\"变动金额\"]}}</td><td style=\"border-top:1px solid #CCC;\">{{log_info[\"extra_obj\"][\"变动原因\"]}}</td><td style=\"border-top:1px solid #CCC;\">{{log_info.create_date}}</td></tr></table><div style=\"width:100%;clear:both;height:50px;\"></div></div>";

/***/ }
/******/ ]);