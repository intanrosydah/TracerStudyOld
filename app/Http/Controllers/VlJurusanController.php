<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class VLJurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $data = Jurusan::get();
            if ($request->ajax()) {
                $allData = DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('aksi', function($row) {
                        $button = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'. $row->id .'"
                            data-original-title="Edit" class="btn btn-success btn-sm editJurusan"><i class="fas fa-fw fa-pen"></i></a>';
                        $button .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'. $row->id .'"
                            data-original-title="Hapus" class="btn btn-danger btn-sm hapusJurusan ml-1"><i class="fas fa-fw fa-trash-alt"></i></a>';

                        return $button;
                    })
                    ->rawColumns(['aksi'])
                    ->make(true);

                return $allData;
            }

            return view('jurusan', [
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
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
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = [
                'jurusan' => $request->jurusan,
            ];

            $jurusan = Jurusan::updateOrCreate([
                'id' => $request->id_jurusan
            ], $data);

            DB::commit();
            return response()->json([
                'success' => 'Data berhasil disimpan',
                'data' => $jurusan
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
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
        try {
            $jurusan = Jurusan::where('id', $id)->first();

            return response()->json($jurusan);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
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
        try {
            DB::beginTransaction();

            $jurusan = Jurusan::where('id', $id)->delete();

            DB::commit();
            return response()->json([
                'message' => 'Data berhasil dihapus',
                'data' => $jurusan
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th->getMessage();
        }
    }
}
