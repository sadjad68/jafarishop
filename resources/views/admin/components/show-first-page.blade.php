@php
    $theme_provider = app(\App\Modules\General\Helper\ThemeProvider::class);
@endphp
@if($theme_provider->hasSection('firstPageSections',$section))
<div class="col-12 p-2">
    <div class="form-group">
        <x-cms-check-box
            name="show_in_first_page"
            label="نمایش در صفحه اول"
            :valueData="@$data"
        />
    </div>
</div>
@endif
