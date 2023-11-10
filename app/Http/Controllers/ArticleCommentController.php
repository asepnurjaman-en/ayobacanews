<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleComment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;

class ArticleCommentController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
	}
    
    /**
     * Display a listing of the resource.
     */
    public function index(string $id): Response
    {
        $comment = ArticleComment::where('publish', 'publish');
        $comment_waiting = ArticleComment::where('publish', 'draft');
        if ($id=='all') :
            $article = json_decode(json_encode(['title' => 'Semua']));
        else :
            $comment = $comment->whereArticleId($id);
            $comment_waiting = $comment_waiting->whereArticleId($id);
            $article = Article::whereId($id)->first();
        endif;
        $comment = $comment->get();
        $comment_waiting = $comment_waiting->get();
        $data = [
            'title' => 'komentar: '.$article->title,
        ];

        return response()->view('panel.article.comment', compact('data', 'article', 'comment', 'comment_waiting'));
    }

    public function update(string $id)
    {
        ArticleComment::whereId($id)->where('publish', 'draft')->update(['publish'=>'publish']);
        
        return redirect()->back();
    }

    public function destroy(string $id): RedirectResponse
    {
        ArticleComment::whereId($id)->delete();

        return redirect()->back();
    }
}
