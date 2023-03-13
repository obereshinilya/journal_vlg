<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EmailSend;
use App\Models\UserAuth;
use Illuminate\Http\Request;

class SendMailController extends Controller
{
    public function send_mail_view()
    {

        return view('web.send_email_view');

    }

    public function get_mail()
    {
        $theme = EmailSend::groupby('theme')->select('theme')->get();

        foreach ($theme as $row) {
            $rec = EmailSend::where('theme', '=', $row->theme)->get()->toArray();
            $to_table[$row->theme] = $rec;
        }
        return $to_table;
    }

    public function new_mail()
    {
        return view('web.new_mail');
    }

    public function save_mail(Request $request)
    {
        $data = $request->all();
        foreach ($data['recepient'] as $recepient) {
            $to_write['message'] = $data['message'];
            $to_write['theme'] = $data['theme'];
            $to_write['recepient'] = $recepient;
            $to_write['sender'] = UserAuth::where('ip', '=', \request()->ip())->orderbydesc('id')->first()->username;
            $to_write['timestamp'] = date('Y-m-d H:i:s');
            EmailSend::create($to_write);
        }
    }

}
