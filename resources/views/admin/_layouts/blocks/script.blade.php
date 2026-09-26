<script src="{{asset('assets/admin/js/jquery-3.3.1.min.js')}}"></script>
<script src="{{asset('assets/admin/js/libscripts.bundle.js')}}"></script>
<script src="{{asset('assets/admin/js/admin-theme.js')}}"></script>
<script src="{{asset('assets/admin/js/template.js')}}"></script>
<script src="{{asset('assets/admin/js/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('assets/admin/js/script.min.js')}}"></script>
<script src="{{asset('assets/admin/js/admin-dropzone.js')}}"></script>
<script src="{{asset('assets/admin/js/833bootstrap-select.min.js')}}"></script>

<script>
    $(document).ready(function() {
        // Prevent alert from closing dropdown
        $('.alert-dismissible .btn-close').click(function(event) {
            $(this).closest('.alert-dismissible').alert('close');
            event.stopPropagation();

            // Check if this is the last alert
            if ($('#drop-alert .alert-dismissible').length === 1) {
                dropClose();
            }
            if ($('#drop-alert2 .alert-dismissible').length === 1) {
                dropClose2();
            }
        });
    });

    function dropClose() {
        const alerts = document.getElementById('drop-alert');
        alerts.classList.remove('show');
    }
    function dropClose2(){
        const alerts2 = document.getElementById('drop-alert2');
        alerts2.classList.remove('show')
    }

    const toggle = document.getElementById('openMenu');
    const sidebar = document.getElementById('sidebar');
    if (toggle && sidebar) {
        toggle.addEventListener('click', function () {
            sidebar.classList.toggle('is-mini');
            sidebar.classList.remove('is-mini-open');
        });

        sidebar.addEventListener('mouseenter', function () {
            if (sidebar.classList.contains('is-mini')) {
                sidebar.classList.add('is-mini-open');
            }
        });

        sidebar.addEventListener('mouseleave', function () {
            if (sidebar.classList.contains('is-mini')) {
                sidebar.classList.remove('is-mini-open');
            }
        });
    }
</script>
{{--<script src="{{asset('assets/admin/js/form-validate.js')}}"></script>--}}
@stack('scripts')
