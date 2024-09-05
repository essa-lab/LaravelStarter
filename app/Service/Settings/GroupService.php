<?php

namespace App\Service\Settings;

use App\Models\Permission\UserGroup;
use App\Service\Interfaces\CrudServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GroupService implements CrudServiceInterface
{
    
    public function all(array $params):LengthAwarePaginator|Collection{

        $params = [
            'select'=>$params['page']??true,
            'page' => $params['page']?? 1,
            'pageSize' => $params['pageSize'] ?? 10,
            'search' => $params['search']??'',
            'sortBy' =>  $params['sortBy']?? (new UserGroup())->getKeyName(),
            'sortOrder' => $params['sortOrder']??'asc',

        ];

        if(is_bool($params['select'])){
            $userGroup = UserGroup::where('enabled',1)->get();
            return $userGroup;
        }
        
        Paginator::currentPageResolver(function () use ( $params ) {
            return $params['page'] ?? 1;
        });

        $result = UserGroup::with('User')->orderBy($params['sortBy'], $params['sortOrder']);

        if ($params['search']) {
            $result->where('group_name', 'like', "%" . $params['search'] . "%");
        }
        
        
        return $result->paginate($params['pageSize']);;
    }
    
    public function find(int $id):?UserGroup{

        $data = UserGroup::find($id);
        return $data;
        
    }

    public function save(array $data):UserGroup{
        try{
            $userGroup =UserGroup::create([
                'group_name'=>$data['group_name'],
                'enabled'=>$data['enabled'],
                'created_by'=>$data['createdBy'] 
            ]);
            return $userGroup;
        }
        catch(Exception $e){
            throw new \Exception('Failed to create UserGroup due to a Unexpected error.', 500);
        }
    }
    public function update(array $data,int $id):UserGroup{

        $userGroup = UserGroup::find($id);
        if ($userGroup) {
            $userGroup->group_name = $data['group_name'];
            $userGroup->enabled = $data['enabled'];

            $userGroup->save();
        }
        return $userGroup;
    }
    public function delete(int $id):bool{
        return UserGroup::destroy($id)?true:false;
    }

    public function getGroupsCount(){
        return UserGroup::count();
    }
}
