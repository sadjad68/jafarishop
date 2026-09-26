<div class="container-fluid">
    <div class="card-block row w-100 m-0">
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <div class="form-group">
                <x-cms-input
                    name="full_name"
                    label="نام و نام خانوادگی"
                    :validations="['requiredCms']"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 col-12 col-12 p-2">
            <div class="form-group">
                <x-cms-input name="mobile" label="شماره تماس" :validations="['requiredCms','numberCms']" type="tel" :valueData="@$data"/>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 col-12 col-12 p-2">
            <div class="form-group">
                <x-cms-input name="email" label="ایمیل" :validations="['requiredCms']" type="email" :valueData="@$data"/>
            </div>
        </div>
        @if(!isset($data))
        <div class="col-xxl-3 col-sm-6 col-12 col-12 p-2">
            <div class="form-group">
                <x-cms-password-input name="password" label="رمز عبور" :validations="['requiredCms']"/>
            </div>
        </div>
        @endif
        <div class="col-xxl-3 col-sm-6 col-12 col-12 p-2">
            <x-cms-multi-select
                name="user_types"
                label="نقش"
                :options="Config::get('site.user_types')"
                :searchable="false"
                :selectedOptions="isset($data) ? $data->userTypes->map(function($item){return $item->type;})->toArray() : []"
                :validations="['requiredCms']"
            />
        </div>
        <div class="col-xxl-3 col-sm-6 col-12 col-12 p-2">
            <x-cms-image-input
                name="avatar"
                label="تصویر پروفایل (سایز : w250 * h250 )"
                :imageSrc="(isset($data) && $data->avatar) ? $data->getAvatar() : null"
                :deletable="true"
                :deleteUrl="'model='.\App\Modules\User\Entities\User::class.'&id='.@$data['id']"
                width="250"
                height="250"
                 cropper="1"
            />
        </div>
        <div class="col-xxl-3 col-sm-6 col-12 col-12 p-2">
            <x-cms-multi-select
                name="roles"
                label="سطح دسترسی"
                :options="$roles"
                optionValue="id"
                optionLabel="name"
                :searchable="true"
                :selectedOptions="isset($data) ? $data->roles->map(function($item){return $item->id;})->toArray() : []"
            />
        </div>
        <div class="col-xxl-3 col-sm-6 col-12 col-12 p-2">
            <x-cms-multi-select
                name="services"
                label="خدمات"
                :options="$services"
                optionValue="id"
                optionLabel="title"
                :searchable="true"
                :selectedOptions="isset($data) ? $data->services->map(function($item){return $item->id;})->toArray() : []"
            />
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
        @include('admin.components.show-first-page',['section'=>'manager'])
        </div>
        <div class="w-100 pe-0">
            @include('admin._layouts.blocks.utils.page-getter')
            <button type="submit" id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-3 py-2">
                ذخیره
            </button>
        </div>
    </div>
</div>
