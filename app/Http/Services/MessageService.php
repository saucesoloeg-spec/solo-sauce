<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Notification;
use App\Http\Repositories\MessageRepository;
use App\Notifications\SendPushNotification;

use App\Models\Admin;
use App\Models\Sales;
use App\Models\User;

class MessageService
{
    private $message_repository;

    public function __construct(MessageRepository $message_repository) 
    {
        $this->message_repository = $message_repository;
    }

    public function getAll() 
    {
        return $this->message_repository->all();    
    }

    public function save($request) 
    {
        if($request['type'] == 'sales') {
            $user = Sales::first();
        }
        elseif($request['type'] == 'users') {
            $user = User::first();
        }
        else {
            $user = Admin::first();
        }

        Notification::send($user, new SendPushNotification($request['title'], $request['message'], $request['type']));

        return $this->message_repository->create($request);
    }

    public function updatePercentage($input) 
    {
        return $this->message_repository->update($input['message_id'], ['percentage' => $input['percentage']]);
    }

    public function delete($request) 
    {
        return $this->message_repository->delete($request['message_id']);
    }

}
