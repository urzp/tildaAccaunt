const URL_UPDATE_PAGES = 'https://smmbakparse.ru/php/listPageWrite.php'
const URL_UPDATE_CARDS = 'https://smmbakparse.ru/php/parceCardProducts.php'
const AJAX_OPTIONS = { type: 'POST', headers: { 'Content-Type': 'application/json;charset=utf-8' }, }
let data = { folders, pages }

async function ajaxReq(url, options, content){
    let response = await fetch(url,{
        method: options.type,
        headers: options.heders,
        body: JSON.stringify(content)
    })
    return await response.json();
}

let result = await ajaxReq(URL_UPDATE_PAGES, AJAX_OPTIONS, data)
console.log(result.msg)
result = await ajaxReq(URL_UPDATE_CARDS, AJAX_OPTIONS, {})
console.log(result.msg)