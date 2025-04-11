<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function updateType(Request $request, $depannageId)
    {
        // Enregistrement du type
        $type = Type::where('depannage_id', $depannageId)->first();
        if($type){
            $type->garantie = $request->input('garantie');
            $type->contrat = $request->input('contrat');
            $type->save();
        } else {
            return response()->json(['message' => 'Type non trouvé!'], 404);
        }

        return response()->json(['message' => 'Type ajouté avec succès!']);
    }
}
