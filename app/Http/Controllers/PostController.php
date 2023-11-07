<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use \Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Reaction;
use App\Models\View;
use App\Models\Comment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Session;

class PostController extends Controller
{
    public function getAllPosts(Request $request){
        $post = new Post();
        $all_posts = $post::all();

        return response()->json([
            'status' => true,
            'statusCode' => 200,
            'message' => 'Post Fetched Successfully',
            'data' => $all_posts
        ], 200);
    }

    public function createPost(Request $request){
        try{
          
            $rules = [
                'user_id'  =>  ['required'],
                'subject'  =>  ['required'],
                'categories' =>  ['required'],
                'sub_categories' =>  ['required'],
                'sub_categories_child' =>  ['required'],
                'content'  =>  ['required'],
                'file_type'     =>  ['required'],
            ];
        
            $validator = Validator::make($request->all(), $rules);
        
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 422); 
            }

            $post = new Post();

            if (
                Post::where('subject', $request->subject)->exists() or 
                Post::where('content', $request->content)->exists()
            ) {
                return response()->json(['message' => 'No duplicate post, post already exist'], 400);
            } 

            $response = $post::create([
                'user_id'  => $request->user_id,
                'subject'  => $request->subject,
                'content'  => $request->content,
                'file'     => $request->file_type,
                'sub_categories_child'  => $request->sub_categories_child,
                'sub_categories'     => $request->sub_categories,
                'categories' => $request->categories,
            ]);

            return response()->json(['message' => 'Post created successfully'], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occured while creating Post',
                'error Code' => $e
            ], 500);
        }
    }

    public function react(Request $request){

        try{
           
            $rules = [
                'user_id'  =>  ['required'],
                'react_id' =>  ['required'],
                "post_id" =>  ['required'],
                'parent_comment_id'=>  [],
                'ref_type'=>  ['required']
            ];
        
            $validator = Validator::make($request->all(), $rules);
        
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 422); 
            }

            $reaction = new Reaction();
            $post_to_react_to = new Post();
            

            $res = $reaction::create([
                'user_id'  =>  $request->user_id,
                'react_id' =>  $request->react_id,
                "post_id" =>  $request->post_id,
                'parent_comment_id'=>  $request->parent_comment_id,
                'ref_type'=>  $request->ref_type
            ]);

            if($res){
                $post_to_react_to = Post::find($res->user_id);
               
                return response()->json([
                    'post'=> $post_to_react_to,
                    'message' => 'Reaction recorded successfully'
            ], 200);
            }
        } catch(Exception $e){
            return response()->json(['message' => 'An error occurred while reacting'], 500);
        }
    }

    public function view(Request $request){
        $rules = [
            'user_id'  =>  ['required'],
            'ref_id'  =>  ['required'],
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422); 
        }

        $view = new View();
        $post = new Post();
        $res = $view::create([
            'user_id'=>$request->user_id,
            'ref_id'=>$request->ref_id
        ]);

        if($res){
            return response()->json(['message' => 'View recorded successfully'], 200);


        }

    }

    public function comment(Request $request){
        $rules = [
            'user_id'=> ['required'],
            'comment'=> ['required'],
            'post_id'=> [],
            'parent_comment_id'=> [],
            'ref_type'=> ['required']
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422); 
        }

        $view = new Comment();
        $res = $view::create([
            'user_id'=>$request->user_id,
            'comment'=> $request->comment,
            'post_id'=> $request->post_id,
            'parent_comment_id'=> $request->parent_comment_id,
            'ref_type'=>$request->ref_type
        ]);

        if($res){
            $post_to_react_to = Post::find($res->user_id);
           
            return response()->json([
                'post'=> $post_to_react_to,
                'message' => 'Comment recorded successfully'
        ], 200);
        }
    }
}
