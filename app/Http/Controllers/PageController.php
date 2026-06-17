<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageTranslation;
use App\Models\TeamMember;
use Illuminate\Support\Facades\Mail;


class PageController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:add_website_page'])->only('create');
        $this->middleware(['permission:edit_website_page'])->only('edit');
        $this->middleware(['permission:delete_website_page'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.website_settings.pages.create');
    }
// public function submit_delivery_partner(Request $request)
// {
//     // Validate the form
//     $data = $request->validate([
//         'company_name'      => 'required|string|max:255',
//         'email'             => 'required|email',
//         'contact_number'    => 'required|string|max:20',
//         'area_coverage'     => 'required|string',
//         'services_provided' => 'required|string',
//     ]);

//     // Send email to admin
//     Mail::send([], [], function($message) use ($data) {
//         $message->to('admin@example.com') // replace with your admin email
//                 ->subject('New Delivery Partner Request')
//                 ->setBody(
//                     "Company Name: {$data['company_name']}\n".
//                     "Email: {$data['email']}\n".
//                     "Contact Number: {$data['contact_number']}\n".
//                     "Area of Coverage: {$data['area_coverage']}\n".
//                     "Services Provided: {$data['services_provided']}",
//                     'text/plain'
//                 );
//     });
    

//     // Flash success message
//     flash(translate('Your request has been submitted successfully'))->success();
//     return redirect()->back();
// }
   public function submitDeliveryPartner(Request $request)
{
    $request->validate([
        'company_name'      => 'required|string|max:255',
        'email'             => 'required|email',
        'contact_number'    => 'required',
        'area_coverage'     => 'required',
        'services_provided' => 'required',
    ]);

    
    $mailData = [
        'company_name'      => $request->company_name,
        'email'             => $request->email,
        'contact_number'    => $request->contact_number,
        'area_coverage'     => $request->area_coverage,
        'services_provided' => $request->services_provided,
    ];
    
    try {
        Mail::send('emails.delivery-partner', $mailData, function ($message) {
            $message->to(env('MAIL_FROM_ADDRESS'))
                    ->subject('New Delivery Partner Request');
        });
        return back()->with('success', 'Thanks for applying! We have received your details.');

    } catch (\Exception $e) {
        return back()->with('error', 'Email failed to send. ' . $e->getMessage());
    }
}
public function DeliveryPartner()
{
    return view('frontend.become_delivery_partner');
}

   /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $page = new Page;
        $page->title = $request->title;
        if (Page::where('slug', preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug)))->first() == null) {
            $page->slug             = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
            $page->type             = "custom_page";
            $page->content          = $request->content;
            $page->meta_title       = $request->meta_title;
            $page->meta_description = $request->meta_description;
            $page->keywords         = $request->keywords;
            $page->meta_image       = $request->meta_image;
            $page->save();

            $page_translation           = PageTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'page_id' => $page->id]);
            $page_translation->title    = $request->title;
            $page_translation->content  = $request->content;
            $page_translation->save();

            flash(translate('New page has been created successfully'))->success();
            return redirect()->route('website.pages');
        }

        flash(translate('Slug has been used already'))->warning();
        return back();
    }
    
    
    
     public function submit_contact(Request $request) {
       $request->validate([
            'name'      => 'required|string|max:255',
            'email'             => 'required|email',
            'phone'    => 'required', 
        ]);
    
        
        $mailData = [
            'name'      => $request->name,
            'email'             => $request->email,
            'phone'    => $request->phone,
            'message1'     => $request->message, 
        ];
        
        try {
            Mail::send('emails.contact_us', $mailData, function ($message) {
                $message->to(env('MAIL_FROM_ADDRESS'))
                        ->subject('Wants to Contact you');
            });
            return back()->with('success', 'Thank you for contacting us. We will respond as soon as possible');
    
        } catch (\Exception $e) {
            return back()->with('error', 'Email failed to send. ' . $e->getMessage());
        }
    }

    //contact us page
    public function contact_us()
    {
        return view('frontend.contact_us');
    }
    
    
    public function career()
{
    return view('frontend.career');
}


 
public function career_submit(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'role' => 'required',
        'cv' => 'required|mimes:pdf,doc,docx|max:2048'
    ]);

    if ($request->hasFile('cv')) {
        $file = $request->file('cv');
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('uploads/cv'), $filename);
    }

    return back()->with('success','Application submitted successfully');
}
    
    
//     public function submit_contact(Request $request)
// {
//     // Validate input
//     $request->validate([
//         'name'    => 'required|string|max:255',
//         'email'   => 'required|email',
//         'phone'   => 'nullable|string|max:20',
//         'message' => 'required|string',
//     ]);

//     // Example: You can save to DB or send email
//     // Mail::to('admin@example.com')->send(new ContactFormMail($request->all()));
    // Mail::to(env('MAIL_FROM_ADDRESS'))->send(new \App\Mail\ContactMail($request->all()));


//     flash(translate('Your message has been sent successfully'))->success();
//     return redirect()->back();
// }

// public function submit_contact(Request $request)
// {
//     $request->validate([
//         'name'    => 'required',
//         'email'   => 'required|email',
//         'message' => 'required'
//     ]);

//     // Send Email
//     Mail::to(('arorashivani74577@gmail.com'))->send(new \App\Mail\ContactMail($request->all()));

//     return back()->with('success', 'Your message has been sent successfully!');
// }

public function become_delivery_partner()
{
    return view('frontend.become_delivery_partner');
}
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function edit(Request $request, $id)
   {
        $lang = $request->lang;
        $page_name = $request->page;
        $page = Page::where('slug', $id)->first();
        if($page != null){
            if ($page_name == 'home') {
                return view('backend.website_settings.pages.'.get_setting('homepage_select').'.home_page_edit', compact('page','lang'));
            }
            return view('backend.website_settings.pages.edit', compact('page','lang'));
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        if (Page::where('id','!=', $id)->where('slug', preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug)))->first() == null) {
            if($page->type == 'custom_page'){
              $page->slug           = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
            }
            if($request->lang == env("DEFAULT_LANGUAGE")){
              $page->title          = $request->title;
              $page->content        = $request->content;
            }
            $page->meta_title       = $request->meta_title;
            $page->meta_description = $request->meta_description;
            $page->keywords         = $request->keywords;
            $page->meta_image       = $request->meta_image;
            $page->save();

            $page_translation           = PageTranslation::firstOrNew(['lang' => $request->lang, 'page_id' => $page->id]);
            $page_translation->title    = $request->title;
            $page_translation->content  = $request->content;
            $page_translation->save();

            flash(translate('Page has been updated successfully'))->success();
            return redirect()->route('website.pages');
        }

      flash(translate('Slug has been used already'))->warning();
      return back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->page_translations()->delete();

        if(Page::destroy($id)){
            flash(translate('Page has been deleted successfully'))->success();
            return redirect()->back();
        }
        return back();
    }

    public function show_custom_page($slug){
        $page = Page::where('slug', $slug)->first();
        if($page != null){
            return view('frontend.custom_page', compact('page'));
        }
        abort(404);
    }
    public function meet_the_team()
    {
        if (get_setting('team_members_page_status', 0) != 1) {
            abort(404);
        }
        $team_members = TeamMember::where('is_active', 1)->orderBy('created_at', 'desc')->get();
        return view('frontend.meet_the_team', compact('team_members'));
    }
    public function mobile_custom_page($slug){
        $page = Page::where('slug', $slug)->first();
        if($page != null){
            return view('frontend.m_custom_page', compact('page'));
        }
        abort(404);
    }
}
