<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{        
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function like($id){
        /** @var User $loggedUser */   
        $loggedUser = Auth::guard('api')->user();      
        $array = ['error'=>'']; 

        // 1. Check if the post exist
        $postExists = Post::find($id);
        if($postExists){
            // 2. Check if I've already liked the post

            $isLiked = PostLike::where('id_post', $id)
            ->where('id_user', $loggedUser->id)
            ->count();

            if($isLiked > 0){
                // 2.1. If so, remove
                $pl = PostLike::where('id_post', $id)
                ->where('id_user', $loggedUser->id)
                ->first();
                $pl->delete();

                $array['isLiked'] = false;
            }else{
                // 2.2. If not, add
                $newPostLike = new PostLike();
                $newPostLike->id_post = $id;
                $newPostLike->id_user = $loggedUser->id;
                $newPostLike->created_at = date('Y-m-d H:i:s');
                $newPostLike->save();

                $array['isLiked'] = true;
            }

            $likeCount = PostLike::where('id_post', $id)->count();
            $array['likeCount'] = $likeCount;

        }else{
            return ['error' => 'Post does not exist.'];
        }


        return $array;
    } 

    public function comment(Request $request, $id){
        /** @var User $loggedUser */   
        $loggedUser = Auth::guard('api')->user();      
        $array = ['error'=>'']; 

        $body = $request->input('body');

        $postExists = Post::find($id);
        if($postExists){
            if($body){

                $newComment = new PostComment();
                $newComment->id_post = $id;
                $newComment->id_user = $loggedUser->id;
                $newComment->created_at = date('Y-m-d H:i:s');
                $newComment->body = $body;
                $newComment->save();

            }else{
                return ['error'=>'No message sent.'];
            }
        }else{
            return ['error' => 'Post does not exist.'];
        }
        return $array;
    }

    public function delete($id)
{
    $user = auth()->user();

    $post = Post::where('id',$id)
                ->where('id_user',$user->id)
                ->first();

    if(!$post){
        return response()->json([
            'error'=>'Post não encontrado.'
        ],404);
    }

    $post->delete();

    return response()->json([
        'success'=>true
    ]);
}
}
