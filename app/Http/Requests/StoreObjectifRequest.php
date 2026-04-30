<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreObjectifRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $tabObjectif = collect($this->input('tabObjectif', []))
            ->map(function ($objectif) {
                $actions = collect($objectif['actions'] ?? [])
                    ->map(fn($action) => trim((string)$action))
                    ->filter(fn($action) => $action !== '')
                    ->values()
                    ->all();

                $resultats = collect($objectif['resultats'] ?? [])
                    ->map(fn($resultat) => trim((string)$resultat))
                    ->filter(fn($resultat) => $resultat !== '')
                    ->values()
                    ->all();

                return [
                    'libelle' => trim((string)($objectif['libelle'] ?? '')),
                    'actions' => $actions,
                    'resultats' => $resultats,
                    'echeance' => $objectif['echeance'] ?? null,
                ];
            })
            ->values()
            ->all();

        $this->merge([
            'tabObjectif' => $tabObjectif,
        ]);
    }

    public function rules()
    {
        return [
            'type' => 'required|string|in:formObjectif,formEntretien',
            'tabObjectif' => 'required|array|min:1|max:5',
            'tabObjectif.*.libelle' => 'required|string|max:255',
            'tabObjectif.*.actions' => 'required|array|min:1',
            'tabObjectif.*.actions.*' => 'required|string|max:255',
            'tabObjectif.*.resultats' => 'required|array|min:1',
            'tabObjectif.*.resultats.*' => 'required|string|max:255',
            'tabObjectif.*.echeance' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Le type du formulaire est obligatoire.',
            'type.in' => 'Le type du formulaire est invalide.',
            'tabObjectif.required' => 'Veuillez ajouter au moins un objectif.',
            'tabObjectif.array' => 'Le format des objectifs est invalide.',
            'tabObjectif.min' => 'Veuillez ajouter au moins un objectif.',
            'tabObjectif.max' => 'Vous ne pouvez pas soumettre plus de 5 objectifs à la fois.',
            'tabObjectif.*.libelle.required' => 'Le libellé de l’objectif est obligatoire.',
            'tabObjectif.*.actions.required' => 'Veuillez renseigner au moins une action clé par objectif.',
            'tabObjectif.*.actions.min' => 'Veuillez renseigner au moins une action clé par objectif.',
            'tabObjectif.*.resultats.required' => 'Veuillez renseigner au moins un résultat attendu par objectif.',
            'tabObjectif.*.resultats.min' => 'Veuillez renseigner au moins un résultat attendu par objectif.',
            'tabObjectif.*.echeance.required' => 'L’échéance de l’objectif est obligatoire.',
            'tabObjectif.*.echeance.date' => 'L’échéance de l’objectif doit être une date valide.',
        ];
    }
}
