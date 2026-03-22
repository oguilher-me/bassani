import './bootstrap';
import Sortable from 'sortablejs';

window.Sortable = Sortable;
import jQuery from 'jquery';
import Swal from 'sweetalert2';

window.$ = window.jQuery = jQuery;
window.Swal = Swal;

// Import DataTables
import 'datatables.net-bs5';

// Import other vendor scripts
import '../assets/vendor/js/helpers.js';
import '../assets/js/config.js';
import '../assets/vendor/libs/popper/popper.js';
import '../assets/vendor/js/bootstrap.js';
import '../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js';
import '../assets/vendor/js/menu.js';
import '../assets/js/main.js';

/*
  Add custom scripts here
*/
import.meta.glob([
    '../assets/img/**',
    // '../assets/json/**',
    '../assets/vendor/fonts/**'
]);

// SweetAlert2 flash messages
document.addEventListener('DOMContentLoaded', function () {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    // Check for success message
    if (window.flash && window.flash.success) {
        Toast.fire({
            icon: 'success',
            title: window.flash.success
        });
    }

    // Check for error message
    if (window.flash && window.flash.error) {
        Toast.fire({
            icon: 'error',
            title: window.flash.error
        });
    }
});
