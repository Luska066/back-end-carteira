<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Service;

class ServiceController extends Controller {

    public function __construct() {
		$this->authorizeResource(Service::class, 'service');
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ) {

        $services = Service::query();

        if(!empty($request->search)) {
			$services->where('id', 'like', '%' . $request->search . '%');
		}

        $services = $services->paginate(10);

        return view('services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        return view('services.create', []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ) {

        $request->validate([]);

        try {

            $service = new Service();

            $service->save();

            return redirect()->route('services.index', [])->with('success', __('Service created successfully.'));
        } catch (\Throwable $e) {
            return redirect()->route('services.create', [])->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Service $service
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service,) {

        return view('services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Service $service
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service,) {

        return view('services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service,) {

        $request->validate([]);

        try {

            $service->save();

            return redirect()->route('services.index', [])->with('success', __('Service edited successfully.'));
        } catch (\Throwable $e) {
            return redirect()->route('services.edit', compact('service'))->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Service $service
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service,) {

        try {
            $service->delete();

            return redirect()->route('services.index', [])->with('success', __('Service deleted successfully'));
        } catch (\Throwable $e) {
            return redirect()->route('services.index', [])->with('error', 'Cannot delete Service: ' . $e->getMessage());
        }
    }

    
}
