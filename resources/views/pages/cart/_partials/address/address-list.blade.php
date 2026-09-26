<div class="cart-address-list">
    <div class="cart-address-item" v-for="(location, index) in locations" :key="location.id">
        <div class="cart-address-item__head">
            <label class="cart-radio" :for="'flexCheckDefault'+ location.id">
                <input class="cart-radio__input" type="radio" name="address_id"
                       v-model="defaultAddressId"
                       @change="getShippingMethod()"
                       :checked="location.id == defaultAddressId"
                       :id="'flexCheckDefault'+ location.id" :value="location.id">
                <span class="cart-radio__mark"></span>
                <span class="cart-radio__label">آدرس تحویل</span>
            </label>
            <button type="button" @click="editAddress(location.id)" class="cart-address-item__edit" data-bs-toggle="collapse" :data-bs-target="'#editAdress-' + location.id" aria-expanded="false">
                <i class="bi bi-pencil"></i>
                ویرایش
            </button>
        </div>

        <div class="cart-address-item__location">
            <i class="bi bi-geo-alt"></i>
            <span>@{{ location.state_name }}، @{{ location.city_name }}</span>
        </div>
        <p class="cart-address-item__text">@{{ location.address }}</p>

        <div class="collapse" :id="'editAdress-' + location.id">
            <div class="cart-address-item__edit-form">
                @include('pages.cart._partials.address.edit-form')
            </div>
        </div>
    </div>
</div>
