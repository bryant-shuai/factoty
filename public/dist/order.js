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

	var _order = __webpack_require__(14);

	var _order2 = _interopRequireDefault(_order);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	new Vue({
	  el: '#main',
	  replace: false,
	  template: '#tpl_main',
	  components: { App: _order2.default }
	});

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
/* 10 */,
/* 11 */,
/* 12 */,
/* 13 */,
/* 14 */
/***/ function(module, exports, __webpack_require__) {

	var __vue_script__, __vue_template__
	__vue_script__ = __webpack_require__(15)
	__vue_template__ = __webpack_require__(22)
	module.exports = __vue_script__ || {}
	if (module.exports.__esModule) module.exports = module.exports.default
	if (__vue_template__) { (typeof module.exports === "function" ? module.exports.options : module.exports).template = __vue_template__ }
	if (false) {(function () {  module.hot.accept()
	  var hotAPI = require("vue-hot-reload-api")
	  hotAPI.install(require("vue"), true)
	  if (!hotAPI.compatible) return
	  var id = "/Users/song/work/client_v2/njzs-wechat/node/src/order.vue"
	  if (!module.hot.data) {
	    hotAPI.createRecord(id, module.exports)
	  } else {
	    hotAPI.update(id, module.exports, __vue_template__)
	  }
	})()}

/***/ },
/* 15 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	// <template lang="jade">
	// extends ./components/page.jade
	//
	// block content
	//   com-order-new
	// </template>
	//
	//
	//
	// <script>
	var tag = 'order';
	exports.default = $$.Aaa({
	  NAME: tag,
	  APP: true,
	  TAG: tag,
	  components: {
	    ComOrderNew: __webpack_require__(16)
	  },

	  ready: function ready() {
	    $$.utils.toggleNav('order');
	  }

	});
	// </script>
	//
	//

/***/ },
/* 16 */
/***/ function(module, exports, __webpack_require__) {

	var __vue_script__, __vue_template__
	__vue_script__ = __webpack_require__(17)
	__vue_template__ = __webpack_require__(21)
	module.exports = __vue_script__ || {}
	if (module.exports.__esModule) module.exports = module.exports.default
	if (__vue_template__) { (typeof module.exports === "function" ? module.exports.options : module.exports).template = __vue_template__ }
	if (false) {(function () {  module.hot.accept()
	  var hotAPI = require("vue-hot-reload-api")
	  hotAPI.install(require("vue"), true)
	  if (!hotAPI.compatible) return
	  var id = "/Users/song/work/client_v2/njzs-wechat/node/src/components/orderNew.vue"
	  if (!module.hot.data) {
	    hotAPI.createRecord(id, module.exports)
	  } else {
	    hotAPI.update(id, module.exports, __vue_template__)
	  }
	})()}

/***/ },
/* 17 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	// <template lang="jade">
	// div.products(v-if="products_loaded")
	//   div(style="position:fixed;top:30px;left:0;width:100%;background:#FFFFFF;")
	//     table(style="width:100%;border:1px solid #CCC;margin:20px 0 2px 0;")
	//       tr
	//         td
	//           b 店铺
	//         td
	//           b 余额
	//         td
	//           b 订货金额
	//       tr
	//         td {{client["storename"]}}
	//         td {{client["deposit"]}}
	//         td {{order_sum}}
	//
	//   div(v-for="type in types" style="margin-top:60px;border:1px solid #CCC;margin-bottom:20px;")
	//     div(style="text-align:center;background:#CCC;color:#000;font-size:24px;font-weight:bold;") {{type.name}}
	//
	//     li.product(style="{border-bottom:1px solid #BBB;}")
	//       div.h1box(style="flex:1.7;")
	//         b 产品
	//       div.h1box(style="width:200px;")
	//         b 订货数量
	//     div(v-for="product in product_with_type['_'+type.id]")
	//       com-order-product(:product="product")
	//
	// div.products(v-if="!products_loaded")
	//   p 加载商品中...       
	//
	// </template>
	//
	//
	//
	// <script>
	var tag = 'orderNew';
	exports.default = $$.Aaa({
	  NAME: tag,
	  TAG: tag,
	  EVENT: ['ORDER_ITEM_CHANGED'],

	  components: {
	    ComOrderProduct: __webpack_require__(18)
	  },

	  data: function data() {
	    return $$.data.TREEDATA[tag] || {
	      'products': [
	        // {"id":"238","name":"368元礼盒2222","weight_type":"1","unit":"22","price":"0.00","amount":"0.000","sign":"0","order":"0","rate":"1.0000","product_type":"9","create_at":"1467545749","update_at":"1468126776","delete_at":"0","deleted":"0"},
	      ],
	      'product_with_type': {},
	      'types': null,
	      'msg': '...测试MSG...',
	      'products_loaded': false,
	      'client': {},
	      'order_sum': 0
	    };
	  },

	  methods: {
	    hd_ORDER_ITEM_CHANGED: function hd_ORDER_ITEM_CHANGED() {
	      // alert('hd_ORDER_ITEM_CHANGED')
	      this.reCountPrice();
	    },

	    reCountPrice: function reCountPrice() {
	      this.order_sum = 0;
	      var order_sum = 0;
	      for (var product_id in $$.data.myorder) {
	        product_id += '';
	        try {
	          var count = parseFloat($$.data.myorder[product_id]);
	          var price = parseFloat($$.data.products[product_id].region_price);
	          console.log('product_id:' + product_id);
	          console.log('count:' + count);
	          console.log('price:' + price);
	          order_sum += count * price;
	          console.log('order_sum:' + order_sum);
	        } catch (e) {
	          // alert('product_id:'+product_id + 'error')
	        }
	      }
	      this.order_sum = order_sum.toFixed(2);
	    }
	  },

	  _init: function _init() {
	    var self = this;

	    self.setState({
	      'products_loaded': false
	    });

	    $$
	    //获取我的订单
	    .then(function (cont) {

	      // if($$.data.myorder_list ){
	      //   cont(null)
	      // }else{

	      $$.data.myorder_list = [];
	      $$.data.myorder = {};
	      $$.ajax({
	        type: 'get',
	        url: $$.BACKSERVER + '/order/orders',
	        success: function success(data) {
	          $$.data.myorder_list = data.orders;
	          for (var i in $$.data.myorder_list) {
	            var _order = $$.data.myorder_list[i];
	            $$.data.myorder['' + _order.product_id] = _order.need_amount;
	          }
	          // alert($$.js2str($$.data.myorder))
	          cont(null);
	        }
	      });
	      // }
	    })
	    //获取产品
	    .then(function (cont) {

	      if ($$.data.types) {
	        cont(null);
	      } else {

	        $$.ajax({
	          type: 'get',
	          url: $$.BACKSERVER + '/order/products',
	          success: function success(data) {
	            $$.data.types = data.types;
	            $$.data.product_with_type = data.product_with_type;
	            $$.data.client = data.client;

	            cont(null);
	          }
	        });
	      }
	    }).then(function (cont) {

	      $$.data.products = {};
	      for (var i in $$.data.product_with_type) {
	        var productsInType = $$.data.product_with_type[i];
	        for (var j in productsInType) {
	          var product = productsInType[j];
	          $$.data.products['' + product.id] = product;
	        }
	      }
	      // alert($$.js2str($$.data.products))

	      self.client = $$.data.client;
	      self.setState({
	        'types': $$.data.types,
	        'product_with_type': $$.data.product_with_type,
	        // 'products': self.products,
	        'products_loaded': true
	      });

	      self.reCountPrice();
	    });
	  }

	});
	// </script>
	//
	//
	//
	//

/***/ },
/* 18 */
/***/ function(module, exports, __webpack_require__) {

	var __vue_script__, __vue_template__
	__vue_script__ = __webpack_require__(19)
	__vue_template__ = __webpack_require__(20)
	module.exports = __vue_script__ || {}
	if (module.exports.__esModule) module.exports = module.exports.default
	if (__vue_template__) { (typeof module.exports === "function" ? module.exports.options : module.exports).template = __vue_template__ }
	if (false) {(function () {  module.hot.accept()
	  var hotAPI = require("vue-hot-reload-api")
	  hotAPI.install(require("vue"), true)
	  if (!hotAPI.compatible) return
	  var id = "/Users/song/work/client_v2/njzs-wechat/node/src/components/orderProduct.vue"
	  if (!module.hot.data) {
	    hotAPI.createRecord(id, module.exports)
	  } else {
	    hotAPI.update(id, module.exports, __vue_template__)
	  }
	})()}

/***/ },
/* 19 */
/***/ function(module, exports) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	// <template lang="jade">
	// li.product(v-bind:style="{background:bgcolor}")
	//   div.h1box(style="flex:1.7;")
	//     div
	//       font(style="font-size:16px;") {{product.id}}.{{product.name}}
	//   div.h1box
	//     input(v-model="amount" type="number" @blur="OnBlur" @focus="OnFocus" style="border:1px solid #CCC;width:110px;height:28px;line-height:28px;font-size:26px;")
	//   div.h1box
	//     font(style="color:#666;")
	//       nobr
	//         font(style="font-size:9px;") (¥
	//         {{product.region_price}}
	//         font(style="font-size:9px;") /{{product.unit}})
	//
	// </template>
	//
	//
	//
	// <script>
	exports.default = $$.Aaa({

	  props: ['product'],

	  data: function data() {
	    return {
	      // 'props_product': {  //没用
	      // "id":"238","name":"368元礼盒2222","weight_type":"1","unit":"22","price":"0.00","amount":"0.000","sign":"0","order":"0","rate":"1.0000","product_type":"9","create_at":"1467545749","update_at":"1468126776","delete_at":"0","deleted":"0",
	      // },
	      'amount': null,
	      'syncing': "false",
	      'changed': "false",
	      'msg': '..',
	      'bgcolor': 'white'
	    };
	  },

	  methods: {

	    OnBlur: function OnBlur() {
	      var self = this;
	      if (this.amount && parseFloat(this.amount) >= 0 && this._last_amount != this.amount) {

	        //提交给服务端
	        self.changed = "true";
	        console.log('AjaxSaveToServer');
	        self.AjaxSaveToServer();

	        //成功的话
	        this._last_amount = this.amount;
	      } else {
	        // this.amount = ''
	      }
	    },

	    OnFocus: function OnFocus() {
	      this._last_amount = this.amount;
	    },

	    AjaxSaveToServer: function AjaxSaveToServer() {
	      var self = this;
	      $$.then(function (cont) {
	        self.syncing = true;
	        self.bgcolor = "#CCC";
	        console.log('syncing......................................' + self.syncing);

	        $$.ajax({
	          type: 'post',
	          url: $$.BACKSERVER + '/order/create',
	          data: {
	            product_id: self.product.id,
	            need_amount: self.amount
	          },
	          success: function success(data) {
	            self.amount = data.need_amount;
	            cont(null);
	          }
	        });
	      }).then(function (cont) {
	        self.syncing = "false";
	        self.changed = "false";
	        self.bgcolor = "#00BE03";
	        $$.data.myorder['' + self.product.id] = self.amount;
	        if (self.amount == 0) self.bgcolor = "#FFFFFF";
	        console.log('syncing.done.....................................' + self.syncing);
	        $$.event.pub('ORDER_ITEM_CHANGED');
	      });
	    }

	  },

	  _init: function _init() {
	    if ($$.data.myorder[this.product.id] && parseFloat($$.data.myorder[this.product.id]) > 0) {
	      this.bgcolor = '#00BE03';
	      this.amount = $$.data.myorder[this.product.id];
	    }
	  }

	  // 这种方式可以监听事件
	  // watch: {
	  //   'amount': function (val, oldVal) {
	  //     this.amount = val
	  //   },
	  //   'changed': function (val, oldVal) {
	  //     console.log('changed:'+val)
	  //     this.changed = val
	  //   },
	  //   'syncing': function (val, oldVal) {
	  //     console.log('syncing:'+val)
	  //     this.syncing = val
	  //   },
	  // },

	});
	// </script>
	//
	//
	//
	//

