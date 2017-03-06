<!DOCTYPE html>
<html class="csstransforms csstransforms3d csstransitions">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="keywords" content="">

    <!-- Set render engine for multi engine browser -->
    <meta name="renderer" content="webkit">

    <link type="text/css" rel="stylesheet" href="/libs/danke1_files/font-awesome.min.css">

    <script charset="UTF-8" src="/libs/danke1_files/base.js"></script>
    <script charset="UTF-8" src="/libs/vue.js"></script>

    <title>【惠有-中网旗下积分商城】</title>
    <link type="text/css" rel="stylesheet" href="/libs/danke1_files/pc_home-b7169cabb1.css">

    <style type="text/css">
    [v-cloak] {
      display: none;
    }
    #MECHAT-PCBTN {
    font-family: "Helvetica Neue", Helvetica, "Microsoft Yahei", Arial, sans-serif;
            font-size: 16px;
            line-height: 24px;
            position: fixed;
            bottom: -100%;
            background: #1abc9c;
            color: #fff;
            text-align: center;
            padding: 10px 15px 8px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            cursor: pointer;
            z-index: 2147483645;
        }

        #MECHAT-PCBTN span {
            display: inline-block;
            height: 24px;
            padding: 0 0 0 34px;
            background: url("//meiqia.com/images/pc_btn_icon.png") 0 0 no-repeat
        }

        #MECHAT-LAYOUT {
            position: fixed;
            right: 80px;
            bottom: -100%;
            width: 300px;
            height: 430px;
            z-index: 2147483647;
            overflow: hidden;
            background: #fff;
            border: 1px solid #1abc9c;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            box-shadow: 0 0 3px 2px rgba(135, 135, 135, 0.1)
        }

        #MECHAT-PCBTN,
        #MECHAT-LAYOUT {
            _position: absolute;
            _bottom: auto;
            _top: expression(eval(document.documentElement.scrollTop+document.documentElement.clientHeight-this.offsetHeight-(parseInt(this.currentStyle.marginTop, 10)||0)-(parseInt(this.currentStyle.marginBottom, 10)||0)))
        }
    </style>

    <style type="text/css">
    @media (max-width: 991px) {
    .logo {
        height: 45px;
            }
        }

        @media (max-width: 601px) {
    .logo {
        height: 25px;
            }
        }

        @media (min-width: 1px) {
    #home-prom {
    display: block;
}
        }

        @media (min-width: 602px) {
    #home-prom {
    display: none;
}
        }

        @media (min-width: 1px) {
    article .entry-thumbnail {
        height: 186px;
                /*max-height: 560px;*/
                overflow: hidden;
            }
        }

        @media (min-width: 500px) {
    article .entry-thumbnail {
        height: 220px;
                /*max-height: 560px;*/
                overflow: hidden;
            }
        }

        @media (min-width: 600px) {
    article .entry-thumbnail {
        height: 370px;
                /*max-height: 560px;*/
                overflow: hidden;
            }
        }

        @media (min-width: 768px) {
    article .entry-thumbnail {
        max-height: 400px;
                overflow: hidden;
            }
        }

        @media (min-width: 800px) {
    article .entry-thumbnail {
        max-height: 470px;
                overflow: hidden;
            }
        }

        @media (min-width:992px) {
    article .entry-thumbnail {
        max-height: 243px; //
                overflow: hidden;
            }
        }

        @media (min-width:1200px) {
    article .entry-thumbnail {
        max-height: 280px;
                overflow: hidden;
            }
        }
    </style>

</head>

<body>
    <div id="topbar"></div>
    <div id="page">
        <header id="header" class="site-header">
            <nav id="topbar" class="site-topbar visible-lg visible-md">
                <div class="container">
                    <div class="row text-center">
                        <div class="col-sm-6">售前咨询: <a href="tel:13031114768"><i class="fa fa-phone"></i> 13031114768 </a> 10:00 ~ 22:00</div>
                        <div class="col-sm-6">售后客服: <a href="tel:13031114768"><i class="fa fa-phone"></i> 13031114768 </a> 09:00 ~ 21:00</div>
                    </div>
                </div>
            </nav>

            <nav id="navbar" class="site-navbar navbar navbar-static-top affix" role="navigation">
                <div class="container">

                    <!-- <div class='row'> -->

                    <div class="navbar-header">
                        <style media="screen">
                            .site-navbar .navbar-toggle{
    margin: 5px 0px;
                                    font-size: 18px;
                            }
                        </style>
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
                            <span class="sr-only">导航选项</span>
                            <i class="fa fa-bars"></i>
                        </button>

                        <button type="button" class="navbar-toggle">

                            <li style="list-style: none">
                                <a onClick="javascript:window.location='/user/';return false;">
                                    <i class="fa fa-user"></i>
                                </a>
                            </li>


                        </button>

                        <h1 class="navbar-brand"><a href="/"><i class="fa fa-home"></i><img class="logo" src="/img/logo.png" class="img-responsive" style="margin-top:-6px;" /></a></h1>
                    </div>

                    <div class="collapse navbar-collapse" id="navbar-collapse-1">
                        <ul id="navigation" class="nav navbar-nav navbar-right">
                            <li class="" style="cursor:pointer;"><a onClick="javascript:window.location='/';return false;" class="current">首页</a></li>
                            <li class="" style="cursor:pointer;"><a onClick="javascript:window.location.href='/area';return false;">商品列表</a></li>
                            <li style="cursor:pointer;"><a onClick="javascript:window.location='/about/promise';return false;">品质承诺</a></li>
                            <li style="cursor:pointer;"><a onClick="javascript:window.location='/about/contact';return false;">联系我们</a></li>
<!--                            <li style="cursor:pointer;">-->
<!--                                <a onClick="javascript:window.location='/user/showorder';return false;">-->
<!--                                    <nobr> [[$curUser.mobile]]</nobr>-->
<!--                                </a>-->
<!--                            </li>-->
<!--                            <li style="cursor:pointer;">-->
<!--                                <a onClick="javascript:window.location='/user/logout';return false;">-->
<!--                                    <nobr> 退出</nobr>-->
<!--                                </a>-->
<!--                            </li>-->
                            <li>
                                <a style="cursor:pointer;" onClick="javascript:window.location='/user/';return false;">登录/注册</a>
                                <div id="app">
                                    <p>{{ message }}</p>
                                    <input v-model="message">
                                </div>

                            </li>

                        </ul>
                    </div>

                    <!-- </div> -->
                    <!-- row -->
                </div>
            </nav>
        </header>
        <script>
            new Vue({
                el: '#app',
                data: {
                    message: 'Hello Vue.js!'
                }
            })
        </script>
