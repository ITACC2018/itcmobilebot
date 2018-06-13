<?php
use Illuminate\Http\Request;

Route::get('/', 'HomeController@index')->name('/');

Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman/tinker', 'BotManController@tinker');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/faq/datatables', 'FaqController@anyData');
Route::resource('faq', 'FaqController');
Route::get('/faq', 'FaqController@index')->name('faq');
Route::get('/faq/getcategory/{id}/{type}', 'FaqController@getCategory');

Route::get('/category-faqs/datatables', 'CategoryFaqsController@anyData');
Route::resource('categoryfaqs', 'CategoryFaqsController');
Route::get('/category-faqs', 'CategoryFaqsController@index')->name('category-faqs');

//Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'auth'], function() {
    Route::get('/posts/datatables', 'PostController@anyData');
    Route::resource('/posts', 'PostController');
    Route::put('/posts/{post}/publish', 'PostController@publish')->middleware('admin');
    Route::resource('/categories', 'CategoryController', ['except' => ['show']]);
    Route::resource('/tags', 'TagController', ['except' => ['show']]);
    Route::resource('/comments', 'CommentController', ['only' => ['index', 'destroy']]);
    Route::resource('/users', 'UserController', ['middleware' => 'admin', 'only' => ['index', 'destroy']]);
//});

// Route::get('/redirect', function () {
//     $query = http_build_query([
//         'client_id' => '1',
//         'redirect_uri' => 'http://itcmobilebot.local/callback',
//         'response_type' => 'code',
//         'scope' => '',
//     ]);

//     return redirect('http://itcmobilebot.local/oauth/authorize?'.$query);
// })->name('get.token');


// Route::get('/callback', function (Request $request) {
//     $http = new GuzzleHttp\Client;
//     $response = $http->post('http://itcmobilebot.local/oauth/token', [
//         'form_params' => [
//             'grant_type' => 'authorization_code',
//             'client_id' => 'client-id',
//             'client_secret' => 'client-secret',
//             'redirect_uri' => 'http://example.com/callback',
//             'code' => $request->code,
//         ],
//     ]);
//     return json_decode((string) $response->getBody(), true);
// });

// Route::post('/login-post', function (Request $request) {
//     $http = new GuzzleHttp\Client;
//     $response = $http->post('http://itcmobilebot.local/oauth/token', [
//             'form_params' => [
//                 'grant_type' => 'password',
//                 'client_id' => '1',
//                 'client_secret' => '2xZMmJOEjiQJ8VU3TznLmmbrKp1pZARMD0VJUD3U',
//                 'username' => 'bundanarty@gmail.com',
//                 'password' => 'acr473',
//                 'scope' => '',
//             ],
//     ]);
//     return json_decode((string) $response->getBody(), true);
// });