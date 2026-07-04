<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function search(Request $request){
        /** @var User $loggedUser */   
        $loggedUser = Auth::guard('api')->user();      
        $array = ['error'=>'', 'users' => []]; 

        $txt = $request->input('txt');

        if($txt){

            // Users Search
            $userList = User::where('name', 'like', '%'.$txt.'%')->get();
            foreach($userList as $userItem){
                $array['users'][] = [
                    'id' => $userItem->id,
                    'name' => $userItem->name,
                    'avatar' => url('media/avatars/' .$userItem->avatar)
                ];
            }
            // Posts Search

        }else{
            return ['error' => 'Nothing to look for.'];
        }

        return $array;
    }
}
