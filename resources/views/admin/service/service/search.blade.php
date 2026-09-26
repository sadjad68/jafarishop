<div id="searchModal" class="modal fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content rounded-custom border-custom shadow bg-white">
            <div class="modal-header px-3 py-2">
                <h4 class="m-0">
                    جستجو
                </h4>
                <button type="button" class="close btn px-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg d-flex" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body p-2">
                <form method="GET" action="{{URL::current()}}" class="m-0">
                    <div class="row w-100 m-0">
                        <div class="col-lg-6 p-2">
                            <div class="form-group">
                                <x-cms-input
                                    name="title"
                                    label="عنوان"
                                    type="text"
                                />
                            </div>
                        </div>
                        <div class="col-lg-6 p-2">
                            <x-cms-select
                                name="parent_id"
                                label="خدمت والد"
                                :options="$all_services"
                                optionValue="id"
                                optionLabel="title"
                                :searchable="true"
                                :selectedOption="null"
                            />
                        </div>
                        <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-7 col-sm-10 col-12 ms-auto p-2">
                            <button type="submit" class="btn btn-custom rounded-custom w-100">
                                <i class="bi bi-search"></i>
                                جستجو
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
