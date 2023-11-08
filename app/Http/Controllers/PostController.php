<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    public function getOrderedComments($postId, $parentId = null)
    {
        $comments = Comment::where('post_id', $postId)
            ->where('parent_comment_id', $parentId)
            ->orderBy('created_at', 'asc')
            ->get();

        $reactions = Reaction::all();

        $nestedComments = [];
        $nestedReaction = [];

        $commentCount = count($comments);
        $reactionCount = count($reactions);

        if ($commentCount > 0) {
            $comment = $comments->get(0);
            $reaction = $reactions->get(0);
            $nestedComments[] = [
                'comment' => $comment,
                'reaction' => $reaction,
                'replies' => $this->getOrderedComments($comment->parent_comments_id, $comment->id),

            ];

            if ($commentCount > 1) {
                for ($i = 1; $i < $commentCount; $i++) { 
                        for ($j = 1; $j < $reactionCount; $j++) {
                            if ($reactions->get($j)->parent_comment_id === $postId) {
                                $nestedReaction[] = $reactions->get($j);
                            }
                        }
                    

                    $nestedComments[] = [
                        'comment' => $comments[$i],
                        'reaction' => $nestedReaction,
                        'replies' => $this->getOrderedComments($comments->get($i)->parent_comments_id, $comments->get($i)->id),
                    ];
                }
            }
        }

        return $nestedComments;
    }
    public function getAllPosts(Request $request)
    {
        $post_response = [];

        $all_posts = Post::all();

        foreach ($all_posts as $value) {
            $user_val = User::find($value->user_id);
            $view_val = View::where('ref_id', $value->id)->get();

            $post = [
                'post' => [
                    'post_data' => [
                        'id' => $value->id,
                        'name' => $value->subject,
                        'content' => $value->content,
                    ],
                    'post_user_data' => [
                        'id' => $user_val->id,
                        "first_name" => $user_val->first_name,
                        "last_name" => $user_val->last_name,
                        "email" => $user_val->email,
                        "country" => $user_val->country,
                        "followers" => $user_val->followers,
                        "post" => $user_val->post,
                        "account_type" => $user_val->account_type,
                        "active_atatus" => $user_val->active_atatus,
                        "state" => $user_val->state,
                    ],
                    'post_comment_data' => $this->getOrderedComments($value->id),
                    'post_view_data' => $view_val,
                ],
            ];
            $view_val = Reaction::ll();

            array_push($post_response, $post);
        }
        return response()->json([
            'status' => true,
            'statusCode' => 200,
            'message' => 'Post Fetched Successfully',
            'data' => $post_response,
        ], 200);
    }

    public function createPost(Request $request)
    {
        // $fileUploadServiceProvider = new FileUploadServiceProvider();
        try {

            $rules = [
                'user_id' => ['required'],
                'subject' => ['required'],
                'categories' => ['required'],
                'sub_categories' => ['required'],
                'sub_categories_child' => ['required'],
                'content' => ['required'],
                'file_type' => ['required'],
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
                'user_id' => $request->user_id,
                'subject' => $request->subject,
                'content' => $request->content,
                'file' => $request->file_type,
                'sub_categories_child' => $request->sub_categories_child,
                'sub_categories' => $request->sub_categories,
                'categories' => $request->categories,
            ]);

            return response()->json(['message' => 'Post created successfully'], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occured while creating Post',
                'error Code' => $e,
            ], 500);
        }
    }

    public function react(Request $request)
    {

        try {

            $rules = [
                'user_id' => ['required'],
                'react_id' => ['required'],
                "post_id" => [],
                'parent_comment_id' => [],
                'ref_type' => ['required'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 422);
            }

            $reaction = new Reaction();
            $post_to_react_to = new Post();

            $res = $reaction::create([
                'user_id' => $request->user_id,
                'react_id' => $request->react_id,
                "post_id" => $request->post_id,
                'parent_comment_id' => $request->parent_comment_id,
                'ref_type' => $request->ref_type,
            ]);

            if ($res) {
                $post_to_react_to = Post::find($res->user_id);

                return response()->json([
                    'post' => $post_to_react_to,
                    'message' => 'Reaction recorded successfully',
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while reacting'], 500);
        }
    }

    public function view(Request $request)
    {
        $rules = [
            'user_id' => ['required'],
            'ref_id' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }

        $view = new View();
        $post = new Post();
        $res = $view::create([
            'user_id' => $request->user_id,
            'ref_id' => $request->ref_id,
        ]);

        if ($res) {
            return response()->json(['message' => 'View recorded successfully'], 200);

        }

    }

    public function comment(Request $request)
    {
        $rules = [
            'user_id' => ['required'],
            'comment' => ['required'],
            'post_id' => [],
            'parent_comment_id' => [],
            'ref_type' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }

        $view = new Comment();
        $res = $view::create([
            'user_id' => $request->user_id,
            'comment' => $request->comment,
            'post_id' => $request->post_id,
            'parent_comment_id' => $request->parent_comment_id,
            'ref_type' => $request->ref_type,
        ]);

        if ($res) {
            $post_to_react_to = Post::find($res->user_id);

            return response()->json([
                'post' => $post_to_react_to,
                'message' => 'Comment recorded successfully',
            ], 200);
        }
    }
}
