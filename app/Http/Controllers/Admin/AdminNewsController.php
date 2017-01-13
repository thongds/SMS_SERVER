<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 8/25/16
 * Time: 8:13 AM
 */
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminNewsController extends Controller{

    public  function  __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        if($request->input('active')!=null){
            if($request->input('type') =='newspaper'){
                $active = $request->input('active');
                $id = $request->input('id');
                $db = DB::table('newspaper')->where('id',$id)->update(['status' => $active]);

            }
            if($request->input('type') =='category'){
                $active = $request->input('active');
                $id = $request->input('id');
                $db = DB::table('category')->where('id',$id)->update(['status' => $active]);
            }

        }
        if($request->input('delete')!=null && $request->input('delete')==true){
            if($request->input('type') =='newspaper'){
                $id = $request->input('id');
                $db = DB::table('newspaper')->where('id',$id)->delete();

            }
            if($request->input('type') =='category'){
                $id = $request->input('id');
                $db = DB::table('category')->where('id',$id)->delete();
            }
        }
        $category = DB::table('category')->paginate(5,['*'],'category');
        $newspaper = DB::table('newspaper')->paginate(5,['*'],'newspaper');
        return view('admin.news.index',['category'=>$category,'newspaper'=>$newspaper]);
    }
    public function listNewsmedia(Request $request){
            $page = $request->input('page');
            if($request->input('active')!=null){

                if($request->input('type') =='news'){
                    $active = $request->input('active');
                    $id = $request->input('id');
                    $db = DB::table('news')->where('id',$id)->update(['status' => $active]);

                }
            }
            if($request->input('delete')!=null && $request->input('delete')==true){
                if($request->input('type') =='news'){
                    $id = $request->input('id');
                    $db = DB::table('news')->where('id',$id)->delete();

                }
            }
            $news = DB::table('news')->orderBy('created','desc')->paginate(5);
            return view('admin.news.listnews_media',['news'=>$news,'page' =>$page]);
    }
    public function addNewsMedia(Request $request){
        if($request->isMethod('post')){
            $post_title = $request->input('post_title');
            $post_content = $request->input('post_content');
            $video_link = $request->input('video_link');
            $post_image = $request->input('post_image');
            $category_id = $request->input('category_id');
            $newspaper_id = $request->input('newspaper_id');
            $is_video = $request->input('is_video') == NULL?0:1;
            $status  = $request->input('status') == NULL ?0:1;
            $full_link = $request->input('full_link');
            if($request->input('is_update') >0)
                $result = DB::table('news')->where('id',$request->input('is_update'))->update(['post_title' =>$post_title,
                    'post_content' => $post_content,
                    'video_link' => $video_link,
                    'post_image' => $post_image,
                    'category_id' => $category_id,
                    'newspaper_id' => $newspaper_id,
                    'is_video' => $is_video,
                    'created' =>time(),
                    'full_link' => $full_link,
                    'status' =>$status
                ]);
            else
                $result = DB::table('news')->insert(['post_title' =>$post_title,
                    'post_content' => $post_content,
                    'video_link' => $video_link,
                    'post_image' => $post_image,
                    'category_id' => $category_id,
                    'newspaper_id' => $newspaper_id,
                    'is_video' => $is_video,
                    'created' =>time(),
                    'full_link' => $full_link,
                    'status' =>$status
                ]);
            if(!$result){
                var_dump($result);exit;
            }
        }
        $update_data = null;
        $id = $request->input('id') !=null ? $request->input('id'):-1;
        if($id >0)
            $update_data = DB::table('news')->where('id',$id)->get();
        $category = DB::table('category')->where('status',1)->get();
        $newspaper = DB::table('newspaper')->where('status',1)->get();
        return view('admin.news.add_news_media',['category'=>$category,'newspaper'=>$newspaper,'update_data' =>$update_data]);
    }
    public function addNewspaper(Request $request){
        if($request->isMethod('post')){
            $newspaper_name = $request->input('newspaper_name');
            $newspaper_logo = $request->file('newspaper_logo');
            $status = $request->input('status');
            $status = $status == NULL ?0:1;
            $newspaper_logo_url = $this->_getFilepath($newspaper_logo);
            $video_tag = $request->file('video_tag_image');
            $video_tag_url = $this->_getFilepath($video_tag);
            $title_color = $request->input('title_color');
            $newspaper_tag_color = $request->input('newspaper_tag_color');
            $result = DB::table('newspaper')->insert(['newspaper_name' =>$newspaper_name,'title_color' =>$title_color
            ,'paper_logo' =>$newspaper_logo_url,'paper_tag_color' => $newspaper_tag_color,'video_tag_image' =>$video_tag_url,
            'status' =>$status]);
            if($result){
                return view('admin.news.add_newspaper');
            }else{
                var_dump($result);
                exit;
            }
        }
        return view('admin.news.add_newspaper');
    }
    public function addNewCategory(Request $request){
        if($request->isMethod('post')){
            $category_name = $request->input('category_name');
            $status = $request->input('status');
            $status = $status == NULL ?0:1;
            $result = DB::table('category')->insert(['category_name'=>$category_name,'status'=>$status]);
            if(!$result){
                var_dump($result);exit;
            }
        }
        return view('admin.news.add_new_category');
    }
    private function _getFilepath($file_upload){
        if($file_upload == null)
            return '';
        $file_name = time().$file_upload->getFilename().'.'.$file_upload->getClientOriginalExtension();
        $file_upload->move(public_path("uploads"), $file_name);

        return  url('/').'/uploads/'.$file_name;
    }
}