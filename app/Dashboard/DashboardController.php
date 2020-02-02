<?php

namespace App\Dashboard;

use App\Demand\Demand;
use Spatie\Image\Image;
use App\Commune\Commune;
use App\Contract\Contract;
use App\District\District;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use App\Candidature\Candidature;
use Metko\Galera\Facades\Galera;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    /**
     * index.
     */
    public function index()
    {
        $demandsCount = Demand::all()
                ->where('valid_until', '>', now())
                ->where('contracted', true)
                ->where('owner_id', auth()->user()->id)->count();
        $candidaturesCount = Candidature::whereHas('demand', function (Builder $query) {
            $query->where('contracted', 0);
        })
        ->where('owner_id', auth()->user()->id)->count();

        $contractsCount = Contract::where('demand_owner_id', auth()->user()->id)->orWhere('candidature_owner_id', auth()->user()->id)
                ->where('finished_at', null)->count();

        return $this->view('index', compact('demandsCount', 'candidaturesCount', 'contractsCount'));
    }

    /**
     * demands.
     */
    public function demands()
    {
        $demands = Demand::with(['candidatures'])->where('owner_id', auth()->user()->id)->get();

        return $this->view('demands.index', compact('demands'));
    }

    /**
     * profile.
     */
    public function profile()
    {
      
        $user = auth()->user()->load('commune', 'district');
        return $this->view('users.index', compact('user'));
    }

    /**
     * profile.
     */
    public function editProfile()
    {
        $communes = Commune::all();
        $districts = District::all();
        $user = auth()->user()->load('commune', 'district');
        return $this->view('users.edit', compact('user', 'communes', 'districts'));
    }

    /**
     * profile.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user()->load('commune', 'district');

        $fields = [
            'first_name' => 'required|min:2|string',
            'first_name' => 'required|min:2|string',
            'biography' => 'nullable|min:20|',
            'adress_1' => 'required|regex:/[^a-z_\-0-9]/i',
            'adress_2' => 'nullable',
            'postal' => 'required|numeric',
            'commune_id' => 'required|int',
            'district_id' => 'required|int',
            'phone_1' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'phone_2' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'date_of_birth' => 'nullable|date',
        ];
        if ($user->email != $request->email) {
            $fields['email'] = 'required|unique:users|email';
        }

        if ($user->username != $request->username) {
            $fields['username'] = 'required|unique:users|min:4';
        }
        $data = $request->validate($fields);
        if($user->update($data)) {
            laraflash('Profile updated', 'Yeah!')->success();
        }else{
            laraflash('Something bad...', 'Oups!')->danger();
        }
        return redirect()->route('dashboard.profile.edit');
    }

     /**
     * profile.
     */
    public function editPassword()
    {
        $user = auth()->user();
        return $this->view('users.password.edit', compact('user'));
    }

    /**
     * profile.
     */
    public function updatePassword(Request $request)
    {   
        
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['min:6','max:16','required', 'confirmed'],
        ]);
       $user = auth()->user();
       if($user->update(['password'=> Hash::make($request->new_password)])) {
            laraflash('Password updated', 'Yeah!')->success();
       } else {
            laraflash('Something bad...', 'Oups!')->danger();
        };

       return redirect()->route('dashboard.profile.edit');
    }

     /**
     * profile.
     */
    public function updateAvatar(Request $request)
    {   
        $user = auth()->user();
        if ($image = $request->file('file')) {
            $avatar_name = $user->id.'_'.$user->username.'.jpg';
          
            if ($image->isValid()) {
                $image->storeAs('/public/avatars', $avatar_name);
                
                $user->avatarImage()->create([
                    'slug' => Str::slug($user->id.'_'.$user->username),
                    'name' => 'Avatar '.$user->username,
                    'path' => '/public/avatars/'.$avatar_name,
                    'owner_id' => $user->id,
                ]);
                Image::load($request->file('file'))->width(50)->save();
            }
        };
        die('en dev');
        return redirect()->route('dashboard.profile.edit');

         laraflash('Something bad...', 'Oups!')->danger();
    }



    /**
     * messages.
     */
    public function messages()
    {
        $user = auth()->user();
        $conversations = $user->getLastConversations();

        return $this->view('messages.index', compact('conversations'));
    }

    /**
     * showConversation.
     *
     * @param mixed $conversationId
     */
    public function showConversation($conversationId)
    {
        $conversation = Galera::conversation($conversationId);
        $messages = Galera::ofConversation($conversation->id)
            ->orderBy('message', 'asc')->get();

        return $this->view('messages.show', compact('conversation', 'messages'));
    }

    protected function view(String $view, array $data = [])
    {
        $user = auth()->user();
        $data['user'] = $user;

        return view('dashboard.'.$view, $data);
    }
}
