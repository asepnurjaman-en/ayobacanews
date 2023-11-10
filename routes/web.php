<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\LinkExController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ArticleCommentController;
use App\Http\Controllers\ArticleRunningController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\ArticleCategoryController;
use App\Http\Controllers\ArticleHighlightController;
use App\Http\Controllers\Editor\ArticleController as AeArticle;
use App\Http\Controllers\Editor\ArticleCategoryController as AeArticleCategory;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/auth/redirect', [SocialiteController::class, 'redirectToProvider']);
Route::get('/auth/callback', [SocialiteController::class, 'handleProviderCallback']);

// Storage
Route::post('storage/modal/{mode}', [SettingController::class, 'storage_modal'])->name('home.storage-modal');
Route::post('storage/modal/select/{mode}', [SettingController::class, 'put_storage_modal'])->name('home.put-storage-modal');
Route::post('storage/youtube/select', [SettingController::class, 'from_youtube'])->name('home.from-youtube');

Auth::routes();
Route::prefix('panel')->middleware('is_admin')->group(function () {
	Route::get('/', [SettingController::class, 'dashboard'])->name('home.dashboard');
	Route::get('storage/{type?}', [SettingController::class, 'storage'])->name('home.storage');
	Route::get('storage/{id}/edit', [SettingController::class, 'storage_edit'])->name('home.storage-edit');
	Route::patch('storage/{id}/update', [SettingController::class, 'storage_update'])->name('home.storage-update');
	Route::delete('storage/{id}/delete/', [SettingController::class, 'storage_delete'])->name('home.storage-delete');
	Route::post('storage/list/{type}', [SettingController::class, 'storage_list'])->name('home.storage-list');
	Route::post('storage/upload/{type}', [SettingController::class, 'storage_store'])->name('home.storage-store');

	// Setting
	Route::get('account/{tab}', [SettingController::class, 'account'])->name('setting.account');
	Route::patch('account/profile/update', [SettingController::class, 'profile_update'])->name('setting.profile.update');
	Route::patch('account/password/update', [SettingController::class, 'password_update'])->name('setting.password.update');
	Route::get('setting/{tab}', [SettingController::class, 'site'])->name('setting.site');
	Route::patch('setting/site/update', [SettingController::class, 'site_update'])->name('setting.site.update');
	Route::patch('setting/meta/update', [SettingController::class, 'meta_update'])->name('setting.meta.update');
	Route::patch('setting/maintenance/update', [SettingController::class, 'maintenance_update'])->name('setting.maintenance.update');
	Route::patch('setting/{id}/change', [SettingController::class, 'icolo_update'])->name('setting.icolo.update');
	Route::get('activity-log', [SettingController::class, 'log_activity'])->name('setting.log_activity');
	Route::post('activity-log/list', [SettingController::class, 'log_list'])->name('setting.log_activity.list');
	Route::get('activity-log/{id}/show', [SettingController::class, 'log_detail'])->name('setting.log_activity.show');
	Route::delete('activity-log/clear', [SettingController::class, 'log_clear'])->name('setting.log_activity.clear');
	// Article
	Route::resource('article-category', ArticleCategoryController::class);
	Route::post('article-category/list', [ArticleCategoryController::class, 'list'])->name('article-category.list');
	Route::get('article-category-reorder', [ArticleCategoryController::class, 'reorder'])->name('article-category.reorder');
	Route::patch('article-category/re-order/update', [ArticleCategoryController::class, 'reorder_update'])->name('article-category.reorder.update');
	Route::get('article/publish', [ArticleController::class, 'index_publish'])->name('article.publish');
	Route::get('article/draft', [ArticleController::class, 'index_draft'])->name('article.draft');
	Route::get('article/schedule', [ArticleController::class, 'index_schedule'])->name('article.schedule');
	Route::get('article/create', [ArticleController::class, 'create'])->name('article.create');
	Route::post('article/store', [ArticleController::class, 'store'])->name('article.store');
	Route::get('article/{id}/edit', [ArticleController::class, 'edit'])->name('article.edit');
	Route::patch('article/{id}/update', [ArticleController::class, 'update'])->name('article.update');
	Route::delete('article/delete', [ArticleController::class, 'destroy'])->name('article.destroy');
	Route::post('article/list/{publish}/{category?}', [ArticleController::class, 'list'])->name('article.list');
	Route::post('article/choice/list', [ArticleController::class, 'choice_list'])->name('article.choice.list');
	Route::get('article-option', [ArticleController::class, 'select_option'])->name('article.option');
	// Running
	Route::get('article-running-text', [ArticleRunningController::class, 'select_runningtext'])->name('article.running');
	Route::post('article-running-text/store', [ArticleRunningController::class, 'store_runningtext'])->name('article.running.store');
	Route::get('article-running-text/{id}/delete', [ArticleRunningController::class, 'delete_runningtext'])->name('article.running.delete');
	// Highlight
	Route::get('article-highlight', [ArticleHighlightController::class, 'highlight_option'])->name('article.highlight');
	Route::put('article-highlight/update', [ArticleHighlightController::class, 'highlight_update'])->name('article.highlight.update');
	// Comment
	Route::get('article-comment/{id?}', [ArticleCommentController::class, 'index'])->name('article.comment');
	Route::get('article-comment/{id}/confirm', [ArticleCommentController::class, 'update'])->name('article.comment.update');
	Route::delete('article-comment/{id}/delete', [ArticleCommentController::class, 'destroy'])->name('article.comment.delete');
	// Ad
	Route::resource('ads', AdsController::class);
	Route::post('ads/list', [AdsController::class, 'list'])->name('ads.list');
	Route::get('article/{page}/setup-tp', [AdsController::class, 'ad_option_tp'])->name('article.ad.tp');
	Route::get('article/{id}/ads-setup', [AdsController::class, 'ad_option'])->name('article.ad');
	Route::get('article-category/{id}/ads-setup', [AdsController::class, 'ad_category_option'])->name('article-category.ad');
	Route::post('article/ads-list', [AdsController::class, 'ad_list'])->name('article.ad.list');
	Route::patch('article/{page}/ads-tp-update', [AdsController::class, 'ad_option_tp_update'])->name('article.ad.update.tp');
	Route::patch('article/{id}/ads-update', [AdsController::class, 'ad_option_update'])->name('article.ad.update');
	Route::patch('article-category/{id}/ads-update', [AdsController::class, 'ad_category_option_update'])->name('article-category.ad.update');
	// Contact
	Route::get('contact/{tab}', [ContactController::class, 'index'])->name('contact.index');
	Route::patch('contact/update', [ContactController::class, 'update'])->name('contact.update');
	// Editor
	Route::resource('editor', EditorController::class);
	Route::post('editor/list', [EditorController::class, 'list'])->name('editor.list');
	// Feedback
	Route::get('info/{slug}', [InfoController::class, 'info'])->name('info.edit');
	Route::patch('info/{slug}/save', [InfoController::class, 'update_info'])->name('info.update');
	// Info
	Route::get('profile', [InfoController::class, 'profile'])->name('profile.index');
	Route::patch('profile/update', [InfoController::class, 'update_profile'])->name('profile.update');
	// Links
	Route::get('social-media', [LinkExController::class, 'social'])->name('social.index');
	Route::patch('social-media/update', [LinkExController::class, 'update_social'])->name('social.update');
	Route::get('ecommerce', [LinkExController::class, 'ecommerce'])->name('ecommerce.index');
	Route::patch('ecommerce/update', [LinkExController::class, 'update_ecommerce'])->name('ecommerce.update');

	// set
	Route::get('generate', function (){
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        echo 'linked';
    });
});
Route::prefix('editor')->middleware('is_editor')->group(function () {
	Route::get('/', [SettingController::class, 'dashboard_ae'])->name('ae.dashboard');
	// Article
	Route::resource('articlecategory', AeArticleCategory::class);
	Route::post('articlecategory/list', [AeArticleCategory::class, 'list'])->name('articlecategory.list');
	Route::get('article/publish', [AeArticle::class, 'index_publish'])->name('ae.article.publish');
	Route::get('article/draft', [AeArticle::class, 'index_draft'])->name('ae.article.draft');
	Route::get('article/schedule', [AeArticle::class, 'index_schedule'])->name('ae.article.schedule');
	Route::get('article/create', [AeArticle::class, 'create'])->name('ae.article.create');
	Route::post('article/store', [AeArticle::class, 'store'])->name('ae.article.store');
	Route::get('article/{id}/edit', [AeArticle::class, 'edit'])->name('ae.article.edit');
	Route::patch('article/{id}/update', [AeArticle::class, 'update'])->name('ae.article.update');
	Route::delete('article/delete', [AeArticle::class, 'destroy'])->name('ae.article.destroy');
	Route::post('article/list/{publish}/{category?}', [AeArticle::class, 'list'])->name('ae.article.list');
	Route::post('article/choice/list', [AeArticle::class, 'choice_list'])->name('ae.article.choice.list');
	Route::get('article-option', [AeArticle::class, 'select_option'])->name('ae.article.option');
	// Comment
	Route::get('article-comment/{id?}', [AeArticle::class, 'index_comment'])->name('ae.article.comment');
	Route::get('article-comment/{id}/confirm', [AeArticle::class, 'update_comment'])->name('ae.article.comment.update');
	Route::delete('article-comment/{id}/delete', [AeArticle::class, 'destroy_comment'])->name('ae.article.comment.delete');
});

Route::get('/', [HomeController::class, 'home'])->name('l.home');
Route::get('hubungi-kami', [HomeController::class, 'contact'])->name('l.contact');
Route::get('info/{slug}', [HomeController::class, 'info'])->name('l.info');
Route::get('tentang-kami', [HomeController::class, 'profile'])->name('l.profile');
Route::get('topik/{slug}', [HomeController::class, 'category'])->name('l.category');
Route::get('sediakan_topik/{category}', [HomeController::class, 'category_load'])->name('l.category.load');
Route::get('comments/{slug}', [HomeController::class, 'news_detail_comment_show'])->name('l.news-comment-show');
Route::post('commenting/{slug}', [HomeController::class, 'news_detail_comment'])->name('l.news-comment');
Route::get('cari', [HomeController::class, 'news_search'])->name('l.search');
Route::get('tag/{tag}', [HomeController::class, 'news_tag'])->name('l.tag');
Route::get('/{slug}', [HomeController::class, 'news_detail'])->name('l.news');

