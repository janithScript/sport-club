<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id())
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $admins = User::where('is_admin', true)->get();

        return view('messages.index', compact('messages', 'admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'subject' => $request->subject,
            'body' => $request->body,
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    public function show(Message $message)
    {
        if ($message->receiver_id === auth()->id() && !$message->isRead()) {
            $message->markAsRead();
        }

        return view('messages.show', compact('message'));
    }
}