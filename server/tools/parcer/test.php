<?php
    //parser.php

    // echo file_get_contents('http://project8540948.tilda.ws/instagram-podpishiki')

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

    $ch = curl_init('http://project8540948.tilda.ws/instagram-podpishiki');
    curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__ . '/cookie.txt');
    curl_setopt($ch, CURLOPT_COOKIEJAR, __DIR__ . '/cookie.txt');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 400);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $html = curl_exec($ch);
    curl_close($ch);

    require __DIR__.'/phpQuery/phpQuery/phpQuery.php';

    $pq = phpQuery::newDocument($html);

    echo $pq->find('.t123 script')[1]->text();   
    echo '<br/>'; 

    foreach ($pq->find('.t1070__col') as $item){
        $item = pq($item);
        $id_servis = $item->find('.t1070__col')->attr('id');
        $icon = $item->find('.t1070__img')->attr('src');
        $title = $item->find('.t-card__title div')->text();
        $description = $item->find('.t-card__descr')->text();
        $price = $item->find('.t1070__price')->text();

        echo $icon;
        echo '<br/>';
        echo $title;
        echo '<br/>';
        echo $description;
        echo '<br/>';
        echo $price;
        echo '<br/>';
        echo $id_servis;
        echo '<br/>';
        echo '<br/>';
    }

    

?>