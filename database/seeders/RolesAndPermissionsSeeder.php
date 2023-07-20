<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create permissions
        $createMeterPermId = Permission::insertGetId(['name' => 'create-meter']);
        $updateMeterPermId = Permission::insertGetId(['name' => 'update-meter']);
        $deleteMeterPermId = Permission::insertGetId(['name' => 'delete-meter']);
        $restoreMeterPermId = Permission::insertGetId(['name' => 'restore-meter']);
        $forceDeleteMeterPermId = Permission::insertGetId(['name' => 'force-delete-meter']);
        $viewMeterReadingPermId = Permission::insertGetId(['name' => 'view-meter-reading']);
        $createMeterReadingPermId = Permission::insertGetId(['name' => 'create-meter-reading']);
        $updateMeterReadingPermId = Permission::insertGetId(['name' => 'update-meter-reading']);
        $deleteMeterReadingPermId = Permission::insertGetId(['name' => 'delete-meter-reading']);
        $restoreMeterReadingPermId = Permission::insertGetId(['name' => 'restore-meter-reading']);
        $forceDeleteMeterReadingPermId = Permission::insertGetId(['name' => 'force-delete-meter-reading']);
        $uploadMeterReadingPermId = Permission::insertGetId(['name' => 'upload-meter-reading']);

        //Create roles
        $userRoleId = Role::insertGetId(['name' => 'user']);
        $adminRoleId = Role::insertGetId(['name' => 'admin']);

        // Link roles and permissions
        DB::table('permission_role')->insert([
            ['role_id' => $adminRoleId, 'permission_id' => $createMeterPermId],
            ['role_id' => $adminRoleId, 'permission_id' => $updateMeterPermId],
            ['role_id' => $adminRoleId, 'permission_id' => $deleteMeterPermId],
            ['role_id' => $adminRoleId, 'permission_id' => $restoreMeterPermId],
            ['role_id' => $adminRoleId, 'permission_id' => $forceDeleteMeterPermId],

            ['role_id' => $adminRoleId, 'permission_id' => $viewMeterReadingPermId],
            ['role_id' => $adminRoleId, 'permission_id' => $updateMeterReadingPermId],
            ['role_id' => $adminRoleId, 'permission_id' => $deleteMeterReadingPermId],
            ['role_id' => $adminRoleId, 'permission_id' => $restoreMeterReadingPermId],
            ['role_id' => $adminRoleId, 'permission_id' => $forceDeleteMeterReadingPermId],
            ['role_id' => $adminRoleId, 'permission_id' => $uploadMeterReadingPermId],


            ['role_id' => $userRoleId, 'permission_id' => $createMeterReadingPermId],
            ['role_id' => $userRoleId, 'permission_id' => $deleteMeterReadingPermId],
        ]);
    }
}
