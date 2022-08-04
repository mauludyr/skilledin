<?php

namespace App\Repositories;

use App\Mail\CreateUser;
use App\Imports\EmployeesImport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Interfaces\UserInterface;
use App\Models\SystemStatus;
use App\Models\User;
use App\Models\Profile;
use App\Models\UserVerify;

use App\Traits\EmployeeFileManagement;
use App\Traits\ResponseAPI;
use App\Traits\UserManagement;

use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRepository implements UserInterface
{
    use ResponseAPI, EmployeeFileManagement, UserManagement;

    private function queryUser()
    {
        return User::with([

            "roles" => function($r) {
                $r->select("id", "name");
            },
            "systemStatus" => function($q) {
                $q->select("id", "name");
            },
            "profile" => function($q) {
                $q->with([
                    "location" => function($x) {
                        $x->select("id", "location_name", "location_code");
                    },
                    "nationality" => function($x) {
                        $x->select("id", "nationality_name", "nationality_code");
                    }
                ]);
            },
            "employment" => function($q) {
                $q->with([
                    'jobPosition' => function($Q) {
                        $Q->select('id', 'job_name', 'job_slug');
                    },
                    'grade' => function($Q) {
                        $Q->select('id','grade_name', 'grade_slug');
                    },
                    'employmentType' => function($Q) {
                        $Q->select('id','emp_type_name', 'emp_type_slug');
                    }
                ]);
            },
            "employee" => function($x) {
                $x->with([
                    "user" => function($q) {
                        $q->select("id", "name", "email");
                    }
                ])->select("id","user_id","manager_id");
            },
            "directReport" => function($x) {
                $x->with([
                    "manager" => function($q) {
                        $q->select("id", "name", "email")->with(["roles"]);

                    },
                    "dotlineManager" => function($q) {
                        $q->select("id", "name", "email")->with(["roles"]);
                    }
                ]);
            },
        ]);
    }

    public function getAllUser()
    {
        $limit  = 5;

        if(request()->get('limit') && !empty(request()->get('limit')))
        {
            $limit = request()->get('limit');
        }


        try {
            $data = $this->queryUser()
                ->whereHas("roles", function($q) {
                    $q->where("name", "!=", "superadmin");
                });

            if(request()->get('role_name') && !empty(request()->get('role_name')) && request()->get('role_name') != 'all') {
                $roleName = Str::lower(request()->get("role_name"));
                $data = $data->whereHas("roles", function($q) use($roleName) {
                    $q->where('name', $roleName);
                });
            }

            $data = $data->orderBy('id', 'asc');

            if(request()->get('is_datatable') && Str::lower(request()->get('is_datatable')) == "yes") {
                $data = $data->paginate($limit);
                $data->getCollection = $data->getCollection()->transform(function($item) {
                    $item->roles = $item->roles()->first()->setHidden(["created_at", "updated_at", "pivot", "guard_name"]);
                    return $item;
                });
            }
            else {
                $data = $data->get();
            }

            return $this->successResponse("Get all user success", $data);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getUserAuth()
    {

        try {
            $data = $this->queryUser()->find(request()->user()->id)->setHidden([
                "created_at", "updated_at", "deleted_at"
            ]);

            if(!$data) {
                return $this->errorResponse("User not found", 404);
            }

            return $this->successResponse("User found", [
                "id" => $data->id,
                "name" => $data->name,
                "email" => $data->email,
                "role" => $data->getRoleNames()[0],
                "permissions" => $data->getAllPermissions()->map(function($permissions) {
                    return collect($permissions)->only(['id', 'name'])->all();
                }),
                "organization_team_name" => $data->organization_team_name,
                "systemStatus" => $data->systemStatus,
                "profile" => $data->profile,
                "employment" => (object) [
                    "id" => $data->employment->id,
                    "salary" => $data->employment->salary,
                    "job_position" => $data->employment->jobPosition,
                    "grade" => $data->employment->grade,
                    "employment_type" => $data->employment->employmentType
                ],
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getUserAdmin()
    {

        try {
            $data = $this->queryUser()->whereHas("roles", function($q) {
                        $q->where('name', '!=' ,'hr admin');
                    })->get()->makeHidden(["created_at", "updated_at", "deleted_at"]);

            if(!$data) {
                return $this->errorResponse("User not found", 404);
            }

            return $this->successResponse("User found", $data);

        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getUserById($id)
    {
        try {
            $data = $this->queryUser()->find($id)->setHidden([
                "created_at", "updated_at", "deleted_at"
            ]);

            if(!$data) {
                return $this->errorResponse("User not found", 404);
            }

            return $this->successResponse("User found", [
                "id" => $data->id,
                "name" => $data->name,
                "email" => $data->email,
                "role" => $data->getRoleNames()[0],
                "permissions" => $data->getAllPermissions()->map(function($permissions) {
                    return collect($permissions)->only(['id', 'name'])->all();
                }),
                "organization_team_name" => $data->organization_team_name,
                "systemStatus" => $data->systemStatus,
                "profile" => $data->profile,
                "employment" => (object) [
                    "id" => $data->employment->id,
                    "salary" => $data->employment->salary,
                    "job_position" => $data->employment->jobPosition,
                    "grade" => $data->employment->grade,
                    "employment_type" => $data->employment->employmentType
                ]

            ]);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function saveUser(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|unique:users,email',
                'make_admin' => 'required',
            ],
            [
                'first_name.required' => 'The :attribute field can not be blank value.',
                'last_name.required' => 'The :attribute field can not be blank value.',
                'email.required' => 'The :attribute field can not be blank value.',
                'make_admin.required' => 'The :attribute field can not be blank value.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $fullname = $this->combineToFullname($request->first_name, '', $request->last_name);

        if($request->make_admin) {
            $role = Role::where('name', 'hr admin')->first();
        }
        else {
            $role = Role::where('name', 'employee')->first();
        }



        $password = Str::random(10);

        try {

            $user = User::create([
                "name" => $fullname,
                "email" => $request->email,
                "password" => Hash::make($password),
                "status_id" => SystemStatus::where('slug', 'pending')->first()->id
            ]);

            $user->profile()->create([
                "user_id" => $user->id,
                "first_name" => $request->first_name,
                "last_name" => $request->last_name,
            ]);

            $user->assignRole([$role->id]);

            if($role->name == "hr admin") {
                $permissions = Permission::get();
                $user->syncPermissions($permissions);
            }

            $token = Str::random(64);
            UserVerify::create([
                "user_id" => $user->id,
                "token" => $token
            ]);

            $data = [
                "token" => $token,
                "password" => $password
            ];

            Mail::to($request->email)->send(new CreateUser($data));

            return $this->successResponse("User created successfully", [
                "user" => $user,
                "profile" => Profile::where('user_id', $user->id)->first()->makeHidden(["created_at","updated_at","deleted_at"]),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'user' => null
            ], 500);
            // return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function changeUserRole(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'role_name' => 'required',
                'users' => 'required|array'
            ],
            [
                'users.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {
            $role = Role::where('name', $request->role_name)->first();

            if(!$role) {
                return $this->errorResponse("Role Name not found", 404);
            }

            // $users = User::with('roles')->whereIn('id', $request->users)->whereHas("roles", function($q) {
            //                 $q->where('name', '!=' ,'hr admin');
            //             })->get();
            $users = User::with('roles')->whereIn('id', $request->users)->get();

            $permissions = Permission::get();
            foreach ($users as $key => $value) {
                $value->syncRoles([$role->id]);
                $value->syncPermissions($permissions);
            }

            return $this->successResponse("User HR Admin created successfully", 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'user' => null
            ], 500);
        }
    }

    public function importFileExcel(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'file' => 'required|mimes:xlsx,xls,csv',
            ],
            [
                'file.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        if(!$request->hasFile("file")) {
            return $this->errorResponse("Please import file with extention xlsx, xls and csv", 400);
        }

        $rows = Excel::toArray(new EmployeesImport, $request->file('file'));

        if(count($rows[0]) <= 0)
        {
            return $this->errorResponse("Empty data in first sheet or your file upload", 400);
        }


        $mainRows = $rows[0];
        $columns = $this->getFieldCompareFromFileRowWithDatabaseColumn($mainRows[0]);

        $data = [];

        for ($i=0; $i < count($mainRows); $i++) {
            if($i != 0) {
                array_push($data, $mainRows[$i]);
            }
        }

        return $this->successResponse("Get all rows file excel", [
            "columns" => $columns,
            "rows" => $data
        ]);

    }

    public function importFileExcelSave(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'columns.*' => 'required',
                'rows.*' => 'required',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }
        DB::beginTransaction();
        try {

            $password = Str::random(10);
            $token = Str::random(64);
            foreach ($request->rows as $key => $value) {
                $fullname = $this->combineToFullname($value[1], $value[2], $value[3]);
                
                if($value[9] == null){
                    return $this->errorResponse("Some Email is Null", 404);
                }

                if($value[4] == 'HR Admin') {
                    $role = Role::where('name', 'hr admin')->first();
                }
                else {
                    $role = Role::where('name', 'employee')->first();
                }

                $user = User::create([
                    "name" => $fullname,
                    "email" => $value[9],
                    "password" => Hash::make($password),
                    "status_id" => SystemStatus::where('slug', 'pending')->first()->id
                ]);

                $user->profile()->create([
                    "user_id" => $user->id,
                    "first_name" => $value[1],
                    "middle_name" => $value[2],
                    "last_name" => $value[3],
                ]);

                $user->assignRole([$role->id]);

                if($value[4] == 'HR Admin') {
                    $permissions = Permission::get();
                    $user->syncPermissions($permissions);
                }

                UserVerify::create([
                    "user_id" => $user->id,
                    "token" => $token
                ]);

                $data = [
                    "token" => $token,
                    "password" => $password
                ];

                Mail::to($request->email)->send(new CreateUser($data));
                // code...
            }
            DB::commit();    
            return $this->successResponse("Data imported successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }



    }

    public function downloadEmployeeTemplate($name)
    {
        $name = Str::lower($name);
        $fileName = '';
        $filePath = '';

        if($name == 'excel') {
            $filePath = public_path("/templates/employee_file.xlsx");
            $fileName = time() . "_emplooyee_template.xlsx";
        }
        else {
            $filePath = public_path("/templates/employee_file.csv");
            $fileName = time() . "_emplooyee_template.csv";
        }


        return $this->downloadResponse($filePath, $fileName);
    }

}
