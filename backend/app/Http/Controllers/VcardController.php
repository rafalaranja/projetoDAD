<?php

namespace App\Http\Controllers;



use App\Http\Requests\NewVcardRequest;
use App\Services\Base64Services;
use App\Http\Resources\VcardResource;
use App\Models\VCard;

class VcardController extends Controller
{

    private function storeBase64AsFile(VCard $user, String $base64String)
    {
        $targetDir = storage_path('app/public/fotos');
        $newfilename = $user->phone_number . "_" . rand(1000,9999);
        $base64Service = new Base64Services();
        return $base64Service->saveFile($base64String, $targetDir, $newfilename);
    }

    public function store(NewVcardRequest $request)
    {
        
        // Os dados já foram validados pelo NewVcardRequest
        $validated = $request->validated();
       
    
        // Criação do novo vCard
        $vcard = new VCard();
        $vcard->name = $validated['name'];
        $vcard->phone_number = $validated['phone'];
        $vcard->email = $validated['email'];
        $vcard->password = bcrypt($request->validated()['password']);
        $vcard->blocked = false;
        $vcard->confirmation_code = bcrypt($request->validated()['pin']);

        if ($request->has('photo') && $request->photo != '') {
            $vcard->photo_url = $this->storeBase64AsFile($vcard, $request->photo);
        }
        
       
        $vcard->save();
    
        return new VcardResource($vcard);
    }
    
}
