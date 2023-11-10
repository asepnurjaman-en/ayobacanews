<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use App\Models\Info;
use App\Models\StrBox;
use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ArticleCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdsController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
	}

    public function index(): Response
    {
        $data = [
			'title' => 'berita rilis',
			'list'	=> route('ads.list'),
            'create'=> ['action' => route('ads.create')],
			'delete'=> ['action' => route('ads.destroy', 0), 'message' => 'Hapus iklan?']
		];

        return response()->view('panel.ad.index', compact('data'));
    }

    public function list(Request $request): JsonResponse
	{
		$totalFilteredRecord = $totalDataRecord = $draw_val = "";
		$column = [0 => 'id', 1 => 'title', 2 => 'info', 3 => 'log'];
		$totalDataRecord = Ads::count();
		$totalFilteredRecord = $totalDataRecord;
		$limit_val	= $request->input('length');
		$start_val	= $request->input('start');
		if (empty($request->input('search.value'))) :
			$datatable = Ads::offset($start_val)->limit($limit_val)->latest()->get();
		else :
			$search_text = $request->input('search.value');
			$datatable =  Ads::where('title', 'LIKE', "%{$search_text}%")->offset($start_val)->limit($limit_val)->get();
			$totalFilteredRecord = Ads::where('title', 'LIKE', "%{$search_text}%")->count();
		endif;
		$data_val = [];
		if (!empty($datatable)) :
			foreach ($datatable as $key => $item) :
				$data_val[$key]['id'] = input_check(name:"check[]", value:$item->id, class:['form-check-input', 'check-row'], mode:'multiple');
				$data_val[$key]['title'] = anchor(text:$item->title, href:route('ads.edit', $item->id));
				$data_val[$key]['info'] = null;
				$data_val[$key]['log'] = date_info($item->created_at);
			endforeach;
		endif;
		$draw_val = $request->input('draw');
		$get_json_data = ["draw" => intval($draw_val), "recordsTotal" => intval($totalDataRecord), "recordsFiltered" => intval($totalFilteredRecord), "data" => $data_val];

		return response()->json($get_json_data);
	}

    public function create(): Response
	{
		$data = [
			'title'	=> 'tambah iklan',
			'form'	=> ['action' => route('ads.store'), 'class' => 'form-insert']
		];

		return response()->view('panel.ad.form', compact('data'));
	}

    public function store(Request $request): JsonResponse
	{
		$this->validate($request, [
			'title'		=> 'required|max:110',
        ],
        [
			'required'	=> '<code>:attribute</code> harus diisi.',
			'max'		=> '<code>:attribute</code> tidak boleh lebih dari <b>:max</b> huruf.',
		]);
        $column = [
			'title'		=> $request->title,
			'url'   	=> $request->url ?? '#',
			'ip_addr'	=> $_SERVER['REMOTE_ADDR'],
			'user_id'	=> Auth::user()->id
		];
        if ($request->file_type == 'upload-file') :
			$this->validate($request, ['upload_file' => 'required|mimes:jpg,jpeg,png'], ['mimes' => 'hanya file <b>jpg, jpeg</b> atau <b>png</b> saja.']);
			if (!empty($request->file)) :
				$image_name = $request->file('upload_file')->hashName();
				Storage::disk('public')->put($image_name, file_get_contents($request->file('upload_file')));
				image_reducer(file_get_contents($request->file('upload_file')), $image_name);
				$column['file']	= $image_name;
				// strbox
				StrBox::create(['title' => $request->title, 'file' => $image_name, 'file_type' => 'image', 'user_id' => Auth::user()->id, 'ip_addr' => $_SERVER['REMOTE_ADDR']]);
			endif;
		elseif ($request->file_type == 'image') :
			$this->validate($request, ['file' => 'required'], ['required' => '<code>:attribute</code> harus diisi.']);
			$column['file']	= $request->file;
        endif;
        Ads::create($column);

        return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "\"<b>{$request->title}</b>\" telah ditambahkan"],
			'redirect'	=> ['type' => 'assign', 'value' => route('ads.index')]
		]);
    }

	public function show(string $id): Response
	{
		$category = ArticleCategory::select('id', 'title')->withCount(['articles' => function ($query) {
			$query;
		}])->get();
		$data = [
			'title'	=> 'kelola iklan',
		];

		return response()->view('panel.ad.show', compact('data', 'category'));
	}

    public function edit(string $id): Response
	{
		$ad = Ads::findOrFail($id);
		$data = [
			'title'	=> 'edit iklan',
			'form' => ['action' => route('ads.update', $id), 'class' => 'form-update']
		];

		return response()->view('panel.ad.form', compact('data', 'ad'));
	}

    public function update(Request $request, string $id): JsonResponse
	{
		$this->validate($request, [
			'title'		=> 'required|max:110',
        ],
        [
			'required'	=> '<code>:attribute</code> harus diisi.',
			'max'		=> '<code>:attribute</code> tidak boleh lebih dari <b>:max</b> huruf.',
		]);
        $column = [
			'title'		=> $request->title,
			'url'   	=> $request->url ?? '#',
			'ip_addr'	=> $_SERVER['REMOTE_ADDR'],
			'user_id'	=> Auth::user()->id
		];
        $ads = Ads::findOrFail($id);
        if ($request->file_type == 'upload-file') :
			$this->validate($request, ['upload_file' => 'required|mimes:jpg,jpeg,png'], ['mimes' => 'hanya file <b>jpg, jpeg</b> atau <b>png</b> saja.']);
			if (!empty($request->file)) :
				$image_name = $request->file('upload_file')->hashName();
				Storage::disk('public')->put($image_name, file_get_contents($request->file('upload_file')));
				image_reducer(file_get_contents($request->file('upload_file')), $image_name);
				$column['file']	= $image_name;
				// strbox
				StrBox::create(['title' => $request->title, 'file' => $image_name, 'file_type' => 'image', 'user_id' => Auth::user()->id, 'ip_addr' => $_SERVER['REMOTE_ADDR']]);
			endif;
		elseif ($request->file_type == 'image') :
			$this->validate($request, ['file' => 'required'], ['required' => '<code>:attribute</code> harus diisi.']);
			$column['file']	= $request->file;
        endif;
        $ads->update($column);

        return response()->json([
            'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "\"<b>{$request->title}</b>\" telah disimpan"],
            'redirect'	=> ['type' => 'assign', 'value' => route('ads.'.$request->publish)]
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $ids = explode(',', $request->id);
		$ids_count = count($ids);
        Ads::whereIn('id', $ids)->delete();

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('dihapus'), 'html' => "<b>{$ids_count}</b> data telah buang"],
			'redirect'	=> ['type' => 'dataTables']
		]);
	}

	public function ad_option_tp(string $page): Response
	{
		$info = Info::whereType($page)->firstOrFail();
		$info->ad = json_decode($info->content, true);
		if (in_array($info->ad['1'], ['random', null])) :
			$ad_1 = $info->ad['1'];
		elseif ($info->ad['1'] > 0) :
			$ad_1 = Ads::whereId($info->ad['1'])->first();
		endif;
		if (in_array($info->ad['2'], ['random', null])) :
			$ad_2 = $info->ad['2'];
		elseif ($info->ad['2'] > 0) :
			$ad_2 = Ads::whereId($info->ad['2'])->first();
		endif;
		if ($page=='home-ad') :
			if (in_array($info->ad['3'], ['random', null])) :
				$ad_3 = $info->ad['3'];
			elseif ($info->ad['3'] > 0) :
				$ad_3 = Ads::whereId($info->ad['3'])->first();
			endif;
			if (in_array($info->ad['4'], ['random', null])) :
				$ad_4 = $info->ad['4'];
			elseif ($info->ad['4'] > 0) :
				$ad_4 = Ads::whereId($info->ad['4'])->first();
			endif;
			if (in_array($info->ad['5'], ['random', null])) :
				$ad_5 = $info->ad['5'];
			elseif ($info->ad['5'] > 0) :
				$ad_5 = Ads::whereId($info->ad['5'])->first();
			endif;
			if (in_array($info->ad['6'], ['random', null])) :
				$ad_6 = $info->ad['6'];
			elseif ($info->ad['6'] > 0) :
				$ad_6 = Ads::whereId($info->ad['6'])->first();
			endif;
		endif;
		$data = [
			'title'	=> 'iklan: '.$info->title,
			'form'	=> ['action' => route('article.ad.update.tp', $page), 'class' => 'form-update']
		];
		if ($page=='home-ad') :
			return response()->view('panel.ad.home-ad', compact('data', 'info', 'ad_1', 'ad_2', 'ad_3', 'ad_4', 'ad_5', 'ad_6'));
		elseif ($page=='contact-ad') :
			return response()->view('panel.ad.contact-ad', compact('data', 'info', 'ad_1', 'ad_2'));
		endif;
	}

    public function ad_option(int $id): Response
	{
		$article = Article::whereId($id)->first();
		$article->ad = json_decode($article->ad_setup, true);
		if (in_array($article->ad['1'], ['random', null])) :
			$ad_1 = $article->ad['1'];
		elseif ($article->ad['1'] > 0) :
			$ad_1 = Ads::whereId($article->ad['1'])->first();
		endif;
		if (in_array($article->ad['2'], ['random', null])) :
			$ad_2 = $article->ad['2'];
		elseif ($article->ad['2'] > 0) :
			$ad_2 = Ads::whereId($article->ad['2'])->first();
		endif;
		if (in_array($article->ad['3'], ['random', null])) :
			$ad_3 = $article->ad['3'];
		elseif ($article->ad['3'] > 0) :
			$ad_3 = Ads::whereId($article->ad['3'])->first();
		endif;
		if (in_array($article->ad['4'], ['random', null])) :
			$ad_4 = $article->ad['4'];
		elseif ($article->ad['4'] > 0) :
			$ad_4 = Ads::whereId($article->ad['4'])->first();
		endif;
		$data = [
			'title'	=> 'iklan: '.$article->title,
			'form'	=> ['action' => route('article.ad.update', $id), 'class' => 'form-update']
		];

		return response()->view('panel.article.ad', compact('data', 'article', 'ad_1', 'ad_2', 'ad_3', 'ad_4'));
	}

	public function ad_category_option(int $id): Response
	{
		$article = ArticleCategory::whereId($id)->first();
		$article->ad = json_decode($article->ad_setup, true);
		if (in_array($article->ad['1'], ['random', null])) :
			$ad_1 = $article->ad['1'];
		elseif ($article->ad['1'] > 0) :
			$ad_1 = Ads::whereId($article->ad['1'])->first();
		endif;
		if (in_array($article->ad['2'], ['random', null])) :
			$ad_2 = $article->ad['2'];
		elseif ($article->ad['2'] > 0) :
			$ad_2 = Ads::whereId($article->ad['2'])->first();
		endif;
		if (in_array($article->ad['3'], ['random', null])) :
			$ad_3 = $article->ad['3'];
		elseif ($article->ad['3'] > 0) :
			$ad_3 = Ads::whereId($article->ad['3'])->first();
		endif;
		if (in_array($article->ad['4'], ['random', null])) :
			$ad_4 = $article->ad['4'];
		elseif ($article->ad['4'] > 0) :
			$ad_4 = Ads::whereId($article->ad['4'])->first();
		endif;
		$data = [
			'title'	=> 'iklan: '.$article->title,
			'form'	=> ['action' => route('article-category.ad.update', $id), 'class' => 'form-update']
		];

		return response()->view('panel.article.ad-category', compact('data', 'article', 'ad_1', 'ad_2', 'ad_3', 'ad_4'));
	}

	public function ad_list(Request $request): JsonResponse
	{
		$totalFilteredRecord = $totalDataRecord = $draw_val = "";
		$column = [0 => 'id', 1 => 'title', 2 => 'info', 3 => 'log'];
		$totalDataRecord = Ads::count();
		$totalFilteredRecord = $totalDataRecord;
		$limit_val	= $request->input('length');
		$start_val	= $request->input('start');
		if (empty($request->input('search.value'))) :
			$datatable = Ads::offset($start_val)->limit($limit_val)->latest()->get();
		else :
			$search_text = $request->input('search.value');
			$datatable =  Ads::where('title', 'LIKE', "%{$search_text}%")->offset($start_val)->limit($limit_val)->get();
			$totalFilteredRecord = Ads::where('title', 'LIKE', "%{$search_text}%")->count();
		endif;
		$data_val = [];
		if (!empty($datatable)) :
			foreach ($datatable as $key => $item) :
				$data_val[$key]['id'] = anchor(text:Str::title('pilih'), class:['btn','btn-sm','btn-primary', 'select-ad'], data:['id'=>$item->id, 'image'=>url('storage/'.$item->file)]);
				$data_val[$key]['title'] = $item->title;
				$data_val[$key]['info'] = $item->url;
				$data_val[$key]['log'] = null;
			endforeach;
		endif;
		$draw_val = $request->input('draw');
		$get_json_data = ["draw" => intval($draw_val), "recordsTotal" => intval($totalDataRecord), "recordsFiltered" => intval($totalFilteredRecord), "data" => $data_val];

		return response()->json($get_json_data);
	}

	public function ad_option_tp_update(Request $request, string $page): JsonResponse
	{
		$info = Info::whereType($page);
		$ad_1 = $ad_2 = $ad_3 = $ad_4 = $ad_5 = $ad_6 = null;
		if (!empty($request->input('ad-input-1'))) :
			$ad_1 = (int)$request->input('ad-input-1');
		elseif (empty($request->input('ad-input-1')) && $request->input('ad-random-1')=='on') :
			$ad_1 = 'random';
		endif;
		if (!empty($request->input('ad-input-2'))) :
			$ad_2 = (int)$request->input('ad-input-2');
		elseif (empty($request->input('ad-input-2')) && $request->input('ad-random-2')=='on') :
			$ad_2 = 'random';
		endif;
		if ($page=='home-ad') :
			if (!empty($request->input('ad-input-3'))) :
				$ad_3 = (int)$request->input('ad-input-3');
			elseif (empty($request->input('ad-input-3')) && $request->input('ad-random-3')=='on') :
				$ad_3 = 'random';
			endif;
			if (!empty($request->input('ad-input-4'))) :
				$ad_4 = (int)$request->input('ad-input-4');
			elseif (empty($request->input('ad-input-4')) && $request->input('ad-random-4')=='on') :
				$ad_4 = 'random';
			endif;
			if (!empty($request->input('ad-input-5'))) :
				$ad_5 = (int)$request->input('ad-input-5');
			elseif (empty($request->input('ad-input-5')) && $request->input('ad-random-5')=='on') :
				$ad_5 = 'random';
			endif;
			if (!empty($request->input('ad-input-6'))) :
				$ad_6 = (int)$request->input('ad-input-6');
			elseif (empty($request->input('ad-input-6')) && $request->input('ad-random-6')=='on') :
				$ad_6 = 'random';
			endif;
		endif;
		if ($page=='home-ad') :
			$ad_setup = json_encode(['1' => $ad_1, '2' => $ad_2, '3' => $ad_3, '4' => $ad_4, '5' => $ad_5, '6' => $ad_6]);
		elseif ($page=='contact-ad') :
			$ad_setup = json_encode(['1' => $ad_1, '2' => $ad_2]);
		endif;
		$info->update(['content' => $ad_setup]);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "Iklan telah disimpan"],
			'redirect'	=> ['type' => 'nothing']
		]);
	}

	public function ad_option_update(Request $request, int $id): JsonResponse
	{
		$article = Article::findOrFail($id);
		$ad_1 = $ad_2 = $ad_3 = $ad_4 = null;
		if (!empty($request->input('ad-input-1'))) :
			$ad_1 = (int)$request->input('ad-input-1');
		elseif (empty($request->input('ad-input-1')) && $request->input('ad-random-1')=='on') :
			$ad_1 = 'random';
		endif;
		if (!empty($request->input('ad-input-2'))) :
			$ad_2 = (int)$request->input('ad-input-2');
		elseif (empty($request->input('ad-input-2')) && $request->input('ad-random-2')=='on') :
			$ad_2 = 'random';
		endif;
		if (!empty($request->input('ad-input-3'))) :
			$ad_3 = (int)$request->input('ad-input-3');
		elseif (empty($request->input('ad-input-3')) && $request->input('ad-random-3')=='on') :
			$ad_3 = 'random';
		endif;
		if (!empty($request->input('ad-input-4'))) :
			$ad_4 = (int)$request->input('ad-input-4');
		elseif (empty($request->input('ad-input-4')) && $request->input('ad-random-4')=='on') :
			$ad_4 = 'random';
		endif;
		$ad_setup = json_encode(['1' => $ad_1, '2' => $ad_2, '3' => $ad_3, '4' => $ad_4]);
		$article->update(['ad_setup' => $ad_setup]);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "Iklan \"<b>{$article->title}</b>\" telah disimpan"],
			'redirect'	=> ['type' => 'nothing']
		]);
	}

	public function ad_category_option_update(Request $request, int $id): JsonResponse
	{
		$article = ArticleCategory::findOrFail($id);
		$ad_1 = $ad_2 = $ad_3 = $ad_4 = null;
		if (!empty($request->input('ad-input-1'))) :
			$ad_1 = (int)$request->input('ad-input-1');
		elseif (empty($request->input('ad-input-1')) && $request->input('ad-random-1')=='on') :
			$ad_1 = 'random';
		endif;
		if (!empty($request->input('ad-input-2'))) :
			$ad_2 = (int)$request->input('ad-input-2');
		elseif (empty($request->input('ad-input-2')) && $request->input('ad-random-2')=='on') :
			$ad_2 = 'random';
		endif;
		if (!empty($request->input('ad-input-3'))) :
			$ad_3 = (int)$request->input('ad-input-3');
		elseif (empty($request->input('ad-input-3')) && $request->input('ad-random-3')=='on') :
			$ad_3 = 'random';
		endif;
		if (!empty($request->input('ad-input-4'))) :
			$ad_4 = (int)$request->input('ad-input-4');
		elseif (empty($request->input('ad-input-4')) && $request->input('ad-random-4')=='on') :
			$ad_4 = 'random';
		endif;
		$ad_setup = json_encode(['1' => $ad_1, '2' => $ad_2, '3' => $ad_3, '4' => $ad_4]);
		$article->update(['ad_setup' => $ad_setup]);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "Iklan \"<b>{$article->title}</b>\" telah disimpan"],
			'redirect'	=> ['type' => 'nothing']
		]);
	}
}
