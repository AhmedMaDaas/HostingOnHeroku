<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Department;
use DB;


class DepartmentsController extends Controller
{
    public function getDepartments(){
        $departments = Department::where(function($query){
            return $query->where('parent', null)->whereExists( function ($query) {
                return $query->select(DB::raw(1))
                ->from('products')
                ->whereRaw('departments.id = products.department_id');
            });
        })->orWhere('parent', '!=', null)
        ->get();

        $departmentsFilter = $this->filter($departments);
        return json_encode($departmentsFilter);
    }

    private function filter($departments){
        foreach ($departments as $key => $department) {
            if($this->checkParent($departments, $department->parent)) {
                unset($departments[$key]);
                continue;
            }
            unset($department->owner);
        }
        return $departments;
    }

    private function checkParent($departments, $parentId){
        foreach ($departments as $key => $department) {
            if($parentId == $department->id){
                return true;
            }
        }
        return false;
    }
}
