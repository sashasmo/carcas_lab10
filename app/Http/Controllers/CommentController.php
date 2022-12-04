<?php

namespace App\Http\Controllers;

use App\Events\CommentAdded;
use App\Events\CommentCountChanged;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Comment;
use App\Models\Post;
use App\Repositories\CommentRepository;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class CommentController extends AppBaseController
{
    /** @var CommentRepository $commentRepository*/
    private $commentRepository;
    

    public function __construct(CommentRepository $commentRepo)
    {
        $this->commentRepository = $commentRepo;
    }

    /**
     * Display a listing of the Comment.
     */
    public function index(Request $request)
    {
        $comments = Comment::where('post_id', $request->post)->with('author:id,name')->paginate(10);
        $post_title = $comments[0]->post->title;
        return view('comments.index')
            ->with('comments', $comments)
            ->with('post_id', $request->post)
            ->with('post_name', $post_title);
    }

    /**
     * Show the form for creating a new Comment.
     */
    public function create(Request $request)
    {
        return view('comments.create')->with('post_id', $request->post);
    }

    /**
     * Store a newly created Comment in storage.
     */
    public function store(CreateCommentRequest $request)
    {
        $input = $request->all();
        $res = Arr::add($input, 'user_id', Auth::user()->id);
        $res = Arr::add($res, 'post_id', $request->post);
        $comment = Comment::create($res);
        $title = $comment->post->title;
        $author_email = $comment->post->author->email;
        Flash::success('Comment was added');
        CommentAdded::dispatch($author_email, $title);
        CommentCountChanged ::dispatch(Comment::count());
        return redirect(route('posts.show', ['post'=>$request->post]));
    }

    /**
     * Display the specified Comment.
     */
    public function show($id)
    {
        $comment = $this->commentRepository->find($id);

        if (empty($comment)) {
            Flash::error('Comment not found');

            return redirect(route('comments.index'));
        }

        return view('comments.show')->with('comment', $comment);
    }

    /**
     * Show the form for editing the specified Comment.
     */
    public function edit($id)
    {
        $comment = $this->commentRepository->find($id);

        if (empty($comment)) {
            Flash::error('Comment not found');

            return redirect(route('comments.index'));
        }

        return view('comments.edit')->with('comment', $comment);
    }

    /**
     * Update the specified Comment in storage.
     */
    public function update($id, UpdateCommentRequest $request)
    {
        $comment = $this->commentRepository->find($id);

        if (empty($comment)) {
            Flash::error('Comment not found');

            return redirect(route('comments.index'));
        }

        $comment = $this->commentRepository->update($request->all(), $id);

        Flash::success('Comment updated successfully.');

        return redirect( route('posts.show', $comment->post_id));
    }

    /**
     * Remove the specified Comment from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $comment = $this->commentRepository->find($id);

        if (empty($comment)) {
            Flash::error('Comment not found');

            return redirect( route('posts.index'));
        }

        $this->commentRepository->delete($id);
        CommentCountChanged ::dispatch(Comment::count());
        Flash::success('Comment deleted successfully.');

        return redirect( route('posts.show', $comment->post_id));
    }
}
