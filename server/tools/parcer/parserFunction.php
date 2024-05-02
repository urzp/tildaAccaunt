<?php
require __DIR__.'/phpQuery/phpQuery/phpQuery.php';
 
function selectCurrency($text){
    $currency = [];
    $currency[0] = array(
        'much_symb'=> '₽',
        'result_symb'=> 'руб'
    );

    foreach($currency as $item){
        $much_symb = $item['much_symb'];
        $reg = "[$much_symb]";
        if(preg_match($reg, $text, $matches)){
            $res = $item['result_symb'];
            return $res;
        }
    }

    return '';
}

function pesePage($homeUrl, $page_url){
    //sleep(1);
   
    $headers = array(
        'cache-control: max-age=0',
        'upgrade-insecure-requests: 1',
        'user-agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97 Safari/537.36',
        'sec-fetch-user: ?1',
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/web,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
        'x-compress: null',
        'sec-fetch-site: none',
        'sec-fetch-mode: navigate',
        'accept-encoding: derflate, br',
        'accept-language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7'
    );

    $url = $homeUrl.$page_url;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__ . '/cookie.txt');
    curl_setopt($ch, CURLOPT_COOKIEJAR, __DIR__ . '/cookie.txt');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 400);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $html = curl_exec($ch);
    curl_close($ch);
    $pq = phpQuery::newDocument($html);
    
    if(!isset($pq->find('.t1070__col')[0])){ 
        return []; 
    };

    $sctipt = $pq->find('.t123 script')[0]->text();  

    $type = '';
    $str = $sctipt;
    $f_begin = 'name="type" value="';
    $f_end = '"';
    $pos_beg = strpos($str, $f_begin);
    if($pos_beg > 0){
        $pos_beg = $pos_beg + strlen($f_begin);
        $str  = substr($str, $pos_beg);
        $pos_end = strpos($str, $f_end );
        $type  = substr($str, 0, $pos_end);
    }
    
    $f_begin   = 'let array = {';
    $f_end   = 'k = 1';
    $pos_beg = strpos($sctipt, $f_begin)+14;
    $pos_end = strpos($sctipt, $f_end) - $pos_beg - 2;
    $str =  substr($sctipt, $pos_beg, $pos_end);
    $str = str_replace('}','',$str);
    $str = preg_replace('/(\ \/\*.*\*\/)/s', '',  $str);

    $arr = explode("],", $str);
    $arr_servis = [];

    $i = 0;
    foreach($arr as $item){
        $item = explode('" : ', $item)[1];
        $item = str_replace('[','',$item);
        $item = str_replace(']','',$item);
        $item = explode(', ', $item);
        if($item[0]!= '1'){
            $arr_servis[$i] = $item;
            $i++;
        }  
    }

    $result  = [];
    $i=0;
    foreach ($pq->find('.t1070__col') as $item){
        $item = pq($item);
        $icon = $item->find('.t1070__img')->attr('src');
        $title = $item->find('.t-card__title div')->text();
        $description = $item->find('.t-card__descr')->text();
        $price_title = $item->find('.t1070__price')->text();
        $price = preg_replace('/[^0-9,]+/', '', $price_title);
        $price =  str_replace(',', '.', $price);
        $currency = selectCurrency($price_title);
        $result[$i] = array(
            'icon' => $icon,
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'type' => $type,
            'id_provider' => $arr_servis[$i][1],
            'id_servis' => $arr_servis[$i][0],
            'currency' => $currency,
        );
        $i++;
    }



    return $result;
}

?>