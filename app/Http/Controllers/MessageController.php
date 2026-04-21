<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Http\Services\MessageService;
use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    private $message_service;

    public function __construct(MessageService  $messages_service)
    {
        $this->message_service = $messages_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = $this->message_service->getAll();

        return view('pages/messages/index', compact('messages')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages/messages/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SendMessageRequest $request)
    {
        $send_message = $this->message_service->save($request->validated());

        if($send_message)
            return redirect()->route('messages.get')->with('status', 'Notification sent to all '.$request['type']);

        return redirect()->back()->with('status', 'Failed to send Notification !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if($this->message_service->delete($request->input())) {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
    }
}
