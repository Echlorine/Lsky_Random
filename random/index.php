<?php
    /*
     *  通过 Lsky 提供的图片列表接口随机返回一张图床中的图片
     */
    function curl_file_get_contents($url, $data){
        // 设置请求 header 标头
        $headers = array(
            # 2. 将此处修改为你的 (Bearer Token) ，否则无法通过验证
            "Authorization:Bearer 1|1bJbwlqBfnggmOMEZqXT5XusaIwqiZjCDs7r1Ob5"
        );
        // 添加get参数
        $url = $url.'?'.http_build_query($data);
        // 初始化
        $curl = curl_init();
        // 设置url路径
        curl_setopt($curl, CURLOPT_URL, $url);
        // 将 curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
        // 添加头信息
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        // CURLINFO_HEADER_OUT选项可以拿到请求头信息
        // curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        // 不验证SSL
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        // 执行
        $resp = curl_exec($curl);
        // 关闭连接
        curl_close($curl);
        // 解析结果
        $resp = json_decode($resp, true);
        $total = $resp['data']['total'];
        // 无返回值直接退出
        if ($total <= 0) {
            return false;
        }
        $array = array();
        $i = 0;
        foreach($resp['data']['data'] as $resp['data']['data']=>$value) {
            $array[$i++] = $value['links']['url'];
        }
        // 返回数据
        return $array[array_rand($array)];
    }

    function getCurrentUrl(){
        // 获取当前完整url
        $scheme = $_SERVER['REQUEST_SCHEME']; // 协议
        $domain = $_SERVER['HTTP_HOST']; // 域名/主机
        $requestUri = $_SERVER['REQUEST_URI']; // 请求参数
        // 将得到的各项拼接起来
        $currentUrl = $scheme . "://" . $domain . $requestUri;
        return $currentUrl; // 传回当前url
    }

    $query = parse_url(getCurrentUrl())["query"];
    // 解析参数并存到数组中
    parse_str($query, $data);

    // 1. 设置自己的 Lsky 接口URL
    $pic = curl_file_get_contents('****Lsky API URL****/images', $data);
    
    // 重定向页面
    header('content-type:text/html;charset=uft-8');
    if ($pic) {
        header('location:'.$pic);
    } else {
        // 如果返回为空，返回默认404图片
        // 3. 设置默认404图片
        // header('location:'.'404.png');
    }

?>