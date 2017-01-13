<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 8/22/16
 * Time: 11:57 PM
 */

namespace App\Http\Controllers;

use DB;
use App\Http\Controllers\Controller;

class UserController extends Controller{

    public function showProfile($email){
        $users = DB::select('select * from users WHERE email = ?',[$email]);
        return view('users.user')->with('users',$users);
    }
    public function insertUser($email){
        DB::insert('insert into users (email, name) values (?, ?)', [$email, 'Dayle']);
        echo $email;
    }
}