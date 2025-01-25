<?php

namespace App\Repository;

use App\Http\Resources\AkunResource;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AkunRepository
{
    protected $user;

    /**
     * @param $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function storeData(mixed $data)
    {
        $data = $this->user->create($data);

        return $data?true:false;
    }

    public function update(mixed $request, string $filename, $id)
    {
        if(!empty($filename)){
            $cekImg = $this->user->where('id',$id)->select('img')->firstOrFail($id);
            if(isset($cekImg->img)){
                if((Storage::disk('profile')->exists($cekImg->img))) {
                    Storage::disk('profile')->delete($cekImg->img);
                }
            }

            $imagePath = $request['img']->getRealPath();
            Storage::disk('profile')->put($filename, file_get_contents($imagePath));
            $request['img'] = $filename;
        }else{
            unset($request['img']);
        }

        return  $this->user->where('id',$id)->update($request);
    }



    public function getData($id)
    {
        $data = $this->user->where('id',$id)->firstOrFail();
        return new AkunResource($data);
    }

}
