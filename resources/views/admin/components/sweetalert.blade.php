
@if(isset($errors) && ($errors->any() || Session::has('error')))
    @if(Session::has('error'))
        <script>
            var msg = "{!! Session::get('error') !!}";
            Swal.fire({
                icon: 'error',
                text: msg,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true
            });
        </script>
    @endif
    @if(isset($errors))
        @foreach($errors->all() as $error )
            <script>
                var msg = "{!! $error !!}";
                Swal.fire({
                    icon: 'error',
                    text: msg,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true
                });
            </script>
        @endforeach
    @endif
@endif

@if(Session::has('success'))
    <script>
        var msg = "{!! Session::get('success') !!}";
        Swal.fire({
            icon: 'success',
            text: msg,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true
        });
    </script>
@endif

@if(Session::has('warning'))
    <script>
        var msg = "{!! Session::get('warning') !!}";
        Swal.fire({
            icon: 'warning',
            text: msg,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 8000,
            timerProgressBar: true
        });
    </script>
@endif

@if(Session::has('info') || isset($info))
    <script>
        var msg = "{!! Session::get('info') ?? $info !!}";
        Swal.fire({
            icon: 'info',
            text: msg,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true
        });
    </script>
@endif

@if(Session::has('receipt_accept_alert'))
    @php($receiptAcceptAlert = Session::get('receipt_accept_alert'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(!empty($receiptAcceptAlert['warning']))
            Swal.fire({
                icon: 'success',
                title: @json($receiptAcceptAlert['message'] ?? 'تائيد شد.'),
                html: '<p class="mb-0">' + '</p><p class="text-warning mt-3 mb-0">' + @json($receiptAcceptAlert['warning']) + '</p>',
                confirmButtonText: 'باشه',
            });
            @else
            Swal.fire({
                icon: 'success',
                title: @json($receiptAcceptAlert['message'] ?? 'تائيد شد.'),
                confirmButtonText: 'باشه',
            });
            @endif
        });
    </script>
@endif
