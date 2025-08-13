<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\UploadPdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ManualController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = UploadPdf::with('category')
                ->when($request->category, fn ($query) => $query->where('category_id', $request->category));

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d-m-Y');
                })
                ->addColumn('action', function ($row) {
                    $route = route('manuals.show', $row->id);
                    $downloadPdf = route('manuals.download.pdf', $row->path);
                    $btn = '<div class="btn-group">';
                    $btn .= '<a href="'.$route.'" class="btn btn-sm btn-secondary">
                                <i class="ri-eye-line me-1"></i>
                                View
                             </a>';
                    $btn .= '<a href="'.$downloadPdf.'" class="btn btn-sm btn-danger">
                                 <i class="ri-download-2-line me-1"></i>
                                    Download
                             </a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->make(true);
        }

        return view('manuals.index', [
            'categories' => Category::get()
        ]);
    }

    public function show(UploadPdf $manual)
    {
        return view('manuals.show', compact('manual'));
    }

    public function downloadPdf(Request $request)
    {
        $fileUrl = 'https://koormal-extra.4emus.com/pdf/upload-pdfs/' . $request->path;

        $fileContent = file_get_contents($fileUrl);

        if ($fileContent === false) {
            abort(404, 'File not found.');
        }

        return response($fileContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . basename($request->path) . '"');
    }
}
