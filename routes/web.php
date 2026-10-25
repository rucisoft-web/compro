<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/solusi/{solution}', [PageController::class, 'solution'])->name('solutions.show');
Route::get('/academy', [PageController::class, 'academy'])->name('academy');
Route::post('/prospek', [LeadController::class, 'store'])->middleware('throttle:10,1')->name('leads.store');
Route::get('/terima-kasih', [LeadController::class, 'thanks'])->name('leads.thanks');
Route::get('/artikel', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/artikel/{article}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/katalog', [PageController::class, 'catalog'])->name('catalog.index');
Route::get('/katalog/{item}', [PageController::class, 'catalogItem'])->name('catalog.show');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
