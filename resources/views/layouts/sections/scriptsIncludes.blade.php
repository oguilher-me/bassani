@php
use Illuminate\Support\Facades\Vite;
@endphp
<!-- laravel style -->
@vite(['resources/assets/vendor/js/helpers.js'])

<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
@vite(['resources/assets/js/config.js'])

<!-- Fix for PerfectScrollbar error in menu.js -->
<script>
// Store original console.error
const originalConsoleError = console.error;
// Suppress PerfectScrollbar errors
console.error = function() {
    const msg = arguments[0];
    if (typeof msg === 'string' && (msg.includes('PerfectScrollbar') || msg.includes('Cannot read properties of undefined'))) {
        return;
    }
    originalConsoleError.apply(console, arguments);
};
</script>

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
