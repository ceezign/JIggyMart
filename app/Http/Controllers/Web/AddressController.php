<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = $request->user()->addresses()->orderByDesc('is_default')->get();

        return view('dashboard.customer.addresses', compact('addresses'));
    }

    public function store(StoreAddressRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        if ($request->boolean('is_default')) {
            $request->user()->addresses()->update(['is_default' => false]);
        }

        Address::create($data);

        return back()->with('status', 'Address added.');
    }

    public function update(StoreAddressRequest $request, Address $address)
    {
        $this->authorize('update', $address);

        if ($request->boolean('is_default')) {
            $request->user()->addresses()->update(['is_default' => false]);
        }

        $address->update($request->validated());

        return back()->with('status', 'Address updated.');
    }

    public function destroy(Request $request, Address $address)
    {
        $this->authorize('delete', $address);
        $address->delete();

        return back()->with('status', 'Address removed.');
    }
}
