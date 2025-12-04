@extends('layouts.app')

@section('title', 'View Message - Sports Club Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Message Details</h1>
                <a href="{{ route('messages.index') }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Messages
                </a>
            </div>

            <div class="border-b pb-4 mb-4">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <span class="font-semibold">From:</span> 
                        <span>{{ $message->sender->name }}</span>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $message->created_at->format('M d, Y g:i A') }}
                    </div>
                </div>
                
                <div class="mb-2">
                    <span class="font-semibold">To:</span> 
                    <span>{{ $message->receiver->name }}</span>
                </div>
                
                @if($message->subject)
                <div class="mb-4">
                    <span class="font-semibold">Subject:</span> 
                    <span>{{ $message->subject }}</span>
                </div>
                @endif
                
                <div class="mt-4">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $message->body }}</p>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                @if(auth()->user()->is_admin)
                <a href="mailto:{{ $message->sender->email }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                    <i class="fas fa-reply mr-1"></i> Reply via Email
                </a>
                @endif
                
                <button onclick="window.history.back()" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection