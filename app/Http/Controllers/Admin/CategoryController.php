<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 1/21/17
 * Time: 2:10 PM
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Validator;
class CategoryController extends Controller {

    public function index(Request $request){
        $page = $request->get('page');
        $categoryModel = new Category();
        $validate_make = Validator(array(),array(),array());
        $update_data = null;
        if ($request->isMethod('POST')){
            $this->validate($request,['name'=>'required|max:255']);
            $nameRepose = $categoryModel->where('name',$request->get('name'))->get()->toArray();
            if(!empty($nameRepose)){
                $validate_make->errors()->add('field', "name was exist");
                return redirect()->route('get_category')->withErrors($validate_make);
            }
            $active = !empty($request->get('isActive')) ? 1 : 0 ;
            //create new
            if($request->get('id')==null){
                if($this->create($request->get('name'),$active,$categoryModel)){
                    $validate_make->errors()->add('field', "Create new Successful!");
                }else{
                    $validate_make->errors()->add('field', "can not save!");
                }
            }
            //update
            if($request->get('id') !=null){
                if($this->update($request->get('id'),$request->get('name'),$active,$categoryModel)){
                    $validate_make->errors()->add('field', "Update Successful!");
                }else{
                    $validate_make->errors()->add('field', "can not update!");
                }
            }

            return redirect()->route('get_category')->withErrors($validate_make);
        }
        if ($request->isMethod('GET')){
            if($request->get('delete')){
               $this->delete($request->get('id'),$categoryModel);
            }
            if($request->get('active')!=null){
                $this->changeStatus($request->get('id'),$request->get('active'),$categoryModel);
            }
            if($request->get('isEdit')!=null && $request->get('id') != null){
                $update_data = $categoryModel->where('id',$request->get('id'))->get()->toArray();
                $update_data = $update_data[0];
            }
        }

        $categoryList = $categoryModel->orderBy('created_at')->paginate(3);
        return view('admin/setting/category.categoryIndex',['category_list'=>$categoryList,'page'=>$page,'isEdit'=>$request->get('isEdit'),'update_data' =>$update_data]);
    }

    public function delete($id,Category $category){
        $category::destroy($id);
    }
    public function changeStatus($id,$status,Category $category){
        $result = $category->find($id);
        $result->active = $status;
        $result->save();
    }
    public function create($name,$active,Category $category){
        $category->name = $name;
        $category->active = $active;
        return $category->save();
    }
    public function update($id,$name,$active,Category $category){
        $data = $category->find($id);
        $data->name = $name;
        $data->active = $active;
        return $data->save();
    }
}