<?php

namespace App\Admin\Services;

use App\Admin\Imports\ProductSheetImport;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\Product\Entities\Brand;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductCategory;

class ProductImportService
{
    protected ProductImportResult $result;
    /** @var array<string, int> title => id */
    protected array $categoryMap = [];
    /** @var array<string, int> title => id */
    protected array $brandMap = [];

    public function __construct()
    {
        $this->result = new ProductImportResult();
    }

    public function process(UploadedFile $file): ProductImportResult
    {
        $this->result = new ProductImportResult();
        $this->loadCategoryAndBrandMaps();

        $rows = $this->readExcel($file);
        if ($rows === null) {
            $this->result->addRowError(1, 'فایل اکسل معتبر نیست یا قابل خواندن نیست.');
            return $this->result;
        }

        $this->result->totalRows = count($rows);

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // 1-based + header
            if ($this->isEmptyRow($row)) {
                continue;
            }
            $validated = $this->validateAndNormalizeRow($row, $rowNumber);
            if ($validated === null) {
                $this->result->failed++;
                continue;
            }

            try {
                DB::transaction(function () use ($validated, $rowNumber) {
                    $product = Product::withTrashed()->where('url', $validated['url'])->first();

                    if ($product) {
                        $this->updateProduct($product, $validated);
                        $this->result->updated++;
                    } else {
                        $this->createProduct($validated);
                        $this->result->imported++;
                    }
                });
            } catch (\Throwable $e) {
                $this->result->failed++;
                $this->result->addRowError($rowNumber, 'خطای سیستمی: ' . $e->getMessage());
                Log::warning('Product import row error', [
                    'row' => $rowNumber,
                    'data' => $validated ?? $row ?? null,
                    'exception' => $e->getMessage(),
                ]);
            }
        }

