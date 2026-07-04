<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Models\User;
use App\Models\UserRelation;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class FeedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    } 

    public function create(Request $request){  
        /** @var User $loggedUser */   
        $loggedUser = Auth::guard('api')->user();      
        $array = ['error'=>''];        
        $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];

        $type = $request->input('type');
        $body = $request->input('body');
        $photo = $request->file('photo');

        $user = $loggedUser;

        if($type){

            switch($type){
                case 'text':
                    if(!$body){
                        return ['error' => "Text not sent."];
                    }
                break;
                case 'photo':
                    if($photo){
                        if(in_array($photo->getClientMimeType(), $allowedTypes)){
                            $filename = md5(time() . rand(0, 9999)) . '.jpg'; 
                            $destPath = public_path('/media/uploads');                            

                            $manager = new ImageManager(new Driver());

                            $image = $manager->decodePath($photo->getPathname());
                            $image = $image->scale(width: 800);
                            $image->save($destPath . '/' . $filename);

                            $body = $filename;
                        }else{
                            return ['error' => 'Unsupported file.'];
                        }
                    }else{
                        return ['error'=> "File not sent."];                        
                    }
                break;
                default:
                    return ['error' => "This post type does not exist."];                    
                break;
            }

            if($body){
                $newPost = new Post();
                $newPost->id_user = $user->id;
                $newPost->type = $type;
                $newPost->created_at = date('Y-m-d H:i:s');
                $newPost->body = $body;
                $newPost->save();
            }

        }else{
            return ['error' => 'Data not sent.'];
        }

        return $array;
    }

    public function read(Request $request){
        /** @var User $loggedUser */   
        $loggedUser = Auth::guard('api')->user();      
        $array = ['error'=>''];

        $page = intval($request->input('page'));
        $perPage = 12;

        // 1. Get the list of users I follow (including myself).
        $users = [];
        $userList = UserRelation::where('user_from', $loggedUser->id)->get();
        foreach($userList as $userItem){
            $users[] = $userItem->user_to;
        }
        $users[] = $loggedUser->id;

        // 2. Get the posts from these people, SORTED BY DATE.
        $postList = Post::whereIn('id_user', $users)
        ->orderBy('created_at', 'desc')
        ->offset($page * $perPage)
        ->limit($perPage)
        ->get();

        $total = Post::whereIn('id_user', $users)->count();
        $pageCount = ceil($total / $perPage);

        // 3. Fill in the additional information.
        $posts = $this->_postListToObject($postList, $loggedUser->id);

        $array['posts'] = $posts;
        $array['pageCount'] = $pageCount;
        $array['currentPage'] = $page;

        return $array;
    }

    public function userFeed(Request $request, $id = false){
        /** @var User $loggedUser */   
        $loggedUser = Auth::guard('api')->user();      
        $array = ['error'=>''];

        if($id == false){
            $id = $loggedUser->id;
        }

        $page = intval($request->input('page'));
        $perPage = 12;

        // Get the user's posts, SORTED BY DATE.
        $postList = Post::where('id_user', $id)
        ->orderBy('created_at', 'desc')
        ->offset($page * $perPage)
        ->limit($perPage)
        ->get();

        $total = Post::where('id_user', $id)->count();
        $pageCount = ceil($total / $perPage);

        // Fill in the additional information.
        $posts = $this->_postListToObject($postList, $loggedUser->id);

        $array['posts'] = $posts;
        $array['pageCount'] = $pageCount;
        $array['currentPage'] = $page;

        return $array;
    }

    private function _postListToObject($postList, $loggedId){
        foreach($postList as $postKey => $postItem){
            // Verify if the post is mine            
            if($postItem->id_user == $loggedId){
                $postList[$postKey]['mine'] = true;
            }else{                
                $postList[$postKey]['mine'] = false;
            }

            // Fill in user information
            $userInfo = User::find($postItem->id_user);
            $userInfo['avatar'] = url('media/avatars/'.$userInfo->avatar);
            $userInfo['cover'] = url('media/covers/'.$userInfo->cover);
            $postList[$postKey]['user'] = $userInfo;
            if ($postItem->type == 'photo') {
            $postList[$postKey]['body'] = url('media/uploads/' . $postItem->body);
            }

            // Fill in LIKE information
            $likes = PostLike::where('id_post', $postItem->id)->count();
            $postList[$postKey]['likeCount'] = $likes;

            $isLiked = PostLike::where('id_post', $postItem->id)
            ->where('id_user', $loggedId)
            ->count();
            $postList[$postKey]['liked'] = ($isLiked > 0) ? true : false;

            // Fill in COMMENTS information
            $comments = PostComment::where('id_post', $postItem->id)->get();
            foreach($comments as $commentKey => $comment){
                $user = User::find($comment->id_user);
                $user['avatar'] = url('media/avatars/'.$user->avatar);
                $user['cover'] = url('media/covers/'.$user->cover);
                $comments[$commentKey]["user"] = $user;
            }
            $postList[$postKey]['comments'] = $comments;
        }

        return $postList;
    }

    public function userPhotos(Request $request, $id = false){
         /** @var User $loggedUser */   
        $loggedUser = Auth::guard('api')->user();      
        $array = ['error'=>''];

        if($id == false){
            $id = $loggedUser->id;
        }

        $page = intval($request->input('page'));
        $perPage = 2;

        // Get the user's photos, SORTED BY DATE.
        $postList = Post::where('id_user', $id)
        ->where('type', 'photo')
        ->orderBy('created_at', 'desc')
        ->offset($page * $perPage)
        ->limit($perPage)
        ->get();

        $total = Post::where('id_user', $id)
        ->where('type', 'photo')
        ->count();
        $pageCount = ceil($total / $perPage);

        // Fill in the additional information.
        $posts = $this->_postListToObject($postList, $loggedUser->id);

        $array['posts'] = $posts;
        $array['pageCount'] = $pageCount;
        $array['currentPage'] = $page;

        return $array;
    }
}
