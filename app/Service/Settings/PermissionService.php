<?php

namespace App\Service\Settings;

use App\Models\Permission\GroupPermission;
use App\Models\Permission\Permission;
use App\Models\Permission\UserGroup;
use App\Service\Interfaces\CrudServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class PermissionService implements CrudServiceInterface
{
    
    public function all(array $params):?LengthAwarePaginator{
        return null;  
    }
    
    public function find(int $id):Collection{

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

        return $permissions;
    }

    public function save(array $data):Permission{
        return Permission::find(1);
    }

    public function update(array $data,int $id):bool{

        GroupPermission::where('group_id', $id)->delete();
       $perms =  GroupPermission::insert($data['permissions']);

        return $perms;

    }
    public function delete(int $id):bool{
        return true;
    }

}
