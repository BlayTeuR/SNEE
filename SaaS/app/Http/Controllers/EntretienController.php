<?php

namespace App\Http\Controllers;

use App\Models\Entretien;
use App\Models\Historique;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EntretienController extends Controller
{

    public function index(Request $request)
    {
        $query = Entretien::with('historiques');

        // Filtrer par nom si l'utilisateur a saisi quelque chose
        if ($request->filled('nom')) {
            $query->where('nom', 'like', '%' . $request->input('nom') . '%');
        }

        // Filtrer par date minimale si spécifiée
        if ($request->filled('date_min')) {
            $query->whereDate('derniere_date', '>=', $request->input('date_min'));
        }

        // Filtrer par date maximale si spécifiée
        if ($request->filled('date_max')) {
            $query->whereDate('derniere_date', '<=', $request->input('date_max'));
        }

        // Vérifie si le bouton toggle pour le mois courant est activé
        // Par défaut, on filtre par mois courant si le paramètre 'mois_courant' n'est pas précisé
        // Si explicitement "on", on filtre mois courant
        if ($request->input('mois_courant', 'on') === 'on') {
            $query->whereMonth('derniere_date', Carbon::now()->month)
                ->whereYear('derniere_date', Carbon::now()->year);
        }


        // Récupérer les entretiens filtrés et trier par date de création
        $entretiens = $query->where('archived', false)->orderBy('created_at', 'asc')->get();

        // Retourner la vue avec les entretiens
        return view('entretien', compact('entretiens'));
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

    public function updateDate(Request $request, $id)
    {
        try {
            $entretien = Entretien::findOrFail($id);
            $entretien->derniere_date = $request->input('date');
            $entretien->save();

            return response()->json(['message' => 'Date mise à jour avec succès'], 200); // <--- ICI
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la mise à jour'], 500); // <--- et ici
        }
    }

    public function archiver($id)
    {
        $entretien = Entretien::find($id);

        if ($entretien) {
            $entretien->archived = true;
            $entretien->save();

            return response()->json(['message' => 'Entretien archivé avec succès!']);
        }

        return response()->json(['message' => 'Entretien non trouvé'], 404);
    }

    public function desarchiver($id)
    {
        $entretien = Entretien::find($id);

        if ($entretien) {
            $entretien->archived = false;
            $entretien->save();

            return response()->json(['message' => 'Entretien désarchivé avec succès!']);
        }

        return response()->json(['message' => 'Entretien non trouvé'], 404);
    }
}
