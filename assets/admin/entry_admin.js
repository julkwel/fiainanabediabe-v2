import './css/admin_theme.css';

require('./vendor/jquery/jquery.min.js');
require('./vendor/bootstrap/js/bootstrap.bundle.min.js');
require('./vendor/jquery-easing/jquery.easing.min.js');
require('./js/sb-admin-2.min.js');
require('./vendor/chart.js/Chart.min.js');
require('./js/demo/chart-area-demo.js');
require('./js/demo/chart-pie-demo.js');
require('./vendor/datatables/jquery.dataTables.min.js');
require('./vendor/datatables/dataTables.bootstrap4.min.js');

$(document).ready(function() {
    $('#dataTable').DataTable();
});
