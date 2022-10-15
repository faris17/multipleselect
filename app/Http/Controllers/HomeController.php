<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\Nilai;
use Exception;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Nilai::with('mahasiswa', 'matakuliah')->all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('form_ngampu_mk');
    }

    public function getngampu($id)
    {
        $mk = Nilai::with('matakuliah')->where('mahasiswas_id', $id)->get();
        $data = '';
        foreach ($mk as $row) {

            $data .= "<option value='$row->matakuliahs_id' selected>{$row->matakuliah->nama_matakuliah}</option>";
        }
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            //get data
            $id = $request->mahasiswa_id;
            $dataMK = $request->matakuliah_id;
            $insertData = [];
            for ($i = 0; $i < count($dataMK); $i++) {
                array_push($insertData, ['mahasiswas_id' => $id, 'matakuliahs_id' => $dataMK[$i]]);
            }

            Nilai::insertOrIgnore($insertData);

            return response()->json(['status' => true, 'message' => "Berhasil Simpan"]);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
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
        $edit = Nilai::with(['mahasiswas', 'matakuliahs'])->find($id);
        return view('form_edit_ngampu', compact('edit'));
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

    public function selectmhs()
    {
        $data = Mahasiswa::where('nama', 'LIKE', '%' . request('q') . '%')->paginate(10);

        return response()->json($data);
    }

    public function selectmk($id)
    {
        //get data nilai berdasarkan idmhs;
        $mk = Nilai::select('matakuliahs_id')->where('mahasiswas_id', $id)->get();
        $data = Matakuliah::whereNotIn('id', $mk)->where('nama_matakuliah', 'LIKE', '%' . request('q') . '%')->paginate(10);

        return response()->json($data);
    }
}
