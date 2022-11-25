<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Controllers\Api\Auth;

use Validator;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return response()->json([
            "success" => true,
            "message" => "Product List",
            "data" => $posts
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'post_title' => 'required',
            'post_excerpt' => 'required',
            'post_content' => 'required',
            'featured_image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        $user = PassportAuthController::userInfo();
        if (auth()->check()){
            $id = auth()->user()->getId();
            $input['post_author'] = $id;
        }
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()->messages());
        }

        $post = Post::create($input);
        $image  = $request->featured_image;
        $imagename = $image->getClientOriginalName() . time() .'.'. $image->getClientOriginalExtension();
        $request->featured_image->move('post',$imagename);
        $post->featured_image()->create(['path'=> $imagename]);
        return response()->json([
            "success" => true,
            "message" => "Product created successfully.",
            "data" => $post
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        if (is_null($post)) {
            return $this->sendError('Post not found.');
        }
        $post->featured_image;
        return response()->json([
            "success" => true,
            "message" => "Post retrieved successfully.",
            "data" => $post
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'post_title' => 'required',
            'post_excerpt' => 'required',
            'post_content' => 'required',
            'featured_image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.',  $validator->errors()->messages());
        }
        $post->post_title = $input['post_title'];
        $post->post_excerpt = $input['post_excerpt'];
        $post->post_content = $input['post_content'];
        $post->post_status = $input['post_status'];
        $image  = $request->featured_image;
        if($image != null) {
            $imagename = $image->getClientOriginalName() . time() . '.' . $image->getClientOriginalExtension();
            $request->featured_image->move('post', $imagename);
            $post->featured_image()->create(['path' => $imagename]);
        }
        $post->save();
        return response()->json([
            "success" => true,
            "message" => "Post updated successfully.",
            "data" => $post
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)  {
        $post->delete();
        return response()->json([
            "success" => true,
            "message" => "Post deleted successfully.",
            "data" => $post
        ]);
    }

    /**
     * Show error.
     *
     * @param  string  $error
     * @return \Illuminate\Http\Response
     */
    public function sendError($text, $errors = array()){
        return response()->json([
            "success" => false,
            "message" => $text,
            "list_error" => $errors
        ]);
    }

    /**
     * Show error.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function list_posts_user($user_id){
        $post = Post::where('post_author','=',$user_id)->get();
        if ($post->isEmpty()) {
            return $this->sendError('Post not found.');
        }
        return response()->json([
            "success" => true,
            "message" => "Post retrieved successfully.",
            "data" => $post
        ]);
    }

    public function list_posts_users_follow(){
        $current_user_id = auth()->user()->getId();
        $user = User::find($current_user_id);
        $list_follows = $user->follows;
        $user_follow = [];
        foreach ($list_follows as $follow){
            $user_follow[] = $follow->user_id;
        }
        $post = Post::whereIn('post_author',$user_follow)->get();
        if ($post->isEmpty()) {
            return $this->sendError('Post not found.');
        }
        return response()->json([
            "success" => true,
            "message" => "Post retrieved successfully.",
            "data" => $post
        ]);
    }
}
