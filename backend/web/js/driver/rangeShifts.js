$("#addShifts").on('click',function(){
    $("#modalSave").off( "click")
    let resultAjax;
    let range = $("#dateRange").val();
    let rangeArr = range.split(" до ");
    let data = {
        driverId: $(this).attr("data-driver-id"),
        carId: $(this).attr("data-car-id"),
        range: range,
        from: rangeArr[0],
        to: rangeArr[1]
    }
    console.log(data);
    sendAjax2('/admin/driver/verify-range-shift', data, 'html').done(function (data){
        resultAjax = data;
    })

        $("#modalSave").one("click",function(){
            let dataFromRows = getDataFromRows();
            sendAjax2(
                '/admin/driver/save-range-shift',
                {
                    rangeData: data,
                    rowsData: dataFromRows
                    },
                'json').done(function (data){
                console.log(data)
                $('#exampleModalCenter').modal('hide');
                $.pjax.reload({container: "#allTabelDriver", async:false});
            })
        })
    $(".modal-body").html(resultAjax);
    let checkRD = checkRowData(resultAjax);
    if (checkRD) {
        checkRD = '<div style="color: red; font-size: 18px; font-weight: bold">Внимание есть занятые смены!!!</div>';
    }else {
        checkRD = "";
    }
    $('.modal-header').html('<div class="custom-control custom-checkbox">\n' +
        '                          <input class="custom-control-input" type="checkbox" id="checkbox" >\n' +
        '                          <label for="checkbox" class="custom-control-label">Отметить все</label>\n' +
        '                        </div>&#160;&#160;'+
        '                        <div class="custom-control custom-checkbox">\n' +
        '                          <input class="custom-control-input" type="checkbox" id="radioChenge" checked >\n' +
        '                          <label for="radioChenge" class="custom-control-label">День/Ночь</label>\n' +
        '                        </div>&#160;&#160;&#160;&#160;' +
        checkRD+
        '<button type="button" class="close" data-dismiss="modal" aria-label="Close">\n' +
        '                    <span aria-hidden="true">&times;</span>\n' +
        '                </button>');

    $('#exampleModalCenter').modal('show');

});


function verifyNotNull() //функция проверки на пустоту
{
    if ($(this).val().trim()){
        $("#addShifts").prop('disabled', false);
    }else{
        $("#addShifts").prop('disabled', true);
    }

}

$("#custom-tabs-one-all-tabel").on("change", "input.inputNotNull", function (){ //накидываем обработчик событий на класс inputNotNull
    $('.inputNotNull').each(
        verifyNotNull
    ); // проверяем все инпуты в posts-form с классом inputNotNull на пустоту
});

$(".modal-header").on("click", '#checkbox',function(){
    if ($(this).is(':checked')){
        $('.modal-body input:checkbox').prop('checked', true);
    } else {
        $('.modal-body input:checkbox').prop('checked', false);
    }
});

$(".modal-header").on("click", '#radioChenge',function(){
    let stat;
    if ($(this).is(':checked')){
        stat = 1;
    }else{
        stat = 2;
    }
    $('.rowSelectedDate').each(
        function() {changeRadioState(stat)}
    );
});

function changeRadioState(stat)
{
    if (stat === 1){
        $('input[type="radio"][value="1"]').prop('checked', true);
    } else {
        $('input[type="radio"][value="2"]').prop('checked', true);
    }
}

function getDataFromRows()
{
    let data = [];
    $('.rowSelectedDate').each(
        function() {
            if ($(this).find('input[type="checkbox"]').is(':checked')){
                data.push({
                    date: $(this).find('input[type="checkbox"]').val(),
                    period: $(this).find('input[type="radio"]:checked').val()
            });
            }
        });
    return data;
}

function checkRowData(html)
{
    let count = $(html).find('[data-busy="1"]')
    return count.length;
}

function sendAjax2(url, data, type)
{
    return $.ajax({
        url: url,
        type: 'POST',
        data: data,
        async: false,
        dataType: type
    });
}