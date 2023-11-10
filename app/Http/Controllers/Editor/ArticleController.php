<?php

namespace App\Http\Controllers\Editor;

use App\Models\StrBox;
use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ArticleComment;
use App\Models\ArticleRunning;
use App\Models\ArticleCategory;
use App\Models\ArticleHighlight;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	/**
	 * Display a listing of the resource.
	 */
	public function index_publish(): Response
	{
		$data = [
			'title' => 'berita rilis',
			'list'	=> route('ae.article.list', ['publish'=>'publish', 'category'=>request()->category ?? null]),
            'create'=> ['action' => route('ae.article.create')],
			'delete'=> ['action' => route('ae.article.destroy', 0), 'message' => 'Hapus berita?']
		];

		return response()->view('editor.article.index', compact('data'));
	}

	public function index_draft(): Response
	{
		$data = [
			'title' => 'berita tersimpan',
			'list'	=> route('ae.article.list', ['publish'=>'draft', 'category'=>request()->category ?? null]),
            'create'=> ['action' => route('ae.article.create')],
			'delete'=> ['action' => route('ae.article.destroy', 0), 'message' => 'Hapus berita dari draft?']
		];

		return response()->view('editor.article.index', compact('data'));
	}

	public function index_schedule(): Response
	{
		$data = [
			'title' => 'berita dijadwal',
			'list'	=> route('ae.article.list', ['publish'=>'schedule', 'category'=>request()->category ?? null]),
            'create'=> ['action' => route('ae.article.create')],
			'delete'=> ['action' => route('ae.article.destroy', 0), 'message' => 'Hapus berita terjadwal?']
		];

		return response()->view('editor.article.index', compact('data'));
	}

	public function list(Request $request, string $publish, string $category = null): JsonResponse
	{
		$filter = false;
		if (!empty($category)) :
			$article_category = ArticleCategory::select('id')->whereSlug($category)->first();
			if ($article_category->id > 0) :
				$filter = true;
			endif;
		endif;
		$totalFilteredRecord = $totalDataRecord = $draw_val = "";
		$column = [0 => 'id', 1 => 'title', 2 => 'info', 3 => 'log'];
		$totalDataRecord = Article::wherePublish($publish)->where('user_id', Auth::user()->id);
		$totalDataRecord = ($filter===true) ? $totalDataRecord->where('article_category_id', $article_category->id) : $totalDataRecord;
		$totalDataRecord = $totalDataRecord->count();
		$totalFilteredRecord = $totalDataRecord;
		$limit_val	= $request->input('length');
		$start_val	= $request->input('start');
		if (empty($request->input('search.value'))) :
			$datatable = Article::wherePublish($publish)->where('user_id', Auth::user()->id)->withCount('comment');
			$datatable = ($filter===true) ? $datatable->where('article_category_id', $article_category->id) : $datatable;
			$datatable = $datatable->offset($start_val)->limit($limit_val)->orderBy('publish', 'DESC')->latest()->get();
		else :
			$search_text = $request->input('search.value');
			$datatable =  Article::wherePublish($publish)->where('title', 'LIKE', "%{$search_text}%")->where('user_id', Auth::user()->id)->withCount('comment');
			$datatable = ($filter===true) ? $datatable->where('article_category_id', $article_category->id) : $datatable;
			$datatable = $datatable->offset($start_val)->limit($limit_val)->get();
			$totalFilteredRecord = Article::wherePublish($publish)->where('title', 'LIKE', "%{$search_text}%")->where('user_id', Auth::user()->id);
			$totalFilteredRecord = ($filter===true) ? $totalFilteredRecord->where('article_category_id', $article_category->id) : $totalFilteredRecord;
			$totalFilteredRecord = $totalFilteredRecord->count();
		endif;
		$data_val = [];
		if (!empty($datatable)) :
			foreach ($datatable as $key => $item) :
				$publish = ['title' => Str::title($item->publish), 'publish' => 'bg-primary', 'draft' => 'bg-warning', 'schedule' => 'bg-warning'];
				$data_val[$key]['id'] = input_check(name:"check[]", value:$item->id, class:['form-check-input', 'check-row'], mode:'multiple');
				$data_val[$key]['title'] = anchor(text:$item->title, href:route('ae.article.edit', $item->id), class:['d-inline-block', 'fw-bold'])."<br/><span class=\"badge me-1\" style=\"font-size:10px;background-color:{$item->article_category->color}\">{$item->article_category->title}</span><span style=\"font-size:12px;\">Editor: <b>{$item->user->name}</b></span> | ".anchor(href:route('ae.article.comment', $item->id), text:"<i class=\"bx bx-comment\"></i> Lihat komentar ({$item->comment_count})", class:['text-primary', 'small', 'text-nowrap']);
				$data_val[$key]['info'] = null;
				$data_val[$key]['log'] = date_info($item->created_at);
			endforeach;
		endif;
		$draw_val = $request->input('draw');
		$get_json_data = ["draw" => intval($draw_val), "recordsTotal" => intval($totalDataRecord), "recordsFiltered" => intval($totalFilteredRecord), "data" => $data_val];

		return response()->json($get_json_data);
	}

	public function choice_list(Request $request): JsonResponse
	{
		$totalFilteredRecord = $totalDataRecord = $draw_val = "";
		$column = [0 => 'id', 1 => 'title', 2 => 'info', 3 => 'log'];
		$totalDataRecord = Article::publish()->count();
		$totalFilteredRecord = $totalDataRecord;
		$limit_val	= $request->input('length');
		$start_val	= $request->input('start');
		if (empty($request->input('search.value'))) :
			$datatable = Article::publish()->offset($start_val)->limit($limit_val)->orderBy('publish', 'DESC')->latest()->get();
		else :
			$search_text = $request->input('search.value');
			$datatable =  Article::publish()->where('title', 'LIKE', "%{$search_text}%")->offset($start_val)->limit($limit_val)->get();
			$totalFilteredRecord = Article::publish()->where('title', 'LIKE', "%{$search_text}%")->count();
		endif;
		$data_val = [];
		if (!empty($datatable)) :
			foreach ($datatable as $key => $item) :
				$data_val[$key]['id'] = anchor(text:Str::title('pilih'), class:['btn','btn-sm','btn-primary', 'select-hl'], data:['id'=>$item->id, 'title'=>$item->title, 'image'=>url('storage/'.$item->file)]);
				$data_val[$key]['title'] = $item->title;
				$data_val[$key]['info'] = null;
				$data_val[$key]['log'] = null;
			endforeach;
		endif;
		$draw_val = $request->input('draw');
		$get_json_data = ["draw" => intval($draw_val), "recordsTotal" => intval($totalDataRecord), "recordsFiltered" => intval($totalFilteredRecord), "data" => $data_val];

		return response()->json($get_json_data);
	}

	public function select_option(Request $request): JsonResponse
	{
		$q = $request->q;
		$article = Article::select('id', 'title AS text')->where('title', 'LIKE', "%{$q}%")->publish()->get();

		return response()->json($article);
		// return response()->json(['id'=>0, 'text'=>json_encode($article)]);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(): Response
	{
		$article_category = ArticleCategory::select('id', 'title')->get();
		$article = json_decode(json_encode(['tags'=>null, 'related'=>null]));
		$article->tags = [];
		$article->related = [];
        $article->publish = "draft";
        $article_related = [];
		$data = [
			'title'	=> 'buat berita',
			'form'	=> ['action' => route('ae.article.store'), 'class' => 'form-insert']
		];

		return response()->view('editor.article.form', compact('data', 'article', 'article_related', 'article_category'));
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request): JsonResponse
	{
		$this->validate($request, [
			'title'		=> 'required|max:110',
			'content'	=> 'required',
			'file_type'	=> 'required',
			'date'		=> 'required',
			'time'		=> 'required',
			'author'	=> 'required',
			'description'			=> 'required',
			'article_category_id'	=> 'required'
		],
		[
			'required'	=> '<code>:attribute</code> harus diisi.',
			'max'		=> '<code>:attribute</code> tidak boleh lebih dari <b>:max</b> huruf.',
		]);

		$column = [
			'title'		=> $request->title,
			'slug'		=> clean_str($request->title),
			'content'	=> $request->content,
			'author'	=> $request->author,
			'datetime'	=> date('Y-m-d H:i:s', strtotime($request->date.' '.$request->time)),
			'tags'		=> json_encode($request->tags ?? []),
			'publish'	=> ($request->publish=='publish') ? 'publish' : 'draft',
            'file_source'	=> $request->file_source,
			'description'			=> $request->description,
			'article_category_id'	=> $request->article_category_id,
            'ad_setup'  => json_encode(['1'=>"random", '2'=>"random", '3'=>"random", '4'=>"random" ]),
			'ip_addr'	=> $_SERVER['REMOTE_ADDR'],
			'user_id'	=> Auth::user()->id
		];
        if ($request->publish=='schedule') :
			$validation['schedule_date'] = 'required';
			$validation['schedule_time'] = 'required';
			$column['publish'] = $request->publish;
			$column['schedule_time'] = date('Y-m-d H:i:s', strtotime($request->schedule_date.' '.$request->schedule_time));
		elseif (in_array($request->publish, ['publish', 'draft'])) :
			$column['publish'] = $request->publish;
		endif;
		if ($request->file_type == 'upload-file') :
			$this->validate($request, ['upload_file' => 'required|mimes:jpg,jpeg,png'], ['mimes' => 'hanya file <b>jpg, jpeg</b> atau <b>png</b> saja.']);
			if (!empty($request->file)) :
				$image_name = $request->file('upload_file')->hashName();
				Storage::disk('public')->put($image_name, file_get_contents($request->file('upload_file')));
				image_reducer(file_get_contents($request->file('upload_file')), $image_name);
				$column['file']	= $image_name;
				$column['file_type'] = 'image';
				// strbox
				StrBox::create(['title' => $request->title, 'file' => $image_name, 'file_type' => 'image', 'user_id' => Auth::user()->id, 'ip_addr' => $_SERVER['REMOTE_ADDR']]);
			endif;
		elseif ($request->file_type == 'image') :
			$this->validate($request, ['file' => 'required'], ['required' => '<code>:attribute</code> harus diisi.']);
			$column['file']	= $request->file;
			$column['file_type'] = 'image';
		elseif ($request->file_type == 'video') :
			$this->validate($request, ['file' => 'required'], ['required' => '<code>:attribute</code> harus diisi.']);
			$column['file']	= $request->file;
			$column['file_type'] = 'video';
		endif;
		Article::create($column);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "\"<b>{$request->title}</b>\" telah ditambahkan"],
			'redirect'	=> ['type' => 'assign', 'value' => route('ae.article.'.$request->publish)]
		]);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(string $id): void
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(string $id): Response
	{
		$article_category = ArticleCategory::select('id', 'title')->get();
		$article = Article::findOrFail($id);
		$article->tags = json_decode($article->tags, true);
		$article->related = json_decode($article->related ?? "[]", true);
		$article_related = Article::select('id', 'title')->whereIn('id', $article->related)->get();
		$data = [
			'title'	=> 'edit berita',
			'form' => ['action' => route('ae.article.update', $id), 'class' => 'form-update']
		];

		return response()->view('editor.article.form', compact('data', 'article_category', 'article_related', 'article'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id): JsonResponse
	{
		$validation = [
			'title'		=> 'required|max:110',
			'content'	=> 'required',
			'file_type'	=> 'required',
			'date'		=> 'required',
			'time'		=> 'required',
			'author'	=> 'required',
			'description'			=> 'required',
			'article_category_id'	=> 'required'
		];

		$column = [
			'title'		=> $request->title,
			'slug'		=> clean_str($request->title),
			'content'	=> $request->content,
			'author'	=> $request->author,
			'datetime'	=> date('Y-m-d H:i:s', strtotime($request->date.' '.$request->time)),
			'tags'		=> json_encode($request->tags ?? []),
			'related'	=> json_encode($request->related ?? []),
			'file_source'	=> $request->file_source,
			'description'	=> $request->description,
			'article_category_id'	=> $request->article_category_id,
			'ip_addr'	=> $_SERVER['REMOTE_ADDR'],
		];
		if ($request->publish=='schedule') :
			$validation['schedule_date'] = 'required';
			$validation['schedule_time'] = 'required';
			$column['publish'] = $request->publish;
			$column['schedule_time'] = date('Y-m-d H:i:s', strtotime($request->schedule_date.' '.$request->schedule_time));
		elseif (in_array($request->publish, ['publish', 'draft'])) :
			$column['publish'] = $request->publish;
		endif;
		$article = Article::findOrFail($id);
		if ($request->file_type == 'upload-file') :
			$validation['upload_file'] = 'required|mimes:jpg,jpeg,png';
			if (!empty($request->file)) :
				$image_name = $request->file('upload_file')->hashName();
				Storage::disk('public')->put($image_name, file_get_contents($request->file('upload_file')));
				image_reducer(file_get_contents($request->file('upload_file')), $image_name);
				$column['file']	= $image_name;
				$column['file_type'] = 'image';
				// strbox
				StrBox::create(['title' => $request->title, 'file' => $image_name, 'file_type' => 'image', 'user_id' => Auth::user()->id, 'ip_addr' => $_SERVER['REMOTE_ADDR']]);
			endif;
		elseif ($request->file_type == 'image') :
			$validation['file'] = 'required';
			$column['file']	= $request->file;
			$column['file_type'] = 'image';
		elseif ($request->file_type == 'video') :
			$validation['file'] = 'required';
			$column['file']	= $request->file;
			$column['file_type'] = 'video';
		endif;
		$this->validate($request, $validation,
		[
			'required'	=> '<code>:attribute</code> harus diisi.',
			'max'		=> '<code>:attribute</code> tidak boleh lebih dari <b>:max</b> huruf.',
			'mimes'		=> 'hanya file <b>jpg, jpeg</b> atau <b>png</b> saja.'
		]);
		$article->update($column);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "\"<b>{$request->title}</b>\" telah disimpan"],
			'redirect'	=> ['type' => 'assign', 'value' => route('ae.article.'.$request->publish)]
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Request $request): JsonResponse
    {
        $ids = explode(',', $request->id);
		$ids_count = count($ids);
		foreach (Article::whereIn('id', $ids)->get() as $item) :
            ArticleComment::where('article_id', $item->id)->delete();
            ArticleRunning::where('article_id', $item->id)->delete();
			ArticleHighlight::where('article_id', $item->id)->update(['article_id'=>null]);
			Article::whereId($item->id)->delete();
		endforeach;

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('dihapus'), 'html' => "<b>{$ids_count}</b> data telah buang"],
			'redirect'	=> ['type' => 'dataTables']
		]);
	}

	public function index_comment(string $id): Response
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

        return response()->view('editor.article.comment', compact('data', 'article', 'comment', 'comment_waiting'));
    }

    public function update_comment(string $id): RedirectResponse
    {
        ArticleComment::whereId($id)->where('publish', 'draft')->update(['publish'=>'publish']);
        
        return redirect()->back();
    }

    public function destroy_comment(string $id): RedirectResponse
    {
        ArticleComment::whereId($id)->delete();

        return redirect()->back();
    }
}
