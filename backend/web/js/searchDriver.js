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

            $("#driver-first_name").val(msg.driver_profiles[0].driver_profile.first_name);
            $("#driver-last_name").val(msg.driver_profiles[0].driver_profile.last_name);
            $("#driver-yandex_id").val(msg.driver_profiles[0].driver_profile.id);
            $("#driver-phone").val(msg.driver_profiles[0].driver_profile.phones[0]);
            $("#driver-driving_license").val(msg.driver_profiles[0].driver_profile.driver_license.normalized_number);

            $('#loader').hide();
        }
    }) //ajax
});