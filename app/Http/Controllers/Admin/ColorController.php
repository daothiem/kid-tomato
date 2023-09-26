<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request, $limit = 20)
    {
        $data = Color::where('id', '>=', 0)->paginate($limit);

        return view('admin.color.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.color.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $input['description'] = $input['description'] ?? '';
            Color::create($input);

            return redirect()->route('admin.colors.index')->with('success','Thêm mới thành công');
        } catch (\Exception $e) {
            return redirect()->route('admin.colors.index')->with('error','Thêm mới không thành công');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function show(Color $color)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\RedirectResponse |\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $color = Color::find($id);
        if (isset($color)) {
            return view('admin.color.create', compact('color'));

        } else {
            return redirect()->route('admin.colors.index')->with('error','Màu không tồn tại');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $color = Color::find($id);

        if ($color) {
            try {
                $input = $request->all();
                $color->update($input);

                return redirect()->route('admin.colors.index')->with('success','Cập nhật thành công');
            } catch (\Exception $e) {
                return redirect()->route('admin.colors.index')->with('error','Cập nhật không thành công');
            }
        } else {
            return redirect()->route('admin.colors.index')->with('error','Màu không tồn tại');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $color = Color::find($id);

        if ($color) {
            try {
                $color->delete();

                return redirect()->route('admin.colors.index')->with('success','Xoá thành công');
            } catch (\Exception $e) {
                return redirect()->route('admin.colors.index')->with('error','Xoá không thành công');
            }
        } else {
            return redirect()->route('admin.colors.index')->with('error','Màu không tồn tại');
        }
    }
}
