<?php

namespace App\Http\Controllers;

use App\Models\StrBox;
use App\Models\Article;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ProductCatalog;
use App\Models\ArticleCategory;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{
    public function article_index(): Response
	{
		$article = Article::latest()->get();
		$message = "<p>Kumpulkan ide-ide artikelmu disini, buat sebanyak mungkin yang kamu bisa!</p>";

		return response([
			'success'   => true,
			'message'	=> $message,
			'data'      => $article
		], 200);
	}

	public function article_category()
	{
		$article_category = ArticleCategory::latest()->get();

		return response([
			'success'   => true,
			'data'      => $article_category
		], 200);
	}

	public function article_show($id): Response
	{
		$article = Article::findOrFail($id);
		$article->tags = implode(',', json_decode($article->tags, true));

		return response([
			'success'   => true,
			'data'      => $article
		], 200);
	}

	public function article_store(Request $request): Response
	{
		$this->validate($request, [
			'title'			=> 'required',
			'content'		=> 'required',
			'label'			=> 'required',
			'author'		=> 'required',
			'description'	=> 'required',
			'file'			=> 'required',
		]);
		$column = [
			'title'		=> $request->title,
			'slug'		=> clean_str($request->title),
			'content'	=> $request->content,
			'author'	=> $request->author,
			'datetime'	=> date('Y-m-d H:i:s'),
			'tags'		=> json_encode(explode(',', $request->tags) ?? []),
			'publish'	=> ($request->publish=='publish') ? 'publish' : 'draft',
			'description'			=> $request->description,
			'article_category_id'	=> $request->label,
			'ip_addr'	=> $_SERVER['REMOTE_ADDR'],
			'user_id'	=> '1'
		];
		if (!empty($request->file)) :
			$image_name = $request->file('file')->hashName();
			Storage::disk('public')->put($image_name, file_get_contents($request->file('file')));
			image_reducer(file_get_contents($request->file('file')), $image_name);
			$column['file']	= $image_name;
			$column['file_type'] = 'image';
			// strbox
			StrBox::create(['title' => $request->title, 'file' => $image_name, 'file_type' => 'image', 'user_id' => '1', 'ip_addr' => $_SERVER['REMOTE_ADDR']]);
		endif;
		Article::create($column);

		return response([
			'success'	=> true
		], 200);
	}

	public function article_update(Request $request, $id): Response
	{
		$this->validate($request, [
			'title'			=> 'required',
			'content'		=> 'required',
			'label'			=> 'required',
			'author'		=> 'required',
			'description'	=> 'required',
		]);
		$column = [
			'title'		=> $request->title,
			'slug'		=> clean_str($request->title),
			'content'	=> $request->content,
			'author'	=> $request->author,
			'datetime'	=> date('Y-m-d H:i:s'),
			'tags'		=> json_encode(explode(',', $request->tags) ?? []),
			'publish'	=> ($request->publish=='publish') ? 'publish' : 'draft',
			'description'			=> $request->description,
			'article_category_id'	=> $request->label,
			'ip_addr'	=> $_SERVER['REMOTE_ADDR'],
			'user_id'	=> '1'
		];
		$article = Article::findOrFail($id);
		if (!empty($request->file)) :
			$image_name = $request->file('file')->hashName();
			Storage::disk('public')->put($image_name, file_get_contents($request->file('file')));
			image_reducer(file_get_contents($request->file('file')), $image_name);
			$column['file']	= $image_name;
			$column['file_type'] = 'image';
			// strbox
			StrBox::create(['title' => $request->title, 'file' => $image_name, 'file_type' => 'image', 'user_id' => '1', 'ip_addr' => $_SERVER['REMOTE_ADDR']]);
		endif;
		$article->update($column);

		return response([
			'success'	=> true
		], 200);
	}

	public function article_delete(Request $request, int $id): void
	{
		$article = Article::find($id);
		$article->delete();
	}

	// Jual Pulau
	public function productCatalog(): Response
	{
		$productCatalog = ProductCatalog::select('id', 'title', 'color')->latest()->get();

		return response([
			'success'	=> true,
			'title'		=> 'Pulau siap guna',
			'message'	=> 'Silahkan lihat katalog kami',
			'data'		=> $productCatalog
		], 200);
	}

	public function product(int $id): Response
	{
		$productCatalog = ProductCatalog::select('title')->whereId($id)->first();
		$product = Product::select('title', 'slug', 'price', 'content', 'file', 'publish')->where('product_catalogs_id', $id)->latest()->get();

		return response([
			'success'	=> true,
			'title'		=> $productCatalog->title,
			'message'	=> 'Silahkan lihat katalog kami',
			'data'		=> $product
		], 200);
	}

	public function productDetail(string $slug): Response
	{
		$product = Product::select('title', 'price', 'content', 'file', 'publish', 'product_catalogs_id')->where('slug', $slug)->first();
		$productCatalog = ProductCatalog::select('title')->whereId($product->product_catalogs_id)->first();

		return response([
			'success'	=> true,
			'title'		=> $product->title,
			'message'	=> $productCatalog->title,
			'data'		=> $product
		], 200);
	}
}
