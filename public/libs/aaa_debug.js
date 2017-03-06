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
  return JSON.parse(str)
};
$$.js2str = $$.jsonToString
$$.str2js = $$.stringToJSON
$$.copy = function(v) {
  return  $$.str2js($$.js2str(v))
}

$$.then = Thenjs

$$.Aaa = function(opt) {
  opt.data = opt.data || function(){return {}}
  opt._init = opt._init || function(){return {}}
  opt.methods = opt.methods || {}

  for(var i in opt){
    var v = opt[i]
    // console.dir(v)
    // console.log('i:'+i)
    // console.log('v:'+$$.js2str(v))
  }

  opt.created = function() {
    // console.log('created......')
    // console.dir(this.props)
    // console.dir(this)

    console.log($$.js2str($$.data.TREEDATA))

    for(var mi in opt.methods){
      // console.log('mi:::'+mi)
      // console.log('mi:::'+mi)
      // console.log('mi:::'+mi)
      // console.log('mi:::'+mi)
      opt.methods[mi] = opt.methods[mi].bind(this)

      // console.dir(this)
      // opt.methods[mi].apply(this)
    }
  }

  opt.attached = function() {
    var self = this
    // console.log('attached.........................................................')
    if (opt.APP) {
      $$.TREE.app = this
    }
    if (opt.EVENT) {
      for (var i in opt.EVENT) {
        // console.log('bind event:'+opt.EVENT[i])
        // console.log('aaa bind this')
        // console.dir(this)
        $$.event.sub(opt.EVENT[i], this)
      }
    }

    opt._init.apply(this)
    // console.dir(this)
    // alert($$.js2str(this.$data))
    // console.dir('$$.event.nodes')
    // console.dir($$.event.nodes)
    // console.log('attached.........................................................3')
  }

  opt.beforeDestroy = function(){
    // alert('beforeDestroy:'+$$.js2str(this.$data))
  }

  opt.detached = function() {
    if(opt.TAG){
      $$.data.TREEDATA[opt.TAG] = $$.copy(this.$data)
    }
    // console.log($$.js2str($$.data.TREEDATA))
    $$.event.fire(this)
    // console.log('detacheddetacheddetacheddetacheddetacheddetacheddetacheddetacheddetacheddetacheddetacheddetacheddetacheddetacheddetacheddetached...................................')
    // console.dir('$$.event.nodes')
    // console.dir($$.event.nodes)
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

      var publish = function (eventName_, data_, data2_, from_) { //data_={}
        // console.log('eventName_:'+eventName_)
        // console.log('data_:'+data_)
        data_ = data_ || {};
        from_ = from_ || null

        // console.log('_observer:')
        // console.dir(_observer)
        
        var handlers = _observer[eventName_] || [];
        
        // console.log('handlers:')
        // console.dir(_observer[eventName_])
        // console.dir(handlers)
        // console.dir(handlers.length)
        
        var l = handlers.length;
        
        // console.log('l.length')
        // console.log(l)

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

            // console.log('obj')
            // console.dir(obj)
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






// js loader
var _js_caches = {}
$$.loadJs = function (file,cb){
  $('#loading').show()
  // alert('0')
  $$.data.loading = true
  // alert($$.TREE.app)
  // alert($$.js2str(_js_caches))
  if(!cb){ cb = function(){} }

  if(_js_caches[file]){
    // alert('1')

    if($$.TREE.app){
      // alert('3')
      // alert('$$.TREE.app')
      // alert($$.TREE.app)
      try{
        $$.TREE.app.$remove(function(){
          // alert('remove self 1')
          $$.TREE.app = null
          // alert('remove && mount')
          eval(''+_js_caches[file])
          $('#loading').hide()
          cb()
        })
      }catch(e){
          eval(''+_js_caches[file])
          $('#loading').hide()
          cb()
      }
    }else{
      // alert('2')
      eval(''+_js_caches[file])
      $('#loading').hide()
      cb()
    }
    return 
  }

  $.ajax({
    type:'get',
    dataType:'text',
    url:'./dist/'+file+'.js?'+__ver__,
    // url:'./dist/'+file+'.js',
    success: function(data){
      _js_caches[file] = data
      // alert(file)

      if($$.TREE.app){
        // alert('4')
        $$.TREE.app.$remove(()=>{
        // alert('remove self 2')
          $$.TREE.app = null
          eval(''+data)
          $('#loading').hide()
          cb()
        })
      }else{
        eval(''+data)
        $('#loading').hide()
      }

    }
  })
}






