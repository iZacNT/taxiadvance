$('.car-repairs-view').on('click', '#addParts', function(){
    $('#exampleModalLongTitle').text("Добавить запчасти: ")
    html = '<div class="input-group input-group-sm">' +
        '                  <input type="text" id="inputParts" class="form-control inputParts">' +
        '                </div>'+
        '<div id="resultParts"><br>' +
        '<table id="partsTable" class="table table-striped">' +
        '<thead>' +
        '<tr>' +
            '<th>Наименование детали</th>' +
            '<th>Остаток</th>' +
            '<th>Количество</th>' +
            '<th>Деиствия</th>' +
        '</tr>' +
        '</thead>' +
        '  <tbody>' +
        '  </tbody>' +
        '</table>' +
        '</div>'
    $(".modal-body").html(html);
    $(".modal-body").on('click', '.delParts',function(){
        $("tr[data-number=" + $(this).attr('data-number') + "]").remove();
    });

    $(".modal-body").on('change', '.countAddParts',function(){
        if (parseInt($(this).val()) > parseInt($(this).attr('data-count-on-stock'))){
            $(this).val($(this).attr('data-count-on-stock'));
        }
    });

    $('#exampleModalCenter').modal('show');

    $( "#inputParts" ).autocomplete({
        source: parts,
        appendTo : $('.modal-body'),
        select: function (event, ui) {
            let number = $('#partsTable > tbody > tr').length+1;
            let selectHtml = '<tr class="rowsWithParts" data-number="'+number+'" data-id="'+ui.item.id+'">' +
                '<td style="font-size: 14; font-weight: bold">'+ui.item.value+'</td>' +
                '<td>'+ui.item.count+' шт.</td>' +
                '<td><input type="text" data-count-on-stock="'+ui.item.count+'" class="form-control countAddParts" style="width: 100px;" value="1"></td>' +
                '<td><button class="btn btn-link delParts" data-number="'+number+'"><i class="far fa-trash-alt"></i></button></td>' +
                '</tr>'
            $('#partsTable > tbody:last-child').append(selectHtml);
        }
    });

    $("#modalSave").one("click", function(){
        let dataParts = [];

        let resultAjax;
        $('#partsTable > tbody > tr').each(
            function (){
                dataParts.push({
                    id_repair: $('#addParts').attr("data-id-repair"),
                    name_parts: $(this).attr("data-id"),
                    count: $(this).find('input[type="text"]').val()
                });
                $("#inputParts").val("");
                console.log(dataParts);
            }
        );
        console.log(dataParts);
        let data = {
            data: dataParts
        };
        sendAjax(
            '/admin/car-repairs/save-parts-for-repair',
            data,
            'json').done(function (data){ resultAjax = data; })

        if(resultAjax){
            $.each(resultAjax, function (key,data){
                if(data['type'] === 'true'){
                    toastr.success(data['message']).delay(5000);
                }else{
                    toastr.error(data['message']).delay(5000);
                }
            })
        }
        $.pjax.reload({container: "#carRepairsPjax", async:false});
        $('#exampleModalCenter').modal('hide');
        console.log(resultAjax);
    });
});

function sendAjax(url, data, type) {
    return $.ajax({
        url: url,
        type: 'POST',
        data: data,
        async: false,
        dataType: type
    }); //ajax
}