<?php

namespace App\Http\Controllers;

use App\Events\CommentCountChanged;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Comment;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;
use Flash;

class PostController extends AppBaseController
{
    /** @var PostRepository $postRepository*/
    private $postRepository;

    public function __construct(PostRepository $postRepo)
    {
        $this->postRepository = $postRepo;
    }

    /**
     * Display a listing of the Post.
     */
    public function index(Request $request)
    {     
        CommentCountChanged ::dispatch(Comment::count());   
        $posts = Post::with('category', 'author:id,name')->paginate(10);
        return view('posts.index')
            ->with('posts', $posts);
    }

    /**
     * Show the form for creating a new Post.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created Post in storage.
     */
    public function store(CreatePostRequest $request)
    {
        $input = $request->all();

        $post = $this->postRepository->create($input);

        Flash::success('Post saved successfully.');

        return redirect(route('posts.index'));
    }

    /**
     * Display the specified Post.
     */
    public function show($id)
    {
        $post = Post::with('author:id,name', 'category')->find($id);
        $comments = Comment::where('post_id', $id)->with('author:id,name')->paginate(10);
        if (empty($post)) {
            Flash::error('Post not found');
            return redirect(route('posts.index'));
        }
        // dd($post);

        return view('posts.show')
        ->with('post', $post)
        ->with('comments', $comments)
        ->with('post_id', $id);
    }

    /**
     * Show the form for editing the specified Post.
     */
    public function edit($id)
    {
        $post = $this->postRepository->find($id);

        if (empty($post)) {
            Flash::error('Post not found');

            return redirect(route('posts.index'));
        }

        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified Post in storage.
     */
    public function update($id, UpdatePostRequest $request)
    {
        $post = $this->postRepository->find($id);

        if (empty($post)) {
            Flash::error('Post not found');

            return redirect(route('posts.index'));
        }

        $post = $this->postRepository->update($request->all(), $id);

        Flash::success('Post updated successfully.');

        return redirect(route('posts.index'));
    }

    /**
     * Remove the specified Post from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $post = $this->postRepository->find($id);

        if (empty($post)) {
            Flash::error('Post not found');

            return redirect(route('posts.index'));
        }

        $this->postRepository->delete($id);

        Flash::success('Post deleted successfully.');

        return redirect(route('posts.index'));
    }
}
