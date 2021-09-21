
$("#goToRepair").on("click", function (){
    let resultAjax = false;
    let data = {
            car_id: car_id,
        }
    if(confirm("Отправить автомобиль в Ремонт?")){
    sendAjax( '/admin/cars/go-to-repair',data).done(function (data){
            resultAjax = data;
        })

    console.log(resultAjax);
    $.pjax.reload({container: "#carRepair", async:false});
    }

});

$("#closeRepair").on("click", function (){
    let resultAjax = false;
    let data = {
        car_id: car_id,
    }
    if(confirm("Закончить Ремонт?")){
        sendAjax( '/admin/cars/close-repair',data).done(function (data){
            resultAjax = data;
        })

        console.log(resultAjax);
        location.reload();
    }
});

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