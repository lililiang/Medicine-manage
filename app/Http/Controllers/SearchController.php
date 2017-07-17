<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

use App\Anagraph;
use App\Disease;
use App\Medicament;
use App\MedicineDataSource;
use App\PrescriptionDataSource;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function searchAnagraph(Request $request) {
        $keyword        = $request->get('keyword');
        $search_type    = $request->get('search_type');

        switch ($search_type) {
            case 'anagraph':
                $anagraphs = Anagraph::where('anagraph_name','like','%'.strval($keyword).'%')
                    ->orderBy('ma_id')
                    ->get();

                return view('anagraph.search', compact('anagraphs'));
            case 'medicine':
                $medicines = Medicament::where('medicine_name','like','%'.strval($keyword).'%')
                    ->orderBy('mm_id')
                    ->get();

                return view('medicine.search', compact('medicines'));
            case 'disease':
                $diseases = Disease::where('disease_name','like','%'.strval($keyword).'%')
                    ->orderBy('md_id')
                    ->get();

                return view('disease.search', compact('diseases'));
            case 'medsource':
                $medsources = MedicineDataSource::where('name','like','%'.strval($keyword).'%')
                    ->orderBy('mmds_id')
                    ->get();

                return view('medicinedatasource.search', compact('medsources'));
            default:
                break;
        }
    }

    public function prescriptionSource(Request $request) {
        $keyword        = $request->get('keyword');
        $search_type    = $request->get('search_type');

        $presources = $this->getPrescriptionSearchResult($keyword, $search_type);

        return view('prescriptiondatasource.search', compact('presources', 'keyword', 'search_type'));
    }

    private function getPrescriptionSearchResult($keyword, $search_type) {
        switch ($search_type) {
            case "name":
                $presources = PrescriptionDataSource::where('name','like','%'.strval($keyword).'%')
                    ->orderBy('mp_id')
                    ->get();

                if (!$presources) {
                    $presources = PrescriptionDataSource::where('assist','like','%'.strval($keyword).'%')
                        ->orderBy('mp_id')
                        ->get();
                }

                break;
            case "consist":
                $presources = PrescriptionDataSource::where('components','like','%'.strval($keyword).'%')
                    ->orderBy('mp_id')
                    ->get();
                break;
            case "origin":
                $presources = PrescriptionDataSource::where('origin','like','%'.strval($keyword).'%')
                    ->orderBy('mp_id')
                    ->get();
                break;
            default:
                $presources = null;
                break;
        }

        return $presources;
    }

    public function exportData(Request $request) {
        $keyword        = $request->get('keyword');
        $search_type    = $request->get('search_type');

        $presources = $this->getPrescriptionSearchResult($keyword, $search_type);
        $presources = $presources->toArray();

        if (!empty($presources)) {
            $output_data = [];

            foreach ($presources as $one_source) {
                $tmp_arr = [
                    '名称：' . $one_source['name'],
                    '组成：' . $one_source['components'],
                    '来源：' . $one_source['origin'],
                    '编号：' . $one_source['mp_id']
                ];

                $output_data[] = implode("\n", $tmp_arr);
            }

            $output_data = implode("\n\n", $output_data);

            $file = 'public/prescription.txt';
            Storage::put('public/prescription.txt', $output_data, 'public');

            return '1';
        }

        return '0';
    }
}
