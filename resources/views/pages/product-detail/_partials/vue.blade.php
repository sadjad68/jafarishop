<script type="application/javascript">
    new Vue({
        el: '#app',
        data: {
            productId: "{{@$product->id}}",
            product: {
                title: @json(@$product->title),
                image_big: @json(@$product->getImage('big')),
                discounted_price: @json(intval(@$product->discounted_price ?? 0)),
                price: @json(intval(@$product->price ?? 0)),
                final_price: @json(intval(@$product->final_price ?? 0))
            },
            main_image: @json(@$product->getImage('big')),
            activeImageIndex: 0,
            variantCount: "{{count(@$product->variants)}}",
            finalPrice: "{{ intval(@$product->final_price) != 0 ? number_format(intval(@$product->final_price)).' تومان ' : 0}}",
            finalPriceInt: "{{ intval(@$product->final_price) != 0 ? intval(@$product->final_price) : 0}}",
            price: "{{ intval(@$product->final_price) != intval(@$product->price) ? number_format(intval(@$product->price)).' تومان ' : 0 }}",
            priceInt: "{{ intval(@$product->final_price) != intval(@$product->price) ? intval(@$product->price) : 0 }}",
            percent: "{{intval(@$product->percent)}}",
            stock: "{{intval(@$product->stock)}}",
            quantity: 1,
            selectedOption: '',
            visibleSpecs: @json(collect(@$all_specification_children_ids)),
            loadingSpec: false,
            variants: @json(collect(@$variants)),
            images: @json(collect(@$images)),
            imagesOriginal: @json(collect(@$original_images)),
            mainSwiper: null,
            thumbSwiper: null,
            mainSpecs: @json(@$main_specifications),
            selectedSpecs: {},
            availableVariants: [],
            selectedVariant: null,
            initiateMe: false,
            min_variant: @json(@$min_variant),
            reset: false,
            mobileCarouselReady: true,
            notifyType: null,
            isUserLoggedIn: {{ auth()->check() ? 'true' : 'false' }},
            userNotifications: @json($userNotifications ?? ['available' => [], 'discount' => []]),
            serverStatus: 'instock',
            snappPayActive: {{ $snappPayActive ? 'true' : 'false' }},
            snappData: null,
            snappLoading: false,
            trackingBrandTitle: @json(@$brand->title ?? ''),
            trackingCategoryTitles: @json(isset($categories) ? $categories->pluck('title')->take(2)->values() : []),
            viewItemTimer: null,
            showStickyPurchaseBar: false,
            purchaseStickyObserver: null,

        },
        created() {
            this.autoSelectSingleSpecs();
            this.calculateAvailableVariants();
            if (this.min_variant) {
                this.updatePrice(this.min_variant)
            }
        },
        methods: {
            resolveSpecificationsForTracking() {
                const specs = [];
                if (!this.mainSpecs || !this.mainSpecs.length) {
                    return specs;
                }
                this.mainSpecs.forEach((mainSpec) => {
                    const childId = this.selectedSpecs[mainSpec.main_id];
                    if (!childId) {
                        return;
                    }
                    const child = (mainSpec.children || []).find((c) => c.id == childId);
                    if (child) {
                        specs.push({
                            title: child.title,
                            parent: { title: mainSpec.main_title },
                        });
                    }
                });
                return specs;
            },
            buildTrackingContext() {
                const hasVariants = parseInt(this.variantCount, 10) > 0;
                if (hasVariants && !this.selectedVariant) {
                    return null;
                }
                const variant = this.selectedVariant;
                const finalToman = variant
                    ? parseInt(variant.final_price, 10)
                    : parseInt(this.finalPriceInt, 10);
                if (!finalToman) {
                    return null;
                }
                return {
                    product_id: this.productId,
                    variant_id: variant ? variant.id : null,
                    item_name: this.product.title,
                    final_price_toman: finalToman,
                    quantity: parseInt(this.quantity, 10) || 1,
                    brand_title: this.trackingBrandTitle,
                    category_titles: this.trackingCategoryTitles,
                    specifications: this.resolveSpecificationsForTracking(),
                };
            },
            trackViewItem() {
                if (typeof window.EcommerceTracking === 'undefined') {
                    return;
                }
                const ctx = this.buildTrackingContext();
                if (!ctx) {
                    return;
                }
                const item = window.EcommerceTracking.buildItemFromProductContext(ctx);
                window.EcommerceTracking.pushEcommerceEvent(
                    'view_item',
                    window.EcommerceTracking.eventPayload([item])
                );
            },
            trackViewItemDebounced() {
                if (this.viewItemTimer) {
                    clearTimeout(this.viewItemTimer);
                }
                this.viewItemTimer = setTimeout(() => {
                    this.trackViewItem();
                }, 300);
            },
            trackAddToCart() {
                if (typeof window.EcommerceTracking === 'undefined') {
                    return;
                }
                const ctx = this.buildTrackingContext();
                if (!ctx) {
                    return;
                }
                ctx.quantity = parseInt(this.quantity, 10) || 1;
                const item = window.EcommerceTracking.buildItemFromProductContext(ctx);
                window.EcommerceTracking.pushEcommerceEvent(
                    'add_to_cart',
                    window.EcommerceTracking.eventPayload([item])
                );
            },
            showAuctionBell() {
                if (!this.isAvailable) return false;

                const hasCompleteVariantSelection =
                    parseInt(this.variantCount) > 0 &&
                    Object.keys(this.selectedSpecs).length === this.mainSpecs.length;

                if (hasCompleteVariantSelection && this.selectedVariant) {
                    const variantDiscountedPrice = parseInt(this.selectedVariant.discounted_price || 0);
                    return variantDiscountedPrice === 0;
                }

                const productDiscountedPrice = parseInt(this.product.discounted_price || 0);
                return productDiscountedPrice === 0;
            },

            // isAvailable() {
            //     const variant = this.selectedVariant;
            //     const stock = variant ? variant.stock : this.stock;
            //     const price = variant ? variant.final_price : this.finalPriceInt;
            //
            //     if (parseInt(stock) <= 0 || parseInt(price) === 0) {
            //         return false;
            //     }
            //     return true;
            // },
            openAuthModal(type) {
                if (window.notifyAuthModal && window.notifyAuthModal.openModal) {
                    window.notifyAuthModal.openModal(type);
                    return;
                }

                window.dispatchEvent(new CustomEvent('open-auth-modal', {detail: type}));
            },
            handleNotificationClick(type) {
                if (this.variantCount > 0 && !this.selectedVariant && type === 'discount') {
                    Swal.fire({
                        icon: 'info',
                        text: 'لطفا ابتدا ویژگی‌های محصول را انتخاب کنید',
                        confirmButtonText: 'باشه',
                        timer: 3000
                    });
                    return;
                }

                if (this.isUserLoggedIn) {
                    this.submitNotificationRequest(type);
                } else {
                    this.openAuthModal(type);
                }
            },
            isSubscribed(type) {
                if (!this.userNotifications || !this.userNotifications[type]) return false;
                const id = this.selectedVariant ? this.selectedVariant.id : parseInt(this.productId);
                return this.userNotifications[type].includes(id);
            },
            openNotify(type) {
                this.handleNotificationClick(type);
            },
            async submitNotificationRequest(type) {
                if (type === 'discount' && this.variantCount > 0 && !this.selectedVariant) {
                    Swal.fire({
                        icon: 'info',
                        text: 'لطفا ابتدا ویژگی‌های محصول (رنگ یا سایز) را انتخاب کنید',
                        confirmButtonText: 'باشه'
                    });
                    return;
                }

                const variantId = this.selectedVariant ? this.selectedVariant.id : null;
                const currentPrice = this.selectedVariant
                    ? this.selectedVariant.final_price
                    : this.finalPriceInt;

                try {
                    const response = await axios.post('{{ route("product.notify") }}', {
                        product_id: this.productId,
                        product_variant_id: variantId,
                        type: type,
                        current_price: currentPrice
                    });

                    if (response.data.success) {
                        const id = variantId || parseInt(this.productId);

                        if (response.data.action === 'added') {
                            if (!this.userNotifications[type]) {
                                this.$set(this.userNotifications, type, []);
                            }
                            if (!this.userNotifications[type].includes(id)) {
                                this.userNotifications[type].push(id);
                            }
                        } else {
                            this.userNotifications[type] = this.userNotifications[type].filter(item => item !== id);
                        }

                        Swal.fire({
                            icon: response.data.action === 'added' ? 'success' : 'info',
                            title: response.data.action === 'added' ? 'موفق' : 'توجه',
                            text: response.data.message,
                            confirmButtonText: 'باشه',
                            confirmButtonColor: '#4CAF50',
                            // position: 'center',
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            text: response.data.message || 'خطا در پردازش درخواست',
                            confirmButtonText: 'باشه'
                        });
                    }
                } catch (error) {
                    console.error('Notification error:', error);

                    let message = 'خطا در پردازش درخواست. لطفا دوباره تلاش کنید.';

                    if (error.response) {
                        if (error.response.status === 401) {
                            // if session expired, reopen auth modal
                            this.isUserLoggedIn = false;
                            this.openAuthModal(type);
                            return;
                        }
                        if (error.response.data && error.response.data.message) {
                            message = error.response.data.message;
                        }
                    }

                    Swal.fire({
                        icon: 'error',
                        text: message,
                        confirmButtonText: 'باشه'
                    });
                }
            },
            restoreOriginalGallery() {
                this.images = @json(collect(@$images));
                this.activeImageIndex = 0;
                this.main_image = (this.images[0] && this.images[0].image_big)
                    ? this.images[0].image_big
                    : @json(@$product->getImage('big'));
                this.mobileCarouselReady = false;
                this.$nextTick(() => {
                    this.mobileCarouselReady = true;
                    this.$nextTick(() => {
                        this.updateSwipers();
                    });
                });
            },
            resetAllSelections() {
                this.selectedSpecs = {};
                this.selectedVariant = null;
                this.finalPrice = "{{ intval(@$product->final_price) != 0 ? number_format(intval(@$product->final_price)).' تومان ' : 0}}";
                this.price = "{{ intval(@$product->final_price) != intval(@$product->price) ? number_format(intval(@$product->price)).' تومان ' : 0 }}";
                this.percent = "{{intval(@$product->percent)}}";
                this.stock = "{{intval(@$product->stock)}}";
                this.quantity = 1;
                this.reset = true;
                this.restoreOriginalGallery();
                this.calculateAvailableVariants();
                // if (this.min_variant) {
                //     this.selectVariant(this.min_variant);
                // }
            },
            findAndAutoSelectCheapestVariant() {
                if (this.availableVariants.length === 0) {
                    this.selectedVariant = null;
                    return;
                }

                let cheapestVariant = null;

                // انتخاب min_variant ارسالی از کنترلر (مثلاً ?variant=)
                if (this.min_variant) {
                    cheapestVariant = this.getInitialVariants().find(v => v.id == this.min_variant.id) || null;
                }

                if (!cheapestVariant) {
                    const validVariants = this.availableVariants.filter(variant => {
                        const stock = parseInt(variant.stock || 0);
                        const finalPrice = parseInt(variant.final_price || 0);
                        return stock > 0 && finalPrice > 0;
                    });

                    const candidates = validVariants.length > 0 ? validVariants : this.availableVariants;
                    let minPrice = Infinity;
                    candidates.forEach(variant => {
                        const price = parseInt(variant.final_price);
                        if (price < minPrice) {
                            minPrice = price;
                            cheapestVariant = variant;
                        }
                    });
                }

                if (!cheapestVariant) {
                    return;
                }

                const cheapestVariantSpecIds = cheapestVariant.specification_ids || [];

                this.mainSpecs.forEach(mainSpec => {
                    const selectedChild = mainSpec.children.find(child =>
                        cheapestVariantSpecIds.some(id => id == child.id)
                    );
                    if (selectedChild) {
                        this.$set(this.selectedSpecs, mainSpec.main_id, selectedChild.id);
                    }
                });

                this.selectVariant(cheapestVariant);
            },
            scrollToVariants() {
                const el = document.getElementById('pdp-variant-selector');
                if (!el) {
                    return;
                }

                const stickyBar = document.querySelector('.pdp-sticky-bar--dock');
                const offset = stickyBar ? stickyBar.offsetHeight + 16 : 80;
                const top = el.getBoundingClientRect().top + window.scrollY - offset;

                window.scrollTo({ top, behavior: 'smooth' });
            },
            autoSelectSingleSpecs() {

                this.mainSpecs.forEach(mainSpec => {
                    if (mainSpec.children && mainSpec.children.length === 1) {
                        const singleChild = mainSpec.children[0];
                        this.$set(this.selectedSpecs, mainSpec.main_id, singleChild.id);
                    }
                });

                this.calculateAvailableVariants();
            },
            normalizeSpecId(id) {
                return id === null || typeof id === 'undefined' ? null : String(id);
            },
            isSameSpecId(leftId, rightId) {
                const normalizedLeft = this.normalizeSpecId(leftId);
                const normalizedRight = this.normalizeSpecId(rightId);
                return normalizedLeft !== null && normalizedLeft === normalizedRight;
            },
            isSelectedSpec(mainSpecId, childId) {
                return this.isSameSpecId(this.selectedSpecs[mainSpecId], childId);
            },
            selectedChildTitle(mainSpec) {
                const selectedId = this.selectedSpecs[mainSpec.main_id];
                if (!selectedId || !mainSpec.children) {
                    return '';
                }
                const child = mainSpec.children.find((item) => this.isSameSpecId(item.id, selectedId));
                return child ? child.title : '';
            },
            hasVariantForSpecs(variantSpecIds, selectedSpecIds) {
                const normalizedVariantSpecIds = Array.isArray(variantSpecIds)
                    ? variantSpecIds.map(id => this.normalizeSpecId(id))
                    : [];
                return selectedSpecIds.every(specId => normalizedVariantSpecIds.includes(this.normalizeSpecId(specId)));
            },
            calculateAvailableVariants() {
                let allVariants = this.getInitialVariants();
                const selectedSpecIds = Object.values(this.selectedSpecs);
                if (selectedSpecIds.length === 0) {
                    this.availableVariants = allVariants;
                    this.selectedVariant = null;
                    return;
                }
                this.availableVariants = allVariants.filter(variant => {
                    const variantSpecsIds = variant.specification_ids;
                    return this.hasVariantForSpecs(variantSpecsIds, selectedSpecIds);
                });
                if (this.availableVariants.length === 1 && selectedSpecIds.length === this.mainSpecs.length) {
                    this.selectedVariant = this.availableVariants[0];
                } else {
                    this.selectedVariant = null;
                }
            },
            async selectSpecs(childSpec) {
                if (this.loadingSpec) {
                    return;
                }

                this.loadingSpec = true;

                try {
                    const mainSpecId = childSpec.main_id;
                    const currentSelectedId = this.selectedSpecs[mainSpecId];

                    if (this.isSameSpecId(currentSelectedId, childSpec.id)) {
                        this.$delete(this.selectedSpecs, mainSpecId);
                    } else {
                        this.$set(this.selectedSpecs, mainSpecId, childSpec.id);
                    }

                    await this.runAutoSelectCycle();
                    if (Object.keys(this.selectedSpecs).length === 0) {
                        this.restoreOriginalGallery();
                    }
                } finally {
                    this.loadingSpec = false;
                }
            },
            async runAutoSelectCycle() {
                let selectionChanged = true;
                let maxIterations = 5;
                while (selectionChanged && maxIterations > 0) {
                    const selectedCountBefore = Object.keys(this.selectedSpecs).length;
                    this.calculateAvailableVariants();
                    this.autoSelectSingleSpecs();
                    const selectedCountAfter = Object.keys(this.selectedSpecs).length;
                    selectionChanged = selectedCountBefore !== selectedCountAfter;
                    maxIterations--;
                    if (selectionChanged) {
                        await this.$nextTick();
                    }
                }
            },
            isSpecAvailable(childSpec) {
                let currentSelected = {...this.selectedSpecs};
                currentSelected[childSpec.main_id] = childSpec.id;
                const currentSelectedSpecIds = Object.values(currentSelected);
                let allVariants = this.getInitialVariants();
                const hasMatchingVariant = allVariants.some(variant => {
                    const variantSpecsIds = variant.specification_ids;
                    return this.hasVariantForSpecs(variantSpecsIds, currentSelectedSpecIds);
                });
                return hasMatchingVariant;
            },
            getInitialVariants() {
                let allVariants = [];
                const uniqueVariantIds = new Set();
                this.mainSpecs.forEach(mainSpec => {
                    mainSpec.children.forEach(childSpec => {
                        childSpec.variants.forEach(variant => {
                            if (!uniqueVariantIds.has(variant.id)) {
                                allVariants.push(variant);
                                uniqueVariantIds.add(variant.id);
                            }
                        });
                    });
                });
                return allVariants;
            },
            decreaseQuantity() {
                if (this.quantity > 1) {
                    this.quantity--;
                }
            },
            increaseQuantity() {
                this.quantity++;
            },
            setupPurchaseStickyObserver() {
                if (this.purchaseStickyObserver) {
                    this.purchaseStickyObserver.disconnect();
                    this.purchaseStickyObserver = null;
                }
                if (this._handlePurchaseStickyResize) {
                    window.removeEventListener('resize', this._handlePurchaseStickyResize);
                }
                const anchor = document.getElementById('pdp-purchase-anchor');
                if (!anchor || typeof IntersectionObserver === 'undefined') {
                    return;
                }
                this._handlePurchaseStickyResize = this.handlePurchaseStickyResize.bind(this);
                this.purchaseStickyObserver = new IntersectionObserver(
                    ([entry]) => {
                        const useScrollTrigger = window.matchMedia('(min-width: 992px)').matches;
                        this.showStickyPurchaseBar = useScrollTrigger
                            ? !entry.isIntersecting
                            : true;
                        document.body.classList.toggle('pdp-sticky-active', this.showStickyPurchaseBar);
                    },
                    { threshold: 0, rootMargin: '0px 0px -8px 0px' }
                );
                this.purchaseStickyObserver.observe(anchor);
                window.addEventListener('resize', this._handlePurchaseStickyResize);
                this.handlePurchaseStickyResize();
            },
            handlePurchaseStickyResize() {
                const anchor = document.getElementById('pdp-purchase-anchor');
                if (!anchor) {
                    return;
                }
                const useScrollTrigger = window.matchMedia('(min-width: 992px)').matches;
                if (!useScrollTrigger) {
                    this.showStickyPurchaseBar = true;
                    document.body.classList.add('pdp-sticky-active');
                    return;
                }
                const rect = anchor.getBoundingClientRect();
                const inView = rect.bottom > 0 && rect.top < window.innerHeight;
                this.showStickyPurchaseBar = !inView;
                document.body.classList.toggle('pdp-sticky-active', this.showStickyPurchaseBar);
            },
            selectVariant(variant) {
                this.selectedVariant = variant;
                this.percent = variant.percent != null ? parseInt(variant.percent) : 0;
                this.finalPrice = parseInt(variant.final_price) !== 0 ? parseInt(variant.final_price).toLocaleString() + ' تومان ' : 0;
                this.price = parseInt(variant.price) !== parseInt(variant.final_price) ? parseInt(variant.price).toLocaleString() + ' تومان ' : 0;
                this.stock = parseInt(variant.stock);
            },
            async addToBasket() {
                if (!this.selectedVariant && this.variantCount > 0) {
                    const titles = this.mainSpecs.map(spec => spec.main_title).join('، ');
                    Swal.fire({
                        icon: 'info',
                        text: `لطفا ${titles} را انتخاب کنید `,
                        showConfirmButton: true,
                        confirmButtonText: 'باشه',
                        timer: 5000
                    });
                    return;
                }
                let formData = new FormData();
                formData.append("product_id", this.productId);
                formData.append("product_variant_id", this.selectedVariant ? this.selectedVariant.id : '');
                formData.append("quantity", this.quantity);
                const response = await axios.post('{{route('basket.add')}}', formData);
                if (response.data.success === false && response.data.button === false) {
                    if (response.data.swal) {
                        Swal.fire({
                            icon: 'info',
                            text: response.data.message,
                            showConfirmButton: true,
                            confirmButtonText: 'باشه',
                            timer: 5000
                        });
                    } else {
                        Swal.fire({
                            icon: 'info',
                            text: response.data.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });
                        return false;
                    }
                } else {
                    this.trackAddToCart();
                    Swal.fire({
                        icon: 'success',
                        text: response.data.message,
                        title: 'اضافه شد!',
                        showCancelButton: true,
                        confirmButtonText: 'تکمیل سفارش و پرداخت',
                        cancelButtonText: 'ادامه خرید',
                        customClass: {
                            confirmButton: 'btn btn-danger',
                            cancelButton: 'btn btn-secondary'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{route('basket.cart')}}';
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                        } else {
                            console.log('hi');
                        }
                    });
                    menu_nav.getBasketItemCount();
                }
            },
            updateSwipers() {
                if (this.mainSwiper) {
                    this.mainSwiper.update();
                    this.mainSwiper.slideTo(0, 0);
                }
                if (this.thumbSwiper) {
                    this.thumbSwiper.update();
                    this.thumbSwiper.slideTo(0, 0);
                }
                const mobileCarouselElement = document.getElementById('proImgIndicatorsMob');
                if (mobileCarouselElement && typeof bootstrap !== 'undefined' && bootstrap.Carousel) {
                    bootstrap.Carousel.getOrCreateInstance(mobileCarouselElement, {
                        interval: false,
                    });
                }
                const mobileSheetCarouselElement = document.getElementById('proImgIndicatorsMobSheet');
                if (mobileSheetCarouselElement && typeof bootstrap !== 'undefined' && bootstrap.Carousel) {
                    bootstrap.Carousel.getOrCreateInstance(mobileSheetCarouselElement, {
                        interval: false,
                    });
                }
            },
            initializeSwipers() {
            },
            updatePrice(newVariant) {
                this.percent = newVariant.percent != null ? parseInt(newVariant.percent) : 0;
                this.finalPrice = parseInt(newVariant.final_price) !== 0 ? parseInt(newVariant.final_price).toLocaleString() : 0;
                this.price = parseInt(newVariant.price) !== parseInt(newVariant.final_price) ? parseInt(newVariant.price).toLocaleString() : 0;
                this.stock = parseInt(newVariant.stock);
            },
            handleImage(newVariant) {
                let imagesToDisplay = [];
                imagesToDisplay = [...this.imagesOriginal];
                if (newVariant && newVariant.images && newVariant.images.length > 0) {
                    const variantImageIds = new Set(newVariant.images.map(img => img.id));
                    const filteredOriginalImages = this.imagesOriginal.filter(img => !variantImageIds.has(img.id));
                    imagesToDisplay = newVariant.images.concat(filteredOriginalImages);
                }
                this.images = imagesToDisplay;
                this.activeImageIndex = 0;
                this.main_image = imagesToDisplay[0].image_big;
                this.mobileCarouselReady = false;
                this.$nextTick(() => {
                    this.mobileCarouselReady = true;
                    this.$nextTick(() => {
                        this.updateSwipers();
                    });
                });
            },
            previewDesktopImage(index) {
                const image = this.images[index];
                if (!image) {
                    return;
                }
                this.activeImageIndex = index;
                this.main_image = image.image_big || image.image_small;
            },
            setActiveItem(index) {
                const slideIndex = Number(index);
                const safeIndex = Number.isNaN(slideIndex) ? this.activeImageIndex : slideIndex;
                this.previewDesktopImage(safeIndex);
                const myCarousel = document.querySelector('#proImgIndicatorsDesk');
                if (!myCarousel || typeof bootstrap === 'undefined' || !bootstrap.Carousel) {
                    return;
                }
                bootstrap.Carousel.getOrCreateInstance(myCarousel).to(safeIndex);
            },
            openMobileImageSheet(index) {
                const slideIndex = Number(index) || 0;
                const sheetEl = document.getElementById('pdpMobileImageSheet');
                const carouselEl = document.getElementById('proImgIndicatorsMobSheet');
                if (!sheetEl || typeof bootstrap === 'undefined') {
                    return;
                }
                if (carouselEl && bootstrap.Carousel) {
                    const carousel = bootstrap.Carousel.getOrCreateInstance(carouselEl, {
                        interval: false,
                    });
                    carousel.to(slideIndex);
                }
                bootstrap.Offcanvas.getOrCreateInstance(sheetEl).show();
            },
            async fetchSnappData(price) {
                if (!this.snappPayActive || !price || parseInt(price) === 0) {
                    this.snappData = null;
                    return;
                }
                this.snappLoading = true;
                try {
                    const response = await axios.post('{{ route('product.snapp-check') }}', {
                        price: parseInt(price),
                    });
                    this.snappData = response.data;
                } catch (error) {
                    console.error('Snapp check error:', error);
                    this.snappData = null;
                } finally {
                    this.snappLoading = false;
                }
            },
        },
        mounted() {
            window.mainProductApp = this;
            window.notifyAuth = this;
            console.log("Main Vue mounted");
            const metaAvailability = document.querySelector("meta[name='availability']");
            if (metaAvailability) {
                this.serverStatus = metaAvailability.getAttribute("content");
            }
            this.$nextTick(() => {
                if (this.variantCount > 0) {
                    this.findAndAutoSelectCheapestVariant();
                    // snapp fetch is triggered by resolvedFinalPrice watcher when variant gets auto-selected
                } else if (this.resolvedFinalPrice > 0) {
                    this.fetchSnappData(this.resolvedFinalPrice);
                }
                this.trackViewItemDebounced();
                this.setupPurchaseStickyObserver();
            });
            new Swiper(".mySwiper-size", {
                slidesPerView: 6,
                spaceBetween: 10,
                freeMode: true,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
            });
            const list = document.getElementById("dynamic-text-list");
            if (list) {
                const items = list.querySelectorAll("li");
                let index = 0;
                setInterval(() => {
                    index = (index + 1) % items.length;
                    let offset = 0;
                    for (let i = 0; i < index; i++) {
                        offset += items[i].offsetHeight;
                    }
                    list.style.transition = "transform 0.5s";
                    list.style.transform = `translateY(-${offset}px)`;
                }, 3000);
            }
        },
        beforeDestroy() {
            if (this.purchaseStickyObserver) {
                this.purchaseStickyObserver.disconnect();
            }
            window.removeEventListener('resize', this._handlePurchaseStickyResize);
            document.body.classList.remove('pdp-sticky-active');
        },
        watch: {
            isAvailable() {
                this.$nextTick(() => {
                    this.setupPurchaseStickyObserver();
                });
            },
            selectedVariant() {
                this.trackViewItemDebounced();
            },
            resolvedFinalPrice(newPrice, oldPrice) {
                if (newPrice > 0 && newPrice !== oldPrice) {
                    this.fetchSnappData(newPrice);
                } else if (newPrice === 0) {
                    this.snappData = null;
                    this.snappLoading = false;
                }
            },
            selectedOption(variant) {
                this.selectedVariant = variant ? JSON.parse(variant) : null;
                if (this.selectedVariant != null) {
                    const btnChange = document.getElementById("pills-" + this.selectedVariant.id);
                    const tabLinks = document.querySelectorAll(".nav-link");
                    const tabs = document.querySelectorAll('[id^="pills-"]');
                    tabs.forEach(tab => {
                        tab.classList.remove("active");
                        tab.classList.remove("show");
                    });
                    if (btnChange) {
                        btnChange.classList.add("active");
                        btnChange.classList.add("show");
                        btnChange.setAttribute("aria-selected", "true");
                    }
                    tabLinks.forEach(tabLink => {
                        tabLink.setAttribute("aria-selected", "false");
                    });
                    this.percent = this.selectedVariant.percent != null ? parseInt(this.selectedVariant.percent) : 0;
                    this.finalPrice = parseInt(this.selectedVariant.final_price) !== 0 ? parseInt(this.selectedVariant.final_price).toLocaleString() : 0;
                    this.price = parseInt(this.selectedVariant.price) !== parseInt(this.selectedVariant.final_price) ? parseInt(this.selectedVariant.price).toLocaleString() : 0;
                    this.stock = parseInt(this.selectedVariant.stock);
                } else {
                    this.finalPrice = "{{ intval(@$product->final_price) != 0 ? number_format(intval(@$product->final_price)) : 0}}";
                    this.price = "{{ intval(@$product->final_price) != intval(@$product->price) ? number_format(intval(@$product->price)) : 0 }}";
                    this.percent = "{{intval(@$product->percent)}}";
                }
            },
            selectedVariant: {

                handler(newVariant) {

                    if (newVariant && this.reset === false) {
                        this.handleImage(newVariant)
                        this.updatePrice(newVariant)
                    }
                    this.reset = false
                }, immediate: true
            },
        },
        computed: {
            filteredMainSpecs() {
                const allVariants = this.getInitialVariants();
                return this.mainSpecs.map(mainSpec => {
                    const filteredChildren = mainSpec.children.filter(child => {
                        if (this.isSelectedSpec(mainSpec.main_id, child.id)) {
                            return true;
                        }

                        const selectedSpecIds = Object.values(this.selectedSpecs);
                        const currentFilterIds = [
                            ...selectedSpecIds.filter(id => !this.isSameSpecId(id, this.selectedSpecs[mainSpec.main_id])),
                            child.id
                        ];

                        if (currentFilterIds.length === 0) {
                            return true;
                        }

                        return allVariants.some(variant => {
                            const variantSpecsIds = variant.specification_ids;
                            return this.hasVariantForSpecs(variantSpecsIds, currentFilterIds);
                        });
                    });
                    return {...mainSpec, children: filteredChildren};
                });
            },
            displayPrice() {
                if (!this.selectedVariant) {
                    return parseInt(this.priceInt).toLocaleString();
                }
                return parseInt(this.selectedVariant.price).toLocaleString();
            },
            displayFinalPrice() {
                if (!this.selectedVariant) {
                    return parseInt(this.finalPriceInt).toLocaleString();
                }
                return parseInt(this.selectedVariant.final_price).toLocaleString();
            },
            discountPercent() {
                if (!this.selectedVariant) {
                    if (this.percent > 0) {
                        return Math.floor(this.percent);
                    }
                    const initialPrice = parseInt(this.priceInt);
                    const initialFinalPrice = parseInt(this.finalPriceInt);
                    if (initialPrice > 0 && initialPrice > initialFinalPrice) {
                        return Math.floor(((initialPrice - initialFinalPrice) / initialPrice) * 100);
                    }
                    return 0;
                }
                const price = this.selectedVariant.price;
                const finalPrice = this.selectedVariant.final_price;
                const percent = ((price - finalPrice) / price) * 100;
                return Math.floor(percent);
            },
            hasVariants() {
                return parseInt(this.variantCount) > 0;
            },
            resolvedStock() {
                if (this.hasVariants) {
                    return this.selectedVariant ? parseInt(this.selectedVariant.stock || 0) : 0;
                }
                return parseInt(this.stock || 0);
            },
            resolvedFinalPrice() {
                if (this.hasVariants) {
                    return this.selectedVariant ? parseInt(this.selectedVariant.final_price || 0) : 0;
                }
                return parseInt(this.finalPriceInt || 0);
            },
            contactRequired() {
                return this.resolvedStock > 0 && this.resolvedFinalPrice === 0;
            },
            needsVariantSelection() {
                return this.hasVariants && !this.selectedVariant;
            },
            isAvailable() {
                return this.resolvedStock > 0 && this.resolvedFinalPrice > 0;
            },
            isUnavailable() {
                return !this.isAvailable && !this.contactRequired && !this.needsVariantSelection;
            }
        },
    });
</script>


