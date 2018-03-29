-- http_clientd.lua
-- Created by yaxixi, 2018.3.5
-- http客户端请求模块

-- 声明模块名
module("HTTP_CLIENT_D", package.seeall);
declare_module(L_HTTP_CLIENT_D, "HTTP_CLIENT_D");

local TIMEOUT = 10;
local cookie_map = {};
local timer_id = -1;

local function timer_handle()
    local cur_time = os.time();

    -- 遍历 cookie_map，将超时的请求移除
    local timeout = TIMEOUT;
    for k,v in pairs(cookie_map) do
        timeout = v["timeout"] or TIMEOUT;
        if v["begin_time"] + timeout <= cur_time then
            -- 超时，需要移除

            local crt = v.crt;
            if crt then
                cookie_map[k] = nil;
                CRT_D.resume(crt, -2);
            end
        end
    end
end

local function when_notify_from_cpp(raiser, cookie, err, notify_type, notify)
    local record = cookie_map[tostring(cookie)];
    if not record then
        return;
    end

    -- 从 cookie_map 中移除该操作记录
    cookie_map[tostring(cookie)] = nil;

    local crt = record["crt"];
    if crt then
        CRT_D.resume(crt, err, notify);
        return;
    end
end

function post_crt(url, post_data)
    local cookie = new_cookie();
    local record = {
                     url          = url,
                     post_data    = post_data,
                     begin_time   = os.time(),
                     crt          = CRT_D.running(),
                     timeout      = timeout
    };

    -- 记录该操作
    cookie_map[tostring(cookie)] = record;

    curl_https_post(cookie, url, post_data);
    local err, result = CRT_D.yield();
    if err == 0 then
        return result;
    else
        print(R.."post_crt 失败 url : %o, err : %o, result : %o\n"..W, url, err, result);
        return false;
    end
end

function get_crt(url, header_list)
    local cookie = new_cookie();
    local record = {
                     url          = url,
                     begin_time   = os.time(),
                     crt          = CRT_D.running(),
                     timeout      = timeout
    };

    -- 记录该操作
    cookie_map[tostring(cookie)] = record;

    curl_https_get(cookie, url);
    local err, result = CRT_D.yield();
    if err == 0 then
        return result;
    else
        print(R.."get_crt 失败 url : %o, err : %o, result : %o\n"..W, url, err, result);
        return false;
    end
end

function test_precharge()
    --[[
    CRT_D.xpcall(function()
        local post_data = {
            orderid = NEW_RID(),
            orderuid = 'testuser',
            uid = 'A9D9113YR003',
            price = 100.01,
            istype = 1,
            notify_url = 'http://www.axixi.top/paynotify.php',
            return_url = 'http://www.axixi.top/payreturn.php',
            goodsname = 'testgoods',
        };
        local token = '7HHIH5IFC5A7';
        local str = string.format("%s%s%s%s%s%s%s%s%s", "testgoods", 1, 'http://www.axixi.top/paynotify.php', post_data.orderid, "testuser", 100.01, 'http://www.axixi.top/payreturn.php', token, 'A9D9113YR003');
        local key = md5.sumhexa(str);
        post_data.key = key;
        local post_str = generate_post_string(post_data);
        trace("test_precharge str : %o\n post_str : %o\n", str, post_str);
        local ret = post_crt("http://mpay.yituozhifu.com/mpay/precharge.php", post_str);
        trace("test_precharge :%o\n", json_decode(ret));
    end)]]
    CRT_D.xpcall(function()
        local post_data = {
            orderid = '20180329140519',
            orderuid = 'cs',
            uid = 17,
            istype = 1,
            notify_url = 'http://www.demo.com/paynotify.php',
            goodsname = 'phone',
        };
        local token = 'ef651247869e491bb61585697f845329';
        local str = string.format("%s%s%s%s%s%s%s", "phone", 1, 'http://www.axixi.top/paynotify.php', post_data.orderid, "testuser", token, 4);
        local key = md5.sumhexa(str);
        post_data.key = key;
        local post_str = generate_post_string(post_data);
        trace("test_precharge str : %o\n post_str : %o\n", str, post_str);
        local ret = post_crt("http://k.yituozhifu.com/api/pay", post_str);
        trace("test_precharge :%o\n", json_decode(ret));
    end);
end

function test_pay()
    CRT_D.xpcall(function()
        local data = {
            money = 190.07,
            fromName = "呀嘻嘻 yaxixi***@yahoo.com.cn",
            account = 'zhangqqi',
            remark = 'AA9D6X628006',
            time = '2018/03/05 21:07',
        };
        local data2 = {
            money = 190.07,
            fromName = "呀嘻嘻2 yaxixi***@yahoo.com.cn",
            account = 'zhangqqi',
            remark = 'A9PE0C008009',
            time = '2018/03/06 21:07',
        };
        local dataStr = json.encode({ json.encode(data), json.encode(data2) });
        local salt = "FDs4gd42";
        local key = md5.sumhexa(string.format("%s%s%s", dataStr, salt, "A9D9113YR003"));
        local post_data = {
            data = dataStr,
            uid = 'A9D9113YR003',
            key = key,
        };
        local post_str = generate_post_string(post_data);
        trace("test_pay str : %o\n post_str : %o\n", dataStr, post_str);
        --local ret = post_crt("http://mpay.yituozhifu.com/mpay/pay.php", post_str);
        local ret = post_crt("http://www.axixi.top/phpinfo.php", "");
        trace("test_pay :%o\n", json_decode(ret));
    end)
end

function test_get()
    CRT_D.xpcall(function()
        local r = NEW_RID();
        local key = md5.sumhexa(string.format("%s%s%s%s", "A9D9113YR003", "A9PMPW00800A", r, "7HHIH5IFC5A7"))
        local header_list = {
            "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A403 Safari/8536.25",
        };
        local ret = get_crt("https://mobile.alipay.com/index.htm");
        trace("test_get :%o\n", ret);
    end)
end

function test_post()
    CRT_D.xpcall(function()
        local r = NEW_RID();
        local key = md5.sumhexa(string.format("%s%s%s%s", "A9D9113YR003", "A9PMPW00800A", r, "7HHIH5IFC5A7"))
        local post_data = {
            r = r,
            key = key,
        };
        local post_str = generate_post_string(post_data);
        local ret = post_crt("http://localhost:10000/pay", post_str);
        trace("test_post :%o\n", json_decode(ret));
    end)
end

function destruct()
    if timer_id ~= -1 then
        delete_timer(timer_id);
        timer_id = -1;
    end

    remove_audience_from_raiser("msg_notify_from_cpp", "HTTP_CLIENT_D", {SF_NOTIFY_FROM_CPP});
end

-- 模块的入口执行
function create()
    register_as_audience("msg_notify_from_cpp", "HTTP_CLIENT_D", {
        SF_NOTIFY_FROM_CPP = when_notify_from_cpp,
    });

    timer_id = set_timer(1000, timer_handle, nil, true);
end

create();
