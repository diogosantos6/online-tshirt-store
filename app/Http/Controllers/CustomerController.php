<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use App\Http\Resources\CustomerResource;
use App\Http\Requests\StoreUpdateCustomerRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $filterByUser = $request->user ?? '';

        //Query para a tabela de users
        $userQuery = User::query()->where('user_type', 'LIKE', 'C');

        //Filtrar por user
        if ($filterByUser != '') {
            $userQuery->where('name', 'like', "%$filterByUser%");
        }

        //Paginação (tabela)
        $users = $userQuery->orderBy('name', 'asc')->paginate(15);

        return view('customers.index', compact('users', 'filterByUser'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer): View
    {
        $customer->load('user');
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        $customer->load('user');
        return view('customers.edit', compact('customer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateCustomerRequest $request)      //talvez nao seja necessario
    {
        $newCustomer = Customer::create($request->validated());
        return new CustomerResource($newCustomer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $formData = $request->validated();
        $customer = DB::transaction(function () use ($formData, $customer, $request) {
            $customer->nif = $formData['nif'];
            $customer->address = $formData['address'];
            $customer->default_payment_type = $formData['default_payment_type'];
            $customer->default_payment_ref = $formData['default_payment_ref'];
            $customer->save();
            $user = $customer->user;
            $user->name = $formData['name'];
            $user->email = $formData['email'];
            $user->save();


            if ($request->hasFile('file_foto')) {
                if ($user->photo_url) {
                    Storage::delete('public/photos/' . $user->photo_url);
                }
                $path = $request->file_foto->store('public/photos');
                $user->photo_url = basename($path);
                $user->save();
            }
            return $customer;
        });
        $url = route('customers.show', ['customer' => $customer]);
        $htmlMessage = "Os seus dados foram alterados com sucesso!";
        return redirect()->back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Customer $customer): RedirectResponse
    {
        $user = $customer->user;
        $customer->delete();
        $user->delete();
        if ($request->user()->user_type == 'A') {
            $htmlMessage = "User #{$customer->id} <strong>\"{$customer->user->name}\"</strong> foi apagado com sucesso!";
            return back()->with('alert-msg', $htmlMessage)
                ->with('alert-type', 'success');
        }
        $htmlMessage = "User #{$customer->id} <strong>\"{$customer->user->name}\"</strong> foi apagado com sucesso!";
        return redirect()->route('tshirt_images.catalogo')
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', 'success');
    }

    public function destroy_foto(Customer $customer): RedirectResponse
    {
        if ($customer->user->photo_url) {
            Storage::delete('public/photos/' . $customer->user->photo_url);
            $customer->user->photo_url = null;
            $customer->user->save();
        }
        return redirect()->route('customers.edit', ['customer' => $customer])
            ->with('alert-msg', 'Foto do cliente "' . $customer->user->name . '" foi removida!')
            ->with('alert-type', 'success');
    }
}
