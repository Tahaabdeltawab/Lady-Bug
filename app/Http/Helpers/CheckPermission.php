<?php

namespace App\Http\Helpers;

use App\Models\Business;

class CheckPermission{

    public function check_business_permission($business_id, $user)
    {

        $business = Business::find($business_id);

        if (empty($business))
        {
            return [
                'success' => false,
                'data' => (object)[],
                'code' => 404,
                'message' => 'business not found'
            ];
        }

        if(!$user->hasRole(config('myconfig.admin_role')))
        {
            $user_business = $user->allTeams()->where('id', $business_id)->first();
            if(!$user_business)
            {
                return [
                    'success' => false,
                    'data' => (object)[],
                    'code' => 989,
                    'message' => 'User is not a member in this business'
                ];
            }


            $allowed_roles = config('myconfig.edit_business_allowed_roles');
            if(!$user->hasRole($allowed_roles, $business_id))
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
