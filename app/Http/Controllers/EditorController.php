<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StrBox;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ArticleCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class EditorController extends Controller
{
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
			'title' => 'editor',
			'list'	=> route('editor.list'),
            'create'=> ['action' => route('editor.create')],
			'delete'=> ['action' => route('editor.destroy', 0), 'message' => 'Hapus dari editor?']
		];

        return response()->view('panel.editor.index', compact('data'));
    }

    public function list(Request $request): JsonResponse
	{
		$totalFilteredRecord = $totalDataRecord = $draw_val = "";
		$column = [0 => 'id', 1 => 'title', 2 => 'info', 3 => 'log'];
		$totalDataRecord = User::whereRole('editor')->count();
		$totalFilteredRecord = $totalDataRecord;
		$limit_val	= $request->input('length');
		$start_val	= $request->input('start');
		if (empty($request->input('search.value'))) :
			$datatable = User::whereRole('editor')->offset($start_val)->latest()->limit($limit_val)->get();
		else :
			$search_text = $request->input('search.value');
			$datatable =  User::whereRole('editor')->where('id', 'LIKE', "%{$search_text}%")->orWhere('name', 'LIKE', "%{$search_text}%")->offset($start_val)->limit($limit_val)->get();
			$totalFilteredRecord = User::whereRole('editor')->where('id', 'LIKE', "%{$search_text}%")->orWhere('name', 'LIKE', "%{$search_text}%")->count();
		endif;
		$data_val = [];
		if (!empty($datatable)) :
			foreach ($datatable as $key => $item) :
				$data_val[$key]['id'] = input_check(name:"check[]", value:$item->id, class:['form-check-input', 'check-row'], mode:'multiple');
				$data_val[$key]['title'] = anchor(text:$item->name, href:route('editor.edit', $item->id));
				$data_val[$key]['info'] = $item->email;
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
			'title'	=> 'tambah editor',
			'form'	=> ['action' => route('editor.store'), 'class' => 'form-insert']
		];

		return response()->view('panel.editor.form', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
			'name'		=> 'required|max:110',
			'email'		=> 'required|email|unique:users',
            'password'  => 'required|confirmed'
        ],
        [
			'required'	=> '<code>:attribute</code> harus diisi.',
			'email'	    => '<code>:attribute</code> tidak dapat digunakan.',
			'confirmed' => '<code>:attribute</code> tidak sesuai.',
			'max'		=> '<code>:attribute</code> tidak boleh lebih dari <b>:max</b> huruf.',
		]);
        $column = [
			'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role'  => 'editor'
		];
        User::create($column);

        return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "\"<b>{$request->name}</b>\" telah ditambahkan"],
			'redirect'	=> ['type' => 'assign', 'value' => route('editor.index')]
		]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        $editor = User::whereId($id)->where('role', 'editor')->firstOrFail();
		$data = [
			'title'	=> 'edit editor',
			'form' => ['action' => route('editor.update', $id), 'class' => 'form-update']
		];

		return response()->view('panel.editor.form', compact('data', 'editor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $this->validate($request, [
			'name'		=> 'required|max:110',
			'email'		=> 'required|email',
        ],
        [
			'required'	=> '<code>:attribute</code> harus diisi.',
			'email'	    => '<code>:attribute</code> tidak dapat digunakan.',
			'max'		=> '<code>:attribute</code> tidak boleh lebih dari <b>:max</b> huruf.',
		]);
        $column = [
			'name' => $request->name,
            'email' => $request->email,
		];
        User::whereId($id)->where('role', 'editor')->update($column);

        return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "\"<b>{$request->name}</b>\" telah ditambahkan"],
			'redirect'	=> ['type' => 'assign', 'value' => route('editor.index')]
		]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $ids = explode(',', $request->id);
		$ids_count = count($ids);
        Article::whereIn('user_id', $ids)->update(['user_id' => 1]);
        ArticleCategory::whereIn('user_id', $ids)->update(['user_id' => 1]);
        StrBox::whereIn('user_id', $ids)->update(['user_id' => 1]);
        $delete = User::whereIn('id', $ids)->where('role', 'editor')->delete();
		if ($delete) :
			return response()->json([
				'toast'		=> ['icon' => 'success', 'title' => ucfirst('dihapus'), 'html' => "<b>{$ids_count}</b> data telah buang"],
				'redirect'	=> ['type' => 'dataTables']
			]);
		else :
			return response()->json([
				'toast'		=> ['icon' => 'error', 'title' => ucfirst('permintaan ditolak'), 'html' => "<b>Editor</b> memiliki beberapa berita"],
				'redirect'	=> ['type' => 'nothing']
			]);
		endif;
    }
}
