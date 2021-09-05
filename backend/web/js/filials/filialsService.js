function searchTarifsAtFilial(filial){

    let data = {
        filial: filial
    }

    $.ajax({
        url: '/admin/day-plans/search-day-plans',
        type: 'POST',
        data: data,
        async: true,
        dataType: 'json',
        beforeSend: function() {
            $('#loader').show();
        },
        success: function(msg){
            $('#loader').hide();

            if (msg.length === 0) {
                $("#weekenDay12").val("");
                $("#weekenDay16").val("");
                $("#weekenNight12").val("");
                $("#weekenNight16").val("");

                $("#workingDay12").val("");
                $("#workingDay16").val("");
                $("#workingNight12").val("");
                $("#workingNight16").val("");

                $("#disabledCreateBtn").prop('style', 'display:block')
                $("#disabledUpdateBtn").prop('style', 'display:none')
            }else{
                msg.sort();

                $("#weekenDay12").val(msg[0].weekendDay.hours12);
                $("#weekenDay16").val(msg[0].weekendDay.hours16);
                $("#weekenNight12").val(msg[1].weekendNight.hours12);
                $("#weekenNight16").val(msg[1].weekendNight.hours16);

                $("#workingDay12").val(msg[2].workingDay.hours12);
                $("#workingDay16").val(msg[2].workingDay.hours16);
                $("#workingNight12").val(msg[3].workingNight.hours12);
                $("#workingNight16").val(msg[3].workingNight.hours16);

                $("#disabledCreateBtn").prop('style', 'display: none')
                $("#disabledUpdateBtn").prop('style', 'display:block')

            }
        }
    }) //ajax
}

function getData()
{
    let filial = $("#selectFilials").val();

    return {

        filial: filial,
        weekendDay: {
            'hour12': $("#weekenDay12").val(),
            'hour16': $("#weekenDay16").val()
        },
        weekenNight: {
            'hour12': $("#weekenNight12").val(),
            'hour16': $("#weekenNight16").val(),
        },
        workingDay: {
            'hour12': $("#workingDay12").val(),
            'hour16': $("#workingDay16").val(),
        },
        workingNight: {
            'hour12': $("#workingNight12").val(),
            'hour16': $("#workingNight16").val(),
        },
    };
}

function createTarifs()
{
    if(
        $("#weekenDay12").val() !== "" &&
        $("#weekenDay16").val() !== ""  &&
        $("#weekenNight12").val() !== ""  &&
        $("#weekenNight16").val() !== ""  &&

        $("#workingDay12").val() !== ""  &&
        $("#workingDay16").val() !== ""  &&
        $("#workingNight12").val() !== ""  &&
        $("#workingNight16").val() !== ""
    ){
        sendAjax('/admin/day-plans/create-day-plans', getData());
    }else{
        alert("Заполните все поля!");
    }
}

function updateTarifs()
{
    sendAjax('/admin/day-plans/update-day-plans', getData());
}

function sendAjax(url, data)
{
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        async: true,
        dataType: 'json',
        beforeSend: function() {
            $('#loader').show();
        },
        success: function(msg){
            console.log(msg);
            $('#loader').hide();
            searchTarifsAtFilial(data.filial);
        }
    }) //ajax
}

$(".day-plans-index").on("change", '#selectFilials', function(){
    let filial = $("#selectFilials").val();
    if (filial === ""){
        $("#weekenDay12").val("");
        $("#weekenDay16").val("");
        $("#weekenNight12").val("");
        $("#weekenNight16").val("");

        $("#workingDay12").val("");
        $("#workingDay16").val("");
        $("#workingNight12").val("");
        $("#workingNight16").val("");
        alert("Выберите филиал!")
        $(".dataElements").prop('disabled', true);
    }else{
        $(".dataElements").prop('disabled', false);
        searchTarifsAtFilial(filial);
    }
});

$("#disabledCreateBtn").on('click', function (){
    createTarifs();
});
$("#disabledUpdateBtn").on('click', function (){
    updateTarifs();
});