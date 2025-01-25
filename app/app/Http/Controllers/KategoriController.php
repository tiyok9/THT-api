<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKategoriRequest;
use App\Http\Resources\KategoriResource;
use App\Http\Resources\KategoriResourceCollection;
use App\Service\KategoriService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    protected $kategori;

    /**
     * @param $kategori
     */
    public function __construct(KategoriService $kategori)
    {
        $this->kategori = $kategori;
    }

    public function index(Request $request): KategoriResourceCollection
    {
        $search = $request->query('search');
        $params = $request->query('query');
        $sort = $request->query('sort');
        $desc = $request->query('desc');
        $data = $this->kategori->getData($search,$params,$sort,$desc);
        return $data;
    }

    public function store(StoreKategoriRequest $request): JsonResponse
    {
        $data = $request->validated();
        $response = $this->kategori->store($data);
        return response()->json([
            'message' => $response
        ])->setStatusCode(201);
    }

    public function show($id):KategoriResource
    {
        $data = $this->kategori->showData($id);
        return $data;
    }

    public function update(StoreKategoriRequest $request,$id): JsonResponse
    {
        $data = $request->validated();
        $response = $this->kategori->update($data,$id);
        return response()->json([
            'message' => $response
        ])->setStatusCode(201);
    }

    public function destroy($id): JsonResponse
    {
        $response = $this->kategori->destory($id);
        return response()->json([
            'message' => $response
        ])->setStatusCode(201);
    }
}
