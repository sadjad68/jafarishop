<?php

namespace App\Modules\Location\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Location\Entities\Address;
use App\Modules\Location\Entities\City;
use App\Modules\Location\Entities\State;
use App\Modules\Location\Filters\CityFilter;
use App\Modules\Location\Filters\StateFilter;
use App\Modules\Location\Filters\AddressFilter;

class LocationController extends Controller
{


    /**
     * /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function getState(Request $request)
    {
        $query = State::query();
        if ($request->has(['name'])) {
            $filters = [
                'name' => $request->input('name'),
            ];
            $query = app(StateFilter::class)->apply($query, $filters);
        }
        $states = $query->orderByDesc('id')->paginate(20);
        return view('admin.location.state.index', compact('states'));

    }


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function changeStateStatus($id)
    {
        $data = State::findOrfail($id);
        $data->update(
          [
              'status'=>$data->status == 0 ? 1 : 0
          ]
        );

        return redirect()->route('admin.state.index')
            ->with('success', 'وضعیت ویرایش شد.');;

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function getCity(Request $request)
    {
        $query = City::query();
        if ($request->has(['name'])) {
            $filters = [
                'name' => $request->input('name'),
            ];
            $query = app(CityFilter::class)->apply($query, $filters);
        }
        $cities = $query->orderByDesc('id')->paginate(20);
        return view('admin.location.city.index', compact('cities'));

    }


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function changeCityStatus($id)
    {
        $data = City::findOrfail($id);
        $data->update(
            [
                'status'=>$data->status == 0 ? 1 : 0
            ]
        );

        return redirect()->route('admin.city.index')
            ->with('success', 'وضعیت ویرایش شد.');;

    }
    public function getAddress(Request $request)
    {
        $query = Address::query();
        if ($request->has(['filter'])) {
            $filters = [
                'user_id' => $request->input('user_id'),
            ];
            $query = app(AddressFilter::class)->apply($query, $filters);
        }
        $addresses = $query->orderByDesc('id')->paginate(20);
        return view('admin.location.address.index', compact('addresses'));

    }
}
