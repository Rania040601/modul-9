<?php

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;

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
// // Route sebelum ada login

// // route home
// Route::get('home', [HomeController::class, 'index'])->name('home');

// // route profile
// Route::get('profile', ProfileController::class)->name('profile');

// // route emplyoyee
// Route::resource('employees', EmployeeController::class);

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/', function () {
    return view('welcome');
});

Route::redirect('/', '/login');

Auth::routes();

// // buat route grouping
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('profile', ProfileController::class)->name('profile');
    Route::resource('employees', EmployeeController::class);

    // untuk download file di
    Route::get('download-file/{employeeId}', [EmployeeController::class, 'downloadFile'])->name('employees.downloadFile');

});

// untuk meletakkan file pada lokal disk
Route::get('/local-disk', function() {
    Storage::disk('local')->put('local-example.txt', 'This is local example content');
    return asset('storage/local-example.txt');
});

// Meletakkan File pada Public Disk
Route::get('/public-disk', function() {
    Storage::disk('public')->put('public-example.txt', 'This is public example content');
    return asset('storage/public-example.txt');
});

// Menampilkan Isi File Local
Route::get('/retrieve-local-file', function() {
    if (Storage::disk('local')->exists('local-example.txt')) {
        $contents = Storage::disk('local')->get('local-example.txt');
    } else {
        $contents = 'File does not exist';
    }

    return $contents;
});

// Menampilkan Isi File Public
Route::get('/retrieve-public-file', function() {
    if (Storage::disk('public')->exists('public-example.txt')) {
        $contents = Storage::disk('public')->get('public-example.txt');
    } else {
        $contents = 'File does not exist';
    }

    return $contents;
});

// Mendownload File Local
Route::get('/download-local-file', function() {
    return Storage::download('local-example.txt', 'local file');
});

// Mendownload File Public
Route::get('/download-public-file', function() {
    return Storage::download('public/public-example.txt', 'public file');
});

// Menampilkan URL file
Route::get('/file-url', function() {
    // Just prepend "/storage" to the given path and return a relative URL
    $url = Storage::url('local-example.txt');
    return $url;
});

// Menampilkan size file
Route::get('/file-size', function() {
    $size = Storage::size('local-example.txt');
    return $size;
});

// Menampilkan path file
Route::get('/file-path', function() {
    $path = Storage::path('local-example.txt');
    return $path;
});


// Menampilkan file yang dibuat dengan nama upload_example.blade.php
Route::get('/upload-example', function() {
    return view('upload_example');
});

// Menyimpan file via form
Route::post('/upload-example', function(Request $request) {
    $path = $request->file('avatar')->store('public');
    return $path;
})->name('upload-example');

// Menghapus file local pada storage
Route::get('/delete-local-file', function(Request $request) {
    Storage::disk('local')->delete('local-example.txt');
    return 'Deleted';
});

// Menghapus file public pada storage
Route::get('/delete-public-file', function(Request $request) {
    Storage::disk('public')->delete('public-example.txt');
    return 'Deleted';
});

Route::get('download-file/{employeeId}', [EmployeeController::class, 'downloadFile'])->name('employees.downloadFile');