        return $this->result;
    }

    protected function loadCategoryAndBrandMaps(): void
    {
        $this->categoryMap = ProductCategory::query()
            ->pluck('id', 'title')
            ->toArray();
        $this->brandMap = Brand::query()
            ->pluck('id', 'title')
            ->toArray();
    }

    /**
     * @return array[]|null
     */
    protected function readExcel(UploadedFile $file): ?array
    {
        try {
            $data = Excel::toArray(new ProductSheetImport(), $file);
            $rows = $data[0] ?? [];
            return is_array($rows) ? $rows : null;
        } catch (\Throwable $e) {
            Log::warning('Product import read excel failed', ['exception' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>|null
     */
    protected function validateAndNormalizeRow(array $row, int $rowNumber): ?array
    {
        $errors = [];
        $get = function (array $keys) use ($row) {
            foreach ($keys as $k) {
                if (array_key_exists($k, $row) && $row[$k] !== null && $row[$k] !== '') {
                    return $row[$k];
                }
            }
            return null;
        };

        $name = $this->trimCell($get(['name', 'نام']));
        if ($name === '') {
            $errors[] = 'نام محصول الزامی است.';
        }

        $urlRaw = $this->trimCell($get(['url', 'آدرس']));
        $url = $this->sanitizeUrl($urlRaw);
        if ($url === '') {
            $errors[] = 'آدرس (url) الزامی است.';
        }

        $categoryTitle = $this->trimCell($get(['category', 'دسته‌بندی']));
        if ($categoryTitle === '') {
            $errors[] = 'دسته‌بندی الزامی است.';
        }
        $categoryId = $this->resolveCategoryId($categoryTitle);
        if ($categoryId === null && $categoryTitle !== '') {
            $errors[] = 'دسته‌بندی یافت نشد: ' . $categoryTitle;
        }

        $brandTitle = $this->trimCell($get(['brand', 'برند']));
        if ($brandTitle === '') {
            $errors[] = 'برند الزامی است.';
        }
        $brandId = $this->resolveBrandId($brandTitle);
        if ($brandId === null && $brandTitle !== '') {
            $errors[] = 'برند یافت نشد: ' . $brandTitle;
        }

        $price = $this->parsePrice($get(['price', 'قیمت']));
        if ($price === null || $price < 0) {
            $errors[] = 'قیمت الزامی و باید عدد نامنفی باشد.';
        }
        $price = $price ?? 0;

        $discountedPrice = $this->parsePrice($get(['discounted_price', 'discounted-price', 'قیمت_تخفیف']));
        if ($discountedPrice !== null && $discountedPrice < 0) {
            $errors[] = 'قیمت تخفیف‌خورده نمی‌تواند منفی باشد.';
        }
        if ($discountedPrice !== null && $price > 0 && $discountedPrice > $price) {
            $errors[] = 'قیمت تخفیف‌خورده نباید بیشتر از قیمت اصلی باشد.';
        }

        $stock = $this->parseInteger($get(['stock', 'موجودی']));
        if ($stock === null || $stock < 0) {
            $errors[] = 'موجودی الزامی و باید عدد صحیح نامنفی باشد.';
        }
        $stock = $stock ?? 0;

        if (! empty($errors)) {
            $this->result->failed++;
            $this->result->addRowErrors($rowNumber, $errors);
            return null;
        }

        $finalPrice = ($discountedPrice !== null && (int) $discountedPrice > 0) ? (int) $discountedPrice : (int) $price;
        $discountedPriceValue = ($discountedPrice !== null && (int) $discountedPrice > 0) ? (int) $discountedPrice : 0;

        return [
            'title' => $name,
            'url' => $url,
            'category_id' => $categoryId,
            'brand_id' => $brandId,
            'price' => (int) $price,
            'discounted_price' => $discountedPriceValue,
            'final_price' => $finalPrice,
            'stock' => (int) $stock,
            'active' => 1,
        ];
    }

    protected function trimCell(mixed $value): string
    {
        if ($value === null || is_array($value)) {
            return '';
        }
        return trim((string) $value);
    }

    protected function sanitizeUrl(string $raw): string
    {
        $s = trim(str_replace(' ', '-', $raw));
        $s = preg_replace('/[^\p{L}\p{N}\-_]/u', '-', $s);
        $s = preg_replace('/-+/', '-', $s);
        return trim($s, '-');
    }

    protected function resolveCategoryId(string $title): ?int
    {
        $key = $title;
        if (isset($this->categoryMap[$key])) {
            return $this->categoryMap[$key];
        }
        $found = ProductCategory::query()->where('title', $key)->value('id');
        if ($found !== null) {
            $this->categoryMap[$key] = $found;
            return $found;
        }
        return null;
    }

    protected function resolveBrandId(string $title): ?int
    {
        $key = $title;
        if (isset($this->brandMap[$key])) {
            return $this->brandMap[$key];
        }
        $found = Brand::query()->where('title', $key)->value('id');
        if ($found !== null) {
            $this->brandMap[$key] = $found;
            return $found;
        }
        return null;
    }

    protected function parsePrice(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }
        $normalized = NumberHelper::persian2LatinDigit((string) $value);
        $normalized = preg_replace('/[^\d.-]/', '', $normalized);
        if ($normalized === '') {
            return null;
        }
        return (float) $normalized;
    }

    protected function parseInteger(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        $normalized = NumberHelper::persian2LatinDigit((string) $value);
        $normalized = preg_replace('/[^\d-]/', '', $normalized);
        if ($normalized === '' && $normalized !== '0') {
            return null;
        }
        return (int) $normalized;
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function createProduct(array $data): void
    {
        $product = Product::create([
            'title' => $data['title'],
            'url' => $data['url'],
            'brand_id' => $data['brand_id'],
            'active' => $data['active'],
            'price' => $data['price'],
            'discounted_price' => $data['discounted_price'],
            'final_price' => $data['final_price'],
            'stock' => $data['stock'],
        ]);
        $product->categories()->sync([$data['category_id']]);
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function updateProduct(Product $product, array $data): void
    {
        if ($product->trashed()) {
            $product->restore();
        }
        $product->update([
            'title' => $data['title'],
            'url' => $data['url'],
            'brand_id' => $data['brand_id'],
            'active' => $data['active'],
            'price' => $data['price'],
            'discounted_price' => $data['discounted_price'],
            'final_price' => $data['final_price'],
            'stock' => $data['stock'],
        ]);
        $product->categories()->sync([$data['category_id']]);
    }

    public function getSampleHeaders(): array
    {
        return [
            'name' => 'نام محصول',
            'url' => 'آدرس (slug)',
            'category' => 'دسته‌بندی',
            'brand' => 'برند',
            'price' => 'قیمت',
            'discounted_price' => 'قیمت تخفیف‌خورده',
            'stock' => 'موجودی',
        ];
    }

    protected function isEmptyRow(array $row): bool
    {
        foreach ($row as $v) {
            if ($v !== null && trim((string) $v) !== '') {
                return false;
            }
        }
        return true;
    }
}
