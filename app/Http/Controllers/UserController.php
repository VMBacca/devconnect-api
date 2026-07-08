<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserRelation;
use DateTime;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\PostLike;
use Illuminate\Support\Facades\Hash;
use App\Models\PostComment;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    } 

    public function update(Request $request){
        /** @var User $loggedUser */   
        $loggedUser = Auth::guard('api')->user();
        $array = ['error'=>''];

        $name = $request->input('name');
        $email = $request->input('email');
        $birthdate = $request->input('birthdate');
        $city = $request->input('city');
        $work = $request->input('work');
        $password = $request->input('password');
        $password_confirm = $request->input('password_confirm');

        $user = $loggedUser; 
        $user['photoCount'] = Post::where('id_user', $user->id)
        ->where('type', 'photo')
        ->count();       

        // NAME
        if($name){
            $user->name = $name;
        }

        // E-MAIL
        if($email){
            if($email != $user->email){
                $emailExists = User::where('email', $email)->count();
                if($emailExists === 0){
                    $user->email = $email;
                }else{
                    $array['error'] = 'Email already exists.';
                    return $array;
                }
            }
        }

        // BIRTHDATE
        if($birthdate){
            if(strtotime($birthdate) === false){
                    $array['error'] = 'Invalid birthdate.';
                    return $array;
            }
            $user->birthdate = $birthdate;
        }

        // CITY
        if($city){
            $user->city = $city;
        }

        // WORK
        if($work){
            $user->work = $work;
        }

        // PASSWORD
        if($password && $password_confirm){
            if($password === $password_confirm){
                $user->password = Hash::make($password);
            }else{
                $array['error'] = 'The passwords do not match.';
                return $array;
            }
        }

        $user->save();

        return $array;
    }

    public function updateAvatar(Request $request)
    {
        $file = $request->file('avatar');

        if (!$file) {
            return ['error' => 'File not sent.'];
        }

        $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];

        if (!in_array($file->getClientMimeType(), $allowedTypes)) {
            return ['error' => 'Unsupported file.'];
        }

        $filename = md5(time() . rand(0, 9999)) . '.png';
        $destPath = public_path('/media/avatars');

        $manager = new ImageManager(new Driver());

        $image = $manager->decodePath($file->getPathname());

        $image = $image->cover(200, 200);

        $image->save($destPath . '/' . $filename);

        $user = Auth::guard('api')->user();
        $user->avatar = $filename;
        $user->save();

        return [
            'url' => url('/media/avatars/' . $filename),
            'error' => ''
        ];
    }

    public function updateCover(Request $request)
    {
        $file = $request->file('cover');

        if (!$file) {
            return ['error' => 'File not sent.'];
        }

        $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];

        if (!in_array($file->getClientMimeType(), $allowedTypes)) {
            return ['error' => 'Unsupported file.'];
        }

        $filename = md5(time() . rand(0, 9999)) . '.png';
        $destPath = public_path('/media/covers');

        $manager = new ImageManager(new Driver());

        $image = $manager->decodePath($file->getPathname());

        $image = $image->cover(850, 310);

        $image->save($destPath . '/' . $filename);

        $user = Auth::guard('api')->user();
        $user->cover = $filename;
        $user->save();

        return [
            'url' => url('/media/covers/' . $filename),
            'error' => ''
        ];
    }

    private function getAvatarUrl($avatar)
    {
        if(
            empty($avatar) ||
            !file_exists(public_path('media/avatars/'.$avatar))
        ){
            return url('media/avatars/avatar_default.png');
        }

        return url('media/avatars/'.$avatar);
    }

    public function read($id = false){
        /** @var User $loggedUser */   
        $loggedUser = Auth::guard('api')->user();
        $array = ['error'=>''];

        if($id){
            $info = User::find($id);
            if(!$info){
                return ['error' => 'Non-existent user.'];
            }
        }else {
            $info = $loggedUser;
        }

        $info['avatar'] = $this->getAvatarUrl($info->avatar);

        $info['cover'] = $this->getCoverUrl($info->cover);

        $info['me'] = ($info['id'] == $loggedUser->id) ? true :false;

        $dateFrom = new DateTime($info['birthdate']);
        $dateTo = new DateTime('today');
        $info['age'] = $dateFrom->diff($dateTo)->y;

        $info['followers'] = UserRelation::where('user_to', $info->id)->count();
        $info['following'] = UserRelation::where('user_from', $info->id)->count();

        $info['photoCount'] = Post::where('id_user', $info->id)
        ->where('type', 'photo')
        ->count();

        $photos = Post::where('id_user', $info->id)
            ->where('type', 'photo')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach($photos as $photoKey => $photo){
            $photos[$photoKey]['url'] =
                url('media/uploads/'.$photo->body);
        }

        $info['photos'] = $photos;

        $posts = Post::where('id_user', $info->id)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach($posts as $postKey => $post){

            $user = User::find($post->id_user);

            $user['avatar'] = $this->getAvatarUrl($user->avatar);
            $user['cover'] = $this->getCoverUrl($user->cover);

            $posts[$postKey]['user'] = $user;

            $posts[$postKey]['mine'] = ($post->id_user == $loggedUser->id);

            if($post->type == 'photo'){
                $posts[$postKey]['body'] =
                    url('media/uploads/'.$post->body);
            }

            $posts[$postKey]['likeCount'] =
                PostLike::where('id_post', $post->id)->count();

            $isLiked = PostLike::where('id_post', $post->id)
                ->where('id_user', $loggedUser->id)
                ->count();

            $posts[$postKey]['liked'] = ($isLiked > 0);

            $comments = PostComment::where('id_post', $post->id)->get();

            foreach ($comments as $commentKey => $comment) {

                $commentUser = User::find($comment->id_user);

                $commentUser['avatar'] = $this->getAvatarUrl($commentUser->avatar);
                $commentUser['cover'] = $this->getCoverUrl($commentUser->cover);

                $comments[$commentKey]['user'] = $commentUser;
            }

            $posts[$postKey]['comments'] = $comments;
        }

        $info['posts'] = $posts;

        $following = UserRelation::where('user_from', $info->id)->get();

        $followingList = [];

        foreach($following as $item){

            $user = User::find($item->user_to);

            if($user){
                $user['avatar'] = $this->getAvatarUrl($user->avatar);

                $followingList[] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $user->avatar
                ];
            }
        }

        $info['followingList'] = $followingList;

        $hasRelation = UserRelation::where('user_from', $loggedUser->id)
        ->where('user_to', $info->id)
        ->count();        
        $info['isFollowing'] = ($hasRelation > 0) ?true : false;

        $array['data'] = $info;

        return $array;
    }

    private function getCoverUrl($cover)
    {
        if (
            empty($cover) ||
            !file_exists(public_path('media/covers/'.$cover))
        ) {
            return url('media/covers/cover_default.png');
        }

        return url('media/covers/'.$cover);
    }

    public function follow($id){
        /** @var User $loggedUser */   
        $loggedUser = Auth::guard('api')->user();
        $array = ['error'=>''];

        if($id == $loggedUser->id){
            return ['error' => 'You cannot follow yourself.'];
        }

        $userExists = User::find($id);
        if ($userExists) {

            $relation = UserRelation::where('user_from', $loggedUser->id)
            ->where('user_to', $id)
            ->first();

            if($relation){
                // Unfollow
                $relation->delete();
            }else{
                // Follow
                $newRelation = new UserRelation();
                $newRelation->user_from = $loggedUser->id;
                $newRelation->user_to = $id;
                $newRelation->save();                
            }

        }else{
            return ['error' => 'User not found.'];
        }

        return $array;
    }

    public function followers($id){
        /** @var User $loggedUser */   
        $loggedUser = Auth::guard('api')->user();
        $array = ['error'=>''];

        $userExists = User::find($id);
        if ($userExists) {

            $followers = UserRelation::where('user_to', $id)->get();
            $following = UserRelation::where('user_from', $id)->get();

            $array['followers'] =[];
            $array['following'] =[];

            foreach($followers as $item){
                $user = User::find($item->user_from);
                $array['followers'][] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $this->getAvatarUrl($user->avatar)
                ];
            }

            foreach($following as $item){
                $user = User::find($item->user_to);

                $array['following'][] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => url('media/avatars/' .$user->avatar)
                ];
            }

        }else{
            return ['error' => 'User does not exist.'];
        }

        return $array;
    }
}
