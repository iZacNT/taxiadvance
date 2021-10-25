$(".closeCashRegistry").on("click", function (){
    let cashNal = parseInt(prompt("Введите сумму в кассе:", ''));
    let resultAjax = 0;

    if(!isNaN(cashNal)){
        let data = {
            type_cash: 4,
            cashNal: cashNal,
            cashInRegister: cashRegister,
        }
        sendAjax( '/admin/cash-register/save-cash-register',data).done(function (data){
            resultAjax = data;
        })
    }else{
        alert("Вы ввели текст а не Сумму!")
    }
    console.log(resultAjax);
    $.pjax.reload({container: "#cashRegistryPjax", async:false});
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