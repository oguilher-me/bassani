<!-- BEGIN: Vendor JS-->
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.min.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/jquery-mask/jquery.mask.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
@vite(['resources/assets/vendor/libs/popper/popper.js', 'resources/assets/vendor/js/bootstrap.js', 'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js', 'resources/assets/vendor/js/menu.js'])
@vite(['resources/assets/js/main.js'])
<script src="{{ asset('assets/js/plate-mask.js') }}"></script>

<!-- END: Theme JS-->
<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->

<script>
    window.flash = {};
    @if (session('success'))
        window.flash.success = "{{ session('success') }}";
    @endif
    @if (session('error'))
        window.flash.error = "{{ session('error') }}";
    @endif
</script>

<!-- app JS -->
@vite(['resources/js/app.js'])
<!-- END: app JS-->

<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.phone-mask').on('input', function() {
            var value = $(this).val().replace(/\D/g, ''); // Remove non-digits
            var formattedValue = '';

            if (value.length > 10) { // (00) 00000-0000
                formattedValue = '(' + value.substring(0, 2) + ') ' + value.substring(2, 7) + '-' + value.substring(7, 11);
            } else if (value.length > 6) { // (00) 0000-0000
                formattedValue = '(' + value.substring(0, 2) + ') ' + value.substring(2, 6) + '-' + value.substring(6, 10);
            } else if (value.length > 2) { // (00) 0000
                formattedValue = '(' + value.substring(0, 2) + ') ' + value.substring(2, 6);
            } else if (value.length > 0) { // (00)
                formattedValue = '(' + value.substring(0, 2);
            }

            $(this).val(formattedValue);
        });

        $('.cpf').on('input', function() {
            var value = $(this).val().replace(/\D/g, ''); // Remove non-digits
            var formattedValue = '';

            if (value.length > 9) { // 000.000.000-00
                formattedValue = value.substring(0, 3) + '.' + value.substring(3, 6) + '.' + value.substring(6, 9) + '-' + value.substring(9, 11);
            } else if (value.length > 6) { // 000.000.000
                formattedValue = value.substring(0, 3) + '.' + value.substring(3, 6) + '.' + value.substring(6, 9);
            } else if (value.length > 3) { // 000.000
                formattedValue = value.substring(0, 3) + '.' + value.substring(3, 6);
            } else if (value.length > 0) { // 000
                formattedValue = value.substring(0, 3);
            }

            $(this).val(formattedValue);
        });

        $('.cnpj').on('input', function() {
            var value = $(this).val().replace(/\D/g, ''); // Remove non-digits
            var formattedValue = '';

            if (value.length > 12) { // 00.000.000/0000-00
                formattedValue = value.substring(0, 2) + '.' + value.substring(2, 5) + '.' + value.substring(5, 8) + '/' + value.substring(8, 12) + '-' + value.substring(12, 14);
            } else if (value.length > 8) { // 00.000.000/0000
                formattedValue = value.substring(0, 2) + '.' + value.substring(2, 5) + '.' + value.substring(5, 8) + '/' + value.substring(8, 12);
            } else if (value.length > 5) { // 00.000.000
                formattedValue = value.substring(0, 2) + '.' + value.substring(2, 5) + '.' + value.substring(5, 8);
            } else if (value.length > 2) { // 00.000
                formattedValue = value.substring(0, 2) + '.' + value.substring(2, 5);
            } else if (value.length > 0) { // 00
                formattedValue = value.substring(0, 2);
            }

            $(this).val(formattedValue);
        });

        // Money mask for Brazilian currency
        $('.money-mask').on('input', function() {
            var value = $(this).val();
            value = value.replace(/\D/g, ''); // Remove non-digits
            value = value.replace(/(\d)(\d{2})$/, '$1,$2'); // Add comma before last 2 digits
            value = value.replace(/(?=(\d{3})+)(?!\d)/g, '.'); // Add dots for thousands
            $(this).val('R$ ' + value);
        });

        // Trigger masks on page load for pre-filled values
        $('.phone-mask').trigger('input');
        $('.cpf').trigger('input');
        $('.cnpj').trigger('input');
        $('.money-mask').trigger('input');

        $('.cnh').on('input', function() {
            var value = $(this).val().replace(/\D/g, ''); // Remove non-digits
            var formattedValue = '';

            if (value.length > 0) {
                formattedValue = value.substring(0, 11);
            }

            $(this).val(formattedValue);
        });

        // Flatpickr and Select2 initialization
        $.getScript("{{ asset('assets/vendor/libs/flatpickr/pt.js') }}", function() {
            $.getScript("{{ asset('assets/vendor/libs/select2/select2.js') }}", function() {
                flatpickr.setDefaults({
                    locale: flatpickr.l10ns.pt
                });

                flatpickr('.flatpickr-date', {
                    altInput: true,
                    altFormat: 'd/m/Y',
                    dateFormat: 'Y-m-d',
                });

                $('.select2').select2();
            });
        });

        // Logic for conditional required document_reference based on payment_status
        $('#payment_status').on('change', function() {
            if ($(this).val() === 'Contested') {
                $('#document_reference').prop('required', true);
            } else {
                $('#document_reference').prop('required', false);
            }
        }).trigger('change'); // Trigger on load to set initial state
    });
</script>