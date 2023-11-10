<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ArticleHighlight;
use Illuminate\Http\JsonResponse;

class ArticleHighlightController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
	}
    
    public function highlight_option(): Response
	{
		$highlight = ArticleHighlight::with('article')->get();
		$data = [
			'title' => 'berita highlight',
            'form' => ['action' => route('article.update', 0), 'class' => 'form-update']
		];

		return response()->view('panel.article.highlight', compact('data', 'highlight'));
	}

	public function highlight_update(Request $request): JsonResponse
	{
		$this->validate($request, [
			'id'		=> 'required',
			'article_id'=> 'required'
		],
		[
			'required'	=> 'Pilih salah satu artikel yang disediakan.',
		]);
		ArticleHighlight::findOrFail($request->id)->update(['article_id'=>($request->article_id>0) ? $request->article_id : null]);

		return response()->json([]);
	}
}
