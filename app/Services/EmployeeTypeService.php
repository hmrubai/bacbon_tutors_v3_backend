<?php

namespace App\Services;

use App\Models\EmployeeType;
use App\Http\Traits\HelperTrait;

class EmployeeTypeService
{
    // Optionally include HelperTrait if you need any common utilities here.
    use HelperTrait;

    public function getAll()
    {
        return EmployeeType::all();
    }

    public function create(array $data)
    {
        return EmployeeType::create($data);
    }

    public function getById($id)
    {
        return EmployeeType::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $employeeType = EmployeeType::findOrFail($id);
        $employeeType->update($data);
        return $employeeType;
    }

    public function delete($id)
    {
        $employeeType = EmployeeType::findOrFail($id);
        $employeeType->delete();
        return true;
    }
}
