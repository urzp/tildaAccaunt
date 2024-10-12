let url = 'https://smmbackmy.ru/php/outcom_payment.php'
content = {
    "quantity":"10",
    "quantity-text":"10 подписчиков в Ютубе",
    "link":"https:\/\/m.youtube.com\/channel\/UCKAoHbUhZSoixq63zGikwKw",
    "hours":"23",
    "rand":"124608",
    "endtime":"1717228749",
    "verify":"e7ac0786668e0ff0f02b62bd04f45ff636fd82db63b1104601c975dc005f3a67",
    "service":"11",
    "tranid":"4412478:6308254621",
    "formid":"form465448781",
    "api_k":"0234$567DAs"
}
let options = {
    type: 'POST',
    headers: { 'Content-Type': 'application/json;charset=utf-8' },
}
let response = await fetch(url,{
    method: options.type,
    headers: options.heders,
    body: JSON.stringify(content)
})
return await response.json();