<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Validator;

class PostController extends Controller
{
    //
    public function index()
    {
        $posts = auth()->user()->posts;
        $data = $posts->toArray();

        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'Posts retrieved successfully.'
        ];

        return response()->json($response, 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => 'Validation Error.',
                'message' => $validator->errors()
            ];
            return response()->json($response, 404);
        }

        $post = auth()->user()->posts()->create($input);
        $data = $post->toArray();

        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'Post stored successfully.'
        ];

        return response()->json($response, 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);

        if (is_null($post)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'Post not found.'
            ];
            return response()->json($response, 404);
        }

        $data = $post->toArray();
        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'Post retrieved successfully.'
        ];

        return response()->json($response, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if (is_null($post)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'Post not found.'
            ];
            return response()->json($response, 404);
        }

        if(auth()->id()!==$post->user_id){
            return response()->json('forbidden', 403);
        }
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'title' => 'required',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => 'Validation Error.',
                'message' => $validator->errors()
            ];
            return response()->json($response, 404);
        }

        $post->update([
            'title'=>$input['title'],
            'content'=>$input['content']
        ]);

        $data = $post->toArray();

        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'Post updated successfully.'
        ];

        return response()->json($response, 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        if (is_null($post)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'Post not found.'
            ];
            return response()->json($response, 404);
        }

        if(auth()->id()!==$post->user_id){
            return response()->json('forbidden', 403);
        }
        $post->delete();
        $data = $post->toArray();

        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'Post deleted successfully.'
        ];

        return response()->json($response, 200);
    }
}
