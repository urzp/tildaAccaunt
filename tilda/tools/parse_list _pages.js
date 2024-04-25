let data = []

$('.td-project-pages-list .td-page__table').each(function(){
    let link =  $(this).find('.td-page__td-url a').text().split('/')[1] 
    let title = $(this).find('.td-page__td-title .td-page__td-title-span').text()
    data.push({
        link,
        title
    })
})



async function ajaxReq(url, options, content){
    let response = await fetch(url,{
        method: options.type,
        headers: options.heders,
        body: JSON.stringify(content)
    })
    return await response.json();
}

const URL_GET_BALANS = 'https://smmbackmy.ru/php/tools/listPageWrite.php'
const AJAX_OPTIONS = { type: 'POST', headers: { 'Content-Type': 'application/json;charset=utf-8' }, }
let result = await ajaxReq(URL_GET_BALANS, AJAX_OPTIONS, data)