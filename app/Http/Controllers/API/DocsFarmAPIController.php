<?php

class DocsFarmAPIController
{

    public function get_weather(Request $request)
    {
        /**
         * get the weather in selected coordinates
         * accepts lat and lon of the place
         */
    }


    public function index(Request $request)
    {
        /**
         * get all farms
         * used only by admin
         */
    }

    public function relations_index()
    {
        /**
         * get the data required in creating and editing farm
         */
    }

    public function toggleArchive($id)
    {
        /**
         * archive or un-archive a farm
         * archive the farm if it is in-archived and vice versa
         */
    }


    public function getArchived()
    {
        /**
         * get the archived farms of the user
         */
    }


    protected function generateRandomString($length = 10) {
       /**
        * generate random string for farm code number
        */
    }

    public function store(CreateFarmAPIRequest $request)
    {
        /**
         * store a farm in the database
         *
         */
    }

    public function calculate_compatibility($id)
    {
        /**
         * حساب الملاءمة
         * انظر التفاصيل داخل كلاس الملاءمة
         */
        $data = (new Compatibility())->calculate_compatibility($id);
        return response()->json($data);
    }

    public function app_roles(Request $request)
    {
        /**
         * get the roles to add someone to the farm or change someone's role in the farm
         */
    }


    public function app_users(Request $request)
    {
       /**
        * get the users who are not present in the farm, to add someone to the farm
        */
    }

    private function is_valid_invitation($request){
        /**
         * checks if the invitation is valid
         * a valid invitation in the one that its 'accepted' value is null and not true (accepted before) nor false (declined before)
         */
    }

    public function first_attach_farm_role(Request $request)
    {
        /**
         * attach a farm role to a user who has a valid invitation link
         **/
    }

    public function decline_farm_invitation(Request $request)
    {
       /**
        * decline farm invitation by setting its 'accepted' value to false
        */
    }

    public function update_farm_role(Request $request)
    {
        /**
         * 1-attach, 2-edit or 3-delete farm roles (send empty or no role_id when deleting a role)
         * if role key is sent with the request and not null and the user is not in the farm, an invitation will be sent to him.
         * and if the user present in the farm so his role will be updated
         * if role key is not sent with the request or is null, the user will be deleted from the farm
         */
    }


    public function get_farm_users($id)
    {
        /**
         * get the users of a farm
         */
    }


    public function get_farm_posts($id)
    {
        /**
         * get the posts of a farm
         */
    }


    public function show($id)
    {
        /**
         * get the details of a farm
         */
    }


    public function update($id, CreateFarmAPIRequest $request)
    {
        /**
         * update a farm
         */
    }


    public function destroy($id)
    {
        /**
         * delete a farm
         */
    }

}
