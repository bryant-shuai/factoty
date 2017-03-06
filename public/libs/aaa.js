var $$ = {}
$$.data = {TREEDATA:{},cache:{}}
$$.data.loading = true
$$.TREE = {
  app: null
}

$$.jsonToString = function (json) {
  return JSON.stringify(json)
};
$$.stringToJSON = function (str) {
  var r = null
  try{
    r = JSON.parse(str)
  } catch (e) {
    console.error("str parse to obj fail! str: " + str);
  }
  return r
};
$$.js2str = $$.jsonToString
$$.str2js = $$.stringToJSON
$$.copy = function(v) {
  return  $$.str2js($$.js2str(v))
}
$$.formatMonth = function(m){
  if(m<10) return '0'+m
  else return m
}

$$.then = Thenjs

$$.Aaa = function(opt) {
  opt.data = opt.data || function(){return {}}
  opt._init = opt._init || function(){return {}}
  opt._created = opt._created || function(){return {}}
  opt.methods = opt.methods || {}

  for(var i in opt){
    var v = opt[i]
  }

  if(opt.props){
    // console.log($$.js2str(opt.props))
  }

  opt.created = function() {
    opt._created()
    // console.log($$.js2str($$.data.TREEDATA))

    // 绑了会出错
    // for(var mi in opt.methods){
    //   opt.methods[mi] = opt.methods[mi].bind(this)
    // }
    // if(opt.watch){
    //   for(var mi in opt.watch){
    //     opt.watch[mi] = opt.watch[mi].bind(this)
    //   }
    // }
  }

  opt.attached = function() {
    var self = this
    if (opt.APP) {
      $$.TREE.app = this
    }
    if (opt.EVENT) {
      for (var i in opt.EVENT) {
        $$.event.sub(opt.EVENT[i], this)
      }
    }

    opt._init.apply(this)
  }

  opt.beforeDestroy = function(){
    // alert('beforeDestroy:'+$$.js2str(this.$data))
  }

  opt.detached = function() {
    if(opt.TAG){
      $$.data.TREEDATA[opt.TAG] = $$.copy(this.$data)
    }
    $$.event.fire(this)
  }

  return opt
}


Vue.prototype.setState = function(data){
  for(var i in data){
    this.$set(i,$$.copy(data[i]))
  }
}






////////////// $$.event

;(function ($) {
  $.event = $.event || function () {
      var _observer = {};
      var subscribe = function (eventName_, obj_, args_) {
        args_ = args_ || {};

        var fireOthers_ = args_.fireOthers || false;
        if (fireOthers_) {
          _observer[eventName_] = [];
        }
        _observer[eventName_] = _observer[eventName_] || [];

        if (_observer[eventName_].length > 0) {
          $.event.remove(eventName_, obj_);
        }

        _observer[eventName_].push(obj_);
        return true;
      };

      var publish = function (eventName_, data_, data2_, from_) { 
        data_ = data_ || {};
        from_ = from_ || null

        var handlers = _observer[eventName_] || [];
        
        var l = handlers.length;
        
        var _stop = false;
        if (from_) {
          from_['hd_' + eventName_](data_, data2_);
          return
        }

        for (var _i = l - 1; _i >= 0; _i--) {
          if (!_stop) {
            var obj = handlers[_i];
            if (typeof obj === 'string') {
              if (!obj) {
                console.log('........fire')
                $.event.fire(obj)
              }
            }

            if (obj && obj['hd_' + eventName_]) {
              obj['hd_' + eventName_](data_, data2_);
            } else {
            }
          }
        }
      };

      var remove = function (eventName_, obj_) {
        var handlers = _observer[eventName_];
        var l = handlers.length;
        for (var i = l - 1; i >= 0; i--) {
          if (handlers[i] === obj_) {
            handlers.splice(i, 1);
            break;
          }
        }
      };

      var removeEvent = function (eventName_) {
        _observer[eventName_] = [];
      };

      var fire = function (obj_) {
        for (var eventName in _observer) {
          var handlers;
          if (_observer.hasOwnProperty(eventName)) {
            handlers = _observer[eventName];
          }
          var length = handlers.length;
          for (var i = length; i > -1; i--) {
            if (handlers[i] === obj_) {
              handlers.splice(i, 1);
            }
          }
        }
      };

      var fireAll = function () {
        _observer = {};
      };

      return {
        nodes: _observer,
        sub: subscribe,
        pub: publish,
        remove: remove,
        fire: fire,
        fireAll: fireAll
      };
    }();
})($$);


$$.ajax = function(args_){
  args_.success = args_.success || function(){};
  args_.fail = args_.fail || function(error) {alert(error);};

  var _success = args_.success;
  args_.success = function(data_){

    var res = $$.str2js(data_);
    var code = res.code;
    var message = res.message;
    var result = res.result;

    if (code == 1004) {
      $.removeCookie('client');
      window.location.href="#/login";
      return;
    } else if (code != 0) {
      alert(message);
      return;
    } 

    _success(result,message,code)
  };

  $.ajax(args_)

};






// js loader
var _js_caches = $$._js_caches = {};
$$.loadJs = function (file,cb){
  $('#loading').show();
  $$.data.loading = true;
  if(!cb){ cb = function(){} }

  if(_js_caches[file]){
    if($$.TREE.app){
      try{
        $$.TREE.app.$remove(function(){
          $$.TREE.app = null;
          eval(''+_js_caches[file]);
          $('#loading').hide();
          cb()
        })
      }catch(e){
          try{
            eval(''+_js_caches[file])
          }catch(e){
            console.log('eval error')
          }
          $('#loading').hide();
          cb()
      }
    }else{
      eval(''+_js_caches[file]);
      $('#loading').hide();
      cb()
    }
    return
  }

  $.ajax({
    type:'get',
    dataType:'text',
    url:'./dist/'+file+'.js?'+__ver__,
    success: function(data){
      _js_caches[file] = data;
      if($$.TREE.app){
        $$.TREE.app.$remove(function(){
          $$.TREE.app = null;
          eval(''+data);
          $('#loading').hide();
          cb()
        })
      }else{
        // alert(data)
          eval(''+data);
        // try{
        //   eval(''+data)
        // }catch(e){
        //   console.log('eval error')
        // }
        $('#loading').hide()
      }

    }
  })
};






