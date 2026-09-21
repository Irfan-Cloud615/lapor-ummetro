<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::where('is_active', true)
            ->orderBy('urutan')
            ->orderBy('pertanyaan')
            ->get();

        return view('content.pages.faq', ['faqs' => $faqs]);
    }
}
