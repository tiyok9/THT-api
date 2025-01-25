<?php

namespace App\Service\Impl;

use App\Repository\AkunRepository;
use App\Service\AkunService;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AkunServiceImpl implements AkunService
{
    protected $user;

    /**
     * @param $user
     */
    public function __construct(AkunRepository $user)
    {
        $this->user = $user;
    }

    public function storeData(mixed $data)
    {
        $data['password']= Hash::make($data['password']);
        DB::connection();
        try {
            $response = $this->user->storeData($data);
            if ($response == true){
                DB::commit();
                return $response;
            }
            DB::rollBack();
            return $response;
        }   catch (Exception $exception){
            Log::debug($exception->getMessage());
            DB::rollBack();
            throw new HttpResponseException(response([
                'errors' => "Failed Registration"
            ],400));
        }
    }

    public function update(mixed $request, $id)
    {
        $filename = '';
        if(isset($request['img']) && $request['img'] !== 'undefined'){
            $file = $request['img'];
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
        }
        DB::connection();
        try {
            $response= $this->user->update($request,$filename,$id);
            if ($response){
                DB::commit();
                return $this->user->getData($id);
            }
            DB::rollBack();
            return [
                'message' => $response
            ];
        }catch (Exception $exception){
            Log::debug($exception->getMessage());
            DB::rollBack();
            if((Storage::disk('profile')->exists($filename))) {
                Storage::disk('profile')->delete($filename);
            }
            throw new HttpResponseException(response([
                'errors' => "Failed Upload Image"
            ],400));
        }
    }


}
