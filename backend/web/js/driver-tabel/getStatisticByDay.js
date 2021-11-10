$('.driver-tabel-index').on('click', '.statDate', function (){
    let data = {
        date: $(this).data('date')
    }
    sendAjax2('/admin/driver-tabel/generate-statistic-by-day',data);
    $('#modalSave').hide();
    $('#exampleModalCenter').modal('show');
});

function sendAjax2(url, data){
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        async: true,
        dataType: 'json',

        success: function(msg){
             console.log(msg);
                $(".modal-body").html(msg);
        }
    }); //ajax
}