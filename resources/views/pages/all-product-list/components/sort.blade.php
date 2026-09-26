<div class="sort sort-toolbar d-flex align-items-center justify-content-lg-end justify-content-start gap-2 flex-wrap">
    <p class="m-0 sort-toolbar__label d-flex align-items-center">
        <span class="sort-toolbar__mark" aria-hidden="true"></span>
        مرتب‌سازی
    </p>
    <ul class="p-0 m-0 d-flex align-items-center gap-2 sort-toolbar__options">
        <li class="list-unstyled">
            <div class="form-check mb-0">
                <input class="form-check-input" type="radio" value="newest" name="" v-model="sortBy" id="sort-item1">
                <label class="form-check-label font-re w-100" for="sort-item1">
                    جدیدترین
                </label>
            </div>
        </li>
        <li class="list-unstyled">
            <div class="form-check mb-0">
                <input class="form-check-input" type="radio" value="cheapest" v-model="sortBy" id="sort-item2">
                <label class="form-check-label font-re w-100" for="sort-item2">
                    ارزانترین
                </label>
            </div>
        </li>
        <li class="list-unstyled">
            <div class="form-check mb-0">
                <input class="form-check-input" type="radio" value="expensive" v-model="sortBy" id="sort-item3">
                <label class="form-check-label font-re w-100" for="sort-item3">
                    گرانترین
                </label>
            </div>
        </li>
    </ul>
</div>
