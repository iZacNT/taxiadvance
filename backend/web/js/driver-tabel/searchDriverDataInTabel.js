$("#custom-tabs-one-tabel").on("click", ".searchDate", function (){
    let date = $(this).data("date");
    $('#exampleModalLongTitle').text("Поиск водителя: "+new Date(date*1000).toLocaleDateString())
    html = '<div class="input-group input-group-sm">' +
        '                  <input type="text" id="fioInput" class="form-control">' +
        '                  <span class="input-group-append">' +
        '                    <button type="button" id="searchFioButton" class="btn btn-info btn-flat">Найти водителя</button>' +
        '                  </span>' +
        '                </div>'+
        '<div id="resultSearchDriver"></div>'
    $(".modal-body").html(html);
    $( "#fioInput" ).autocomplete({
        source: drivers,
        appendTo : $('.modal-body'),
        select: function (event, ui) {
            $('#searchFioButton').attr('data-driver-id', ui.item.id);
            $('#searchFioButton').attr('data-date', date);
        }
    });
    $(".modal-body").on("click", "#searchFioButton", function (){
        let id = $(this).data("driver-id");
        let date = $(this).data("date");
        console.log(id);
        let data = {
            driver_id: id,
            date:date
        }
        sendAjax('/admin/driver-tabel/search-driver-by-date-and-fio',data)
    });
    $('#modalSave').hide();
    $('#exampleModalCenter').modal('show');
    console.log(date);
});

function sendAjax(url,data)
{
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        async: true,
        dataType: 'json',

        success: function(msg){
            console.log(msg);
            if(msg.length === 1 ){
                console.log(msg)
                let html = prepareSerchDriverResult(msg);
                $("#resultSearchDriver").html(html);
                html = '';
            }else{
                alert("Ничего не найдено!")
            }
        }
    }); //ajax
} //function

function prepareSerchDriverResult(msg)
{
    let html = '<div class="row mt-5">' +
        '          <div class="col-12">' +
        '            <div class="card">' +
        '<div class="card-header">' +
        '                <h3 class="card-title" style="font-weight: bolder">'+msg.car+'</h3>' +
        '              </div>'+
        '              <div class="card-body table-responsive p-0">' +
        '                <table class="table table-hover text-nowrap">' +
        '                  <thead>' +
        '                    <tr>' +
        '                      <th>Водитель</th>' +
        '                      <th>Смена</th>' +
        '                      <th>Статус</th>' +
        '                      <th>Закрытия</th>' +
        '                      <th>Топливо</th>' +
        '                      <th>Телефон</th>' +
        '                    </tr>' +
        '                  </thead>' +
        '                  <tbody>' +
        '                    <tr>' +
        '                      <td>'+msg.full_name+'</td>' +
        '                      <td>'+msg.shift+'</td>' +
        '                      <td>'+msg.status_shift+'</td>' +
        '                      <td>'+msg.close_shift+'</td>' +
        '                      <td>'+msg.card+'</td>' +
        '                      <td>'+msg.phone+'</td>' +
        '                    </tr>' +
        '                  </tbody>' +
        '                </table>' +
        '              </div>' +
        '              <!-- /.card-body -->' +
        '            </div>' +
        '            <!-- /.card -->' +
        '          </div>' +
        '        </div>';

    return html;
}