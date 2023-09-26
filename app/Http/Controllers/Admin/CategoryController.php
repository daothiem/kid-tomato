<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\Promise\all;

class CategoryController extends Controller
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
        $data = Category::where('id', '>=', 0)->orderBy('ordering', "ASC")->paginate($limit);

        return view('admin.category.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $parentId = Category::all();
        $category_html = \App\Helper\StringHelper::getSelectOption($parentId);

        return view('admin.category.create', compact('category_html'));
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
        $thumbnailPath = public_path('/images/categories');

        if ($request->has('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailName = time() . '.' . $thumbnail->getClientOriginalExtension();
            $thumbnail->move($thumbnailPath, $thumbnailName);
        }
        $input['avatar'] ='/images/categories/' . $thumbnailName;

        try {
            Category::create($input);
            return redirect()->route('admin.categories.index')->with('success','Thêm mới thành công');
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')->with('error','Thêm mới không thành công');
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
        $category = Category::find($id);
        if (isset($category)) {
            $categoryParent = Category::where('id', '>=', 0)->where('id', '<>', $category->id)->get();

            $category_html = \App\Helper\StringHelper::getSelectOption($categoryParent, $category->parent_id);
            return view('admin.category.create', compact('category', 'category_html'));

        } else {
            return redirect()->route('admin.categories.index')->with('error','Tin tức không tồn tại');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if ($category) {
            $input = $request->all();
            $imageOld = $category->avatar;

            $thumbnailName = explode('/', $imageOld)[count(explode('/', $imageOld)) - 1];
            $thumbnailPath = public_path('/images/categories');

            if ($request->has('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $thumbnailName = time() . '.' . $thumbnail->getClientOriginalExtension();
                $thumbnail->move($thumbnailPath, $thumbnailName);
            }

            $input['avatar'] ='/images/categories/' . $thumbnailName;
            try {
                $category->update($input);

                return redirect()->route('admin.categories.index')->with('success','Cập nhật thành công');
            } catch (\Exception $e) {
                return redirect()->route('admin.categories.index')->with('error','Cập nhật không thành công');
            }

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
        $category = Category::find($id);
        if (isset($category)) {
            DB::beginTransaction();
            try {
                $input['parent_id'] = null;
                $category->child->each->update($input);
                $category->products()->sync([]);
                $category->delete();

                DB::commit();

                return redirect()->route('admin.categories.index')->with('success','Xoá danh mục sản phẩm thành công');
            } catch(\Exception $exception) {
                DB::rollBack();

                return redirect()->route('admin.categories.index')->with('error','Xoá danh mục sản phẩm không thành công');
            }

        } else {
            return redirect()->route('admin.categories.index')->with('error','Danh mục sản phẩm không tồn tại');

        }
    }

}
