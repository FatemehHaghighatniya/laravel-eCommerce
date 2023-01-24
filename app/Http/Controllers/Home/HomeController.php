<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\ContactUs;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;
use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;
class HomeController extends Controller
{
    public function index()
    {
        SEOTools::setTitle('صفحه اصلی سایت وکس');
        SEOTools::setDescription('پوشاکی برای تمام فصل ها');
        SEOTools::opengraph()->setUrl(route('home.index'));
//        SEOTools::setCanonical('https://codecasts.com.br/lesson');
        SEOTools::opengraph()->addProperty('type', 'WebPage');
        SEOTools::opengraph()->addProperty('description', 'آنلاین شاپ وکس ;هرآنچه نیاز دارید با بهترین قیمت');
        SEOTools::opengraph()->addProperty('title', 'آنلاین شاپ وکس ');
        SEOTools::twitter()->setSite('@vaxcom');
        SEOTools::jsonLd()->addImage('https://codecasts.com.br/img/logo.jpg');

        $sliders = Banner::where('type', 'slider')->where('is_active', 1)->orderBy('priority')->get();
        $indexTopBanners = Banner::where('type', 'index_top')->where('is_active', 1)->orderBy('priority')->get();
        $banners = Banner::where('type', 'index_bottom')->orderBy('priority')->get();
        $products = Product::where('is_active', 1)->get()->take(6);
        return view('home.index', compact(['sliders', 'products', 'banners', 'indexTopBanners']));
    }

    public function aboutUs()
    {
        $bottomBanners = Banner::where('type', 'index_bottom')->orderBy('priority')->get();
        return view('home.about-us', compact('bottomBanners'));
    }

    public function contactUs()
    {
        $setting = Setting::findOrFail(1);
        return view('home.contact-us', compact('setting'));
    }

    public function contactUsForm(Request $request)
    {
        $request->validate([
            'name'=>'required|min:4|max:100',
            'email'=>'required',
            'subject'=>'required|min:10|max:1000',
            'text'=>'required|min:10|max:3000',
            'g-recaptcha-response' => [new GoogleReCaptchaV3ValidationRule('contact_us')]
        ]);

        ContactUs::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'text' => $request->text,
        ]);

        alert()->success('پیام شما با موفقیت ثبت شد', 'با تشکر');
        return redirect()->back();

    }
}
