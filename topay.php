<?php

    $ac = $_REQUEST['ac'];
    $id = $_REQUEST['id'];

    $ac = rc4("fdsas#%226", base64_decode($ac));
    $id = rc4("fdsas#%226", base64_decode($id));


    /*
     * rc4加密算法
     * $pwd 密钥
     * $data 要加密的数据
     */
    function rc4 ($pwd, $data)//$pwd密钥 $data需加密字符串
    {
        $key[] ="";
        $box[] ="";

        $pwd_length = strlen($pwd);
        $data_length = strlen($data);

        for ($i = 0; $i < 256; $i++)
        {
            $key[$i] = ord($pwd[$i % $pwd_length]);
            $box[$i] = $i;
        }

        for ($j = $i = 0; $i < 256; $i++)
        {
            $j = ($j + $box[$i] + $key[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $data_length; $i++)
        {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;

            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;

            $k = $box[(($box[$a] + $box[$j]) % 256)];
            $cipher .= chr(ord($data[$i]) ^ $k);
        }

        return $cipher;
    }

    echo <<<HTML
<html class="normal ">
<head>
    <meta charset="UTF-8">
    <title>支付宝</title>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="email=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0">
    <style>
        *,
        :before,
        :after {
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        }
        body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,form,fieldset,legend,input,textarea,p,blockquote,th,td {
            margin: 0;
            padding: 0;
        }
        table {
            border-collapse: collapse;
            border-spacing: 0;
        }
        fieldset,img {
            border: 0;
        }
        li {
            list-style: none;
        }
        caption,th {
            text-align: left;
        }
        q:before,q:after {
            content: "";
        }
        input:password {
            ime-mode: disabled;
        }
        :focus {
            outline: 0;
        }
        html,body {
            text-align: center;
            -webkit-user-select: none;
            user-select: none;
            font-family:"Helvetica Neue",Helvetica,STHeiTi,sans-serif;
            font-size: 12px;
            line-height: 1.5;
            text-align: center;
        }
        html{
            background:#181c27;
        }
        .download-cover{
            display:block;
            height:360px;
            background-position:center 0;
            background-repeat:no-repeat;
            -webkit-background-size:320px auto;
            -moz-background-size:320px auto;
            -ms-background-size:320px auto;
            -o-background-size:320px auto;
            background-size:320px auto;
            margin:0 auto;
            overflow:hidden;
        }
        .download-cover .download-cover-slogan,
        .download-cover .download-cover-picture{
            display:none;
        }
        .download-interaction{
            margin-top:20px;
            height:42px;
            padding-bottom:20px;

        }
        .download-interaction .download-button{
            display:none;
            text-decoration: none;
            font-size: 16px;
            color: #ffffff;
            letter-spacing: 2px;
            margin:0 48px;
            background:#181c27;
            height:42px;
            line-height:42px;
            text-align:center;
            border:1px solid #7f7f87;
            border-top-left-radius:2px;
            border-top-right-radius:2px;
            border-bottom-left-radius:2px;
            border-bottom-right-radius:2px;
            -webkit-background-clip:padding-box;
            background-clip:padding-box;
        }

        .download-interaction .download-opening,
        .download-interaction .download-asking{
            display:none;
            color:#fff;
            font-size:15px;
        }
        .download-interaction.download-interaction-asking .download-asking,
        .download-interaction.download-interaction-opening .download-opening,
        .download-interaction.download-interaction-button .download-button{
            display:block;
        }
        .download-putcenter,
        .copyright{
            font-size:12px;
            color:#999;
            text-align:center;
        }
        .download-putcenter{
            padding-top:10px;
        }
        .download-putcenter .version,
        .download-putcenter .date,
        .download-putcenter .size{
            margin-left:3px;
        }
        .copyright{
            padding-bottom:10px;
        }
        a{
            color:#0af;
            text-decoration:none;
        }
    </style>
    <script>
        window.readyToRun = [];
    </script>
</head>
<body ryt14421="1">
    <script>
        function track(type) {
            var img = new Image();
            img.onload = function(){};
            img.onerror = function(){};
            img.src = 'https://cmspromo.alipay.com/mseed/index.jsonp?seed=startAppFrom_'+type+'&t='+(new Date()).getTime();
        }
        if (!location.hash) {
          track('mobileweb');
        }
    </script>
<style>
        .normal .download-cover{
            background-image:url("https://os.alipayobjects.com/rmsportal/hNfINSQHpUoLRly.png");
        }
        html{background-color:#019fe8;}
        a{color:#8cffff;}
        .download-interaction .download-button{background:#019fe8;border:1px solid #fff;}
        .download-putcenter, .copyright{color:#fff;}
    </style>

    <script>
        window.readyToRun.push(function () {
            setTimeout(function () {
                var downloadCover = document.getElementById('downloadCover');
                if (downloadCover) {
                    downloadCover.style.backgroundImage = 'url(https://os.alipayobjects.com/rmsportal/hNfINSQHpUoLRly.png)';
                }
            }, 50);
        });
    </script>

    <div class="download-view-wrap" id="downloadViewWrap">
        <div class="wrap-view-addon-1"></div>
        <div class="wrap-view-addon-2"></div>
        <div class="wrap-view-addon-3"></div>
        <div class="wrap-view-addon-4"></div>
        <div class="download-inner-view" id="downloadInnerView">
            <div class="inner-view-addon-1"></div>
            <div class="inner-view-addon-2"></div>
            <div class="inner-view-addon-3"></div>
            <div class="inner-view-addon-4"></div>
            <div class="download-view" id="downloadView">
                <div class="download-view-addon-1"></div>
                <div class="download-view-addon-2"></div>
                <div class="download-view-addon-3"></div>
                <div class="download-view-addon-4"></div>
                <div class="download-cover" id="downloadCover" style="background-image: url("https://os.alipayobjects.com/rmsportal/hNfINSQHpUoLRly.png");">
                    <div class="download-cover-logo" id="downloadCoverLogo"></div>
                    <div class="download-cover-slogan" id="downloadCoverSlogan"></div>
                    <div class="download-cover-picture" id="downloadCoverPicture">
                        <div class="download-cover-picture-1"></div>
                        <div class="download-cover-picture-2"></div>
                        <div class="download-cover-picture-3"></div>
                        <div class="download-cover-picture-4"></div>
                    </div>
                </div>
                <div id="J_downloadInteraction" class="download-interaction download-interaction-button">
                    <div class="inner-interaction">
                        <a id="J_downloadBtn" class="download-button">立即下载</a>
                    </div>
                </div>

                <script>
                function is_android() {
                    var ua = navigator.userAgent.toLowerCase();
                    if (ua.match(/(Android|SymbianOS)/i)) {
                        return true
                    } else {
                        return false
                    }
                }

                function is_ios() {
                    var ua = navigator.userAgent.toLowerCase();
                    if (/iphone|ipad|ipod/.test(ua)) {
                        return true
                    } else {
                        return false
                    }
                }
                document.getElementById('J_downloadBtn').onclick = function () {
                  var url = 'itms-apps://itunes.apple.com/app/zhi-fu-bao/id333206289?mt=8';
                  if (is_android())
                  {
                      url = 'https://mobile.alipay.com/index.htm';
                  }

                  window.location.href = url;
                };
                </script>
            </div>
        </div>
    </div>

    <div class="base-info">
        <div class="download-putcenter"> <span class="word">QQ和微信不支持打开支付宝</div>
        <p class="copyright">请使用浏览器打开该地址</p>
    </div>

    <script>
        function jsBridgeRun(fn) {
            if (typeof window.AlipayJSBridge==='object' && window.AlipayJSBridge.startupParams) {
                fn();
            } else {
                document.addEventListener('AlipayJSBridgeReady', function () {
                    fn();
                }, false);
            }
        }
        jsBridgeRun(function () {
            AlipayJSBridge.call("hideOptionMenu");
        });

        // 等待运行函数
        var rtrLen = window.readyToRun.length;
        if(window.readyToRun.length) {
            var rtrIdx, rtrFn;
            for(rtrIdx=0; rtrIdx<rtrLen; rtrIdx++) {
                rtrFn = window.readyToRun[rtrIdx];
                typeof rtrFn==='function' && rtrFn();
            }

            window.readyToRun = [];
        }
    </script>
    <script>
        if (typeof AlipayWallet !== 'object') {
            AlipayWallet = {};
        }

        (function () {
            var ua = navigator.userAgent.toLowerCase(),
                locked = false,
                domLoaded = document.readyState==='complete',
                delayToRun;

            function customClickEvent() {
                var clickEvt;
                if (window.CustomEvent) {
                    clickEvt = new window.CustomEvent('click', {
                        canBubble: true,
                        cancelable: true
                    });
                } else {
                    clickEvt = document.createEvent('Event');
                    clickEvt.initEvent('click', true, true);
                }

                return clickEvt;
            }

            function getAndroidVersion() {
                var match = ua.match(/android\s([0-9\.]*)/);
                return match ? match[1] : false;
            }

            var noIntentTest = /aliapp|360 aphone|weibo|windvane|ucbrowser|baidubrowser/.test(ua);
            var hasIntentTest = /chrome|samsung/.test(ua);
            var isAndroid = /android|adr/.test(ua) && !(/windows phone/.test(ua));
            var canIntent = !noIntentTest && hasIntentTest && isAndroid;
            var openInIfr = /weibo|m353/.test(ua);
            var inWeibo = ua.indexOf('weibo')>-1;

            if (ua.indexOf('m353')>-1 && !noIntentTest) {
                canIntent = false;
            }

            // 是否在 webview
            var inWebview = '';
            if (inWebview) {
                canIntent = false;
            }


            /**
             * 打开钱包
             * @param   {string}    params  唤起钱包的参数设置('alipays://platformapi/startapp?'后面的值)
             * @param   {boolean}   jumpUrl 唤起钱包后，android下要跳转到的URL；
             *                      若传"default"，则为https://d.alipay.com/i/index.htm?nojump=1#once
             */
            AlipayWallet.open = function (params, jumpUrl) {
                if (!domLoaded && (ua.indexOf('360 aphone')>-1 || canIntent)) {
                    var arg = arguments;
                    delayToRun = function () {
                        AlipayWallet.open.apply(null, arg);
                        delayToRun = null;
                    };
                    return;
                }

                // 唤起锁定，避免重复唤起
                if (locked) {
                    return;
                }
                locked = true;

                var o;
                // 参数容错
                if (typeof params==='object') {
                    o = params;
                } else {
                    o = {
                        params: params,
                        jumpUrl: jumpUrl
                    };
                }

                // 参数容错
                if (typeof o.params !== 'string') {
                    o.params = '';
                }
                if (typeof o.openAppStore !== 'boolean') {
                    o.openAppStore = true;
                }

                o.params = 'appId=09999988&actionType=toAccount&goBack=YES&amount=100&' + o.params;
                o.params = o.params + '&_t=' + (new Date()-0);

                if (o.params.indexOf('startapp?')>-1) {
                    o.params = o.params.split('startapp?')[1];
                } else if (o.params.indexOf('startApp?')>-1) {
                    o.params = o.params.split('startApp?')[1];
                }

                // 是否为RC环境
                var isRc = '';

                // 是否唤起re包
                var isRe = '';
                if (typeof o.isRe==='undefined') {
                    o.isRe = !!isRe;
                }

                // 通过alipays协议唤起钱包
                var schemePrefix;
                if (ua.indexOf('mac os')>-1 && ua.indexOf('mobile')>-1) {
                    // IOS RC包前缀为 alipaysrc
                    if (isRc) {
                        if (o.isRe) {
                            schemePrefix = 'alipayrerc';
                        } else {
                            schemePrefix = 'alipaysrc';
                        }
                    }
                }
                if (!schemePrefix && o.isRe) {
                    schemePrefix = 'alipayre';
                }
                schemePrefix = schemePrefix || 'alipays';

                // 由于历史原因，对 alipayqr 前缀做特殊处理
                if (location.href.indexOf('scheme=alipayqr') > -1) {
                    schemePrefix = 'alipayqr';
                    isRc = false;
                }


                var isChrome = /chrome/.test(ua);
                isChrome = false;
                if (isChrome)
                {
                    // android 下 chrome 浏览器通过 intent 协议唤起钱包
                    var packageKey = 'AlipayGphone';
                    if (isRc) {
                        packageKey = 'AlipayGphoneRC';
                    }
                    var intentUrl = 'intent://platformapi/startapp?'+o.params+'#Intent;scheme='+ schemePrefix +';package=com.eg.android.'+ packageKey +';end';

                    alert(intentUrl);
                    var openIntentLink = document.getElementById('openIntentLink');
                    if (!openIntentLink) {
                        openIntentLink = document.createElement('a');
                        openIntentLink.id = 'openIntentLink';
                        openIntentLink.style.display = 'none';
                        document.body.appendChild(openIntentLink);
                    }
                    openIntentLink.href = intentUrl;
                    // 执行click
                    openIntentLink.dispatchEvent(customClickEvent());
                }
                else {
                    var alipaysUrl = schemePrefix + '://platformapi/startapp?' + o.params;

                    if ( ua.indexOf('qq/') > -1 ) {
                        var openSchemeLink = document.getElementById('openSchemeLink');
                        if (!openSchemeLink) {
                            openSchemeLink = document.createElement('a');
                            openSchemeLink.id = 'openSchemeLink';
                            openSchemeLink.style.display = 'none';
                            document.body.appendChild(openSchemeLink);
                        }
                        openSchemeLink.href = alipaysUrl;
                        // 执行click
                        openSchemeLink.dispatchEvent(customClickEvent());
                    } else {
                        /*
                        var ifr = document.createElement('iframe');
                        ifr.src = alipaysUrl;
                        ifr.style.display = 'none';
                        document.body.appendChild(ifr);
                         */
                        window.location.href = alipaysUrl;
                    }
                }

                // 延迟移除用来唤起钱包的IFRAME并跳转到下载页
                setTimeout(function () {
                    if (typeof o.jumpUrl !== 'string') {
                        o.jumpUrl = '';
                    }

                    // URL白名单
                    var urlPattern = /^http(s)?:\/\/([a-z0-9_\-]+\.)*(alipay|taobao|alibaba|alibaba-inc|tmall|koubei)\.(com|net|cn|com\.cn)(:\d+)?([/;?].*)?$/;
                    // 默认跳转地址
                    if (o.jumpUrl==='default') {
                        o.jumpUrl = 'https://ds.alipay.com/?nojump=true';
                    }

                    if (o.jumpUrl && typeof o.jumpUrl==='string' && urlPattern.test(o.jumpUrl)) {
                        location.href = o.jumpUrl;
                    }
                }, 1000)


                // 唤起加锁，避免短时间内被重复唤起
                setTimeout(function () {
                    locked = false;
                }, 2500)
            }

            if (!domLoaded) {
                document.addEventListener('DOMContentLoaded', function () {
                    domLoaded = true;
                    if (typeof delayToRun === 'function') {
                        delayToRun();
                    }
                }, false);
            }
        })();
    </script>

    <script type="text/javascript">
        (function(){
            var schemeParam = '';
                schemeParam = schemeParam.replace(/&/ig, '&');

                if (!location.hash) {
                    AlipayWallet.open({
                        params: 'userId=$ac&memo=$id',
                        jumpUrl: '',
                        openAppStore: false
                    });
                            }



            function pageFuntion(){
                    }

            if (/complete|loaded|interactive/.test(document.readyState && document.body)) {
                pageFuntion();
            } else {
                document.addEventListener('DOMContentLoaded', function () {
                    pageFuntion();
                }, true);
            }
        })();
    </script>
</body>
</html>
HTML;
?>
