<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Url;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $limit = 20) {
        $data = Product::where('id', '>=', 0)->orderBy('ordering', "ASC")->paginate($limit);

        return view('admin.product.index', compact('data'));
    }

    public function create() {
        $categories = Category::all();
        $category_html = \App\Helper\StringHelper::getSelectOption($categories, '', 'Vui lòng chọn', false, false);

        return view('admin.product.create', compact('category_html'));
    }

    public function store(Request $request) {
        $input = $request->all();
        $thumbnailName = 'verification-img.png';
        $thumbnailPath = public_path('/images/products');

        if ($request->has('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailName = time() . '.' . $thumbnail->getClientOriginalExtension();
            $thumbnail->move($thumbnailPath, $thumbnailName);
        }
        $input['avatar'] ='/images/products/' . $thumbnailName;
        if (isset($input['gallery'])) {
            $input['images'] = implode(',', $input['gallery']);
        }

        DB::beginTransaction();
        try {
            $product = Product::create($input);
            $product->categories()->sync($input['category_id']);
            $product->tags()->sync($input['tags']);

            $url['module'] = 'Products';
            $url['alias'] = $input['alias'];
            Url::create($url);

            DB::commit();
            return redirect()->route('admin.products.index')->with('success','Thêm mới sản phẩm thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.products.index')->with('error','Thêm mới sản phẩm không thành công');
        }
    }

    public function edit($id) {
        $product = Product::find($id);

        if (isset($product)) {
            $categories = Category::all();
            $categoriesTemp = $product->categories()->select('categories.id as category_id')->get()->toArray();
            $categoriesSelected = [];
            foreach ($categoriesTemp as $categoryId) {
                $categoriesSelected[] = $categoryId['category_id'];
            }
            $category_html = \App\Helper\StringHelper::getSelectOption($categories,$categoriesSelected , 'Vui lòng chọn', false, false);

            return view('admin.product.create', compact('category_html', 'product'));
        } else {
            return redirect()->route('admin.products.index')->with('error','Sản phẩm không tồn tại');
        }
    }

    public function update(Request $request, $id) {
        $product = Product::find($id);
        if ($product) {
            $input = $request->all();
            if (isset($input['gallery'])) {
                $input['images'] = implode(',', array_unique($input['gallery']));
            } else {
                $input['images'] = '';
            }

            $imageOld = $product->avatar;
            $thumbnailName = explode('/', $imageOld)[count(explode('/', $imageOld)) - 1];
            $thumbnailPath = public_path('/images/products');
            if ($request->has('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $thumbnailName = time() . '.' . $thumbnail->getClientOriginalExtension();
                $thumbnail->move($thumbnailPath, $thumbnailName);
            }

            $input['avatar'] ='/images/products/' . $thumbnailName;

            DB::beginTransaction();
            try {
                if($product->alias != $input['alias']){
                    Url::where('alias',$product->alias)->update(['alias'=>$input['alias']]);
                }
                $product->update($input);
                $product->categories()->sync($input['category_id']);
                $product->tags()->sync($input['tags']);

                DB::commit();
                return redirect()->route('admin.products.index')->with('success','Cập nhật sản phẩm thành công');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('admin.products.index')->with('error','Cập nhật sản phẩn không thành công');
            }
        } else {
            return redirect()->route('admin.products.index')->with('error','Sản phẩm không tồn tại');
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if ($product) {
            DB::beginTransaction();
            try {
                Url::where('alias',$product->alias)->delete();
                $product->categories()->sync([]);
                $product->tags()->sync([]);
                $product->delete();

                DB::commit();

                return redirect()->route('admin.products.index')->with('success','Xoá sản phẩm thành công');
            } catch (\Exception $e) {
                DB::rollBack();

                return redirect()->route('admin.products.index')->with('error','Xoá sản phẩn không thành công');
            }

        } else {
            return redirect()->route('admin.products.index')->with('error','Sản phẩm không tồn tại');
        }
    }
}
