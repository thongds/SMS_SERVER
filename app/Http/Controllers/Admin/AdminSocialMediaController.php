<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 8/25/16
 * Time: 4:06 PM
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

$pase_view_path = "admin.social_media";
class AdminSocialMediaController extends Controller{
    private $pase_view_path = "admin.social_media.";

    public function __construct(){
        //$this->middleware('auth');
    }

    public function index(Request $request){
        if($request->input('active')!=null){

            if($request->input('type') =='social'){
                $active = $request->input('active');
                $id = $request->input('id');
                $db = DB::table('social_media')->where('id',$id)->update(['status' => $active]);

            }
        }
        if($request->input('delete')!=null && $request->input('delete')==true){
            if($request->input('type') =='social'){
                $id = $request->input('id');
                $db = DB::table('social_media')->where('id',$id)->delete();

            }
        }
        $socialMedia = DB::table('social_media')->orderBy('created','desc')->paginate(10);
        return view($this->pase_view_path.'index',['socialmedia' =>$socialMedia]);
    }

    public function addNewSocialMedia(Request $request){
        if($request->isMethod('post')) {
            $title = $request->input('title');
            $post_image_url = $request->input('post_image_url');
            $social_content_type = $request->input('social_content_type_id');
            $url_clude = null;
            if ($post_image_url != null) {
                foreach ($post_image_url as $url) {
                        $url_clude .= $url . "--inshortnews--";
                }
            }

            $full_link = $request->input('full_link');
            $fanpage_id = $request->input('fanpage_id');
            $status = $request->input('status') == null ? 0 : 1;
            $id = $request->input('id');

            if ($id != null)
                $result = DB::table('social_media')->where('id', $id)->update(['title' => $title, 'post_image_url' => $url_clude,
                    'full_link' => $full_link, 'fan_page_id' => $fanpage_id, 'status' => $status,
                    'social_content_type_id' => $social_content_type
                ]);
            else
                $result = DB::table('social_media')->insert(['title' => $title, 'post_image_url' => $url_clude,
                    'full_link' => $full_link, 'fan_page_id' => $fanpage_id, 'status' => $status,
                     'social_content_type_id' => $social_content_type, 'created' => time()
                ]);
            if (!$result) {
                var_dump($result);exit;
            }
        }
        $social_data = null;
        if($request->query('id')!=null){
            $social_data = DB::table('social_media')->where('id',$request->query('id'))->get();
        }
        $fanpage = DB::table('fan_page')->where('status',1)->get();
        $social_post_type = DB::table('social_content_type')->where('status',1)->get();
        return  view($this->pase_view_path.'add_new_social_media',['fanpage' => $fanpage,
            'social_data' =>$social_data,
            'social_content_type' => $social_post_type]);
    }

}