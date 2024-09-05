<?php

namespace App\Http\Controllers;

use App\Models\Permission\GroupPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CoreController
{
    public static function isAdmin($id=null){
        if (!$id) $id=Auth::User()->id;
        if ($id==1)
            return true;
        else
            return false;
    }
    public static function hasPermission($prm){
        if(self::isAdmin()) return true;
        $group_id = Auth::user()->group_id;
        $permissions = GroupPermission::where('group_id', $group_id)->pluck('role')->toArray();
        if(is_array($prm)){
            foreach($prm as $prmItem){
                if (in_array($prmItem, $permissions)) {
                    return true;
                }
            }
            abort( 403, 'Forbidden.');

        }else{
            //error_log($prm .":".Session::has($prm));
            if (!in_array($prm, $permissions) ) {
                abort( 403, 'Forbidden.');
            }else{
                return true;
            }
        }

    }

    public static function dropShippingID (){
        $user = Auth::user();
        return $user->id_supplier;
    }
}
