<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Department;


class DepartmentsController extends Controller
{
    public function getDepartments(){
        $departments = $this->filter(Department::all());
        return json_encode($departments);
    }

    private function filter($departments){
        foreach ($departments as $key => $department) {
            unset($department->owner);
        }
        return $departments;
    }
}
