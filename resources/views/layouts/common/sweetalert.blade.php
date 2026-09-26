
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
                timer: 5000
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
                    timer: 5000
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
            timer: 5000
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
            timer: 5000
        });
    </script>
@endif
