<input type="hidden" name="basket_id" value="{{@$basket->id}}">
<div class="row w-100 m-0">
    <div class="col-md-4 col-6 p-1">
        <label class="form-label font-small font-re mb-1" for="subject">نام تحویل گیرنده</label>
        <input class="form-control" placeholder="مثلا رامین جوادی" id="subject" value=""
               name="receiptor_full_name" v-model="receiptorName" @input="validateName">
    </div>
    <div class="col-md-4 col-6 p-1">
        <label class="form-label font-small font-re mb-1" for="subject">شماره تحویل گیرنده</label>
        <input class="form-control" placeholder="مثلا ۰۹۱۲۱۲۳۴۵۶۷" id="subject" value=""
               name="receiptor_mobile" v-model="receiptorMobile" @input="validateMobile">
    </div>
    <div class="col-md-4 col-6 p-1">
        <label class="form-label font-small font-re mb-1" for="postalCode">کدپستی</label>
        <input class="form-control" placeholder="1234567890" id="postalCode" value=""
               name="postal_code" v-model="postalCodeForm" @input="validatePostalCode">
    </div>
    <div class="col-md-6 col-6 p-1">
        <label class="form-label font-small font-re mb-1">استان</label>
        <v-select
            v-model="selectedState"
            :options="states"
            :multiple="false"
            label="name"
            placeholder="استان محل سکونت"
            @input="setCities"
            @search="searchState"
        ></v-select>
        <input type="hidden" name="state_id" :value="selectedState ? selectedState.id : ''">
    </div>
    <div class="col-md-6 col-6 p-1">
        <label class="form-label font-small font-re mb-1">
            شهر
            <div class="spinner-border spinner-border-sm ms-2" role="status" v-if="loadingAddress === true">
                <span class="visually-hidden">Loading...</span>
            </div>
        </label>
        <v-select
            v-model="selectedCity"
            :options="cities"
            :multiple="false"
            label="name"
            placeholder="شهر محل سکونت"
            @search="searchCity"
        ></v-select>
        <input type="hidden" name="city_id" :value="selectedCity ? selectedCity.id : ''">
    </div>
    <div class="col-12 p-1">
        <label class="form-label font-small font-re mb-1" for="exampleFormControlTextarea1">آدرس</label>
        <textarea class="form-control small" name="address" placeholder="خیابان اصلی,خیابان فرعی,کوچه" id="exampleFormControlTextarea1" rows="3"
                  v-model="address"></textarea>
    </div>
    <div class="d-flex justify-content-end p-0 mt-1">
        <button type="submit" class="btn btn-dark  btn-sm rounded-3 px-3 py-2 font-small">
            ذخیره آدرس
            </button>
    </div>
</div>
