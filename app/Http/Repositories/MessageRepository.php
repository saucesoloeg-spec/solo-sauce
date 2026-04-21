<?php

namespace App\Http\Repositories;

use App\Models\Message;

class MessageRepository
{
    private $model;

    public function __construct(Message $model) 
    {
        $this->model = $model;
    }
    
    public function all() 
    {
        return $this->model->all();
    }

    public function create($request) 
    {
        return $this->model->create($request);    
    }

    public function update($id, $array) 
    {
        return $this->model->where('id', $id)->update($array);    
    }

    public function delete($id) 
    {
        return $this->model->where('id', $id)->delete();    
    }

}
