<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Show chat interface for user (chat with admin)
     */
    public function userIndex()
    {
        // Get first admin
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return redirect()->back()->with('error', 'Admin tidak ditemukan.');
        }

        // Get messages between user and admin
        $messages = Message::where(function ($query) use ($admin) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $admin->id);
        })->orWhere(function ($query) use ($admin) {
            $query->where('sender_id', $admin->id)
                ->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();

        // Mark messages from admin as read
        Message::where('sender_id', $admin->id)
            ->where('receiver_id', Auth::id())
            ->where('read_status', false)
            ->update(['read_status' => true]);

        return view('messages.user', compact('messages', 'admin'));
    }

    /**
     * Admin chat interface (list of users with messages)
     */
    public function adminIndex()
    {
        // Get users who have sent messages to admin
        $userIds = Message::where('receiver_id', Auth::id())
            ->orWhere('sender_id', Auth::id())
            ->distinct()
            ->pluck('sender_id')
            ->merge(
                Message::where('sender_id', Auth::id())
                    ->distinct()
                    ->pluck('receiver_id')
            )
            ->unique()
            ->filter(function ($id) {
                return $id !== Auth::id();
            });

        $users = User::whereIn('id', $userIds)
            ->where('role', '!=', 'admin')
            ->get()
            ->map(function ($user) {
                $user->unread_count = Message::where('sender_id', $user->id)
                    ->where('receiver_id', Auth::id())
                    ->where('read_status', false)
                    ->count();
                $user->last_message = Message::where(function ($query) use ($user) {
                    $query->where('sender_id', $user->id)
                        ->where('receiver_id', Auth::id());
                })->orWhere(function ($query) use ($user) {
                    $query->where('sender_id', Auth::id())
                        ->where('receiver_id', $user->id);
                })->latest()->first();
                return $user;
            })
            ->sortByDesc(function ($user) {
                return $user->last_message ? $user->last_message->created_at : null;
            });

        return view('admin.messages.index', compact('users'));
    }

    /**
     * Admin chat with specific user
     */
    public function adminChat($userId)
    {
        $user = User::findOrFail($userId);

        // Get messages between admin and user
        $messages = Message::where(function ($query) use ($userId) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();

        // Mark messages from user as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->where('read_status', false)
            ->update(['read_status' => true]);

        return view('admin.messages.chat', compact('messages', 'user'));
    }

    /**
     * Send message
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:2000',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'read_status' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => $message->load('sender'),
        ]);
    }

    /**
     * Get messages (AJAX polling)
     */
    public function getMessages(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'last_message_id' => 'nullable|integer',
        ]);

        $userId = $request->user_id;
        $lastMessageId = $request->last_message_id ?? 0;

        $messages = Message::where(function ($query) use ($userId) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', Auth::id());
        })
            ->where('id', '>', $lastMessageId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->load('sender');

        // Mark received messages as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->where('read_status', false)
            ->update(['read_status' => true]);

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }
}

