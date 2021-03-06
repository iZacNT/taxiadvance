$(".resultData").on("click", "#saveDataButton", function (){
    let resultAjax;
    let data = getData();
    data.debtFromShift = $("#debtFromShift").val();
    let resultObject = $.extend({}, data, getResultAjaxData());
    sendAjax( '/admin/driver/save-billing',resultObject).done(function (data){
        resultAjax = data;
    })

    if(resultAjax){
        $.each(resultAjax, function (key,data){
                if(data['result'] === 'true'){
                    toastr.success(data['message']).delay(5000);
                    console.log(data['message'])
                }else{
                    toastr.error(data['message']).delay(5000);
                    console.log(data['message'])
                }
        })
        location.reload();
    }
});

function getData()
{
    return {
        filial: filial,
        balanceYandex: balanceYandex,
        bonusYandex: getYaBonus(),
        depo: depo,
        car_id: car_id,
        rolling: getRollingCar(),
        shift_id: shift_id,
        carMark: getCarMark(),
        fuel: getFuel(),
        typeDay: getTypeDay(),
        period: getPeriod(),
        hours: getHours(),
        inputAmount: getInputAmount(),
        debtFromShift: 0,
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

    html = '<div class="info-box bg-info">\n' +
        '              <span class="info-box-icon"><i class="fas fa-hand-holding-usd" style="font-size: 35px;"></i></span>' +
        '              <div class="info-box-content">' +
        '                <span class="info-box-number" style="font-size: 35px;">'+resultAjax.billing+' ??????.</span>' +
        '                <div class="progress">' +
        '                  <div class="progress-bar" style="width: 100%"></div>' +
        '                </div>' +
        '                <span class="progress-description">' +
        '                <label for="debtFromShift">???????? ???? ??????????(?????????? ???? ????????????????):</label>' +
        '                  <input type="text" id="debtFromShift" name="debtFromShift" value="0" class="form-control">' +
        '                </span>' +
        '              </div>' +
        '              <!-- /.info-box-content -->' +
        '            </div>'


    $("#resultAjax").html(html);
    $("#saveDataButton").show();

    $('#resultAjax').attr('data-summ-park', resultAjax.summPark);
    $('#resultAjax').attr('data-summ-driver', resultAjax.summDriver);
    $('#resultAjax').attr('data-percent-park', resultAjax.percentPark);
    $('#resultAjax').attr('data-percent-driver', resultAjax.percentDriver);
    $('#resultAjax').attr('data-plan', resultAjax.plan);
    $('#resultAjax').attr('data-billing', resultAjax.billing);
    $('#resultAjax').attr('data-compensation', resultAjax.compensation);

    console.log("?????????? ??????????: "+resultAjax.summPark);
    console.log("?????????? ????????????????: "+resultAjax.summDriver);
    console.log("% ??????????: "+resultAjax.percentPark);
    console.log("% ????????????????: "+resultAjax.percentDriver);
    console.log(">>>>>>>>>>??????????????????");
});

function getRollingCar()
{
    return validateData($('#rollingCar').val(), "?????????????? ???????????????????? ???????????? ????????????????????!");
}

function getYaBonus()
{
    console.log("?????????????????????? ???? ????????????: "+$('#yaBonus').val());
    return validateData($('#yaBonus').val(), "?????????????? ???????????????????? ??????????!");
}

function getCarPhoneSumm()
{
    return validateData($('#carPhone').val(), "?????????????? ?????????? ?????????????????????? ???? ??????????????!");
}

function getCarFuelSumm()
{
    return validateData($('#carFuel').val(), "?????????????? ?????????? ?????????????????????? ???? ??????????????!");
}

function getCarWash()
{
    return validateData($('#carWash').val(), "?????????????? ?????????? ?????????????????????? ???? ??????????!");
}

function getDebtFromShift()
{
    return validateData($('#debtFromShift').val(), "?????????????? ?????????? ?????????? ???? ??????????!");
}

function getInputAmount()
{
    return validateData($('#fromSummOrders').val(), "?????????????? ?????????? ???????? ??????????????!");
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
        alert("???????????????? ?????????? ????????????????????!");
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