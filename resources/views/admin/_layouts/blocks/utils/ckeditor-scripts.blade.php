<script src="{{ asset('assets/admin/ckeditor/ckeditor.js') }}"></script>
<script>
    setTimeout(function(){
        var cks = document.getElementsByClassName('ckeditor4');
        Array.from(cks).forEach((el) => {
            CKEDITOR.replace(el.id, {
                language: 'fa',
                removeButtons: 'Font,PasteFromWord',
                filebrowserUploadUrl: "{{ route('admin.ckeditor.upload', ['_token' => csrf_token() ]) }}",
                filebrowserUploadMethod: 'form',
                on: {
                    instanceReady: function(event) {
                        event.editor._.forcePasteDialog = true;
                    },
                    maximize: function(event) {
                        setTimeout(function () {


                            var toolbar = document.querySelector('.cke_top');
                            if (toolbar) {
                                toolbar.style.zIndex = '100000';
                            }

                            var iframe = document.querySelector('.cke_wysiwyg_frame');
                            if (iframe) {
                                iframe.contentWindow.document.body.style.height = '100%';
                            }

                            // اطمینان از این که pointer-events درست تنظیم شده باشد
                            document.body.style.pointerEvents = 'auto';
                        }, 100);
                    }
                }
            });
        });
    }, 100);
</script>
