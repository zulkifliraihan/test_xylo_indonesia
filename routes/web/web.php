<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('dev', function () {
    $date1 = new DateTime('2006-04-12T12:30:00');
    $date2 = new DateTime('2006-04-12T11:00:00');

    $diff = $date2->diff($date1);

    $hours = $diff->h;
    $hours = $hours + ($diff->days*24);
    if ($diff->i != 0) {
        $hours = $hours + 1;
    }

    dd($hours, $diff, $hours, $diff->days*24);
    // return view('dashboard.contents.content');
});

Route::get('dev2', function () {
    $star = 5;

    for ($a=1; $a <= $star ; $a++) {
        for ($b=$star; $b >= $a ; $b-=1) {
            echo "&nbsp";
        }

        for ($c=1; $c <= $a ; $c++) {
            echo "**";
        }
        echo "<br>";
    }

    for ($a=1; $a <= $star ; $a++) {
        for ($b=1; $b <= $a ; $b++) {
            echo "&nbsp&nbsp";
        }

        for ($c=$star; $c > $a ; $c-=1) {
            echo "**";
        }
        echo "<br>";

    }
});

// Auth
require 'auth/auth.php';

require 'staff/staff.php';
require 'admin/admin.php';
// Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
