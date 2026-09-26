<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\Product\Entities\Brand;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductCategory;

class ProductImportService
{
    public const REQUIRED_HEADERS = ['name', 'url', 'category', 'brand', 'price', 'discounted_price', 'stock'];

    /** @var array<string, int> category title/url -> id */
    private array $categoryMap = [];

    /** @var array<string, int> brand title/url -> id */
    private array $brandMap = [];

    public function __construct()
    {
        $this->loadCategoryMap();
        $this->loadBrandMap();
    }

    private function loadCategoryMap(): void
    {
        $categories = ProductCategory::query()
            ->select('id', 'title', 'url')
            ->get();
        foreach ($categories as $cat) {
            $this->categoryMap[$this->normalizeKey($cat->title)] = $cat->id;
            $this->categoryMap[$this->normalizeKey($cat->url)] = $cat->id;
        }
    }

    private function loadBrandMap(): void
    {
        $brands = Brand::query()
            ->select('id', 'title', 'url')
            ->get();
        foreach ($brands as $brand) {
            $this->brandMap[$this->normalizeKey($brand->title)] = $brand->id;
            $this->brandMap[$this->normalizeKey($brand->url)] = $brand->id;
        }
    }

    private function normalizeKey(?string $value): string
    {
        return trim((string) $value);
    }

    public function process(UploadedFile $file): ProductImportResult
    {
        $result = new ProductImportResult();
        $path = $file->getRealPath();

        try {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);
        } catch (\Throwable $e) {
            Log::error('Product import: failed to read file', ['error' => $e->getMessage()]);
            $result->addGlobalError('فایل اکسل قابل خواندن نیست: ' . $e->getMessage());
            return $result;
        }

        if (empty($rows)) {
            $result->addGlobalError('فایل خالی است.');
            return $result;
        }

        $headerRow = array_shift($rows);
        $headers = $this->normalizeHeaders($headerRow);
        $missing = $this->checkRequiredHeaders($headers);
        if (!empty($missing)) {
            $result->addGlobalError('ستون‌های الزامی یافت نشد: ' . implode(', ', $missing));
            return $result;
        }

