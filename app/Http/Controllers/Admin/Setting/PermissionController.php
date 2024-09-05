<?php

namespace App\Http\Controllers\Admin\Setting;


use App\Http\Controllers\CoreController;
use App\Models\Permission\GroupPermission;
use App\Models\Permission\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->has('page')){
            $userGroup = Permission::get();
            return response()->json(['data' => $userGroup, 'message' =>'successfully fetch data','status' => 200], 200);
        }

        CoreController::hasPermission('GROUP_LIST');
        
        $pageSize = $request->get('pageSize',10);
        $search = $request->get('search');
        $sortBy = $request->get('sortBy','category');
        $sortOrder = $request->get('sortOrder','asc');


        $result =  Permission::orderBy( $sortBy , $sortOrder);

        if(isset($search)){
            $result->where('role','like',"%$search%");
        }


        return $result->paginate($pageSize);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        CoreController::hasPermission(array('GROUP_ADD'));
        $validator = Validator::make($request->all(), Permission::$rulesAdd);
        if ($validator->fails())
            return response()->json(['data'=>array(),'status' => 400, 'message' => $validator->errors()], 200);

        $data = array(
            'role' => $request->role,
            'category' => $request->category,
            'created_at'=>Carbon::now()
        );
        $user = Permission::create($data);
        return response()->json(['data' => $user, 'message' =>'successful creation','status' => 200], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        CoreController::hasPermission(array('GROUP_EDIT'));
        $permissions = Permission::select(DB::raw('id,role, category, 0 as status' ))->orderBy('category', 'asc')->get();
        $groupPermission = GroupPermission::where('group_id', $id)->get();
        for($i = 0 ; $i < sizeof($permissions) ; $i++){
            foreach ($groupPermission as $perm){
                if($permissions[$i]->role == $perm->role){
                    $permissions[$i]['status'] = 1;
                    break;
                }
            }
        }

        return response()->json(['data' => $permissions, 'message' =>'fetch successfully','status' => 200], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        CoreController::hasPermission(array('GROUP_EDIT'));
        DB::beginTransaction();

        $validator = Validator::make($request->all(), Permission::$rulesAdd);
        if ($validator->fails())
            {
                DB::rollBack();
                return response()->json(['data'=>array(),'status' => 400, 'message' => $validator->errors()], 200);
            }

        Permission::where('id', $id)->delete();
       $perms =  Permission::insert(['role'=>$request->get('role'),'category'=>$request->get('category')]);
        if(!$perms){
            DB::rollBack();
            return response()->json(['data' => [], 'message' =>'UserGroup not found!','status' => 404], 200);
        }
        DB::commit();
        return response()->json(['data' => $perms, 'message' =>'update group permission successfully','status' => 200], 200);
    }




}
