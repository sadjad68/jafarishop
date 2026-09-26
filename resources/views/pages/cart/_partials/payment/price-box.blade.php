<div class="col-xl-4 col-lg-4 pe-0 ps-0 ps-lg-2 mt-4" id="pay">
    <aside class="cart-sidebar cart-sidebar--payment" v-if="priceLoading == false">
        <div class="cart-sidebar__head">
            <h2 class="cart-sidebar__title">پرداخت</h2>
        </div>

        <div class="cart-discount">
            <input type="text" placeholder="کد تخفیف" class="cart-discount__input" v-model="discountCode">
            <button v-if="discountId === ''" type="button" class="cart-discount__btn" @click="addDiscount()">ثبت</button>
            <button v-else type="button" class="cart-discount__btn cart-discount__btn--danger" @click="deleteDiscount()">حذف</button>
        </div>

        <form v-if="priceLoading == false" action="{{ route('basket.order-create') }}" method="POST" enctype="multipart/form-data" @submit.prevent="submitPayment">
            @csrf
            <ul class="cart-sidebar__rows">
                <li class="cart-sidebar__row" v-if="priceSum">
                    <span>قیمت کالاها</span>
                    <strong class="font-num-r">@{{ priceSum }}</strong>
                </li>
                <li class="cart-sidebar__row cart-sidebar__row--discount" v-if="priceDiscount">
                    <span>تخفیف</span>
                    <strong class="font-num-r">@{{ priceDiscount }}</strong>
                </li>
                <li class="cart-sidebar__row" v-if="priceShipping">
                    <span>هزینه ارسال</span>
                    <strong class="font-num-r">@{{ priceShipping }}</strong>
                </li>
                <li class="cart-sidebar__row" v-if="finalPriceSum">
                    <span>مبلغ کل کالا</span>
                    <strong class="font-num-r">@{{ finalPriceSum }}</strong>
                </li>
                <li class="cart-sidebar__row cart-sidebar__row--discount" v-if="discountAmount">
                    <span>کد تخفیف</span>
                    <strong class="font-num-r">@{{ discountAmount }}</strong>
                </li>
                <li class="cart-sidebar__row" v-if="gatewayTariff">
                    <span>تعرفه درگاه <span class="cart-badge">@{{ gatewayTariff }}٪</span></span>
                </li>
                <li class="cart-sidebar__row" v-if="taxPrice">
                    <span>مالیات <span class="cart-badge">@{{ tax }}</span></span>
                    <strong class="font-num-r">@{{ taxPrice }}</strong>
                </li>
                <li class="cart-sidebar__row cart-sidebar__row--total" v-if="priceCart">
                    <span v-if="cartDepositNumber != 0 && (cartDepositNumber <= priceCartNumber)">مبلغ کل</span>
                    <span v-else>مبلغ پرداختی</span>
                    <strong class="font-num-r">@{{ priceCart }}</strong>
                </li>
                <li class="cart-sidebar__row cart-sidebar__row--total" v-if="cartDepositNumber != 0 && (cartDepositNumber <= priceCartNumber)">
                    <span>مبلغ بیعانه</span>
                    <strong class="font-num-r">@{{ depositPrice }}</strong>
                </li>
            </ul>

            @if((int) (@$settings['cart_terms_acceptance_enabled'] ?? 0) === 1)
                <label class="cart-terms" for="terms_accepted">
                    <input class="cart-terms__input" type="checkbox" id="terms_accepted" name="terms_accepted" value="1" v-model="termsAccepted">
                    <span class="cart-terms__text">
                        <a href="{{ route('us.terms') }}" target="_blank" rel="noopener noreferrer" class="dynamic-color">قوانین و مقررات</a>
                        را مطالعه کردم و موافقم.
                    </span>
                </label>
            @endif

            <div class="cart-field">
                <label class="cart-field__label" for="user_description">توضیحات سفارش</label>
                <textarea id="user_description" name="user_description" rows="2" class="cart-field__textarea" placeholder="اگر توضیحی دارید اینجا بنویسید..."></textarea>
            </div>

            <div class="cart-payment-methods">
                <p class="cart-payment-methods__title">روش پرداخت</p>
                <ul class="cart-payment-methods__list">
                    <li class="cart-pay-item" v-for="(bank, index) in filteredBanks" :key="bank.id">
                        <label class="cart-pay-item__label" :for="bank.id">
                            <input class="cart-pay-item__input" type="radio" name="bank_id" :value="bank.id" v-model="selectedBankId" required @invalid="warnRequired('روش پرداخت ')" :id="bank.id">
                            <span class="cart-pay-item__content">
                                <img :src="bank.item_image" width="44" height="44" class="cart-pay-item__logo" alt="">
                                <span class="cart-pay-item__text">
                                    <strong>@{{ bank.title }}</strong>
                                    <template v-if="bank.bank_type == 'snappay'">
                                        <small>@{{ snappData.snapp_title_message }}</small>
                                        <small>@{{ snappData.snapp_description }}</small>
                                    </template>
                                </span>
                            </span>
                        </label>
                    </li>
                </ul>

                <div v-if="selectedCardToCardBank" class="cart-alert cart-alert--info">
                    <div v-if="selectedCardToCardBank.card_number" class="mb-1">
                        شماره کارت:
                        <span class="cart-copy-row">
                            <strong class="font-num">@{{ selectedCardToCardBank.card_number }}</strong>
                            <button type="button" class="btn-copy-text" title="کپی شماره کارت" aria-label="کپی شماره کارت" @click="copyToClipboard(selectedCardToCardBank.card_number, 'شماره کارت')">
                                <i class="bi bi-copy"></i>
                            </button>
                        </span>
                    </div>
                    <div v-if="selectedCardToCardBank.shaba_number" class="mb-1">
                        شماره شبا:
                        <span class="cart-copy-row">
                            <strong class="font-num">@{{ selectedCardToCardBank.shaba_number }}</strong>
                            <button type="button" class="btn-copy-text" title="کپی شماره شبا" aria-label="کپی شماره شبا" @click="copyToClipboard(selectedCardToCardBank.shaba_number, 'شماره شبا')">
                                <i class="bi bi-copy"></i>
                            </button>
                        </span>
                    </div>
                    <div v-if="selectedCardToCardBank.account_holder_name">
                        نام صاحب حساب: <strong>@{{ selectedCardToCardBank.account_holder_name }}</strong>
                    </div>
                    <div v-if="selectedCardToCardBank.reservation_expire_minutes" class="cart-alert__warn">
                        پس از واریز و بارگذاری فیش، حداکثر @{{ selectedCardToCardBank.reservation_expire_minutes }} دقیقه برای تأیید پرداخت فرصت دارید.
                    </div>
                </div>

                <div v-if="selectedCardToCardBank" class="cart-field">
                    <label for="card_to_card_receipt" class="cart-field__label">آپلود فیش واریز</label>
                    <input id="card_to_card_receipt" type="file" class="cart-field__file" name="file" accept=".jpg,.jpeg,.png,.pdf" :required="Boolean(selectedCardToCardBank)">
                    <small class="cart-field__hint">فرمت jpg، jpeg، png یا pdf — حداکثر ۱ مگابایت</small>
                </div>
            </div>

            @if(filled(trim(@$settings['cart_payment_notice'] ?? '')))
                <div class="cart-alert cart-alert--warn">
                    <strong>توجه:</strong> {{ trim($settings['cart_payment_notice']) }}
                </div>
            @endif

            <button type="submit" class="sk-cta cart-sidebar__cta w-100">
                @{{ selectedCardToCardBank ? 'ثبت سفارش و ارسال فیش' : 'تأیید و پرداخت' }}
                <i class="bi bi-arrow-left"></i>
            </button>
        </form>
    </aside>
    <aside class="cart-sidebar cart-sidebar--loading" v-else>
        @include('layouts.common.loading')
    </aside>
</div>
