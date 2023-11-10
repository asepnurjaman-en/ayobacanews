<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ArticleCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class ArticleCategoryController extends Controller
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
    public function index(): Response
    {
        $data = [
            'title' => 'label artikel',
            'list'  => route('article-category.list'),
            'create'=> ['action' => route('article-category.create')],
            'delete'=> ['action' => route('article-category.destroy', 0), 'message' => 'Hapus kategori artikel?']
        ];

        return response()->view('panel.article.index-category', compact('data'));
    }

    public function list(Request $request): JsonResponse
    {
        $totalFilteredRecord = $totalDataRecord = $draw_val = "";
		$column = [0 => 'id', 1 => 'title', 2 => 'info', 3 => 'log'];
		$totalDataRecord = ArticleCategory::count();
		$totalFilteredRecord = $totalDataRecord;
		$limit_val	= $request->input('length');
		$start_val	= $request->input('start');
		if (empty($request->input('search.value'))) :
			$datatable = ArticleCategory::offset($start_val)->latest()->limit($limit_val)->get();
		else :
			$search_text = $request->input('search.value');
			$datatable =  ArticleCategory::where('id', 'LIKE', "%{$search_text}%")->orWhere('title', 'LIKE', "%{$search_text}%")->offset($start_val)->limit($limit_val)->get();
			$totalFilteredRecord = ArticleCategory::where('id', 'LIKE', "%{$search_text}%")->orWhere('title', 'LIKE', "%{$search_text}%")->count();
		endif;
		$data_val = [];
		if (!empty($datatable)) :
			foreach ($datatable as $key => $item) :
				$data_val[$key]['id'] = input_check(name:"check[]", value:$item->id, class:['form-check-input', 'check-row'], mode:'multiple');
				$data_val[$key]['title'] = anchor(text:$item->title, href:route('article-category.edit', $item->id))."<br/><span class=\"badge me-1\" style=\"font-size:10px;background-color:{$item->color}\">{$item->color}</span> | ".anchor(href:route('article-category.ad', $item->id), text:"<i class=\"bx bx-objects-vertical-bottom\"></i> Atur iklan", class:['text-primary', 'small', 'text-nowrap']);
				$data_val[$key]['info'] = null;
				$data_val[$key]['log'] = date_info($item->created_at);
			endforeach;
		endif;
		$draw_val = $request->input('draw');
		$get_json_data = ["draw" => intval($draw_val), "recordsTotal" => intval($totalDataRecord), "recordsFiltered" => intval($totalFilteredRecord), "data" => $data_val];

		return response()->json($get_json_data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $data = [
            'title' => 'tambah label artikel',
            'form'  => ['action' => route('article-category.store'), 'class' => 'form-insert']
        ];

        return response()->view('panel.article.form-category', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
			'title'	=> 'required|max:110',
			'color'	=> 'required|max:30'
        ],
		[
			'required'	=> '<code>:attribute</code> harus diisi.',
			'max'		=> '<code>:attribute</code> tidak boleh lebih dari <b>:max</b> huruf.'
		]);
		$column = [
			'title'		=> $request->title,
			'slug'		=> clean_str($request->title),
			'color'		=> $request->color,
            'ad_setup'  => json_encode(['1'=>"random", '2'=>"random", '3'=>"random", '4'=>"random" ]),
            'publish'	=> ($request->publish=='publish') ? 'publish' : 'draft',
			'user_id'	=> Auth::user()->id,
			'ip_addr'	=> $_SERVER['REMOTE_ADDR']
		];
		ArticleCategory::create($column);

		return response()->json([
            'toast'     => ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "\"<b>{$request->title}</b>\" telah ditambahkan"],
            'redirect'  => ['type' => 'assign', 'value' => route('article-category.index')]
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
        $article_category = ArticleCategory::findOrFail($id);
        $data = [
            'title' => 'edit label artikel',
            'form'  => ['action' => route('article-category.update', $id), 'class' => 'form-update']
        ];

        return response()->view('panel.article.form-category', compact('data', 'article_category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $this->validate($request, [
			'title'	=> 'required|max:110',
			'color'	=> 'required|max:30'
        ],
		[
			'required'	=> '<code>:attribute</code> harus diisi.',
			'max'		=> '<code>:attribute</code> tidak boleh lebih dari <b>:max</b> huruf.'
		]);
		$column = [
			'title'		=> $request->title,
			'slug'		=> clean_str($request->title),
			'color'		=> $request->color,
            'publish'	=> ($request->publish=='publish') ? 'publish' : 'draft',
			'user_id'	=> Auth::user()->id,
			'ip_addr'	=> $_SERVER['REMOTE_ADDR']
		];
        $article_category = ArticleCategory::findOrFail($id);
		$article_category->update($column);

		return response()->json([
            'toast'     => ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "\"<b>{$request->title}</b>\" telah disimpan"],
            'redirect'  => ['type' => 'assign', 'value' => route('article-category.index')]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $ids = explode(',', $request->id);
		$ids_count = count($ids);
		foreach (ArticleCategory::whereIn('id', $ids)->get() as $item) :
            Article::where('article_category_id', $item->id)->delete();
			ArticleCategory::whereId($item->id)->delete();
		endforeach;

		return response()->json([
            'toast'     => ['icon' => 'success', 'title' => ucfirst('dihapus'), 'html' => "<b>{$ids_count}</b> data telah buang"],
            'redirect'  => ['type' => 'dataTables']
        ]);
	}

    public function reorder(): Response
    {
        $article_category = ArticleCategory::orderBy('index_sort', 'ASC')->get();
        $data = [
            'title' => 'atur urutan kategori',
            'form'  => ['action' => route('article-category.reorder.update'), 'class' => 'form-update']
        ];

        return response()->view('panel.article.reorder-category', compact('data', 'article_category'));
    }

    public function reorder_update(Request $request): JsonResponse
    {
        $column = [
            'ip_addr' => $_SERVER['REMOTE_ADDR'],
            'user_id' => Auth::user()->id
        ];
        foreach (ArticleCategory::all() as $key => $item) :
            $column['index_sort'] = $request->input('index_sort_'.$item->id);
            ArticleCategory::whereId($item->id)->update($column);
        endforeach;

        return response()->json([
            'toast'     => ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "Urutan disimpan"],
            'redirect'  => ['type' => 'nothing']
        ]);
    }
}
