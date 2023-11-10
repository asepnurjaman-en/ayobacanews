<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ArticleRunning;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class ArticleRunningController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
	}
    
    public function select_runningtext(): Response
	{
        $article = ArticleRunning::with('article')->paginate(10);
		$data = [
			'title' => 'berita running text',
            'form'	=> ['action' => route('article.running.store'), 'class' => 'form-insert']
		];

		return response()->view('panel.article.running', compact('data', 'article'));
	}

    public function store_runningtext(Request $request): JsonResponse
    {
		$column = [
            'ip_addr'	=> $_SERVER['REMOTE_ADDR'],
			'user_id'	=> Auth::user()->id
        ];
		if ($request->input('custom_running_text')=='custom') :
			$column['content'] = $request->content;
			$this->validate($request, [
				'content'	=> 'required'
			],
			[
				'required'	=> '<code>:attribute</code> harus diisi.',
			]);
		else :
			$column['article_id'] = $request->news;
			$this->validate($request, [
				'news'	=> 'required'
			],
			[
				'required'	=> '<code>:attribute</code> harus diisi.',
			]);
		endif;
        ArticleRunning::create($column);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "Berita telah ditambahkan"],
			'redirect'	=> ['type' => 'reload']
		]);
    }

    public function delete_runningtext(int $id): RedirectResponse
    {
        ArticleRunning::whereId($id)->delete();
        return redirect()->route('article.running');
    }
}
