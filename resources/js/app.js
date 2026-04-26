import './bootstrap';
import 'bootstrap';
import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

// DataTables
import DataTable from 'datatables.net-bs5';
window.DataTable = DataTable;

// SweetAlert (opcional)
import Swal from 'sweetalert2';
window.Swal = Swal;

// 🔥 CAPTURAR ERRORES JS (CRÍTICO)
window.addEventListener("error", function (e) {
    console.error("ERROR GLOBAL:", e.message);
});

// 🔥 ASEGURAR QUE LA PÁGINA NO QUEDE BLOQUEADA
document.addEventListener("DOMContentLoaded", function () {
    const loader = document.getElementById('loader');
    if (loader) {
        loader.style.display = 'none';
    }
});
