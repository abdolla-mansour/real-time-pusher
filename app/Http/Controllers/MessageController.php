<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\ChatSendEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function sender($user_id, Request $request)
    {

        try {
            // DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'message' => 'required|string|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()->first()
                ], 400);
            }

            if ($user_id == auth()->id()) {
                return response()->json([
                    'error' => 'you cant send message for this user'
                ], 400);
            }

            $user = User::findOrFail($user_id);

            $message = Message::create([
                'sender' => auth()->id(),
                'receiver' => $user_id,
                'message' => $request->message,
            ]);

            \broadcast(new ChatSendEvent(auth()->id(), $message->message, $message->created_at));

            // DB::commit();
        } catch (\Throwable $th) {
            // DB::rollBack();
            return response()->json([
                'error' => $th->getMessage()
            ], 400);
        }
    }
}
