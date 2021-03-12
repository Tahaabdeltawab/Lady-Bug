<?php

namespace App\Http\Helpers;

class CheckPermission{

    public function allowed_permissions($Apermissions,$user_id, $farm_id){
        if(!empty($Apermissions)&&!empty($user_id)&&!empty($farm_id)){
            //check if the user has the right permissions in this workable
            $workable = \App\Models\Workable::with(['workable_roles', 'workable_roles.workable_permissions'])->where([['worker_id',$user_id], ['workable_id',$farm_id], ['workable_type','App\Models\Farm']])->first();
            foreach($workable->workable_roles as $wrole){
                foreach($wrole->workable_permissions as $wpermission){
                    $wrole_permissions_names[$wpermission->name] = $wpermission->workable_type_id;
                }
            }
            $allowed_permissions_names = explode('|', $Apermissions);
            $wrole_permissions_names = array_keys($wrole_permissions_names) ;
            $readable_allowed_permissions = str_replace('|', ' or ', $Apermissions);
            if(!array_intersect($allowed_permissions_names, $wrole_permissions_names)){
                return false;
            }else{
                return true;
            }
        }
        return true;
    }

    public static function instance()
    {
        return new CheckPermission();
    }
}
