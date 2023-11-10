<?php

namespace App\Http\Controllers;

use App\Models\Info;
use App\Models\StrBox;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InfoController extends Controller
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
	
    public function profile(): Response
    {
        $profile = Info::whereType('profile')->firstOrFail();
		$data = [
			'title'	=> 'edit profil',
			'form'	=> ['action' => route('profile.update'), 'class' => 'form-update']
		];

		return response()->view('panel.info.profile', compact('data', 'profile'));
    }

    public function update_profile(Request $request): JsonResponse
    {
        $this->validate($request, [
			'title'		=> 'required|max:110',
			'content'	=> 'required',
			'file_type'	=> 'required'
		],
		[
			'required'	=> '<code>:attribute</code> harus diisi.',
			'max'		=> '<code>:attribute</code> tidak boleh lebih dari <b>:max</b> huruf.'
		]);
		$column = [
			'title'		=> $request->title,
			'content'	=> $request->content,
			'ip_addr'	=> $_SERVER['REMOTE_ADDR'],
			'user_id'	=> Auth::user()->id
		];
		$profile = Info::whereType('profile');
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
		$profile->update($column);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "\"<b>Profil</b>\" telah disimpan"],
			'redirect'	=> ['type' => 'nothing']
		]);
	}

	public function info(string $slug): Response
	{
		$info = Info::where('type', $slug)->first();
		$data = [
			'title'	=> 'edit '.$info->title,
			'form'	=> ['action' => route('info.update', $info->id), 'class' => 'form-update']
		];

		return response()->view('panel.info.info', compact('data', 'info'));
	}

	public function update_info(Request $request, int $id): JsonResponse
	{
		$this->validate($request, [
			'title'		=> 'required|max:110',
			'content'	=> 'required',
		],
		[
			'required'	=> '<code>:attribute</code> harus diisi.',
			'max'		=> '<code>:attribute</code> tidak boleh lebih dari <b>:max</b> huruf.'
		]);
		$column = [
			'title'		=> $request->title,
			'content'	=> $request->content,
			'ip_addr'	=> $_SERVER['REMOTE_ADDR'],
			'user_id'	=> Auth::user()->id
		];
		$info = Info::whereId($id);
		$info->update($column);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "\"<b>{$request->title}</b>\" telah disimpan"],
			'redirect'	=> ['type' => 'nothing']
		]);
	}
}
