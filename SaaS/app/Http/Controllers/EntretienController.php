<?php

namespace App\Http\Controllers;

use App\Models\Entretien;
use App\Models\Historique;
use Illuminate\Http\Request;

class EntretienController extends Controller
{
    public function index(){
        $query = Entretien::with('historiques');
        $entretiens = $query->where('archived', '=', false)->get();
        return view('entretien', compact('entretiens'));
    }

    public function show($id){
        $entretien = Entretien::findOrFail($id);
        return view('entretien.show', compact('entretien'));
    }

    public function destroy($id)
    {
        $entretien = Entretien::find($id);

        if ($entretien) {
            $entretien->delete();

            $historiques = Historique::where('historiqueable_id', $id)
                ->where('historiqueable_type', Entretien::class)
                ->get();

            if ($historiques->count() > 0) {
                $historiques->each->delete();
            }

            return response()->json(['message' => 'Entretien supprimé avec succès!']);
        }

        return response()->json(['message' => 'Entretien non trouvé'], 404);
    }

    public function store(Request $request){
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'add' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'panne' => 'required|string|max:255',
                'tel' => 'required|string|max:20',
                'demande_type' => 'required|string|in:portail,portillon,barrière,tourniquet',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'date' => 'nullable|date',
            ]);

            $entretien = Entretien::create([
                'nom' => $request->input('name'),
                'adresse' => $request->input('add'),
                'contact_email' => $request->input('email'),
                'panne_vigilance' => $request->input('panne'),
                'telephone' => $request->input('tel'),
                'type_materiel' => $request->input('demande_type'),
                'derniere_date' => $request->input('date'),
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    if ($image->isValid()) {
                        $imageName = time() . '_' . $image->getClientOriginalName();
                        $image->move(public_path('images'), $imageName);

                        $entretien->photos()->create([
                            'chemin_photo' => $imageName,
                        ]);
                    }
                }
            }
            return redirect('/entretien')->with('success', 'Votre demande a été enregistrée avec succès.');
        }catch(\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement de votre demande.' . $e->getMessage());
        }
    }
}
