<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Test;

class TestController extends Controller {

    public function __construct() {
		$this->authorizeResource(Test::class, 'test');
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ) {

        $test = Test::query();

        if(!empty($request->search)) {
			$test->where('id', 'like', '%' . $request->search . '%');
		}

        $test = $test->paginate(10);

        return view('tests.index', compact('test'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        return view('tests.create', []);
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

            $test = new Test();

            $test->save();

            return redirect()->route('tests.index', [])->with('success', __('Test created successfully.'));
        } catch (\Throwable $e) {
            return redirect()->route('tests.create', [])->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Test $test
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Test $test,) {

        return view('tests.show', compact('test'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Test $test
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Test $test,) {

        return view('tests.edit', compact('test'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Test $test,) {

        $request->validate([]);

        try {

            $test->save();

            return redirect()->route('tests.index', [])->with('success', __('Test edited successfully.'));
        } catch (\Throwable $e) {
            return redirect()->route('tests.edit', compact('test'))->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Test $test
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Test $test,) {

        try {
            $test->delete();

            return redirect()->route('tests.index', [])->with('success', __('Test deleted successfully'));
        } catch (\Throwable $e) {
            return redirect()->route('tests.index', [])->with('error', 'Cannot delete Test: ' . $e->getMessage());
        }
    }

    
}
