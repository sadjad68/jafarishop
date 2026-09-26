<?php

namespace Tests\Feature;

use App\Admin\Services\ProductImportService;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductImportTest extends TestCase
{
    public function test_invalid_row_increases_failed_and_adds_row_errors(): void
    {
        $csvContent = "name,url,category,brand,price,discounted_price,stock\n";
        $csvContent .= "Test Product,test-url,,,1000,0,0\n"; // missing category and brand
        $file = UploadedFile::fake()->createWithContent('import.csv', $csvContent);
        $file->mimeType('text/csv');

        $service = new ProductImportService();
        $result = $service->process($file);

        $this->assertSame(1, $result->totalRows);
        $this->assertSame(1, $result->failed);
        $errors = $result->getRowErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors[0]['errors']);
    }

    public function test_sample_headers_returns_expected_columns(): void
    {
        $service = new ProductImportService();
        $headers = $service->getSampleHeaders();
        $this->assertArrayHasKey('name', $headers);
        $this->assertArrayHasKey('url', $headers);
        $this->assertArrayHasKey('category', $headers);
        $this->assertArrayHasKey('brand', $headers);
        $this->assertArrayHasKey('price', $headers);
        $this->assertArrayHasKey('stock', $headers);
    }

    public function test_sample_export_has_headers_and_one_example_row(): void
    {
        $export = new \App\Admin\Exports\ProductImportSampleExport();
        $this->assertSame(['name', 'url', 'category', 'brand', 'price', 'discounted_price', 'stock'], $export->headings());
        $rows = $export->array();
        $this->assertIsArray($rows);
        $this->assertCount(1, $rows);
        $this->assertCount(7, $rows[0]);
    }
}
