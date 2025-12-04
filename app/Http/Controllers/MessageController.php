<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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
        try {
            $request->validate([
                'receiver_id' => 'required|exists:users,id',
                'body' => 'required|string|max:1000',
            ]);

            // Check if receiver exists and is valid
            $receiver = User::find($request->receiver_id);
            if (!$receiver) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Receiver not found.'
                ], 404);
            }

            // Check if sender is trying to message themselves
            if (auth()->id() == $request->receiver_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You cannot send a message to yourself.'
                ], 400);
            }

            $message = Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $request->receiver_id,
                'subject' => $request->subject ?? null,
                'body' => $request->body,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => $message->load(['sender', 'receiver'])
            ]);
        } catch (\Exception $e) {
            Log::error('Message sending error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'receiver_id' => $request->receiver_id ?? null,
                'body' => $request->body ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send message. Please try again.'
            ], 500);
        }
    }

    public function show(Message $message)
    {
        // Check if user is authorized to view this message
        if ($message->sender_id !== auth()->id() && $message->receiver_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Mark message as read if the authenticated user is the receiver
        if ($message->receiver_id === auth()->id() && $message->read_at === null) {
            $message->update(['read_at' => now()]);
        }

        return view('messages.show', compact('message'));
    }

    public function getConversation($userId)
    {
        try {
            // Validate that userId is a valid user
            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }

            $authUserId = auth()->id();
            
            $messages = Message::where(function($query) use ($authUserId, $userId) {
                $query->where('sender_id', $authUserId)
                      ->where('receiver_id', $userId);
            })->orWhere(function($query) use ($authUserId, $userId) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', $authUserId);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

            // Mark all unread messages from this user as read
            Message::where('sender_id', $userId)
                ->where('receiver_id', $authUserId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return response()->json([
                'status' => 'success',
                'messages' => $messages
            ]);
        } catch (\Exception $e) {
            Log::error('Conversation loading error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'other_user_id' => $userId,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load conversation. Please try again.'
            ], 500);
        }
    }
}