        $result->setTotalRows(count($rows));

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // 1-based + header
            $this->processRow($rowNumber, $row, $headers, $result);
        }

        return $result;
    }

    private function normalizeHeaders(array $headerRow): array
    {
        $out = [];
        foreach ($headerRow as $col => $value) {
            $key = trim(mb_strtolower((string) $value));
            $key = preg_replace('/\s+/', '_', $key);
            if ($key !== '') {
                $out[$key] = $col;
            }
        }
        return $out;
    }

    private function checkRequiredHeaders(array $headers): array
    {
        $missing = [];
        foreach (self::REQUIRED_HEADERS as $required) {
            if (!isset($headers[$required])) {
                $missing[] = $required;
            }
        }
        return $missing;
    }

    private function getCell(array $row, $col): ?string
    {
        $value = $row[$col] ?? null;
        if ($value === null || $value === '') {
            return null;
        }
        return trim((string) $value);
    }

    private function processRow(int $rowNumber, array $row, array $headers, ProductImportResult $result): void
    {
        $name = $this->getCell($row, $headers['name'] ?? 'A');
        $urlRaw = $this->getCell($row, $headers['url'] ?? 'B');
        $categoryValue = $this->getCell($row, $headers['category'] ?? 'C');
        $brandValue = $this->getCell($row, $headers['brand'] ?? 'D');
        $priceRaw = $this->getCell($row, $headers['price'] ?? 'E');
        $discountedPriceRaw = $this->getCell($row, $headers['discounted_price'] ?? 'F');
        $stockRaw = $this->getCell($row, $headers['stock'] ?? 'G');

        $errors = [];

        if ($name === null || $name === '') {
            $errors[] = 'نام محصول الزامی است.';
        }

        $categoryId = null;
        if ($categoryValue !== null && $categoryValue !== '') {
            $key = $this->normalizeKey($categoryValue);
            $categoryId = $this->categoryMap[$key] ?? null;
            if ($categoryId === null) {
                $errors[] = 'دسته‌بندی یافت نشد (از بین دسته‌های موجود انتخاب کنید).';
            }
        } else {
            $errors[] = 'دسته‌بندی الزامی است.';
        }

        $brandId = null;
        if ($brandValue !== null && $brandValue !== '') {
            $key = $this->normalizeKey($brandValue);
            $brandId = $this->brandMap[$key] ?? null;
            if ($brandId === null) {
                $errors[] = 'برند یافت نشد (از بین برندهای موجود انتخاب کنید).';
            }
        } else {
            $errors[] = 'برند الزامی است.';
        }

        $price = $this->parsePrice($priceRaw);
        if ($price === null) {
            $errors[] = 'قیمت معتبر نیست (عددی بزرگ‌تر یا مساوی صفر).';
        } else {
            if ($price < 0) {
                $errors[] = 'قیمت نمی‌تواند منفی باشد.';
            }
        }

        $discountedPrice = $this->parsePrice($discountedPriceRaw);
        if ($discountedPriceRaw !== null && $discountedPriceRaw !== '') {
            if ($discountedPrice === null || $discountedPrice < 0) {
                $errors[] = 'قیمت تخفیف‌خورده معتبر نیست.';
            } elseif ($price !== null && $discountedPrice > $price) {
                $errors[] = 'قیمت تخفیف‌خورده نباید بیشتر از قیمت اصلی باشد.';
            }
        }

        $stock = $this->parseStock($stockRaw);
        if ($stock === null) {
            $errors[] = 'موجودی انبار باید عدد صحیح غیرمنفی باشد.';
        }

        if ($urlRaw === null || $urlRaw === '') {
            $errors[] = 'آدرس (url) الزامی است.';
        }

        if (!empty($errors)) {
            $result->addFailedRow($rowNumber, $name ?? '(بدون نام)', $errors);
            return;
        }

        $url = $this->sanitizeSlug($urlRaw);
        if ($url === '') {
            $result->addFailedRow($rowNumber, $name, ['آدرس (url) پس از اصلاح معتبر نیست.']);
            return;
        }

        $finalPrice = ($discountedPrice !== null && $discountedPrice > 0) ? $discountedPrice : $price;
        $discountedPriceValue = ($discountedPrice !== null && $discountedPrice > 0) ? $discountedPrice : null;

        try {
            DB::beginTransaction();

            $product = Product::query()->where('url', $url)->first();

            $data = [
                'title' => $name,
                'url' => $url,
                'brand_id' => $brandId,
                'price' => (int) $price,
                'discounted_price' => $discountedPriceValue !== null ? (int) $discountedPriceValue : 0,
                'final_price' => (int) $finalPrice,
                'stock' => (int) $stock,
                'active' => 1,
                'show_in_first_page' => 0,
            ];

            if ($product) {
                $product->update($data);
                $result->addUpdated($rowNumber, $name, $product->id);
            } else {
                $product = Product::query()->create($data);
                $result->addImported($rowNumber, $name, $product->id);
            }

            if ($categoryId !== null) {
                $product->categories()->sync([$categoryId]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Product import row error', ['row' => $rowNumber, 'error' => $e->getMessage()]);
            $result->addFailedRow($rowNumber, $name, ['خطای سیستمی: ' . $e->getMessage()]);
        }
    }

    private function parsePrice(?string $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }
        $value = NumberHelper::persian2LatinDigit($value);
        $value = preg_replace('/[^\d.-]/', '', $value);
        if ($value === '') {
            return null;
        }
        $num = (float) $value;
        return $num >= 0 ? $num : null;
    }

    private function parseStock(?string $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        $value = NumberHelper::persian2LatinDigit($value);
        $value = preg_replace('/[^\d-]/', '', $value);
        if ($value === '') {
            return null;
        }
        $num = (int) $value;
        return $num >= 0 ? $num : null;
    }

    private function sanitizeSlug(?string $url): string
    {
        if ($url === null || $url === '') {
            return '';
        }
        $url = trim($url);
        if (preg_match('#^https?://#i', $url)) {
            $url = parse_url($url, PHP_URL_PATH) ?? $url;
            $url = trim($url, '/');
        }
        $url = preg_replace('/[^\p{L}\p{N}\-_]+/u', '-', $url);
        $url = preg_replace('/-+/', '-', $url);
        return trim($url, '-');
    }

    /**
     * Return category and brand maps for UI (e.g. sample or validation hints).
     */
    public function getCategoryTitles(): array
    {
        return ProductCategory::query()->pluck('title', 'id')->toArray();
    }

    public function getBrandTitles(): array
    {
        return Brand::query()->pluck('title', 'id')->toArray();
    }
}
