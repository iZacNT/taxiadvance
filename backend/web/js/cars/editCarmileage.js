$(".cars-index").on('click', '.editMileage', function (){

let mileage=prompt("Введите пробег автомобиля!", "");

    if(!/[a-zа-яё]/i.test(mileage.trim()) && mileage.trim() !== ''){
        let resultAjax;
        let arr = {};
        arr[$(this).attr("data-field")] = mileage;
        console.log(arr)
        let data = {
            car_id: $(this).attr("data-id"),
            arr: {  Cars: arr
            }
        }

        sendAjax( '/admin/cars/update-mileage-attr',data, 'json').done(function (data){
            resultAjax = data;
        })
        console.log(resultAjax);
        $.pjax.reload({container: "#carsData", async:false});
    }else{
       alert("Ошика при указании пробега: "+mileage)
    }
});

$(".cars-index").on('click', '.editCurrentMileage', function (){

    if(confirm("Установить текущий пробег???", false)){
        let resultAjax;
        let arr = {};
        arr[$(this).attr("data-field")] = $(this).attr("data-mileage");
        console.log(arr)
        let data = {
            car_id: $(this).attr("data-id"),
            arr: {  Cars: arr
            }
        }

        sendAjax( '/admin/cars/update-mileage-attr',data, 'json').done(function (data){
            resultAjax = data;
        })
        console.log(resultAjax);
        $.pjax.reload({container: "#carsData", async:false});
    }else{
        alert("Ошика при указании пробега: "+mileage)
    }
});

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