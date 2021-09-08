$(".calculations-index").on("click", '.markButton', function(){
    let mark = $(this).data('mark');
    let filial = $("#selectFilials").val();
    if (filial===""){
        alert("Выберите филиал!");
    }else{
        let resultAjax;
        sendAjax('/admin/calculation/search-calculation-for-mark', {mark: mark, filial: filial}).done(function(data){
            resultAjax = data
        });

        if(resultNotNull(resultAjax)){
            console.log(resultAjax);
            $("#updateTarifs").off("click.myClick");
            $("#updateTarifs").on("click.myClick", function(){
                updateTarifs(filial, mark);
            });
            $("#createTarifs").prop('style', "display:none");
            $("#updateTarifs").prop('style', "display:block");
            clearDataFields();
            setDataToInput(resultAjax)
        }else{
            console.log("null");
            $("#createTarifs").off("click.myClick");
            $("#createTarifs").on("click.myClick", function(){
                createTarifs(filial, mark);
            });
            $("#createTarifs").prop('style', "display:block");
            $("#updateTarifs").prop('style', "display:none");
            clearDataFields();
        }

    }
});

function clearDataFields(){
    $("input.dataItemCalculate").val("");
}

function getDataTarifs(filial, carMark){
    let dayLessPlanDayGas = {
        park: $("#gasLessPlanDayPPark").val(),
        driver: $("#gasLessPlanDayPDriver").val()
    }
    let dayLessPlanDayGasoline = {
        park: $("#gasolineLessPlanDayPPark").val(),
        driver: $("#gasolineLessPlanDayPDriver").val()
    }

    let nightLessPlanGas = {
        park: $("#gasLessPlanNightPPark").val(),
        driver: $("#gasLessPlanNightPDriver").val()
    }
    let nightLessPlanGasoline = {
        park: $("#gasolineLessPlanNightPPark").val(),
        driver: $("#gasolineLessPlanNightPDriver").val()
    }

    let dayBiggerPlanGas = {
        park: $("#gasBiggerPlanDayPPark").val(),
        driver: $("#gasBiggerPlanDayPDriver").val()
    }
    let dayBiggerPlanGasoline = {
        park: $("#gasolineBiggerPlanDayPPark").val(),
        driver: $("#gasolineBiggerPlanDayPDriver").val()
    }

    let nightBiggerPlanGas = {
        park: $("#gasBiggerPlanNightPPark").val(),
        driver: $("#gasBiggerPlanNightPDriver").val()
    }
    let nightBiggerPlanGasoline = {
        park: $("#gasolineBiggerPlanNightPPark").val(),
        driver: $("#gasolineBiggerPlanNightPDriver").val()
    }

    return {
        filial: filial,
        carMark: carMark,
        dayLessPlan: {
            'dayLessPlanDayGas': dayLessPlanDayGas,
            'dayLessPlanDayGasoline': dayLessPlanDayGasoline
        },
        nightLessPlan: {
            'nightLessPlanGas': nightLessPlanGas,
            'nightLessPlanGasoline': nightLessPlanGasoline
        },
        dayBiggerPlan: {
            'dayBiggerPlanGas': dayBiggerPlanGas,
            'dayBiggerPlanGasoline': dayBiggerPlanGasoline
        },
        nightBiggerPlan: {
            'nightBiggerPlanGas': nightBiggerPlanGas,
            'nightBiggerPlanGasoline': nightBiggerPlanGasoline
        }
    };

}

function resultNotNull(data)
{
    let result = 0
    if (data.getDayLessPlan.DayLessPlanGas.length !== 0) { result += 1 }
    if (data.getDayLessPlan.DayLessPlanGasoline.length !== 0) { result += 1 }
    if (data.getNightLessPlan.NightLessPlanGas.length !== 0) { result += 1 }
    if (data.getNightLessPlan.NightLessPlanGasoline.length !== 0) { result += 1 }

    if (data.getDayBiggerPlan.DayBiggerPlanGas.length !== 0) { result += 1 }
    if (data.getDayBiggerPlan.DayBiggerPlanGasoline.length !== 0) { result += 1 }
    if (data.getNightBiggerPlan.NightBiggerPlanGas.length !== 0) { result += 1 }
    if (data.getNightBiggerPlan.NightBiggerPlanGasoline.length !== 0) { result += 1 }

    return result !== 0;

}

function setDataToInput(data)
{
    $("#gasLessPlanDayPPark").val(data.getDayLessPlan.DayLessPlanGas[0].calculation_park);
    $("#gasLessPlanDayPDriver").val(data.getDayLessPlan.DayLessPlanGas[0].calculation_driver);

    $("#gasolineLessPlanDayPPark").val(data.getDayLessPlan.DayLessPlanGasoline[0].calculation_park);
    $("#gasolineLessPlanDayPDriver").val(data.getDayLessPlan.DayLessPlanGasoline[0].calculation_driver);

    $("#gasLessPlanNightPPark").val(data.getNightLessPlan.NightLessPlanGas[0].calculation_park);
    $("#gasLessPlanNightPDriver").val(data.getNightLessPlan.NightLessPlanGas[0].calculation_driver);

    $("#gasolineLessPlanNightPPark").val(data.getNightLessPlan.NightLessPlanGasoline[0].calculation_park);
    $("#gasolineLessPlanNightPDriver").val(data.getNightLessPlan.NightLessPlanGasoline[0].calculation_driver);

    $("#gasBiggerPlanDayPPark").val(data.getDayBiggerPlan.DayBiggerPlanGas[0].calculation_park);
    $("#gasBiggerPlanDayPDriver").val(data.getDayBiggerPlan.DayBiggerPlanGas[0].calculation_driver);

    $("#gasolineBiggerPlanDayPPark").val(data.getDayBiggerPlan.DayBiggerPlanGasoline[0].calculation_park);
    $("#gasolineBiggerPlanDayPDriver").val(data.getDayBiggerPlan.DayBiggerPlanGasoline[0].calculation_driver);

    $("#gasBiggerPlanNightPPark").val(data.getNightBiggerPlan.NightBiggerPlanGas[0].calculation_park);
    $("#gasBiggerPlanNightPDriver").val(data.getNightBiggerPlan.NightBiggerPlanGas[0].calculation_driver);

    $("#gasolineBiggerPlanNightPPark").val(data.getNightBiggerPlan.NightBiggerPlanGasoline[0].calculation_park);
    $("#gasolineBiggerPlanNightPDriver").val(data.getNightBiggerPlan.NightBiggerPlanGasoline[0].calculation_driver);

}

function createTarifs(filial, carMark)
{
    let data = getDataTarifs(filial, carMark);
    let result;
    sendAjax('/admin/calculation/create-tarifs-for-mark', data).done(function(data){
        result = data
    });
    console.log(result);
}

function updateTarifs(filial, carMark)
{
    let data = getDataTarifs(filial, carMark);
    let result;
    sendAjax('/admin/calculation/update-tarifs-for-mark', data).done(function(data){
        result = data
    });
    console.log(result);
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