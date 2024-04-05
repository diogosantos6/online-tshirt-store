<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreCreateUserRequest;
use App\Http\Requests\StoreUpdateUserRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $filterByType = $request->user_type ?? '';
        $filterByUser = $request->user ?? '';

        //Query para a tabela de users
        $userQuery = User::query()->where('user_type', 'NOT LIKE', 'C');

        //Filtrar por tipo
        if ($filterByType != '') {
            $userQuery->where('user_type', 'LIKE', $filterByType);
        }

        //Filtrar por user
        if ($filterByUser != '') {
            $userQuery->where('name', 'like', "%$filterByUser%");
        }

        //Paginação (tabela)
        $users = $userQuery->orderBy('user_type', 'desc')->orderBy('name', 'asc')->paginate(15);

        return view('users.index', compact('users', 'filterByType', 'filterByUser'));
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        $user->load('customer');
        return view('users.show', compact('user'));
    }


    public function edit(User $user): View
    {
        $user->load('customer');
        return view('users.edit', compact('user'));
    }

    public function create(): View
    {
        $user = new User();
        return view('users.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCreateUserRequest $request): RedirectResponse
    {
        try {
            $formData = $request->validated();
            $user = DB::transaction(function () use ($formData) {
                $newUser = new User();
                $newUser->user_type = $formData['user_type'];
                $newUser->name = $formData['name'];
                $newUser->email = $formData['email'];
                $newUser->password = Hash::make($formData['password']);
                $newUser->save();

                return $newUser;
            });

            $url = route('users.show', ['user' => $user]);
            $htmlMessage = "User <a href='$url'>#{$user->id}</a> <strong>\"{$user->name}\"</strong> foi criado com sucesso!";
            return redirect()->route('users.index')
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', 'success');
        } catch (\Exception $error) {
            $url = route('users.show', ['user' => $user]);
            $htmlMessage = "Não foi possível criar o user <a href='$url'>#{$user->id}</a>
                        <strong>\"{$user->name}\"</strong> porque ocorreu um erro!";
            $alertType = 'danger';
        }
        return back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', $alertType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateUserRequest $request, User $user): RedirectResponse
    {
        $formData = $request->validated();
        $user = DB::transaction(function () use ($formData, $user, $request) {
            $user->user_type = $formData['user_type'];
            $user->name = $formData['name'];
            $user->email = $formData['email'];
            $user->blocked = $formData['blocked'];
            $user->save();

            if ($request->hasFile('file_foto')) {
                if ($user->url_foto) {
                    Storage::delete('public/photos/' . $user->photo_url);
                }
                $path = $request->file_foto->store('public/photos');
                $user->photo_url = basename($path);
                $user->save();
            }
            return $user;
        });
        $url = route('users.show', ['user' => $user]);
        $htmlMessage = "User <a href='$url'>#{$user->id}</a>
                        <strong>\"{$user->name}\"</strong> foi alterado com sucesso!";
        return redirect()->back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->customer) {
            $customer = $user->customer;
            $customer->delete();
        }
        $user->delete();
        $htmlMessage = "User #{$user->id} <strong>\"{$user->name}\"</strong> foi apagado com sucesso!";
        return redirect()->route('users.index')
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', 'success');
    }

    public function destroy_foto(User $user): RedirectResponse
    {
        if ($user->photo_url) {
            Storage::delete('public/photos/' . $user->photo_url);
            $user->photo_url = null;
            $user->save();
        }
        return redirect()->route('users.edit', ['user' => $user])
            ->with('alert-msg', 'Foto do cliente "' . $user->name . '" foi removida!')
            ->with('alert-type', 'success');
    }
}
