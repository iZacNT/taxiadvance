let summ = ['10000','9500','9000','8500','8000','7500','7000','6500','6000',
    '5500','5000','4500','4000','3500','3000','2500'];

$(".saveCompensation").on("click", function (){
    let data = getSummFromInput(summ);
    let resultAjax;
    sendAjax('/admin/compensation/save-compensation-summ',data).done(function (dataResult){
        resultAjax = dataResult;
    })
    console.log(resultAjax);
    toastr.success('Компенсации изменены!');
});

$(".updateCompensation").on("click", function (){
    let data = getSummFromInput(summ);
    let resultAjax;
    sendAjax('/admin/compensation/update-compensation-summ',data).done(function (dataResult){
        resultAjax = dataResult;
    })
    console.log(resultAjax);
    toastr.success('Компенсации изменены!');
});

function setSummToInput()
{
    data = getSummFromDb();
    $.each(data, function (index,value){
        for(var key in value){
            $("#day"+key).val(value[key].day);
            $("#night"+key).val(value[key].night);
        }
    })
}

function getSummFromDb()
{
    let data = {};
    let resultAjax;
    sendAjax('/admin/compensation/find-compensation-summ', data).done(function (dataResult){
        resultAjax = dataResult;
    })
    let lenghtArr = 0;
    for (var i = 0; i < resultAjax.length; i++) {
        lenghtArr += 1;
    }

    if (lenghtArr === 0){
        $(".saveCompensation").show();
        $(".updateCompensation").hide();
    }else{
        $(".saveCompensation").hide();
        $(".updateCompensation").show();
    }
    console.log(resultAjax);
    return resultAjax;
}

function getSummFromInput(summ)
{
    let result = {};
    $.each(summ, function(index,value){
       // console.log(value);
        result[value] = {
            day: getDayData(value),
           night: getNightData(value)
        }
    });
    return result;
}

function getDayData(value)
{
    let valueInput = $("#day"+value).val();
    message = "Введите правильную сумму Дневной компенсации для "+value+" руб.";
    return validationData(parseInt(valueInput),message);
}
function getNightData(value)
{
    let valueInput = $("#night"+value).val();
    message = "Введите правильную сумму Ночной компенсации для "+value+" руб.";
    return validationData(parseInt(valueInput),message);
}

function validationData(data, message){
    if (!data){
        alert(message);
        return false;
    }
    return data;
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
setSummToInput();