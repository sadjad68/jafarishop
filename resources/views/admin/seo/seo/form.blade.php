<a class="d-flex me-2 align-items-center btn btn-outline-primary gap-1"
   id="seoBtn{{$data->id}}" data-bs-target="#SeoModal{{$data->id}}" data-bs-toggle="modal"
   title="تنظیمات سئو"
>
    <span data-bs-toggle="tooltip" data-bs-title="تنظیمات سئو">
            <i class="d-flex bi bi-google color-custom2 fs-5"></i>
    </span>
</a>
@push('modals')
    {{-- Seo modal --}}
    <div id="SeoModal{{$data->id}}" class="modal fade" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content rounded-custom border-custom shadow bg-white">
                <div class="modal-header px-3 py-2">
                    <button type="button" class="close btn px-0" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg d-flex" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="modal-body p-2">
                    <form @submit.prevent="validateForm" method="POST" action="{{route('admin.seo.create')}}" class="m-0">
                        @csrf
                        <input type="hidden" name="seoable_id" value="{{ @$data->id }}">
                        <input type="hidden" name="seoable_type" value="{{ get_class(@$data) }}">
                        <div class="row w-100 m-0">
                            <div class="col-12 p-2">
                                <div class="form-group">
                                    <x-cms-input
                                        :class="'seoTitle'.$data['id']"
                                        name="seoTitle"
                                        label="عنوان سئو"
                                        :validations="['requiredCms']"
                                        type="text"
                                        :valueData="@$data"
                                    />
                                    <small id="smallTitle{{$data['id']}}" style="color: #0a58ca; font-size: 1rem; float: left; margin-top: 5px;">
                                    </small>
                                </div>
                            </div>
                            <div class="col-12 p-2">
                                <div class="form-group">
                                    <x-cms-input
                                        :class="'seoH1'.$data['id']"
                                        name="seoH1"
                                        label="H1"
                                        type="text"
                                        :valueData="@$data"
                                    />
                                    <small id="smallH1{{$data['id']}}" style="color: #0a58ca; font-size: 1rem; float: left; margin-top: 5px;"></small>
                                </div>
                            </div>

                            <div class="col-12 p-2">
                                <div class="form-group">
                                    <div class="form-group">
                                        <x-cms-text-area
                                            :class="'seoDes'.$data['id']"
                                            name="seoDescription"
                                            label="توضیحات سئو"
                                            :validations="['requiredCms']"
                                            type="text"
                                            :valueData="@$data"
                                        />
                                        <small id="smallDes{{$data['id']}}"
                                               style="color: #0a58ca; font-size: 1rem; float: left; margin-top: 5px;">
                                        </small>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="form-group">
                                        <x-cms-check-box
                                            name="seoIndex"
                                            label="عدم انتشار در ابزارهای جستجو"
                                            :valueData="@$data"
                                        />
                                    </div>
                                </div>
                                <div class="w-100 pe-0">
                                    <button type="submit"
                                            class="btn btn-custom rounded-custom w-fit px-3 py-2 float-end">
                                        ذخیره
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush
@push('scripts')
    @include('admin.seo.seo.seo-character-counter')
@endpush

