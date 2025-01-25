<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProdukRequest;
use App\Http\Requests\UpdateProdukRequest;
use App\Http\Resources\ProdukResourceCollection;
use App\Http\Resources\ProdukResourceShow;
use App\Service\ProdukService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel as ExcelFormat;

class ProdukController extends Controller
{
    protected $produk;

    /**
     * @param $produk
     */
    public function __construct(ProdukService $produk)
    {
        $this->produk = $produk;
    }

    public function index(Request $request): ProdukResourceCollection
    {
        $search = $request->query('search');
        $sort = $request->query('sort');
        $desc = $request->query('desc');
        $kategori = $request->query('kategori');
        $data = $this->produk->getData($search,$sort,$desc,$kategori);
        return $data;
    }

    public function store(StoreProdukRequest $request): JsonResponse
    {
        $data = $request->validated();
        $response = $this->produk->store($data);
        return response()->json([
            'message' => $response
        ])->setStatusCode(201);
    }
    public function show($id):ProdukResourceShow
    {
        $data = $this->produk->showData($id);
        return $data;
    }
    public function update(UpdateProdukRequest $request,$id)
    {
        $data = $request->validated();
        $response = $this->produk->update($data,$id);
        return response()->json([
            'message' => $response
        ])->setStatusCode(201);
    }

    public function excel(Request $request)
    {
        $search = $request->query('search');
        $kategori = $request->query('kategori');
        $data = $this->produk->printExcel($search,$kategori);
        if ($data){
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ExcelExport($data), 'export.xlsx', ExcelFormat::XLSX);
        }
        return response()->json(['message' => 'No data available for export'], 404);
    }

    public function destroy($id)
    {
        $response = $this->produk->destory($id);
        return response()->json([
            'message' => $response
        ])->setStatusCode(201);
    }

}
