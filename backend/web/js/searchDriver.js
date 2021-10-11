$("#searchDriver").on("click", 'button.btn', function(){
    let data = {
        driver_license: $('#driverLicense').val()
    }

    $.ajax({
        url: '/admin/driver/search-driver-in-yandex',
        type: 'POST',
        data: data,
        async: true,
        dataType: 'json',
        beforeSend: function() {
            $('#loader').show();
        },
        success: function(msg){

            function sleep(milliseconds) {
                const date = Date.now();
                let currentDate = null;
                do {
                    currentDate = Date.now();
                } while (currentDate - date < milliseconds);
            }

            sleep(1000);

            console.log(msg.driver_profiles);
            if(msg.driver_profiles.length > 1 ){
                let html='<table class="table">\n' +
                    '  <thead class="thead-light">\n' +
                    '    <tr>\n' +
                    '      <th scope="col">#</th>\n' +
                    '      <th scope="col">Имя</th>\n' +
                    '      <th scope="col">Фамилия</th>\n' +
                    '      <th scope="col">Статус</th>\n' +
                    '      <th scope="col">Комментарий</th>\n' +
                    '      <th scope="col"></th>\n' +
                    '    </tr>\n' +
                    '  </thead>\n' +
                    '  <tbody>';
                msg.driver_profiles.forEach(function(item, i, arr) {

                    html += '<tr>\n' +
                    '      <th scope="row">'+i+'</th>\n' +
                    '      <td>'+arr[i].driver_profile.first_name+'</td>\n' +
                    '      <td>'+arr[i].driver_profile.last_name+'</td>\n' +
                    '      <td>'+arr[i].driver_profile.work_status+'</td>\n' +
                    '      <td>'+arr[i].driver_profile.comment+'</td>\n' +
                    '      <td><button class="btn btn-primary getDriver">Выбрать</button></td>\n' +
                    '    </tr>';
                });
                html += '</tbody>\n' +
                    '</table>';
                $('.modal-body').html(html)

                $('#exampleModalCenter').modal('show');
            }
            $("#driver-first_name").val(msg.driver_profiles[0].driver_profile.first_name);
            $("#driver-last_name").val(msg.driver_profiles[0].driver_profile.last_name);
            $("#driver-yandex_id").val(msg.driver_profiles[0].driver_profile.id);
            $("#driver-phone").val(msg.driver_profiles[0].driver_profile.phones[0]);
            $("#driver-driving_license").val(msg.driver_profiles[0].driver_profile.driver_license.normalized_number);

            $('#loader').hide();
        }
    }) //ajax
});