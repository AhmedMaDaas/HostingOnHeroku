<?php

use Illuminate\Database\Seeder;
use App\Department;

class DepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $department = new Department();
        $department->name_en = 'Main Departments';
        $department->name_ar = 'الأقسام الرئيسية';
        $department->description = '';
        $department->owner = 'admin';
        $department->is_active = 'active';
        $department->save();

        $department = new Department();
        $department->name_en = 'Electronic';
        $department->name_ar = 'الكهربائيات';
        $department->description = '';
        $department->owner = 'admin';
        $department->is_active = 'active';
        $department->parent = 1;
        $department->save();

        $department = new Department();
        $department->name_en = 'Men';
        $department->name_ar = 'الرجالي';
        $department->description = '';
        $department->owner = 'admin';
        $department->is_active = 'active';
        $department->parent = 1;
        $department->save();

        $department = new Department();
        $department->name_en = 'Women';
        $department->name_ar = 'النسواني';
        $department->description = '';
        $department->owner = 'admin';
        $department->is_active = 'active';
        $department->parent = 1;
        $department->save();
    }
}
