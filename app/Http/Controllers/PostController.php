<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Post;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;
use DB;
use DataTables;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$posts = Post::with(['user', 'category', 'tags', 'comments'])->paginate(10);
        //return view('admin.posts.index', compact('posts'));
        return view('datatables.posts');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::pluck('name', 'id')->all();
        $tags = Tag::pluck('name', 'name')->all();

        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        if($request->hasfile('image_url'))
        {
            $name = !empty($request->file('image_url')->getClientOriginalName()) ? $request->file('image_url')->getClientOriginalName() : 'default-acc.jpg';
            $request->file('image_url')->move(public_path().'/images/', strtolower($name));  
            $data = strtolower($name);
        }

        $post = Post::create([
            'image_url'   => $data,
            'title'       => $request->title,
            'post_body'   => $request->post_body,
            'category_id' => $request->category_id,
            'is_published' => $request->is_published
        ]);

        return redirect('/posts');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $post = $post->load(['user', 'category', 'tags', 'comments']);

        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::pluck('name', 'id')->all();
        $tags = Tag::pluck('name', 'name')->all();

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {

        if($request->hasfile('image_url'))
        {
            $name = !empty($request->file('image_url')->getClientOriginalName()) ? $request->file('image_url')->getClientOriginalName() : $post->image_url;
            $request->file('image_url')->move(public_path().'/images/', strtolower($name));  
            $data = strtolower($name);
        }

        $post->update([
            'image_url'   => !empty($data) ? $data : $post->image_url,
            'title'       => $request->title,
            'post_body'   => $request->post_body,
            'category_id' => $request->category_id,
            'is_published' => $request->is_published
        ]);

        return redirect('/posts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if($post->user_id != auth()->user()->id && auth()->user()->is_admin == false) {
            flash()->overlay("You can't delete other peoples post.");
            return redirect('/admin/posts');
        }

        $post->delete();
        flash()->overlay('Post deleted successfully.');

        return redirect('/admin/posts');
    }

    public function publish(Post $post)
    {
        $post->is_published = !$post->is_published;
        $post->save();
        flash()->overlay('Post changed successfully.');

        return redirect('/admin/posts');
    }


    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        $model = DB::table('mobile_posts')->join('mobile_categories', 'mobile_posts.category_id', '=', 'mobile_categories.id')
                 ->select(['mobile_categories.name', 'mobile_posts.title', 'mobile_posts.is_published', 'mobile_posts.id']);
        return DataTables::of($model)
            ->addColumn('action', function ($model) {
                return '<a href="posts/'.$model->id.'/edit" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i> &nbsp;&nbsp;Edit&nbsp;&nbsp; </a>
                <a href="posts/'.$model->id.'/edit" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i> Delete</a>';
            })
            ->rawColumns(['link', 'action'])
            ->toJson();
    }
}
