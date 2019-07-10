<?php

namespace App\Demand;

use App\User\User;
use Illuminate\Http\Request;
use App\Candidature\Candidature;
use Metko\Galera\Facades\Galera;
use App\Exceptions\CantContactUser;
use App\Demand\Requests\StoreDemand;
use App\Http\Controllers\Controller;
use App\Notifications\CandidatureSubmit;

class DemandController extends Controller
{
    /**
     * List all the demand active not owned by the current user.
     */
    public function index()
    {
        $now = now()->toString();
        $demands = Demand::with('owner.roles', 'candidatures')
                    ->where('owner_id', '!=', auth()->user()->id)
                    ->where('valid_until', '>=', now())
                    ->orderBy('valid_until', 'asc')
                    ->whereContracted(false)->get();

        return view('demands.index', compact('demands'));
    }

    /**
     * Open a demand.
     *
     * @param mixed $demand
     */
    public function show(Demand $demand)
    {
        return view('demands.show', compact('demand'));
    }

    /**
     * store a demand.
     *
     * @param mixed $request
     */
    public function store(StoreDemand $request)
    {
        $attr = $request->all();
        $attr['valid_until'] = now()->addMonths(1);
        $attr['status'] = 'default';

        $demand = Demand::create($attr);
    }

    /**
     * showApply.
     *
     * @param mixed $demand
     */
    public function showApply(Demand $demand)
    {
        $this->authorize('apply', $demand);

        return view('demands.apply', compact('demand'));
    }

    /**
     * apply.
     *
     * @param mixed $demand
     * @param mixed $request
     */
    public function apply(Demand $demand, Request $request)
    {
        $this->authorize('apply', $demand);
        $candidature = $request->validate([
            'content' => 'required|min:20',
        ]);
        $candidature['owner_id'] = $request->user()->id;
        $candidature = $request->user()->apply($demand, $candidature);

        if ($request->ajax()) {
            sleep(2);

            return response()->json($candidature, 200);
        }

        return redirect(route('demands.show', $demand->id))
                ->with('success', 'Vous avez bien soumis votre candidature');
        //$demand->owner->notify(new CandidatureSubmit($candidature));
    }

    /**
     * update.
     *
     * @param mixed $demand
     * @param mixed $request
     */
    public function update(Demand $demand, Request $request)
    {
        $this->authorize('manage', $demand);
        $demand->update($request->all());
    }

    /**
     * contracted.
     *
     * @param mixed $demand
     */
    public function contracted(Demand $demand)
    {
        $this->authorize('manage', $demand);
        $demand->contract();
    }

    /**
     * delete.
     *
     * @param mixed $demand
     */
    public function delete(Demand $demand)
    {
        $this->authorize('manage', $demand);
        $demand->delete();
    }

    /**
     * restore.
     *
     * @param mixed $id
     */
    public function restore($id)
    {
        $demand = Demand::withTrashed()->findOrFail($id);
        $this->authorize('manage', $demand);
        $demand->restore();
    }

    /**
     * destroy.
     *
     * @param mixed $demand
     */
    public function destroy(Demand $demand)
    {
        $this->authorize('manage', $demand);
        $demand->forceDelete();
    }

    /**
     * contacttCandidature.
     *
     * @param mixed $demand
     * @param mixed $candidature
     * @param mixed $request
     */
    public function contactCandidature(Demand $demand, User $userCandidature, Request $request)
    {
        $this->authorize('manage', $demand);
        if ($userCandidature->hasApply($demand)) {
            $user = $request->user();
            if (!$conversation = Galera::converationExist([$user, $userCandidature])) {
                $conversation = Galera::participants($user, $userCandidature)->make();
            }
            $user->write($request->message, $conversation->id);
        } else {
            throw CantContactUser::create();
        }
    }

    /**
     * contractCandidature.
     *
     * @param mixed $demand
     * @param mixed $candidature
     * @param mixed $request
     */
    public function contractCandidature(Demand $demand, Candidature $candidature, Request $request)
    {
        $this->authorize('manage', $demand);

        if ($contract = $demand->contractCandidature($candidature)) {
            return redirect(route('contracts.show', $contract->id))->with('success', 'contract created with success');
        }
        abort(500, 'Une erreur est survenue lors de la création du contrat');
    }
}
