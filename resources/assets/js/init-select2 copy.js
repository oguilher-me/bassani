$(function() {
    $('select').select2({
        placeholder: "Selecione uma opção..."
    });
    
    const stationNameField = $('#station_name_field');
    const stationNameInput = stationNameField.find('input');
    
    $('#fuel_up_type').on('select2:select', function (e) {
        if ($(this).val() === 'Terceirizado') {
            $(stationNameField).removeClass('hide');
        } else {
            $(stationNameField).addClass('hide');
            $(stationNameInput).val(''); // Clear value when hidden
        }
    });

    $('#customer_type').on('select2:select', function (e) {
            const customerType = $('#customer_type').val();
            if (customerType === 'PF') {
                $('#pf_fields').show();
                $('#pj_fields').hide();
            } else if (customerType === 'PJ') {
                $('#pf_fields').hide();
                $('#pj_fields').show();
            } else {
                $('#pf_fields').hide();
                $('#pj_fields').hide();
            }
        });
});