/***/ },
/* 20 */
/***/ function(module, exports) {

	module.exports = "<li v-bind:style=\"{background:bgcolor}\" class=\"product\"><div style=\"flex:1.7;\" class=\"h1box\"><div> <font style=\"font-size:16px;\">{{product.id}}.{{product.name}} </font></div></div><div class=\"h1box\"><input v-model=\"amount\" type=\"number\" @blur=\"OnBlur\" @focus=\"OnFocus\" style=\"border:1px solid #CCC;width:110px;height:28px;line-height:28px;font-size:26px;\"/></div><div class=\"h1box\"><font style=\"color:#666;\"><nobr> <font style=\"font-size:9px;\">(¥</font>{{product.region_price}}<font style=\"font-size:9px;\">/{{product.unit}})</font></nobr></font></div></li>";

/***/ },
/* 21 */
/***/ function(module, exports) {

	module.exports = "<div v-if=\"products_loaded\" class=\"products\"><div style=\"position:fixed;top:30px;left:0;width:100%;background:#FFFFFF;\"><table style=\"width:100%;border:1px solid #CCC;margin:20px 0 2px 0;\"><tr><td> <b>店铺</b></td><td> <b>余额</b></td><td> <b>订货金额</b></td></tr><tr><td>{{client[\"storename\"]}}</td><td>{{client[\"deposit\"]}}</td><td>{{order_sum}}</td></tr></table></div><div v-for=\"type in types\" style=\"margin-top:60px;border:1px solid #CCC;margin-bottom:20px;\"><div style=\"text-align:center;background:#CCC;color:#000;font-size:24px;font-weight:bold;\">{{type.name}}</div><li style=\"{border-bottom:1px solid #BBB;}\" class=\"product\"><div style=\"flex:1.7;\" class=\"h1box\"> <b>产品</b></div><div style=\"width:200px;\" class=\"h1box\"> <b>订货数量</b></div></li><div v-for=\"product in product_with_type['_'+type.id]\"><com-order-product :product=\"product\"></com-order-product></div></div></div><div v-if=\"!products_loaded\" class=\"products\"><p>加载商品中...        </p></div>";

/***/ },
/* 22 */
/***/ function(module, exports) {

	module.exports = "<div id=\"header\" style=\"position:fixed;width:100%;z-index:99;\" onclick=\"$$.utils.showNav();\"><div style=\"text-align:center;background:#FFF;border-bottom:1px solid #F1F1F1;\"><img src=\"/img/logo.png\" height=\"40\" style=\"\"/></div><div id=\"nav\" style=\"z-order:1;width:100%;;height:3000px;\"><div style=\"position:absolute;top:0;z-index:-1;width:100%;background:#000;height:3000px;opacity:0.5;;\"></div><div><a href=\"#/order\"> <div id=\"order\" style=\"background:#64BD77\" class=\"menu-item\">下订单</div></a><a href=\"#/orders\"><div id=\"orders\" style=\"background:#4BC0C1\" class=\"menu-item\">订单历史</div></a><a href=\"#/log_account\"><div id=\"log_account\" style=\"background:#FFC332\" class=\"menu-item\"> 余额变动</div></a><a href=\"#/log_returnback\"><div id=\"log_account\" style=\"background:#64BD77\" class=\"menu-item\"> 返款记录</div></a><a href=\"#/help\"> <div id=\"help\" style=\"background:#FB6B5B\" class=\"menu-item\">帮助</div></a><a href=\"#/login\"><div id=\"login\" style=\"background:#41586E\" class=\"menu-item\"> 登录/注销</div></a></div></div></div><div style=\"position:relative;clear:both;border:0px solid #000000;margin:0px 10px 0 10px;padding-top:20px;\"><div style=\"width:100%;clear:both;height:40px;\"></div><com-order-new></com-order-new><div style=\"width:100%;clear:both;height:50px;\"></div></div>";

/***/ }
/******/ ]);