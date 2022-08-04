<?php

namespace App\Repositories;

use App\Interfaces\VisibilityInterface;
use Spatie\Permission\Models\Role;
use App\Models\Visibility;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VisibilityRepository implements VisibilityInterface
{
    use ResponseAPI;


    // Get All Visibility
    public function showAllVisibilities()
    {
        try {
            $visibility = Visibility::get();
            $results = [];
            foreach ($visibility as $key => $value) {
                $role = Role::find($value->role_id);

                $data = [
                    'id' => $value->id,
                    'role_id' => $value->role_id,
                    'role_name' => $role->name,
                    'nama' => $value->name,
                    'slug' => $value->slug,
                ];
                $results[] = $data;
            }
            return $this->successResponse("Get all Visibilities", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

}
