<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Facades\Log;

class RoleService
{
    public function create_Role($data)
    {
        try {
            //Create a new Role using the provided data
            $role = Role::create([
                'name' => $data['name'],
                'description' => $data['description'],
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error in RoleService@create_Role: ' . $e->getMessage());
            return false;
        }
    }

        /**
     * update the user information
     * @param Role $user
     * @param array $data
     * @return Role $user
     */


     public function update_Role(Role $role,  $data)
     {
         try {
             
             $role->update([
                 'name' => $data['name'] ?? $role->name,
                 'description' => $data['description'] ?? $role->description,
             ]);
             return true;
         } catch (\Exception $e) {
             Log::error('Error in roleService@update_Role: ' . $e->getMessage());
             return false;
         }
     }
 
}
