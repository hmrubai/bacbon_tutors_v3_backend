<?php

namespace App\Services;

use App\Models\Union;
use App\Models\Upazila;
use App\Models\Division;
use App\Models\District;
use App\Http\Traits\HelperTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationService
{
    use HelperTrait;

    public function divisionList()
    {
        return Division::all();
    }
    
    public function districtListByID($division_id)
    {
        return District::where('division_id', $division_id)->get();
    }

    public function upazilaListByID($district_id)
    {
        return Upazila::where('district_id', $district_id)->get();
    }

    public function unionListByID($upazilla_id)
    {
        return Union::where('upazilla_id', $upazilla_id)->get();
    }
}
