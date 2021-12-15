<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Criteria;
use App\Package;
use App\AlternativeCriteria;
use Session;

class SmartOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = Package::where('status', 100)->get();
        $criterias = Criteria::all();

        return view("frontend.smart-order.index")->with(compact('packages', 'criterias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function process(Request $request)
    {
        $alternatif = array(); 
        $alternatif_ids = array(); 
        
        $data_alternative = Package::where('status', 100)->get();
        $i=0;
        foreach ($data_alternative as $item) {
            if ($request->input('alternatif'.$item->id) == "true"){
                $alternatif[$i] = $item->name;
                $alternatif_ids[$i] = $item->id;
                $i++;
            }
        }

		$kriteria = array(); 
		$bobot = array(); 

        $data_criteria = Criteria::all();
        foreach ($data_criteria as $key => $item) {
            $kriteria[$key] = $item->name;
            $bobot[$key] = $request->input('bobot'.$item->id);
        }
		
		$alternatifkriteria = array(); 

        $i=0;
        foreach ($data_alternative as $item) {
            if ($request->input('alternatif'.$item->id) == "true"){
                $j=0;
                foreach ($data_criteria as $val) {
                    $qac = AlternativeCriteria::where('alternative_id', $item->id)->where('criteria_id', $val->id)->first();
                    $alternatifkriteria[$i][$j] = $qac['score'];
                    $j++;
                }
                $i++;
            }
        }
			
		$jumlahbobot = 0;
	
        $normalisasibobot = array();
        
        for ($i=0;$i<count($kriteria);$i++)
        {
            $jumlahbobot = $jumlahbobot + $bobot[$i];
        }
        
        for ($i=0;$i<count($kriteria);$i++)
        {
            $normalisasibobot[$i] = $bobot[$i] / $jumlahbobot;
        }
        
        $total_nilai = array();
        
        for ($i=0;$i<count($alternatif);$i++)
        {
            $total_nilai[$i] = 0; 
            for ($j=0;$j<count($kriteria);$j++)
            {
                $total_nilai[$i] = $total_nilai[$i] + ($alternatifkriteria[$i][$j] * $normalisasibobot[$j]);
            }
        }	
        
        $alternatifrangking = array();
        $alternatifrangking_id = array();
        $hasilrangking = array();
        
        for ($i=0;$i<count($alternatif);$i++)
        {
            $hasilrangking[$i] = $total_nilai[$i];
            $alternatifrangking[$i] = $alternatif[$i];
            $alternatifrangking_id[$i] = $alternatif_ids[$i];
        }
        
        for ($i=0;$i<count($alternatif);$i++)
        {
            for ($j=$i;$j<count($alternatif);$j++)
            {
                if ($hasilrangking[$j] > $hasilrangking[$i])
                {
                    $tmphasil = $hasilrangking[$i];
                    $tmpalternatif = $alternatifrangking[$i];
                    $hasilrangking[$i] = $hasilrangking[$j];
                    $alternatifrangking[$i] = $alternatifrangking[$j];
                    $hasilrangking[$j] = $tmphasil;
                    $alternatifrangking[$j] = $tmpalternatif;

                    $tmpalternatifrangking_id = $alternatifrangking_id[$i];
                    $alternatifrangking_id[$i] = $alternatifrangking_id[$j];
                    $alternatifrangking_id[$j] = $tmpalternatifrangking_id;
                }
            }
        }

        return view('frontend.smart-order.result')->with(compact('alternatif', 'alternatifrangking_id', 'kriteria', 'bobot', 'alternatifkriteria', 'normalisasibobot', 'total_nilai', 'hasilrangking', 'alternatifrangking'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailPackage(Request $request)
    {
        $id = $request->id;
        $package = Package::find($id);

        return $package;
    }
}
