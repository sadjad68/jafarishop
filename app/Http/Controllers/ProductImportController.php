<?php

namespace App\Http\Controllers;

use App\Services\ProductImportResult;
use App\Services\ProductImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductImportController extends Controller
{
    public function __construct(
        private ProductImportService $importService
    ) {
    }

    /**
     * Show the import form and optional result from previous run.
     */
    public function index(Request $request)
    {
        $categories = $this->importService->getCategoryTitles();
        $brands = $this->importService->getBrandTitles();
        $result = $request->session()->get('product_import_result');

        return view('pages.product-import.index', compact('categories', 'brands', 'result'));
    }

    /**
     * Process uploaded Excel file.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ], [
            'file.required' => 'لطفا یک فایل اکسل انتخاب کنید.',
            'file.mimes' => 'فایل باید با پسوند xlsx یا xls باشد.',
            'file.max' => 'حداکثر حجم فایل ۱۰ مگابایت است.',
        ]);

        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $request->file('file');
        $result = $this->importService->process($file);

        return redirect()
            ->route('product.import.index')
            ->with('product_import_result', $result);
    }

    /**
     * Download sample Excel template.
     */
    public function sample(): StreamedResponse
    {
        $headers = [
            'name',
            'url',
            'category',
            'brand',
            'price',
            'discounted_price',
            'stock',
        ];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Products');
        $sheet->fromArray($headers, null, 'A1');
        $sheet->fromArray([
            'واحد کنترل الکترونیکی بدنه BCM ام وی ام 550',
            'bcm-mvm550-a21-3600030fb',
            'قطعات الکترونیک',
            'ام وی ام',
            '1500000',
            '1200000',
            '5',
        ], null, 'A2');

        $writer = new Xlsx($spreadsheet);
        $filename = 'product-import-sample-' . date('Y-m-d') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
