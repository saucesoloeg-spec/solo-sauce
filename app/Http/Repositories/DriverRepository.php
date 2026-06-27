<?php

namespace App\Http\Repositories;

use App\Models\Driver;
use App\Models\Vehicle;

class DriverRepository
{
    private $model;
    private $vehicles_model;

    public function __construct(Driver $drivers, Vehicle $vehicles_model) 
    {
        $this->model          = $drivers;
        $this->vehicles_model = $vehicles_model;
    }

    public function getAll() 
    {
        return $this->model->all();    
    }

    public function getById($id)
    {
        return $this->model->where('id', $id)->with(['orders'])->first();
    }

    public function update($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

}