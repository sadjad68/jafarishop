<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Exports\ProductImportSampleExport;
use App\Admin\Services\ProductImportService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductImportController extends Controller
{
    public function index()
    {
        $service = new ProductImportService();
        $headers = $service->getSampleHeaders();
        return view('pages.admin.product-import.index', compact('headers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ], [
            'file.required' => 'انتخاب فایل الزامی است.',
            'file.mimes' => 'فرمت فایل باید xlsx، xls یا csv باشد.',
            'file.max' => 'حداکثر حجم فایل ۱۰ مگابایت است.',
        ]);

        $service = new ProductImportService();
        $result = $service->process($request->file('file'));

        return redirect()
            ->route('admin.product.import.index')
            ->with('import_result', $result)
            ->withInput();
    }

    public function sample(): BinaryFileResponse
    {
        return Excel::download(
            new ProductImportSampleExport(),
            'product-import-sample.xlsx',
            \Maatwebsite\Excel\Excel::XLSX
        );
    }
}
