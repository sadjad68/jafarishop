(function (window) {
    'use strict';

    var MAX_OPTION_PAIRS = 3;

    function isTrackingActive() {
        return !!(window.__ecommerceTracking__ && window.__ecommerceTracking__.enabled);
    }

    function canPush() {
        if (!isTrackingActive()) {
            return false;
        }
        window.dataLayer = window.dataLayer || [];
        return true;
    }

    function toRial(toman) {
        var n = parseInt(toman, 10);
        if (isNaN(n)) {
            return 0;
        }
        return n;
    }

    function specificationPairs(specifications) {
        if (!specifications || !specifications.length) {
            return [];
        }
        var pairs = [];
        for (var i = 0; i < specifications.length; i++) {
            var spec = specifications[i];
            var parent = spec.parent || {};
            var name = parent.title || '';
            var value = spec.title || '';
            if (name && value) {
                pairs.push({ name: name, value: value });
            }
        }
        return pairs;
    }

    function mapSpecifications(specifications) {
        var pairs = specificationPairs(specifications);
        if (!pairs.length) {
            return {};
        }
        var result = {};
        var variantParts = [];
        var optionParts = [];
        var limit = Math.min(pairs.length, MAX_OPTION_PAIRS);
        for (var i = 0; i < limit; i++) {
            var pair = pairs[i];
            var n = i + 1;
            result['option_' + n + '_name'] = pair.name;
            result['option_' + n + '_value'] = pair.value;
            variantParts.push(pair.name + ': ' + pair.value);
            optionParts.push(pair.name + '=' + pair.value);
        }
        result.item_variant = variantParts.join(' | ');
        result.variant_options = optionParts.join('|');
        return result;
    }

    function buildItemFromBasketLine(line) {
        var productId = parseInt(line.product_id, 10) || 0;
        var variantId = line.variant_id ? parseInt(line.variant_id, 10) : null;
        var quantity = parseInt(line.quantity, 10) || 1;
        var finalToman = parseInt(line.final_price_toman, 10) || 0;
        var item = {
            item_id: variantId ? String(variantId) : String(productId),
            item_name: line.product_title || '',
            price: toRial(finalToman),
            quantity: quantity < 1 ? 1 : quantity,
        };
        if (variantId) {
            item.parent_item_id = String(productId);
            item.variant_id = String(variantId);
        }
        if (line.brand_title) {
            item.item_brand = line.brand_title;
        }
        var categories = line.category_titles || [];
        if (categories[0]) {
            item.item_category = categories[0];
        }
        if (categories[1]) {
            item.item_category2 = categories[1];
        }
        var optionData = mapSpecifications(line.specifications || []);
        for (var key in optionData) {
            if (Object.prototype.hasOwnProperty.call(optionData, key)) {
                item[key] = optionData[key];
            }
        }
        return item;
    }

    function buildItemFromProductContext(ctx) {
        var productId = parseInt(ctx.product_id, 10) || 0;
        var variantId = ctx.variant_id ? parseInt(ctx.variant_id, 10) : null;
        var quantity = parseInt(ctx.quantity, 10) || 1;
        var finalToman = parseInt(ctx.final_price_toman, 10) || 0;
        var item = {
            item_id: variantId ? String(variantId) : String(productId),
            item_name: ctx.item_name || '',
            price: toRial(finalToman),
            quantity: quantity < 1 ? 1 : quantity,
        };
        if (variantId) {
            item.parent_item_id = String(productId);
            item.variant_id = String(variantId);
        }
        if (ctx.brand_title) {
            item.item_brand = ctx.brand_title;
        }
        if (ctx.category_titles && ctx.category_titles[0]) {
            item.item_category = ctx.category_titles[0];
        }
        if (ctx.category_titles && ctx.category_titles[1]) {
            item.item_category2 = ctx.category_titles[1];
        }
        var optionData = mapSpecifications(ctx.specifications || []);
        for (var key in optionData) {
            if (Object.prototype.hasOwnProperty.call(optionData, key)) {
                item[key] = optionData[key];
            }
        }
        return item;
    }

    function sumItemsValueRial(items) {
        var sum = 0;
        for (var i = 0; i < items.length; i++) {
            var row = items[i];
            sum += (parseInt(row.price, 10) || 0) * (parseInt(row.quantity, 10) || 1);
        }
        return sum;
    }

    function eventPayload(items, extra) {
        extra = extra || {};
        var payload = {
            currency: 'TRY',
            value: Number(sumItemsValueRial(items).toFixed(2)),
            items: items,
        };
        for (var key in extra) {
            if (Object.prototype.hasOwnProperty.call(extra, key)) {
                payload[key] = extra[key];
            }
        }
        return payload;
    }

    function pushEcommerceEvent(eventName, ecommerceData) {
        if (!canPush()) {
            return;
        }
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({ ecommerce: null });
        window.dataLayer.push({
            event: eventName,
            ecommerce: ecommerceData,
        });
    }

    function pushOncePerSession(storageKey, eventName, ecommerceData) {
        if (!canPush()) {
            return;
        }
        try {
            if (sessionStorage.getItem(storageKey)) {
                return;
            }
            sessionStorage.setItem(storageKey, '1');
        } catch (e) {
            // ignore quota / private mode
        }
        pushEcommerceEvent(eventName, ecommerceData);
    }

    window.EcommerceTracking = {
        isTrackingActive: isTrackingActive,
        toRial: toRial,
        buildItemFromBasketLine: buildItemFromBasketLine,
        buildItemFromProductContext: buildItemFromProductContext,
        sumItemsValueRial: sumItemsValueRial,
        eventPayload: eventPayload,
        pushEcommerceEvent: pushEcommerceEvent,
        pushOncePerSession: pushOncePerSession,
    };
})(window);
