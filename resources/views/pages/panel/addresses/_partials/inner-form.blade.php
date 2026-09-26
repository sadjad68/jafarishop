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
            :options="states"
            label="name"
            :reduce="state => state"
        v-model="selectedState"
        @input="setCities"
        ></v-select>
        <input type="hidden" name="state_id" :value="selectedState ? selectedState.id : ''">
    </div>
    <div class="col-md-6 col-6 p-1">
        <label class="form-label font-small font-re mb-1">شهر</label>
        <v-select
            :options="cities"
            label="name"
            :reduce="city => city"
            v-model="selectedCity"
        ></v-select>
        <input type="hidden" name="city_id" :value="selectedCity ? selectedCity.id : ''">
    </div>
    <div class="col-12 p-1">
        <label class="form-label font-small font-re mb-1" for="exampleFormControlTextarea1">آدرس</label>
        <textarea class="form-control small" name="address" placeholder="خیابان اصلی,خیابان فرعی,کوچه" id="exampleFormControlTextarea1" rows="3"
                  v-model="address"></textarea>
    </div>
    <div class="d-flex justify-content-end mt-1 p-0 login-form">
        <button type="submit" class="btn bbtn-dark rounded-3 btn-sm py-2 px-3 btn-one">ثبت
            تغییرات</button>
    </div>
</div>
