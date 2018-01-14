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
Route::post('delete', 'AnagraphController@deleteAnagraph');
Route::post('deleteSource', 'AnagraphController@deleteAnagraphSourceRelation');
Route::get('import', function () {
    return view('anagraph.import');
});

Route::post('uploadAnagraphs', 'AnagraphController@uploadAnagraphs');

Route::get('add', function () {
    return view('anagraph.add');
});

Route::post('create', 'AnagraphController@createAnagraph');
Route::post('calculate', 'AnagraphController@calculateSimilarity');

Route::get('medicines', 'MedicineController@list');
Route::get('medicineDetail/{mm_id}', 'MedicineController@showMedicine');
Route::post('deleteMedSource', 'MedicineController@delteMedicineRelation');
Route::post('setMedMissed', 'MedicineController@setMedicineMissing');

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

Route::get('syndromes', 'SyndromeController@list');
Route::get('addSyndrome', function () {
    return view('syndrome.add');
});
Route::get('syndromeDetail/{mts_id}', 'SyndromeController@showSyndrome');
Route::get('editSyndromeAlias/{mts_id}', 'SyndromeController@editSyndromeAlias');
Route::post('doeditSyndromeAlias', 'SyndromeController@doEditSyndromeAlias');
Route::post('createSyndrome', 'SyndromeController@createSyndrome');
Route::post('deleteSyndrome', 'SyndromeController@deleteSyndrome');

Route::get('prescriptions', 'PrescriptionDataSourceController@list');
Route::get('prescriptionDetail/{mp_id}', 'PrescriptionDataSourceController@detail');

Route::get('meddict', 'MedicineDataSourceController@list');
Route::get('medDetail/{mmds_id}', 'MedicineDataSourceController@detail');

Route::post('search/anagraph', 'SearchController@searchAnagraph');
Route::post('search/presource', 'SearchController@prescriptionSource');
Route::post('search/exportFile', 'SearchController@exportData');

Route::get('download',function(){
    return response()->download(storage_path() . '/app/public/prescription.txt', 'prescription.txt');
});

Route::get('anacollections', 'AnagraphCollectionsController@list');

Auth::routes();
Route::get('home', 'HomeController@index');
