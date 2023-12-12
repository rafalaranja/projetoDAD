<?php

namespace App\Http\Controllers;



use App\Http\Requests\NewVcardRequest;
use App\Http\Requests\DeleteVcardRequest;
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
    public function index(){
        
        $vcard = Vcard::all();
        return response ()->json(['data'=>$vcard],200);
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

    public function destroy(DeleteVcardRequest $request)
    {
        $user = $request->user(); // Obtém o usuário logado
    
        if ($user) {
            $vcard = VCard::find($user->id); // Obtém o VCard do usuário logado
    
            if ($vcard) {
                $vcard->delete();
                return response()->json(null, 204);
            } else {
                return response()->json(['error' => 'VCard not found'], 404);
            }
        } else {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    }

    public function load($user)
{
    $vcard = VCard::where('phone_number', $user)->first();

    if ($vcard) {
        return new VcardResource($vcard);
    } else {
        return response()->json(['error' => 'VCard not found'], 404);
    }
}
    
}
