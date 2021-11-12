$("#allShifts").on("click", ".verifyBtn", function (){
    let resultAjax;
    let data = {
        user_id: $(this).data("user"),
        model_id: $(this).data("model")
    };
    sendAjax( '/admin/driver/verify-billing',data).done(function (data){
        resultAjax = data;
    })
    if(resultAjax == true){
        toastr.success('Подписанно');
    }else{
        toastr.error('Ошибка!');
    }
    console.log(resultAjax);
    $.pjax.reload({container: "#allShifts", async:false});
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