<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CommentLog;
use App\Models\CommentLogEvents;
use App\Models\UserAuth;
use Illuminate\Http\Request;

class CommentLogController extends Controller
{
    public function open_comment_log()
    {
        return view('web.reports.open_comment_log');
    }

    public function get_comment_log()
    {
        return CommentLog::orderby('id')->get()->toArray();
    }

    public function create_comment_log()
    {
        return view('web.reports.create_comment_log');
    }

    public function save_comment_log(Request $request)
    {
        $data = $request->all();
        $data['creator'] = UserAuth::where('ip', '=', \request()->ip())->orderbydesc('id')->first()->username;
        if ($request->completion_mark) {
            $data['status'] = in_array(0, $request->completion_mark) ? 'В работе' : 'Выполнено';
        } else {
            $data['status'] = 'В работе';
        }
        CommentLog::create($data);
        if ($request->event) {
            $second_data['parent_id'] = CommentLog::orderby('id', 'desc')->first()->id;
            foreach ($data['event'] as $key => $event) {
                $second_data['event'] = $event;
                $second_data['person'] = $data['person'][$key];
                $second_data['completion_mark'] = $data['completion_mark'][$key];
                $second_data['comment_event'] = $data['comment_event'][$key];
                CommentLogEvents::create($second_data);
            }
        }
    }

    public function delete_record_comment_log($id)
    {
        $row = CommentLog::where('id', '=', $id)->first();
        $row->delete();
    }

    public function edit_comment_log($id)
    {
        $data['Comment'] = CommentLog::where('id', '=', $id)->first();
        $data['events'] = CommentLogEvents::where('parent_id', $id)->get();

        return view('web.reports.edit_comment_log', compact('data'));
    }


    public function update_comment_log(Request $request, $id)
    {
        $data = $request->all();
        $newdata['remark'] = $request->remark;
        $newdata['comment'] = $request->comment;

        $newdata['status'] = in_array(0, $request->completion_mark) ? 'В работе' : 'Выполнено';
        CommentLog::where('id', $id)->update($newdata);

        $count = CommentLogEvents::where('parent_id', $id)->count();

        foreach ($data['event'] as $key => $event) {
            $second_data['event'] = $event;
            $second_data['person'] = $data['person'][$key];
            $second_data['completion_mark'] = $data['completion_mark'][$key];
            $second_data['comment_event'] = $data['comment_event'][$key];
            if ($key <= $count - 1) {
                CommentLogEvents::where('parent_id', $id)->orderby('id')->offset($key)->first()->update($second_data);
            } else {
                $second_data['parent_id'] = $id;
                CommentLogEvents::create($second_data);
            }
        }
    }

    public function delete_event_comment_log($id)
    {
        CommentLogEvents::where('id', $id)->first()->delete();
    }
}
