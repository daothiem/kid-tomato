<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
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
        $data = Slider::where('id', '>=', 0)->orderBy('ordering', "ASC")->paginate($limit);

        return view('admin.slider.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.slider.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $thumbnailName = 'verification-img.png';
        $thumbnailPath = public_path('/images/sliders');

        if ($request->has('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailName = time() . '.' . $thumbnail->getClientOriginalExtension();
            $thumbnail->move($thumbnailPath, $thumbnailName);
        }
        $input['image'] ='/images/sliders/' . $thumbnailName;

        try {
            Slider::create($input);

            return redirect()->route('admin.sliders.index')->with('success','Thêm mới thành công');
        } catch (\Exception $e) {
            return redirect()->route('admin.sliders.index')->with('error','Thêm mới không thành công');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $slider = Slider::find($id);
        if (isset($slider)) {
            return view('admin.slider.create', compact('slider'));
        } else {
            return redirect()->route('admin.sliders.index')->with('error','Slider không tồn tại');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $slider = Slider::find($id);
        if ($slider) {
            $input = $request->all();
            $imageOld = $slider->image;

            $thumbnailName = explode('/', $imageOld)[count(explode('/', $imageOld)) - 1];
            $thumbnailPath = public_path('/images/sliders');
            if ($request->has('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $thumbnailName = time() . '.' . $thumbnail->getClientOriginalExtension();
                $thumbnail->move($thumbnailPath, $thumbnailName);
            }
            $input['image'] ='/images/sliders/' . $thumbnailName;
            try {
                $slider->update($input);

                return redirect()->route('admin.sliders.index')->with('success','Cập nhật thành công');
            } catch (\Exception $e) {
                return redirect()->route('admin.sliders.index')->with('error','Cập nhật không thành công');
            }
        } else {
            return redirect()->route('admin.sliders.index')->with('error','Slider không tồn tại');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $slider = Slider::find($id);
        if ($slider) {
            try {
                $slider->delete();

                return redirect()->route('admin.sliders.index')->with('success','Xoá thành công');
            } catch (\Exception $e) {
                return redirect()->route('admin.sliders.index')->with('error','Xoá không thành công');
            }
        } else {
            return redirect()->route('admin.sliders.index')->with('error','Slider không tồn tại');

        }
    }
}
