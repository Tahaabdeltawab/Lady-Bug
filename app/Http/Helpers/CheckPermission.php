<?php

namespace App\Http\Helpers;

use App\Models\Farm;

class CheckPermission{

    public function check_farm_permission($farm_id, $user)
    {

        $farm = Farm::where('id', $farm_id)->first();

        if (empty($farm))
        {
            return [
                'success' => false,
                'data' => (object)[],
                'code' => 404,
                'message' => 'Farm not found'
            ];
        }

        if(!$user->hasRole(config('myconfig.admin_role')))
        {
            $user_farm = $user->allTeams()->where('id', $farm_id)->first();
            if(!$user_farm)
            {
                return [
                    'success' => false,
                    'data' => (object)[],
                    'code' => 989,
                    'message' => 'User is not a member in this farm'
                ];
            }


            $allowed_roles = config('myconfig.edit_farm_allowed_roles');
            if(!$user->hasRole($allowed_roles, $farm_id))
            {
                return [
                    'success' => false,
                    'data' => (object)[],
                    'code' => 499,
                    'message' => 'User does not have any of the necessary access rights.'
                ];
            }

            return [
                    'success' => true,
                    'data' => (object)[],
                    'code' => 200,
                    'message' => 'Success'
                ];
        }

        return [
                    'success' => true,
                    'data' => (object)[],
                    'code' => 200,
                    'message' => 'Success'
                ];
    }

    public static function instance()
    {
        return new CheckPermission();
    }
}
