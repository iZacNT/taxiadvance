$("#recalculation").on("click", function (){
    console.log(getData());
});

function getData()
{
    return {
        // filial: filial,
        balanceYandex: getYaBalance(),
        bonusYandex: getYaBonus(),
        period: getPeriod(),
        typeDay: getTypeDay(),
        fuel: getFuel(),
        inputAmount: getInputAmount(),
        compensation: getCompensation(),
        plan:  getPlan(),
        hours: getHours(),
        percentDriver: getPercentDriver(),
        percentPark: getPercentPark(),
        carPhoneSumm: getCarPhoneSumm(),
        carFuelSumm: getCarFuelSumm(),
        carWash: getCarWash(),
        depo: getDepo(),

        // car_id: car_id,
        // shift_id: shift_id,
       // carMark: getCarMark(),
        debtFromShift: getDebtFromShift(),
    }
}

function getPercentDriver()
{
    return validateData($('#driverbilling-percent_driver').val(), "Введите правильный % Водителя!");
}

function getPercentPark()
{
    return validateData($('#driverbilling-percent_park').val(), "Введите правильный % Парка!");
}

function getDepo()
{
    return validateData($('#driverbilling-depo').val(), "Введите правильный Депо!");
}

function getPlan()
{
    return validateData($('#driverbilling-plan').val(), "Введите правильный план!");
}

function getCompensation()
{
    return validateData($('#driverbilling-compensations').val(), "Введите правильную компенсацию!");
}

function getYaBalance()
{
    return validateData($('#driverbilling-balance_yandex').val(), "Введите правильный баланс!");
}

function getYaBonus()
{
    return validateData($('#driverbilling-bonus_yandex').val(), "Введите правильный бонус!");
}

function getCarPhoneSumm()
{
    return validateData($('#driverbilling-car_phone_summ').val(), "Введите сумму потраченную на телефон!");
}

function getCarFuelSumm()
{
    return validateData($('#driverbilling-car_fuel_summ').val(), "Введите сумму потраченную на Топливо!");
}

function getCarWash()
{
    return validateData($('#driverbilling-car_wash').val(), "Введите сумму Неустойку!");
}

function getDebtFromShift()
{
    return validateData($('#driverbilling-debt_from_shift').val(), "Введите сумму Долга по смене!");
}

function getInputAmount()
{
    return validateData($('#driverbilling-input_amount').val(), "Введите сумму Всех заказов!");
}

function getFuel()
{
    return $('#driverbilling-fuel').val();
}

function getTypeDay()
{
    return $('#driverbilling-type_day').val();
}

function getPeriod()
{
    return $('#driverbilling-period').val();
}

function getHours()
{
    return $('#driverbilling-hours').val();
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

function sendAjax(url, data, type)
{
    return $.ajax({
        url: url,
        type: 'POST',
        data: data,
        async: false,
        dataType: type
    });
}