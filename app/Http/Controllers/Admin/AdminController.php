<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 8/23/16
 * Time: 2:45 PM
 */
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class AdminController extends Controller{

    public  function  __construct(){
        //$this->middleware('auth');
    }

    public function index(){
        return view('admin.index');
    }

    public function listsocial(Request $request){
        return view('admin.listsocial');
    }
    public function addsocial (Request $request){
        return view('admin.addsocial');
    }
    public function listnews(Request $request){
        return view('admin.listnews');
    }
    public function addnews(Request $request){
        return view('admin.addnews');
    }
    public function socialsetting(Request $request){
        if($request->input('active')!=null){

            if($request->input('type') =='social'){
                $active = $request->input('active');
                $id = $request->input('id');
                $db = DB::table('social_info')->where('id',$id)->update(['status' => $active]);

            }
            if($request->input('type') =='fanpage'){
                $active = $request->input('active');
                $id = $request->input('id');
                $db = DB::table('fan_page')->where('id',$id)->update(['status' => $active]);
            }

        }
        if($request->input('delete')!=null && $request->input('delete')==true){
            if($request->input('type') =='social'){
                $id = $request->input('id');
                $db = DB::table('social_info')->where('id',$id)->delete();

            }
            if($request->input('type') =='fanpage'){
                $id = $request->input('id');
                $db = DB::table('fan_page')->where('id',$id)->delete();
            }
        }
        $social_data = DB::table('social_info')->paginate(5,['*'],'social');
        $fanpage_data = DB::table('fan_page')->paginate(5,['*'],'fan_page');
        return view('admin.socialsetting',['social_data'=>$social_data,'fanpage_data'=>$fanpage_data]);
    }
    public function newssetting(Request $request){
        return view('admin.newssetting');
    }
    public function addnewsocial(Request $request){
        if($request->isMethod('post')){
            $social_name = $request->input('social_name');
            $social_logo = $request->file('social_logo');
            $video_tag = $request->file('video_tag');
            $color_tag = $request->input('color_tag');
            $social_logo_url = $this->_getFilepath($social_logo);
            $video_tag_url = $this->_getFilepath($video_tag);
            DB::table('social_info')->insert(['name'=>$social_name,'logo'=>$social_logo_url,'color_tag' => $color_tag,
                'video_tag' =>$video_tag_url]);
            return view('admin.addnewsocial',['url'=>$social_logo_url]);
        }
        return view('admin.addnewsocial',['url' =>'']);
    }
    public function addnewfanpage(Request $request){
        $social_data = DB::table('social_info')->where('status',1)->get();
        if($request->isMethod('post')){
            $fanpage_name = $request->input('fanpage_name');
            $fanpage_logo = $request->file('fanpage_logo');
            $fanpage_logo_url = $this->_getFilepath($fanpage_logo);
            $active = $request->input('active');
            $socia_info_id = $request->input('social_info_id');
            $active = $active == NULL?0:1;
            $value = DB::table('fan_page')->insert(['name'=>$fanpage_name,'logo'=>$fanpage_logo_url,
                'status' => $active,'social_info_id' =>$socia_info_id]);
            if($value){
                return view('admin.addnewfanpage',['social_data'=> $social_data] );
            }
        }

        return view('admin.addnewfanpage',['social_data' =>$social_data]);
    }
    private function _getFilepath($file_upload){
        if($file_upload == null)
            return '';
        $file_name = time().$file_upload->getFilename().'.'.$file_upload->getClientOriginalExtension();
        $file_upload->move(public_path("uploads"), $file_name);

        return  url('/').'/uploads/'.$file_name;
    }

}