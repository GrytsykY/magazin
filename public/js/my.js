/** Подтверждение удаления заказа */
$('.delete').click(function () {
    var res = confirm('Подтвердите действия!');
    if (!res) return false;
});

/** Редактирование заказа */
$('.redact').click(function () {
    var res = confirm('Вы можете только изменить Комментарий!');
    return false;
});

/** Подтверждение удаления заказа из БД */
$('.deletebd').click(function () {
    var res = confirm('Подтвердите действия!');
    if (res){
        var ress = confirm('ВЫ УДАЛИТЕ ЗАКАЗ ИЗ БД');
        if (!ress) return false;
    }
    if (!res) return false;
})