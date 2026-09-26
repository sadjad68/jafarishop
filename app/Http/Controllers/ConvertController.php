<?php

namespace App\Http\Controllers;

use App\Library\Assistant\Modules\V1\General;
use App\Modules\User\Entities\UserType;
use App\Services\CmsCoreNamespaceConverter;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Modules\Blog\Entities\Blog;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\Location\Entities\Address;
use App\Modules\Location\Entities\City;
use App\Modules\Location\Entities\State;
use App\Modules\Product\Entities\Brand;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductCategory;
use App\Modules\Product\Entities\ProductVariant;
use App\Modules\Seo\Entities\Redirect;
use App\Modules\Service\Entities\Service;
use App\Modules\Service\Entities\WorkSample;
use App\Modules\Tag\Entities\Tag;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ConvertController extends Controller
{
    protected $oldDatabase;
    protected $newDatabase;


    public function __construct()
    {
        $this->oldDatabase = 'PREVIOUS_CONVERT';
        $this->newDatabase = 'NEXT_CONVERT';
    }

    public function addFieldToTables()
    {
        $tables = ['brands', 'redirect', 'states', 'cities', 'users', 'addresses', 'tags', 'socials', 'products', 'categories', 'images', 'prices', 'properties', 'shipments', 'order_statuses', 'orders', 'order_items', 'contents', 'taggables', 'product_variables', 'product_specification_types', 'product_specifications'];
        foreach ($tables as $table) {
            if (!Schema::connection($this->oldDatabase)->hasColumn($table, 'is_convert')) {
                Schema::connection($this->oldDatabase)->table($table, function (Blueprint $table) {
                    $table->integer('is_convert')->default(0);
                });
            }
        }
        return "Field added to tables successfully!";
    }

    function truncateTables()
    {
        $tables = ['brands', 'redirects', 'states', 'cities', 'users', 'addresses', 'tags', 'socials', 'product_categories', 'seo_metas', 'product_category_product', '', 'products', 'images', 'prices', 'properties', 'shipping_methods', 'shipping_method_city', 'order_shipping_statuses', 'orders', 'order_items', 'user_types', 'blogs', 'blog_categories', 'order_histories', 'role_user', 'taggables', 'product_variants', 'specifications', 'product_specification'];
        foreach ($tables as $table) {
            if (Schema::connection($this->newDatabase)->hasTable($table)) {
                DB::connection($this->newDatabase)->table($table)->truncate();
            }
        }
        return "All records have been deleted from the specified tables.";
    }

    public function convert()
    {
        $convert_atroff = DB::connection($this->newDatabase);
        //addresses
        $atroff_addresses = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM addresses WHERE (deleted_at IS NOT NULL OR deleted_at IS NULL) AND is_convert = 0"));
        foreach ($atroff_addresses as $atroff_address) {
            $convert_atroff->table('addresses')->insert([
                'id' => $atroff_address->id,
                'user_id' => $atroff_address->user_id,
                'state_id' => $atroff_address->state_id,
                'city_id' => $atroff_address->city_id,
                'address' => $atroff_address->location,
                'receiptor_full_name' => $atroff_address->recipient_name,
                'receiptor_mobile' => $atroff_address->recipient_phone,
                'postal_code' => $atroff_address->postal_code,
                'created_at' => $atroff_address->created_at ?? Carbon::now(),
                'updated_at' => $atroff_address->updated_at ?? Carbon::now(),
                'deleted_at' => $atroff_address->deleted_at,
            ]);
            DB::connection($this->oldDatabase)->table('addresses')
                ->where('id', $atroff_address->id)
                ->update(['is_convert' => 1]);
        }
        //brands
        $atroff_brands = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM brands WHERE (deleted_at IS NOT NULL OR deleted_at IS NULL) AND is_convert = 0"));
        foreach ($atroff_brands as $atroff_brand) {
            $convert_atroff->table('brands')->insert([
                'id' => $atroff_brand->id,
                'title' => $atroff_brand->title,
                'description' => $atroff_brand->description,
                'image' => $atroff_brand->image,
                'url' => $atroff_brand->url,
                'active' => $atroff_brand->status,
                'created_at' => $atroff_brand->created_at,
                'updated_at' => $atroff_brand->updated_at,
                'deleted_at' => $atroff_brand->deleted_at,
            ]);
            DB::connection($this->oldDatabase)->table('brands')
                ->where('id', $atroff_brand->id)
                ->update(['is_convert' => 1]);
        }
        //product_categories
        $atroff_categories = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM categories WHERE (deleted_at IS NOT NULL OR deleted_at IS NULL) AND is_convert = 0"));
        foreach ($atroff_categories as $atroff_category) {
            $description_cat = $atroff_category->description;
            $description_cat = str_replace('/ckeditor', '', $description_cat);
            $description_cat = str_replace('/assets', '', $description_cat);
            if (str_contains($description_cat, "https://" . env('DOMAIN_NAME') . "/cat") || str_contains($description_cat, "https://" . env('DOMAIN_NAME') . "/pro")) {
                $description_cat = preg_replace("/https:\/\/" . env('DOMAIN_NAME') . "\/cat\b/", "https://" . env('DOMAIN_NAME') . "/category", $description_cat);
            }
            $convert_atroff->table('product_categories')->insert([
                'id' => $atroff_category->id,
                'title' => $atroff_category->title,
                'description' => $description_cat,
                'url' => $atroff_category->url,
                'image' => $atroff_category->cover,
                'active' => 1,
                'parent_id' => $atroff_category->parent_id,
                'created_at' => $atroff_category->created_at ?? Carbon::now(),
                'updated_at' => $atroff_category->updated_at ?? Carbon::now(),
                'deleted_at' => $atroff_category->deleted_at,
            ]);
            $convert_atroff->table('seo_metas')->insert([
                'title_seo' => $atroff_category->title_seo,
                'description_seo' => $atroff_category->description_seo,
                'seoable_id' => $atroff_category->id,
                'seoable_type' => 'App\Modules\Product\Entities\ProductCategory',
                'created_at' => $atroff_category->created_at,
                'updated_at' => $atroff_category->updated_at,
                'deleted_at' => $atroff_category->deleted_at,
            ]);
            DB::connection($this->oldDatabase)->table('categories')
                ->where('id', $atroff_category->id)
                ->update(['is_convert' => 1]);
        }
        //cities
        $atroff_cities = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM cities WHERE (deleted_at IS NOT NULL OR deleted_at IS NULL) AND is_convert = 0"));
        foreach ($atroff_cities as $atroff_city) {
            $convert_atroff->table('cities')->insert([
                'id' => $atroff_city->id,
                'name' => $atroff_city->name,
                'state_id' => $atroff_city->state_code,
                'status' => $atroff_city->status,
                'created_at' => $atroff_city->created_at ?? Carbon::now(),
                'updated_at' => $atroff_city->updated_at ?? Carbon::now(),
                'deleted_at' => $atroff_city->deleted_at,
            ]);
            DB::connection($this->oldDatabase)->table('cities')
                ->where('id', $atroff_city->id)
                ->update(['is_convert' => 1]);
        }
        //states
        $atroff_states = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM states WHERE (deleted_at IS NOT NULL OR deleted_at IS NULL) AND is_convert = 0"));
        foreach ($atroff_states as $atroff_state) {
            $convert_atroff->table('states')->insert([
                'id' => $atroff_state->id,
                'name' => $atroff_state->name,
                'status' => $atroff_state->status,
                'created_at' => $atroff_state->created_at ?? Carbon::now(),
                'updated_at' => $atroff_state->updated_at ?? Carbon::now(),
                'deleted_at' => $atroff_state->deleted_at,
            ]);
            DB::connection($this->oldDatabase)->table('states')
                ->where('id', $atroff_state->id)
                ->update(['is_convert' => 1]);
        }
        //images
        $atroff_images = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM images WHERE (deleted_at IS NOT NULL OR deleted_at IS NULL) AND is_convert = 0"));
        foreach ($atroff_images as $atroff_image) {
            $convert_atroff->table('images')->insert([
                'id' => $atroff_image->id,
                'image' => $atroff_image->file,
                'specification_id' => $atroff_image->product_variable_id,
                'thumbnail' => $atroff_image->thumbnail,
                'product_id' => $atroff_image->product_id,
                'created_at' => $atroff_image->created_at ?? Carbon::now(),
                'updated_at' => $atroff_image->updated_at ?? Carbon::now(),
                'deleted_at' => $atroff_image->deleted_at,
            ]);
            DB::connection($this->oldDatabase)->table('images')
                ->where('id', $atroff_image->id)
                ->update(['is_convert' => 1]);
        }
        ///product-sp-types
        $atroff_product_variables2 = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM product_variables WHERE (deleted_at IS NOT NULL OR deleted_at IS NULL) AND is_convert = 0"));
        foreach ($atroff_product_variables2 as $atroff_product_variable1) {
            $convert_atroff->table('specifications')->insert([
                'id' => $atroff_product_variable1->id,
                'title' => $atroff_product_variable1->title,
                'type' => 'select',
                'is_filter' => 1,
                'active' => 1,
                'created_at' => $atroff_product_variable1->created_at ?? Carbon::now(),
                'updated_at' => $atroff_product_variable1->updated_at ?? Carbon::now(),
                'deleted_at' => $atroff_product_variable1->deleted_at,
            ]);
            DB::connection($this->oldDatabase)->table('product_specification_types')
                ->where('id', $atroff_product_variable1->id)
                ->update(['is_convert' => 1]);
        }
        $convert_atroff->table('specifications')->insert([
            'title' => 'متغییر اصلی',
            'type' => 'select',
            'is_filter' => 1,
            'active' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $specification_parent_id = DB::connection($this->newDatabase)->table('specifications')
            ->where('title', 'متغییر اصلی')
            ->first();
        if ($specification_parent_id) {
            $cms_variables = DB::connection($this->newDatabase)->table('specifications')->get();
            foreach ($cms_variables as $cms_variable) {
                if ($cms_variable->id !== $specification_parent_id->id) {
                    DB::connection($this->newDatabase)->table('specifications')
                        ->where('id', $cms_variable->id)
                        ->update(['parent_id' => $specification_parent_id->id]);
                } else {
                    DB::connection($this->newDatabase)->table('specifications')
                        ->where('id', $cms_variable->id)
                        ->update(['parent_id' => null]);
                }
            }
        }

        //products
        $atroff_products = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM products WHERE (deleted_at IS NOT NULL OR deleted_at IS NULL) AND is_convert = 0"));
        foreach ($atroff_products as $atroff_product) {
            $description_pro = $atroff_product->description;
            $description_pro = str_replace('/ckeditor', '', $description_pro);
            $description_pro = str_replace('/assets', '', $description_pro);
            if (str_contains($description_pro, "https://" . env('DOMAIN_NAME') . "/cat") || str_contains($description_pro, "https://" . env('DOMAIN_NAME') . "/pro")) {
                $description_pro = preg_replace("/https:\/\/" . env('DOMAIN_NAME') . "\/cat\b/", "https://" . env('DOMAIN_NAME') . "/category", $description_pro);
            }
            $image = DB::connection($this->oldDatabase)->table('images')->orderBy('thumbnail', 'desc')->where('product_id', $atroff_product->id)->whereNull('deleted_at')->first();
            $count = intval($atroff_product->count) ?? 0;
            $convert_atroff->table('products')->insert([
                'id' => $atroff_product->id,
                'main_variant_specification_id' => $specification_parent_id->id,
                'title' => $atroff_product->title,
                'description' => $description_pro,
                'url' => $atroff_product->url,
                'active' => $atroff_product->status,
                'show_in_first_page' => $atroff_product->status,
                'brand_id' => $atroff_product->brand_id,
                'stock' => $count,
                'image' => $image->file ?? null,
                'price' => intval(NumberHelper::persian2LatinDigit($atroff_product->old_price)),
                'discounted_price' => intval(NumberHelper::persian2LatinDigit($atroff_product->price)),
                'final_price' => intval(NumberHelper::persian2LatinDigit($atroff_product->price)) != 0 ? intval(NumberHelper::persian2LatinDigit($atroff_product->price)) : intval(NumberHelper::persian2LatinDigit($atroff_product->old_price)),
                'created_at' => $atroff_product->created_at ?? Carbon::now(),
                'updated_at' => $atroff_product->updated_at ?? Carbon::now(),
                'deleted_at' => $atroff_product->deleted_at,
            ]);
            $convert_atroff->table('seo_metas')->insert([
                'title_seo' => $atroff_product->title_seo,
                'description_seo' => $atroff_product->description_seo,
                'seoable_id' => $atroff_product->id,
                'seoable_type' => 'App\Modules\Product\Entities\Product',
                'created_at' => $atroff_product->created_at,
                'updated_at' => $atroff_product->updated_at,
                'deleted_at' => $atroff_product->deleted_at,
            ]);
            DB::connection($this->oldDatabase)->table('products')
                ->where('id', $atroff_product->id)
                ->update(['is_convert' => 1]);
        }
        ///product-sp
        $atroff_product_sps = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM product_specifications WHERE (deleted_at IS NOT NULL OR deleted_at IS NULL) AND is_convert = 0"));
        foreach ($atroff_product_sps as $atroff_product_sp) {
            $convert_atroff->table('product_specification')->insert([
                'id' => $atroff_product_sp->id,
                'product_id' => $atroff_product_sp->product_id,
                'specification_id' => $atroff_product_sp->product_specification_type_id,
                'created_at' => $atroff_product_sp->created_at ?? Carbon::now(),
                'updated_at' => $atroff_product_sp->updated_at ?? Carbon::now(),
                'deleted_at' => $atroff_product_sp->deleted_at,
            ]);
            DB::connection($this->oldDatabase)->table('product_specifications')
                ->where('id', $atroff_product_sp->id)
                ->update(['is_convert' => 1]);
        }
        ///product-sp-type-cats
        $atroff_product_sp_type_cats = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM product_specification_type_category"));
        foreach ($atroff_product_sp_type_cats as $atroff_product_sp_type_cat) {
            $convert_atroff->table('category_specification')->insert([
                'product_category_id' => $atroff_product_sp_type_cat->category_id,
                'specification_id' => $atroff_product_sp_type_cat->pst_id,
            ]);
        }
        //product_variables
        $atroff_product_variables = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM product_variables WHERE (deleted_at IS NOT NULL OR deleted_at IS NULL) AND is_convert = 0"));
        foreach ($atroff_product_variables as $atroff_product_variable) {
            $count_var = intval($atroff_product_variable->stock) ?? 0;
            $convert_atroff->table('product_variants')->insert([
                'id' => $atroff_product_variable->id,
                'specification_id' => $atroff_product_variable->id,
                'specification_parent_id' => $specification_parent_id->id,
                'product_id' => $atroff_product_variable->product_id,
                'price' => intval(NumberHelper::persian2LatinDigit($atroff_product_variable->price)),
                'discounted_price' => intval(NumberHelper::persian2LatinDigit($atroff_product_variable->discounted_price)),
                'final_price' => intval(NumberHelper::persian2LatinDigit($atroff_product_variable->discounted_price)) != 0 ? intval(NumberHelper::persian2LatinDigit($atroff_product_variable->discounted_price)) : intval(NumberHelper::persian2LatinDigit($atroff_product_variable->price)),
                'stock' => $atroff_product_variable->stock,
                'price_affective' => $count_var,
                'created_at' => $atroff_product_variable->created_at ?? Carbon::now(),
                'updated_at' => $atroff_product_variable->updated_at ?? Carbon::now(),
                'deleted_at' => $atroff_product_variable->deleted_at,
            ]);
            DB::connection($this->oldDatabase)->table('product_variables')
                ->where('id', $atroff_product->id)
                ->update(['is_convert' => 1]);
        }
        //product_category_product
        $atroff_category_products = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM product_category"));
        foreach ($atroff_category_products as $atroff_category_product) {
            $convert_atroff->table('product_category_product')->insert([
                'product_id' => $atroff_category_product->product_id,
                'product_category_id' => $atroff_category_product->category_id,
            ]);
        }
        //prices
        $atroff_prices = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM prices WHERE (deleted_at IS NOT NULL OR deleted_at IS NULL) AND is_convert = 0"));
        foreach ($atroff_prices as $atroff_price) {
            $convert_atroff->table('prices')->insert([
                'id' => $atroff_price->id,
                'priceable_id' => $atroff_price->priceable_id,
                'priceable_type' => $atroff_price->priceable_type == 'App\Models\Product' ? 'App\Modules\Product\Entities\Product' : 'App\Modules\Product\Entities\ProductVariant',
                'price' => intval(NumberHelper::persian2LatinDigit($atroff_price->old_price)),
                'discounted_price' => intval(NumberHelper::persian2LatinDigit($atroff_price->price)),
                'final_price' => intval(NumberHelper::persian2LatinDigit($atroff_price->price)) != 0 ? intval(NumberHelper::persian2LatinDigit($atroff_price->price)) : intval(NumberHelper::persian2LatinDigit($atroff_price->old_price)),
                'created_at' => $atroff_price->created_at ?? Carbon::now(),
                'updated_at' => $atroff_price->updated_at ?? Carbon::now(),
                'deleted_at' => $atroff_price->deleted_at,
            ]);
            DB::connection($this->oldDatabase)->table('prices')
                ->where('id', $atroff_price->id)
                ->update(['is_convert' => 1]);
        }
        //properties
        $atroff_properties = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM properties WHERE (deleted_at IS NOT NULL OR deleted_at IS NULL) AND is_convert = 0"));
        foreach ($atroff_properties as $atroff_property) {
            $convert_atroff->table('properties')->insert([
                'id' => $atroff_property->id,
                'value' => $atroff_property->description,
                'product_id' => $atroff_property->product_id,
                'created_at' => $atroff_property->created_at ?? Carbon::now(),
                'updated_at' => $atroff_property->updated_at ?? Carbon::now(),
                'deleted_at' => $atroff_property->deleted_at,
            ]);
            DB::connection($this->oldDatabase)->table('properties')
                ->where('id', $atroff_property->id)
                ->update(['is_convert' => 1]);
        }
        //shipment
        $atroff_shipments = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM shipments WHERE (deleted_at IS NOT NULL OR deleted_at IS NULL) AND is_convert = 0"));
        foreach ($atroff_shipments as $atroff_shipment) {
            $convert_atroff->table('shipping_methods')->insert([
                'id' => $atroff_shipment->id,
                'title' => $atroff_shipment->title,
                'price' => $atroff_shipment->price,
                'status' => $atroff_shipment->status,
                'created_at' => $atroff_shipment->created_at ?? Carbon::now(),
                'updated_at' => $atroff_shipment->updated_at ?? Carbon::now(),
                'deleted_at' => $atroff_shipment->deleted_at,
            ]);
            DB::connection($this->oldDatabase)->table('shipments')
                ->where('id', $atroff_shipment->id)
                ->update(['is_convert' => 1]);
        }
        //shipment_city
        $atroff_shipment_cities = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM shipment_city"));
        foreach ($atroff_shipment_cities as $atroff_shipment_city) {
            $convert_atroff->table('shipping_method_city')->insert([
                'shipping_method_id' => $atroff_shipment_city->ship_ment_id,
                'city_id' => $atroff_shipment_city->city_id,
            ]);
        }
        //order_shipping_statuses
        $order_shipping_statuses = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM order_statuses WHERE (deleted_at IS NOT NULL OR deleted_at IS NULL) AND is_convert = 0"));
        foreach ($order_shipping_statuses as $order_shipping_status) {
            if ($order_shipping_status->id == 4 || $order_shipping_status->id == 5) {
                $convert_atroff->table('order_shipping_statuses')->insert([
                    'id' => $order_shipping_status->id,
                    'title' => $order_shipping_status->title,
                    'color' => '#FFC0CB',
                    'created_at' => $order_shipping_status->created_at ?? Carbon::now(),
                    'updated_at' => $order_shipping_status->updated_at ?? Carbon::now(),
                    'deleted_at' => $order_shipping_status->deleted_at,
                ]);
                DB::connection($this->oldDatabase)->table('order_statuses')
                    ->where('id', $order_shipping_status->id)
                    ->update(['is_convert' => 1]);
            }
        }
        $convert_atroff->table('order_shipping_statuses')->insert([
            'title' => 'در انتطار بررسی',
            'default' => 1,
        ]);
        //orders
        $atroff_orders = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM orders WHERE (deleted_at IS NOT NULL OR deleted_at IS NULL) AND is_convert = 0"));
        foreach ($atroff_orders as $atroff_order) {
            $address = DB::connection($this->newDatabase)->table('addresses')->find($atroff_order->address_id);
            $address_state = DB::connection($this->newDatabase)->table('states')->find($address->state_id);
            $address_city = DB::connection($this->newDatabase)->table('cities')->find($address->city_id);
            $convert_atroff->table('orders')->insert([
                'id' => $atroff_order->id,
                'user_id' => $atroff_order->user_id,
                'city_id' => $atroff_order->city_id,
                'state_id' => $atroff_order->state_id,
                'address_id' => $atroff_order->address_id,
                'shipping_method_id' => $atroff_order->shipment_id,
                'bank_id' => $atroff_order->bank_id,
                'discount_id' => $atroff_order->discount_id,
                'payment_price' => $atroff_order->payment,
                'total_price' => $atroff_order->total_prices,
                'shipping_price' => $atroff_order->post_price,
                'shipping_status_id' => $atroff_order->order_status_id,
                'address' => json_encode([
                    'state' => @$address_state->name ?? '',
                    'city' => @$address_city->name ?? '',
                    'address' => @$address->address ?? '',
                    'receiptor_mobile' => @$address->recipient_phone ?? '',
                    'postal_code' => @$address->postal_code ?? '',
                    'receiptor_full_name' => $address->receiptor_full_name,
                ]),
                'order_status' => in_array($atroff_order->order_status_id, [2, 1]) ? 'paying' :
                    (in_array($atroff_order->order_status_id, [3, 4, 5]) ? 'paid' :
                        ($atroff_order->order_status_id == 10 ? 'unpaid' : null)),
                'created_at' => $atroff_order->created_at ?? Carbon::now(),
                'updated_at' => $atroff_order->updated_at ?? Carbon::now(),
                'deleted_at' => $atroff_order->deleted_at,
            ]);
            $convert_atroff->table('order_histories')->insert([
                'order_id' => $atroff_order->user_id,
                'shipping_status_id' => $atroff_order->order_status_id,
                'order_status' => $atroff_order->order_status_id == 2 ? 'paying' : ($atroff_order->order_status_id == 3 ? 'paid' : null),
            ]);
            DB::connection($this->oldDatabase)->table('orders')
                ->where('id', $atroff_order->id)
                ->update(['is_convert' => 1]);
        }
        //order_items
        $atroff_order_items = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM order_items WHERE (deleted_at IS NOT NULL OR deleted_at IS NULL) AND is_convert = 0"));
        foreach ($atroff_order_items as $atroff_order_item) {
            $convert_atroff->table('order_items')->insert([
                'id' => $atroff_order_item->id,
                'order_id' => $atroff_order_item->order_id,
                'product_id' => $atroff_order_item->product_id,
                'product_variant_id' => $atroff_order_item->product_variable_id,
                'quantity' => $atroff_order_item->quantity,
                'price' => $atroff_order_item->price,
                'discounted_price' => $atroff_order_item->discount,
                'created_at' => $atroff_order_item->created_at ?? Carbon::now(),
                'updated_at' => $atroff_order_item->updated_at ?? Carbon::now(),
                'deleted_at' => $atroff_order_item->deleted_at,
            ]);
            DB::connection($this->oldDatabase)->table('order_items')
                ->where('id', $atroff_order_item->id)
                ->update(['is_convert' => 1]);
        }
        //redirects
        $atroff_reds = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM redirect WHERE deleted_at IS NULL AND is_convert = 0"));
        foreach ($atroff_reds as $atroff_red) {
            if ($atroff_red->new_address != '')
                $convert_atroff->table('redirects')->insert([
                    'id' => $atroff_red->id,
                    'old_address' => $atroff_red->old_address,
                    'new_address' => $atroff_red->new_address,
                    'created_at' => $atroff_red->created_at,
                    'updated_at' => $atroff_red->updated_at,
                ]);
            DB::connection($this->oldDatabase)->table('redirect')
                ->where('id', $atroff_red->id)
                ->update(['is_convert' => 1]);
        }
        //users
        $atroff_users = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM users WHERE (deleted_at IS NOT NULL OR deleted_at IS NULL) AND is_convert = 0"));
        foreach ($atroff_users as $atroff_user) {
            $convert_atroff->table('users')->insert([
                'id' => $atroff_user->id,
                'full_name' =>\Illuminate\Support\Str::limit($atroff_user->name . " " . $atroff_user->family, 150, $end='...'),
                'mobile' => $atroff_user->mobile,
                'email' => $atroff_user->email,
                'confirm_code' => $atroff_user->confirm_code,
                'password' => $atroff_user->password,
                'avatar' => $atroff_user->avatar,
                'created_at' => $atroff_user->created_at ?? Carbon::now(),
                'updated_at' => $atroff_user->updated_at ?? Carbon::now(),
                'deleted_at' => $atroff_user->deleted_at,
            ]);
            DB::connection($this->oldDatabase)->table('users')
                ->where('id', $atroff_user->id)
                ->update(['is_convert' => 1]);
        }
        DB::connection($this->newDatabase)->table('users')->where('id', 1)->update(['password' => bcrypt('123456')]);
        $convert_atroff->table('role_user')->insert([
            'role_id' => 1,
            'user_id' => 1,
        ]);
        foreach ($atroff_users as $atroff_user) {
            $convert_atroff->table('user_types')->insert([
                'user_id' => $atroff_user->id,
                'type' => $atroff_user->admin == 1 ? 'Admin' : 'Teacher',
                'created_at' => $atroff_user->created_at ?? Carbon::now(),
                'updated_at' => $atroff_user->updated_at ?? Carbon::now(),
            ]);
            DB::connection($this->oldDatabase)->table('users')
                ->where('id', $atroff_user->id)
                ->update(['is_convert' => 1]);
        }
        //tags
        $atroff_tags = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM tags WHERE (deleted_at IS NOT NULL OR deleted_at IS NULL) AND is_convert = 0"));
        foreach ($atroff_tags as $atroff_tag) {
            $convert_atroff->table('tags')->insert([
                'id' => $atroff_tag->id,
                'title' => $atroff_tag->title,
                'description' => $atroff_tag->description,
                'url' => $atroff_tag->url,
                'created_at' => $atroff_tag->created_at ?? Carbon::now(),
                'updated_at' => $atroff_tag->updated_at ?? Carbon::now(),
                'deleted_at' => $atroff_tag->deleted_at,
            ]);
            $convert_atroff->table('seo_metas')->insert([
                'title_seo' => $atroff_tag->title_seo,
                'description_seo' => $atroff_tag->description_seo,
                'seoable_id' => $atroff_tag->id,
                'seoable_type' => 'App\Modules\Tag\Entities\Tag',
                'created_at' => $atroff_tag->created_at,
                'updated_at' => $atroff_tag->updated_at,
                'deleted_at' => $atroff_tag->deleted_at,
            ]);
            DB::connection($this->oldDatabase)->table('tags')
                ->where('id', $atroff_tag->id)
                ->update(['is_convert' => 1]);
        }
        //taggables
        $atroff_taggables = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM taggables WHERE  is_convert = 0"));
        foreach ($atroff_taggables as $atroff_taggable) {
            $convert_atroff->table('taggables')->insert([
                'id' => $atroff_taggable->id,
                'taggable_id' => $atroff_taggable->taggable_id,
                'taggable_type' => $atroff_taggable->taggable_type == 'App\Models\Product' ? 'App\Modules\Product\Entities\Product' : 'App\Modules\Product\Entities\ProductVariant',
                'tag_id' => $atroff_taggable->tag_id,
                'created_at' => $atroff_tag->created_at ?? Carbon::now(),
                'updated_at' => $atroff_tag->updated_at ?? Carbon::now(),
            ]);
            DB::connection($this->oldDatabase)->table('taggables')
                ->where('id', $atroff_taggable->id)
                ->update(['is_convert' => 1]);
        }
        //socials
        $atroff_socials = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM socials WHERE (deleted_at IS NOT NULL OR deleted_at IS NULL) AND is_convert = 0"));
        foreach ($atroff_socials as $atroff_social) {
            $convert_atroff->table('socials')->insert([
                'id' => $atroff_social->id,
                'icon' => $atroff_social->name,
                'link' => $atroff_social->address,
                'created_at' => $atroff_social->created_at ?? Carbon::now(),
                'updated_at' => $atroff_social->updated_at ?? Carbon::now(),
                'deleted_at' => $atroff_social->deleted_at,
            ]);
            DB::connection($this->oldDatabase)->table('socials')
                ->where('id', $atroff_social->id)
                ->update(['is_convert' => 1]);
        }
        ///contents
        $convert_atroff->table('blog_categories')->insert([
            'title' => 'مقالات',
            'description' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز ',
            'url' => 'blogs',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $atroff_contents_1 = DB::connection($this->oldDatabase)->select(DB::raw("SELECT * FROM contents WHERE (deleted_at IS NOT NULL OR deleted_at IS NULL) AND is_convert = 0 AND content_type = 1"));
        foreach ($atroff_contents_1 as $atroff_content_1) {
            $description = $atroff_content_1->description;
            $description = str_replace('/ckeditor', '', $description);
            $description = str_replace('/assets', '', $description);
            if (str_contains($description, "https://" . env('DOMAIN_NAME') . "/cat") || str_contains($description, "https://" . env('DOMAIN_NAME') . "/pro")) {
                $description = preg_replace("/https:\/\/" . env('DOMAIN_NAME') . "\/cat\b/", "https://" . env('DOMAIN_NAME') . "/category", $description);
                $description = preg_replace("/https:\/\/" . env('DOMAIN_NAME') . "\/pro\b/", "https://" . env('DOMAIN_NAME') . "/product", $description);
            }
            $convert_atroff->table('blogs')->insert([
                'id' => $atroff_content_1->id,
                'title' => $atroff_content_1->title,
                'description' => $description, // استفاده از توضیحات به‌روز شده
                'parent_id' => 1,
                'url' => $atroff_content_1->id,
                'image' => $atroff_content_1->image,
                'status' => $atroff_content_1->status,
                'created_at' => $atroff_content_1->created_at ?? Carbon::now(),
                'updated_at' => $atroff_content_1->updated_at ?? Carbon::now(),
                'deleted_at' => $atroff_content_1->deleted_at,
            ]);

            $convert_atroff->table('seo_metas')->insert([
                'title_seo' => $atroff_content_1->title_seo,
                'description_seo' => $atroff_content_1->description_seo,
                'seoable_id' => $atroff_content_1->id,
                'seoable_type' => 'App\Modules\Blog\Entities\Blog',
                'created_at' => $atroff_content_1->created_at,
                'updated_at' => $atroff_content_1->updated_at,
                'deleted_at' => $atroff_content_1->deleted_at,
            ]);

            DB::connection($this->oldDatabase)->table('contents')
                ->where('id', $atroff_content_1->id)
                ->update(['is_convert' => 1]);
        }

        $mapping = [
            'siteName_fa' => 'title',
            'siteName_en' => 'title',
            'enemad' => 'footer_enamd',
            'logo' => 'logo',
            'favicon' => 'logo',
            'main_phone_number' => 'contact',
            'phone_numbers' => 'phone',
            'email' => 'email',
            'map' => 'maps',
            'first_page_first_title' => 'title',
            'footer_contacts' => 'phone',
            'footer_logo' => 'logo',
            'about_us' => 'about',
            'first_page_first_text' => 'title',
            'slider_description' => 'h1',
            'kavenegar_key' => 'kave_api',
            'admin_mobile' => 'kave_phonenumber',
            'slider_title' => 'h1',
            'footer_about_text'=> 'h1'
        ];
        $normalSettings = DB::connection($this->oldDatabase)->table('setting')->get();
        foreach ($normalSettings as $setting) {
            foreach ($mapping as $key => $field) {
                $value = $setting->$field ?? null;
                DB::connection($this->newDatabase)->table('settings')->where('key', $key)->update(['value' => $value]);
            }
            if (!empty($setting->merchent)) {
                DB::connection($this->newDatabase)->table('banks')
                    ->where('bank_type', 'zarinPal')
                    ->update(['config' => json_encode(['MerchantId' => $setting->merchent]), 'status' => 1]);

            }
            $convert_atroff->table('seo_metas')->insert([
                'title_seo' => $setting->h1,
                'description_seo' => $setting->description_seo,
                'url' => '/'
            ]);
            $convert_atroff->table('seo_metas')->insert([
                'title_seo' => $setting->abouttitle,
                'description_seo' => $setting->description_seo,
                'url' => '/about-us'
            ]);
            $convert_atroff->table('seo_metas')->insert([
                'title_seo' => $setting->title_contact,
                'description_seo' => $setting->description_seo,
                'url' => '/contact-us'
            ]);
        }
        return 'Data transferred successfully!';
    }

    public function redirectUrls()
    {
        $convert_atroff = DB::connection($this->newDatabase);
        $convert_atroff->table('redirects')->insert([
            'old_address' => 'blogs',
            'new_address' => 'posts',
        ]);
        $convert_atroff->table('redirects')->insert([
            'old_address' => 'page-detail/directselling',
            'new_address' => '/',
        ]);
        $atroff_categories = DB::connection($this->newDatabase)->select(DB::raw("SELECT * FROM product_categories"));
        foreach ($atroff_categories as $atroff_category) {
            $old_address = 'cat' . '/' . $atroff_category->url;
            $new_address = 'category' . '/' . $atroff_category->url;
            $convert_atroff->table('redirects')->insert([
                'old_address' => $old_address,
                'new_address' => $new_address,
            ]);
        }
        $atroff_products = DB::connection($this->newDatabase)->select(DB::raw("SELECT * FROM products"));
        foreach ($atroff_products as $atroff_product) {
            $old_address = 'pro' . '/' . $atroff_product->url;
            $new_address = 'product' . '/' . $atroff_product->url;
            $convert_atroff->table('redirects')->insert([
                'old_address' => $old_address,
                'new_address' => $new_address,
            ]);
        }
        $atroff_contents_1 = DB::connection($this->newDatabase)->select(DB::raw("SELECT * FROM blogs"));
        foreach ($atroff_contents_1 as $atroff_content_1) {
            $old_address = 'blog-detail' . '/' . $atroff_content_1->id;
            $new_address = 'post' . '/' . $atroff_content_1->url;
            $convert_atroff->table('redirects')->insert([
                'old_address' => $old_address,
                'new_address' => $new_address,
            ]);
        }
    }

    function resetIsConvert()
    {
        $tables = ['brands', 'redirect', 'states', 'cities', 'users', 'addresses', 'tags', 'socials', 'products', 'categories', 'images', 'prices', 'properties', 'shipments', 'order_statuses', 'orders', 'order_items', 'contents', 'taggables', 'product_variables', 'product_specification_types', 'product_specifications'];
        foreach ($tables as $table) {
            if (Schema::connection($this->oldDatabase)->hasColumn($table, 'is_convert')) {
                DB::connection($this->oldDatabase)->table($table)
                    ->where('is_convert', 1)
                    ->update(['is_convert' => 0]);
            }
        }
        return "is_convert field reset to 0 in all specified tables.";
    }
    public function productMainSpecifications()
    {
        $products = Product::orderBy('id', 'desc')->whereNotNull('main_variant_specification_id')->get();
        foreach ($products as $product) {
            $product->main_specifications()->sync($product['main_variant_specification_id']);
        }
    }
    public function productVariant()
    {
        $variants = ProductVariant::orderBy('id', 'desc')->whereNotNull('specification_id')->get();
        foreach ($variants as $variant) {
            $variant->specifications()->sync($variant['specification_id']);
        }
    }

    /**
     * Replace stored Rahweb\CmsCore\ class names with App\ in morph / text columns.
     * Protected by AUTH_TOKEN query param or a logged-in Admin user.
     */
    public function convertCmsCoreNamespaces(Request $request, CmsCoreNamespaceConverter $converter): JsonResponse
    {
        $token = (string) $request->query('token', '');
        $expected = (string) env('AUTH_TOKEN', '');
        $tokenOk = $expected !== '' && hash_equals($expected, $token);

        $isAdmin = false;
        if (Auth::check()) {
            $isAdmin = UserType::where('user_id', Auth::id())->where('type', 'Admin')->exists();
        }

        if (!$tokenOk && !$isAdmin) {
            abort(403, 'Unauthorized.');
        }

        $result = $converter->convert($request->boolean('dry_run'));

        return response()->json($result);
    }

    /**
     * Null discounted_price wherever it is equal to price.
     * Protected by AUTH_TOKEN query param or a logged-in Admin user.
     */
    public function nullEqualDiscountedPrices(Request $request): JsonResponse
    {
        $token = (string) $request->query('token', '');
        $expected = (string) env('AUTH_TOKEN', '');
        $tokenOk = $expected !== '' && hash_equals($expected, $token);

        $isAdmin = false;
        if (Auth::check()) {
            $isAdmin = UserType::where('user_id', Auth::id())->where('type', 'Admin')->exists();
        }

        if (!$tokenOk && !$isAdmin) {
            abort(403, 'Unauthorized.');
        }

        $equalToPrice = function ($query) {
            $query->whereNotNull('discounted_price')
                ->whereColumn('discounted_price', 'price');
        };

        $updated = DB::transaction(function () use ($equalToPrice) {
            $now = now();

            return [
                'products' => DB::table('products')->where($equalToPrice)->update([
                    'discounted_price' => null,
                    'updated_at' => $now,
                ]),
                'variants' => DB::table('product_variants')->where($equalToPrice)->update([
                    'discounted_price' => null,
                    'updated_at' => $now,
                ]),
            ];
        });

        return response()->json($updated);
    }

}
