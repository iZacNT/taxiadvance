$(".resultData").on("click", "#saveDataButton", function (){
    let resultAjax;
    let resultObject = $.extend({}, getData(), getResultAjaxData());
    sendAjax( '/admin/driver/save-billing',resultObject).done(function (data){
        resultAjax = data;
    })
    if(resultAjax === true){
        toastr.success('Смена сохранена!');
        location.reload();
    }else{
        toastr.error('Смена уже сохранена, у Вас нет прав для ее изменения');
    }
    console.log(resultAjax);
});

function getData()
{
    return {
        filial: filial,
        balanceYandex: balanceYandex,
        bonusYandex: bonusYandex,
        depo: depo,
        car_id: car_id,
        shift_id: shift_id,
        carMark: getCarMark(),
        fuel: getFuel(),
        typeDay: getTypeDay(),
        period: getPeriod(),
        hours: getHours(),
        inputAmount: getInputAmount(),
        debtFromShift: getDebtFromShift(),
        carWash: getCarWash(),
        carFuelSumm: getCarFuelSumm(),
        carPhoneSumm: getCarPhoneSumm()
    }
}

function getResultAjaxData()
{
    return {
        summPark: $('#resultAjax').attr('data-summ-park'),
        summDriver: $('#resultAjax').attr('data-summ-driver'),
        percentPark: $('#resultAjax').attr('data-percent-park'),
        percentDriver: $('#resultAjax').attr('data-percent-driver'),
        plan: $('#resultAjax').attr('data-plan'),
        compensation: $('#resultAjax').attr('data-compensation'),
        billing: $('#resultAjax').attr('data-billing'),
        driverId: driverId
    }
}

$("#calculateShift").on("click",function (){
    let url = '/admin/driver/calculate-shift';
    let resultAjax;
    let data = getData();
    sendAjax(url, data).done(function(data){
        resultAjax = data
    });

    $("#planSumm").html(resultAjax.plan)

    if (resultAjax<0) {

        html = '<div class="info-box bg-gradient-success">\n' +
            '              <span class="info-box-icon"><i class="far fa-check-circle"></i></span>\n' +
            '\n' +
            '              <div class="info-box-content">\n' +
            '                <span class="info-box-text" style="font-size: 22px;">Выдать водителю: '+(resultAjax.billing*(-1))+' руб.' +
            '</span>\n' +
            '\n' +
            '              <!-- /.info-box-content -->\n' +
            '            </div>';
    }else{
        html = '<div class="info-box bg-danger">\n' +
            '              <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>\n' +
            '\n' +
            '              <div class="info-box-content">\n' +
            '                <span class="info-box-text" style="font-size: 22px;">Получить с водителя: '+resultAjax.billing+' руб.</span>\n' +
            '\n' +
            '              <!-- /.info-box-content -->\n' +
            '            </div>';
    }
    $("#resultAjax").html(html);
    $("#saveDataButton").show();

    $('#resultAjax').attr('data-summ-park', resultAjax.summPark);
    $('#resultAjax').attr('data-summ-driver', resultAjax.summDriver);
    $('#resultAjax').attr('data-percent-park', resultAjax.percentPark);
    $('#resultAjax').attr('data-percent-driver', resultAjax.percentDriver);
    $('#resultAjax').attr('data-plan', resultAjax.plan);
    $('#resultAjax').attr('data-billing', resultAjax.billing);
    $('#resultAjax').attr('data-compensation', resultAjax.compensation);

    console.log("Сумма парка: "+resultAjax.summPark);
    console.log("Сумма Водителя: "+resultAjax.summDriver);
    console.log("% парка: "+resultAjax.percentPark);
    console.log("% Водителя: "+resultAjax.percentDriver);
    console.log(">>>>>>>>>>Расчитали");
});

function getCarPhoneSumm()
{
    return validateData($('#carPhone').val(), "Введите сумму потраченную на телефон!");
}

function getCarFuelSumm()
{
    return validateData($('#carFuel').val(), "Введите сумму потраченную на Топливо!");
}

function getCarWash()
{
    return validateData($('#carWash').val(), "Введите сумму потраченную на Мойку!");
}

function getDebtFromShift()
{
    return validateData($('#debtFromShift').val(), "Введите сумму Долга по смене!");
}

function getInputAmount()
{
    return validateData($('#fromSummOrders').val(), "Введите сумму Всех заказов!");
}

function getFuel()
{
    return $('input[name=fuel]:checked').val();
}

function getTypeDay()
{
    return $('input[name=typeDay]:checked').val();
}

function getPeriod()
{
    return $('input[name=period]:checked').val();
}

function getHours()
{
    return $('input[name=hours]:checked').val();
}
function getCarMark()
{
    return validateCarMark($("#carMarkName").val());
}

function validateCarMark(data)
{
    if (!data){
        alert("Выберите марку автомобиля!");
        return false;
    }
    return data;
}

function validateData(data, message)
{
    if (parseInt(data) || parseInt(data) === 0){
        return data;
    }
    alert(message);
    return false;
}

function sendAjax(url, data)
{
    return $.ajax({
        url: url,
        type: 'POST',
        data: data,
        async: false,
        dataType: 'json'
    });
}