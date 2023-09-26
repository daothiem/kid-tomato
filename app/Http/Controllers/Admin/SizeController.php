<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request, $limit = 20)
    {
        $data = Size::where('id', '>=', 0)->paginate($limit);

        return view('admin.size.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        return view('admin.size.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $input = $request->all();
            $input['description'] = $input['description'] ?? '';
            Size::create($input);

            return redirect()->route('admin.sizes.index')->with('success','Thêm mới thành công');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->route('admin.sizes.index')->with('error','Thêm mới không thành công');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $size = Size::find($id);
        if (isset($size)) {
            return view('admin.size.create', compact('size'));

        } else {
            return redirect()->route('admin.sizes.index')->with('error','Size không tồn tại');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $size = Size::find($id);

        if ($size) {
            try {
                $input = $request->all();
                $size->update($input);

                return redirect()->route('admin.sizes.index')->with('success','Cập nhật thành công');
            } catch (\Exception $e) {
                return redirect()->route('admin.sizes.index')->with('error','Cập nhật không thành công');
            }
        } else {
            return redirect()->route('admin.sizes.index')->with('error','Size không tồn tại');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $size = Size::find($id);

        if ($size) {
            try {
                $size->delete();

                return redirect()->route('admin.sizes.index')->with('success','Xoá thành công');
            } catch (\Exception $e) {
                return redirect()->route('admin.sizes.index')->with('error','Xoá không thành công');
            }
        } else {
            return redirect()->route('admin.sizes.index')->with('error','Size không tồn tại');
        }
    }
}
