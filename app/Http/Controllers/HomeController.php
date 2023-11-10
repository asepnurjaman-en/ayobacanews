<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use App\Models\Info;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ArticleComment;
use App\Models\ArticleRunning;
use App\Models\ArticleCategory;
use App\Models\ArticleHighlight;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
	public function home(): Response
	{
		$category = ArticleCategory::with(['articles' => function ($query) {
			$query->publish();
		}])->publish()->orderBy('index_sort', 'ASC')->get();
		$running = ArticleRunning::with('article')->get();
		$highlight = ArticleHighlight::with('article')->has('article')->get();
		$trending = Article::withCount('comment')->publish()->orderBy('comment_count', 'DESC')->limit(10)->get();
		$info = Info::whereType('home-ad')->first();
		$info->ad = json_decode($info->content, true);
		$ad_1 = Ads::select('url', 'file');
		if ($info->ad['1'] == 'random') :
			$ad_1 = $ad_1->inRandomOrder();
		elseif ($info->ad['1'] > 0) :
			$ad_1 = $ad_1->whereId($info->ad['1']);
		elseif ($info->ad['1'] == null) :
			$ad_1 = $ad_1->whereId(0);
		endif;
		$ad_1 = $ad_1->first();
		$ad_2 = Ads::select('url', 'file');
		if ($info->ad['2'] == 'random') :
			$ad_2 = $ad_2->inRandomOrder();
		elseif ($info->ad['2'] > 0) :
			$ad_2 = $ad_2->whereId($info->ad['2']);
		elseif ($info->ad['2'] == null) :
			$ad_2 = $ad_2->whereId(0);
		endif;
		$ad_2 = $ad_2->first();
		$ad_3 = Ads::select('url', 'file');
		if ($info->ad['3'] == 'random') :
			$ad_3 = $ad_3->inRandomOrder();
		elseif ($info->ad['3'] > 0) :
			$ad_3 = $ad_3->whereId($info->ad['3']);
		elseif ($info->ad['3'] == null) :
			$ad_3 = $ad_3->whereId(0);
		endif;
		$ad_3 = $ad_3->first();
		$ad_4 = Ads::select('url', 'file');
		if ($info->ad['4'] == 'random') :
			$ad_4 = $ad_4->inRandomOrder();
		elseif ($info->ad['4'] > 0) :
			$ad_4 = $ad_4->whereId($info->ad['4']);
		elseif ($info->ad['4'] == null) :
			$ad_4 = $ad_4->whereId(0);
		endif;
		$ad_4 = $ad_4->first();
		$ad_5 = Ads::select('url', 'file');
		if ($info->ad['5'] == 'random') :
			$ad_5 = $ad_5->inRandomOrder();
		elseif ($info->ad['5'] > 0) :
			$ad_5 = $ad_5->whereId($info->ad['5']);
		elseif ($info->ad['5'] == null) :
			$ad_5 = $ad_5->whereId(0);
		endif;
		$ad_5 = $ad_5->first();
		$ad_6 = Ads::select('url', 'file');
		if ($info->ad['6'] == 'random') :
			$ad_6 = $ad_6->inRandomOrder();
		elseif ($info->ad['6'] > 0) :
			$ad_6 = $ad_6->whereId($info->ad['6']);
		elseif ($info->ad['6'] == null) :
			$ad_6 = $ad_6->whereId(0);
		endif;
		$ad_6 = $ad_6->first();

		return response()->view('home', compact('category', 'running', 'highlight', 'trending', 'ad_1', 'ad_2', 'ad_3', 'ad_4', 'ad_5', 'ad_6'));
	}

	public function contact(): Response
	{
		$running = ArticleRunning::with('article')->get();
		$info = Info::whereType('contact-ad')->first();
		$info->ad = json_decode($info->content, true);
		$ad_1 = Ads::select('url', 'file');
		if ($info->ad['1'] == 'random') :
			$ad_1 = $ad_1->inRandomOrder();
		elseif ($info->ad['1'] > 0) :
			$ad_1 = $ad_1->whereId($info->ad['1']);
		elseif ($info->ad['1'] == null) :
			$ad_1 = $ad_1->whereId(0);
		endif;
		$ad_1 = $ad_1->first();
		$ad_2 = Ads::select('url', 'file');
		if ($info->ad['2'] == 'random') :
			$ad_2 = $ad_2->inRandomOrder();
		elseif ($info->ad['2'] > 0) :
			$ad_2 = $ad_2->whereId($info->ad['2']);
		elseif ($info->ad['2'] == null) :
			$ad_2 = $ad_2->whereId(0);
		endif;
		$ad_2 = $ad_2->first();

		return response()->view('contact', compact('running', 'ad_1', 'ad_2'));
	}

	public function info(string $slug): Response
	{
		$info = Info::where('type', $slug)->first();
		$running = ArticleRunning::with('article')->get();

		return response()->view('other', compact('info', 'running'));
	}

	public function profile(): Response
	{
		$info = Info::where('type', 'profile')->first();
		$running = ArticleRunning::with('article')->get();

		return response()->view('profile', compact('info', 'running'));
	}

	public function category(string $slug): Response
	{
		// $article_category = ArticleCategory::whereSlug($slug)->first();
		$category = ArticleCategory::with(['articles' => function ($query) {
			$query->publish()->latest()->first();
		}])->whereSlug($slug)->publish()->first();
		$category->ad = json_decode($category->ad_setup, true);
		$running = ArticleRunning::with('article')->get();
		$highlight = ArticleHighlight::with('article')->has('article')->get();
		$trending = Article::withCount('comment')->publish()->orderBy('comment_count', 'DESC')->limit(5)->get();
		$active = $category->id;
		$ad_1 = Ads::select('url', 'file');
		if ($category->ad['1'] == 'random') :
			$ad_1 = $ad_1->inRandomOrder();
		elseif ($category->ad['1'] > 0) :
			$ad_1 = $ad_1->whereId($category->ad['1']);
		elseif ($category->ad['1'] == null) :
			$ad_1 = $ad_1->whereId(0);
		endif;
		$ad_1 = $ad_1->first();
		$ad_2 = Ads::select('url', 'file');
		if ($category->ad['2'] == 'random') :
			$ad_2 = $ad_2->inRandomOrder();
		elseif ($category->ad['2'] > 0) :
			$ad_2 = $ad_2->whereId($category->ad['2']);
		elseif ($category->ad['2'] == null) :
			$ad_2 = $ad_2->whereId(0);
		endif;
		$ad_2 = $ad_2->first();
		$ad_3 = Ads::select('url', 'file');
		if ($category->ad['3'] == "random") :
			$ad_3 = $ad_3->inRandomOrder();
		elseif ($category->ad['3'] > 0) :
			$ad_3 = $ad_3->whereId($category->ad['3']);
		elseif ($category->ad['3'] == null) :
			$ad_3 = $ad_3->whereId(0);
		endif;
		$ad_3 = $ad_3->first();
		$ad_4 = Ads::select('url', 'file');
		if ($category->ad['4'] == 'random') :
			$ad_4 = $ad_4->inRandomOrder();
		elseif ($category->ad['4'] > 0) :
			$ad_4 = $ad_4->whereId($category->ad['4']);
		elseif ($category->ad['4'] == null) :
			$ad_4 = $ad_4->whereId(0);
		endif;
		$ad_4 = $ad_4->first();

		return response()->view('category', compact('category', 'running', 'highlight', 'trending', 'active', 'ad_1', 'ad_2', 'ad_3', 'ad_4'));
	}

	public function category_load(Request $request, string $category): mixed
	{
		$except = Article::select('id')->where('article_category_id', $category)->publish()->latest()->first();
		$news = Article::where('article_category_id', $category)->where('id', '!=', $except->id)->publish()->latest()->paginate(10);
		$boxes = "";
		if ($request->ajax()) :
			foreach ($news as $item) :
				$boxes .= "<div class=\"news-list-md p-3\"><div class=\"d-flex\">";
				$boxes .= "<div class=\"image me-2\">";
				if ($item->file_type=='video') :
					$boxes .= "<img src=\"https://img.youtube.com/vi/{$item->file}/hqdefault.jpg\" class=\"img-fluid rounded-top\" loading=\"lazy\">";
				elseif ($item->file_type=='image') :
					$boxes .= "<img src=\"".url('storage/md/'.$item->file)."\" class=\"img-fluid rounded-top\" loading=\"lazy\">";
				endif;
				$boxes .= "</div>";
				$boxes .= "<div class=\"desc\">";
				$boxes .= "<h3>".anchor(text:$item->title, href:route('l.news', $item->slug))."</h3>";
				$boxes .= "<div class=\"d-flex align-items-center py-1 small text-muted\">";
				$boxes .= "<span class=\"me-2\">".date_stat($item->datetime)."</span>";
				$boxes .= "<span>{$item->author}</span>";
				$boxes .= "</div>";
				$boxes .= "<blockquote>{$item->description}</blockquote>";
				$boxes .= "</div>";
				$boxes .= "</div></div>";
			endforeach;
		endif;
		return $boxes;
	}

	public function news_detail(string $slug): Response|RedirectResponse
	{
		$read = Article::where('slug', $slug)->publish()->firstorFail();
		$read->related = json_decode($read->related ?? "[]", true);
		$read->ad = json_decode($read->ad_setup, true);
		$related = Article::whereIn('id', $read->related)->publish()->get();
		$running = ArticleRunning::with('article')->get();
		$highlight = ArticleHighlight::with('article')->has('article')->get();
		$trending = Article::withCount('comment')->publish()->orderBy('comment_count', 'DESC')->limit(5)->get();
		$active = $read->article_category_id;
		$ad_1 = Ads::select('url', 'file');
		if ($read->ad['1'] == 'random') :
			$ad_1 = $ad_1->inRandomOrder();
		elseif ($read->ad['1'] > 0) :
			$ad_1 = $ad_1->whereId($read->ad['1']);
		elseif ($read->ad['1'] == null) :
			$ad_1 = $ad_1->whereId(0);
		endif;
		$ad_1 = $ad_1->first();
		$ad_2 = Ads::select('url', 'file');
		if ($read->ad['2'] == 'random') :
			$ad_2 = $ad_2->inRandomOrder();
		elseif ($read->ad['2'] > 0) :
			$ad_2 = $ad_2->whereId($read->ad['2']);
		elseif ($read->ad['2'] == null) :
			$ad_2 = $ad_2->whereId(0);
		endif;
		$ad_2 = $ad_2->first();
		$ad_3 = Ads::select('url', 'file');
		if ($read->ad['3'] == "random") :
			$ad_3 = $ad_3->inRandomOrder();
		elseif ($read->ad['3'] > 0) :
			$ad_3 = $ad_3->whereId($read->ad['3']);
		elseif ($read->ad['3'] == null) :
			$ad_3 = $ad_3->whereId(0);
		endif;
		$ad_3 = $ad_3->first();
		$ad_4 = Ads::select('url', 'file');
		if ($read->ad['4'] == 'random') :
			$ad_4 = $ad_4->inRandomOrder();
		elseif ($read->ad['4'] > 0) :
			$ad_4 = $ad_4->whereId($read->ad['4']);
		elseif ($read->ad['4'] == null) :
			$ad_4 = $ad_4->whereId(0);
		endif;
		$ad_4 = $ad_4->first();

		return response()->view('news', compact('read', 'related', 'running', 'highlight', 'trending', 'active', 'ad_1', 'ad_2', 'ad_3', 'ad_4'));
	}

	public function news_detail_comment_show(string $slug): JsonResponse
	{
		$article = Article::select('id')->whereSlug($slug)->first();
		$comments = ArticleComment::where('article_id', $article->id)->with('user')->latest()->where('publish', 'publish');
		if (Auth::check() && Auth::user()->role=='reader-2') :
			$comments = $comments->orWhere('user_id', Auth::user()->id);
		endif;
		$comments = $comments->get();

		return response()->json($comments);
	}

	public function news_detail_comment(Request $request, string $slug): JsonResponse
	{
		$this->validate($request, [
			'comment' => 'required|max:500'
		],
		[
			'comment.required' => '* Isi komentar kamu.',
			'comment.max' => '* Batas karakter tercapai.',
		]);
		$article = Article::select('id')->whereSlug($slug)->first();
		if (Auth::check() && Auth::user()->role=='reader-2') :
			$comment = ArticleComment::create([
				'comment' => $request->comment,
				'publish' => 'draft',
				'article_id' => $article->id,
				'user_id' => Auth::user()->id,
				'ip_addr' => $_SERVER['REMOTE_ADDR']
			]);
			$response = "sent";
		else :
			Session::put('mark-page', $slug);
			$response = "auth";
		endif;

		return response()->json(['status'=>$response]);
	}

	public function news_search(Request $request): Response
	{
		$keyword = $request->q ?? null;
		$news = Article::where('title', 'LIKE', "%{$keyword}%")->publish()->get();
		$running = ArticleRunning::with('article')->get();
		$highlight = ArticleHighlight::with('article')->has('article')->get();
		$trending = Article::withCount('comment')->publish()->orderBy('comment_count', 'DESC')->limit(5)->get();

		return response()->view('search', compact('news', 'running', 'highlight', 'trending'));
	}

	public function news_tag(string $tag): Response
	{
		$news = Article::where('tags', 'LIKE', "%{$tag}%")->publish()->get();
		$running = ArticleRunning::with('article')->get();
		$highlight = ArticleHighlight::with('article')->has('article')->get();
		$trending = Article::withCount('comment')->publish()->orderBy('comment_count', 'DESC')->limit(5)->get();

		return response()->view('search', compact('news', 'running', 'highlight', 'trending'));
	}
}
