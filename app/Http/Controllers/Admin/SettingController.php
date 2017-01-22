<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 1/20/17
 * Time: 3:34 PM
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Validator;
use App\Models\AdminRole;

class  SettingController extends Controller{

    public function __construct(){

    }
    protected function validator(array $data){
        return Validator::make($data, [
            'name_role' => 'required|max:255',
            'role_type_number' => 'required|number',
        ]);
    }
    public function createRole(Request $request){
        $adminRole = new AdminRole();
        $validate_make = Validator::make(array(),array(),array());
        if ($request->isMethod('POST')){
            $name = $adminRole->where('name',$request->get('name'))->get()->toArray();
            $roleType = $adminRole->where('role_type',$request->get('role_type'))->get()->toArray();
            if (!empty($name)){
                $validate_make->errors()->add('field', "name was exist");
                return redirect()->route('get_createRole')->withErrors($validate_make);
            }
            if (!empty($roleType)){
                $validate_make->errors()->add('field', "role was exist");
                return redirect()->route('get_createRole')->withErrors($validate_make);
            }


            $this->validate($request,['name' => 'required|max:255','role_type' => 'required|numeric']);
            $adminRole->name = $request->get('name');
            $adminRole->role_type = $request->get('role_type');
            $adminRole->active = $request->get('isActive') ? 1 : 0;
            $adminRole->save();
        }
        return view('admin/setting/adminUser.createRole',['role_list' => $adminRole->get()->toArray()]);
    }
}