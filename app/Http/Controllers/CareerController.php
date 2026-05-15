<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CareerController extends Controller
{
    public function index()
    {
        return view('frontend.career');
    }

    public function submit(Request $request)
    {
        $request->validate([
                    'name' => ['required','regex:/^[A-Za-z\s]+$/'],
                    'email' => 'required|email',
                    'phone' => 'required',
                    'role'  => 'required',
                    'cv'    => 'nullable|mimes:pdf,doc,docx|max:2048'
        ]);

        $filename = null;

        // Upload CV
        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/cv'), $filename);
        }

        // Form Data
        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role'  => $request->role
        ];

        // Send Email
        Mail::send([], [], function ($message) use ($data, $filename) {

            $message->to('arorashivani053@gmail.com')
                ->subject('New Career Application')
                ->html(
                    "<h3>New Career Application</h3>
                    <p><strong>Name:</strong> {$data['name']}</p>
                    <p><strong>Email:</strong> {$data['email']}</p>
                    <p><strong>Phone:</strong> {$data['phone']}</p>
                

                    
                    <p><strong>Role:</strong> {$data['role']}</p>"
                );

            // Attach CV if uploaded
            if ($filename) {
                $message->attach(public_path('uploads/cv/'.$filename));
            }
        });

return redirect()->route('home')->with([
    'flash_notification' => collect([
        ['level' => 'success', 'message' => 'Thank you for applying. We will contact you if your profile matches our requirements.']
    ])
]);
}
}
