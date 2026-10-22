<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/solusi/{solution}', [PageController::class, 'solution'])->name('solutions.show');
Route::get('/katalog', [PageController::class, 'catalog'])->name('catalog.index');
Route::get('/katalog/{item}', [PageController::class, 'catalogItem'])->name('catalog.show');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
