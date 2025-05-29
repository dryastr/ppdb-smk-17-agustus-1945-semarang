<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq; // Import model Faq
use App\Models\HeroSection;
use App\Models\RegistrationFlow;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $faqs = Faq::where('is_published', true)->get();

        $sortedFaqs = $faqs->sortBy(function ($faq) {
            if ($faq->order > 0) {
                return $faq->order;
            }
            return $faq->id + 1000000;
        })->values();

        $heroSection = HeroSection::first();

        $registrationFlows = RegistrationFlow::orderBy('step_number')->get();

        return view('home', compact('sortedFaqs', 'heroSection', 'registrationFlows'));
    }
}
