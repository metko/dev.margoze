<?php

namespace App\Demand;

use App\User\User;
use Carbon\Carbon;
use App\Sector\Sector;
use App\Commune\Commune;
use App\Category\Category;
use App\Contract\Contract;
use Illuminate\Support\Str;
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
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->get($request);
        }

        $now = now()->toString();
        $sectors = Sector::all();
        $communes = Commune::all();
        $totalDemands = Demand::all()->count();

        return view('demands.index', compact('sectors', 'communes', 'totalDemands'));
    }

    public function get(Request $request)
    {
        $now = now()->toString();
        //dd($request->input('page'));
        $demands = Demand::with('owner.roles', 'category', 'sector', 'commune', 'district')
                    // ->where('owner_id', '!=', auth()->user()->id)
                    ->withCount('candidatures')
                    ->where('contracted', '!=', null)
                    ->where('valid_until', '>=', now())
                    ->orderBy('valid_until', 'asc')
                    ->whereContracted(false);
        sleep(1);
        if ($request->query('sector')) {
            $demands = $demands->where('sector_id', $request->query('sector'));
        }

        if ($request->query('commune')) {
            $demands = $demands->where('commune_id', $request->query('commune'));
        }

        $demands = $demands->paginate(6);
        // dd($demands->first());
        if ($request->ajax()) {
            //sleep(1);
            //var_dump(response()->json(compact('demands')));

            return response()->json(compact('demands'));
        }
    }

    /**
     * Open a demand.
     *
     * @param mixed $demand
     */
    public function show($demand)
    {
        $demand = Demand::whereId($demand)->with(
            ['candidatures', 'owner', 'sector', 'commune', 'district', 'category', 'images']
            )->first();
        if ($demand->contracted) {
            $contract = Contract::where('demand_id', $demand->id)->first();
            $demand['contract'] = $contract;
        }
        $auth = auth()->user() ?? 'guest';

        return view('demands.show', compact('demand', 'auth'));
    }

    /*
    * @param mixed $request
    */
    public function create()
    {
        $user = auth()->user();
        $sectors = Sector::all()->mapWithKeys(function ($sector) {
            return [$sector['id'] => $sector['name']];
        });
        $categories = Category::all()->mapWithKeys(function ($category) {
            return [$category['id'] => $category['name']];
        });

        return view('demands.create', compact('user', 'sectors', 'categories'));
    }

    /**
     * store a demand.
     *
     * @param mixed $request
     */
    public function store(StoreDemand $request)
    {
        $attr = $request->except(['images']);

        $attr['be_done_at'] = Carbon::parse($attr['be_done_at']);
        $attr['valid_until'] = now()->addMonths(1);
        $attr['status'] = 'default';
        $attr['owner_id'] = $request->user()->id;
        $attr['contracted'] = false;
        $attr['budget'] = 0;

        $demand = Demand::create($attr);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $k => $image) {
                $image_name = $demand->id.'_'.$k.'_'.Str::slug($demand->title).'.jpg';
                if ($image->isValid()) {
                    $image->storeAs('/public/demands', $image_name);
                    $demand->images()->create([
                        'slug' => Str::slug($demand->title).'_'.$demand->id.$k,
                        'name' => 'Image '.$demand->title,
                        'path' => '/storage/demands/'.$demand->id.'_'.$k.'_'.Str::slug($demand->title).'.jpg',
                        'owner_id' => auth()->user()->id,
                    ]);
                }
            }
        }
        $data = [
            'demand' => $demand,
            'statut' => 'success',
        ];
        if ($request->ajax()) {
            return response()->json($data);
        }

        return redirect(route('demands.show', $demand->id))->with('success', 'Demande bien crée');
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
            if (request()->ajax()) {
                sleep(2);

                return response()->json(compact('contract'));
            }

            return redirect(route('contracts.show', $contract->id))->with('success', 'contract created with success');
        }
        abort(500, 'Une erreur est survenue lors de la création du contrat');
    }
}
