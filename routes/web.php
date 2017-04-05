<?php

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
    return redirect('home');
});

Route::get('list', 'AnagraphController@list');
Route::get('detail/{ma_id}', 'AnagraphController@showAnagraph');
Route::get('edit/{ma_id}', 'AnagraphController@editAnagraph');
Route::post('doedit', 'AnagraphController@doEditAnagraph');

Route::get('add', function () {
    return view('anagraph.add');
});

Route::post('create', 'AnagraphController@createAnagraph');

Route::get('medicines', 'MedicineController@list');

Route::get('diseases', 'DiseaseController@list');
Route::get('addDisease', function () {
    return view('disease.add');
});
Route::get('diseaseDetail/{md_id}', 'DiseaseController@showDisease');
Route::get('editDiseaseAlias/{md_id}', 'DiseaseController@editDiseaseAlias');
Route::post('doeditDiseaseAlias', 'DiseaseController@doEditDiseaseAlias');
Route::get('editDiseaseSyndromes/{md_id}', 'DiseaseController@editDiseaseSyndromes');
Route::post('doeditDiseaseSyndromes', 'DiseaseController@doEditDiseaseSyndromes');
Route::post('createDisease', 'DiseaseController@createDisease');

Auth::routes();
Route::get('home', 'HomeController@index');
