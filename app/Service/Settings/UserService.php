<?php

namespace App\Service\Settings;

use App\Models\User;
use App\Service\Interfaces\CrudServiceInterface;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService implements CrudServiceInterface
{
    
    public function all(array $params):LengthAwarePaginator{

        $params = [
            'page' => $params['page']?? 1,
            'pageSize' => $params['pageSize'] ?? 10,
            'search' => $params['search']??'',
            'sortBy' =>  $params['sortBy']?? (new User())->getKeyName(),
            'sortOrder' => $params['sortOrder']??'asc',
        ];

        Paginator::currentPageResolver(function () use ( $params ) {
            return $params['page'] ?? 1;
        });

        $result = User::with('group')->orderBy($params['sortBy'], $params['sortOrder']);

        if ($params['search']) {
            $result->where('title', 'like', "%" . $params['search'] . "%");
        }

        return $result->paginate($params['pageSize']);;
    }
    
    public function find(int $id):?User{
        $data = User::find($id);

        return $data;
    }

    public function save(array $data):User{
        try{
            $user =User::create([
                'email' => $data['email'],
                'name' => $data['name'],
                'password' => Hash::make($data['password']),
                'enabled' => $data['enabled'],
                'group_id' => $data['group_id'],
                // 'phone_number'=>$data['phone_number'],
                'created_by' => Auth::id(),    
            ]);
            
            return $user;
        }
        catch(Exception $e){
            Log::info('Unexpected Exception : ' . $e->getMessage());
            throw new \Exception('Failed to create User due to a Unexpected error.', 500);
        }
    }
    public function update(array $data,int $id):User{

        $user = User::find($id);
        if ($user) {
            $user->name = $data['name'];
            $user->enabled = $data['enabled'];
            $user->group_id = $data['group_id'];

            if (isset($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

        $user->save();
        }
        return $user;
    }
    public function delete(int $id):bool{
        return User::destroy($id)?true:false;
    }

    public function register(array $data){
        try{
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            return $user;
        }catch(Exception $e){
            throw new \Exception('Failed to create User due to a Unexpected error. ' . $e->getMessage(), 500);
        }
    }
    public function login(array $data){
 
        try{
            $user = User::where('email', $data['email'])->first();
            Log::info($user);
            $token = auth()->attempt($data);
            if($token){
                //
                $user['token'] = $token;
                $user['token_type'] = 'bearer';
                
            }
            return ['data'=>$user,'token'=>$token];

        }catch(Exception $e){
            throw new \Exception('Faild to login ' . $e->getMessage());
        }
    }
    public function getUsersCount(){
        return User::count();
    }

}
