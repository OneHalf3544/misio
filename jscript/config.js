/* Удаляет рекламу из загруженного содержимого (нужно для использования AJAX)
 * На вход функция принимает полученный блок данных, а возвращает блок с отсеченным рекламным содержимым
 */
  function removeAdvertisement (data) {
    data = data.substr(0, data.indexOf("!-- ><!-")-1); //Отрезаем рекламу для AJAX запросов
    return data;
  }

var AJAXBackendURL = '/test2.php';

/*
 * По нажатию Tab, Shift+Tab меняется отступ в textarea
 */
$(document).ready(function() {
  $('#LogName').focus(function () { // Убирает строку-подсказку при получении фокуса
    if ($("#LogName").attr("value") == "Введите имя...")
        $("#LogName").attr("value", "");
    });

    $('textarea').keydown(function (event) {
        if (event.keyCode != 9 || event.ctrlKey || event.altKey) return;
        var target = event.target;
        if (!/textarea/i.test(target.nodeName)) return;

        var start = target.selectionStart;
        var end = target.selectionEnd;
        if (start == end) {
            // Тут надо бы добавить вставку группы пробелов
            return;
        }

        var text = target.value.substring(start, end);
        if (!/[\n\r]/.test(text)) return;

        if (!event.shiftKey) {
            text = '    ' + text.replace(/([\r\n]+)/g, '$1    ').replace(/([\r\n]+)    $/, '$1');
        } else {
            text = text.replace(/(^|[\r\n]+)    /g, '$1');
        }

        target.value = target.value.substring(0, start) + text + target.value.substring(end);
        target.selectionStart = start;
        target.selectionEnd = start + text.length;

        event.preventDefault();
        if (window.opera && window.opera.postError) {
            setTimeout(function(){
                target.focus()
                }, 0);
        }
    });
});

function getCookie(name) {
    var cookie = " " + document.cookie;
    var search = " " + name + "=";
    var setStr = null;
    var offset = 0;
    var end = 0;
    if (cookie.length > 0) {
        offset = cookie.indexOf(search);
        if (offset != -1) {
            offset += search.length;
            end = cookie.indexOf(";", offset)
            if (end == -1) {
                end = cookie.length;
            }
            setStr = unescape(cookie.substring(offset, end));
        }
    }
    return(setStr);
}

function setCookie (name, value, expires, path, domain, secure) {
    document.cookie = name + "=" + escape(value) +
    ((expires) ? "; expires=" + expires : "") +
    ((path) ? "; path=" + path : "") +
    ((domain) ? "; domain=" + domain : "") +
    ((secure) ? "; secure" : "");
}
