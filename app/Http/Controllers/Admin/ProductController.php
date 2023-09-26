<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\productDetail;
use App\Models\Size;
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
        $sizes = Size::all();
        $colors = Color::all();
        $category_html = \App\Helper\StringHelper::getSelectOption($categories, '', 'Vui lòng chọn', false, false);
        $sizes_html = \App\Helper\StringHelper::getSelectOption($sizes, '', 'Vui lòng chọn', false, false, 'name');
        $colors_html = \App\Helper\StringHelper::getSelectOption($colors, '', 'Vui lòng chọn', false, false, 'name');

        return view('admin.product.create', compact('category_html', 'sizes_html', 'colors_html'));
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
            foreach ($input['size_id'] as $sizeId) {
                foreach ($input['color_id'] as $colorId) {
                    $detail['product_id'] = $product->id;
                    $detail['size_id'] = (integer)$sizeId;
                    $detail['color_id'] = (integer)$colorId;
                    $detail['quantity'] = $input['quantity'];

                    ProductDetail::create($detail);
                }
            }

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
            $sizes = Size::all();
            $colors = Color::all();
            $categoriesTemp = $product->categories()->select('categories.id as category_id')->get()->toArray();
            $sizesTemp = $product->sizes()->select('product_details.size_id as size_id')->get()->toArray();
            $colorTemp = $product->colors()->select('product_details.color_id as color_id')->get()->toArray();
            $categoriesSelected = [];
            foreach ($categoriesTemp as $categoryId) {
                $categoriesSelected[] = $categoryId['category_id'];
            }
            $sizesSelected = [];
            foreach ($sizesTemp as $size) {
                $sizesSelected[] = $size['size_id'];
            }
            $sizesSelected = array_unique($sizesSelected);

            $colorsSelected = [];
            foreach ($colorTemp as $color) {
                $colorsSelected[] = $color['color_id'];
            }
            $sizesSelected = array_unique($sizesSelected);
            $colorsSelected = array_unique($colorsSelected);

            $category_html = \App\Helper\StringHelper::getSelectOption($categories,$categoriesSelected , 'Vui lòng chọn', false, false);
            $sizes_html = \App\Helper\StringHelper::getSelectOption($sizes,$sizesSelected , 'Vui lòng chọn', false, false, 'name');
            $colors_html = \App\Helper\StringHelper::getSelectOption($colors,$colorsSelected , 'Vui lòng chọn', false, false, 'name');

            return view('admin.product.create', compact('category_html', 'product', 'colors_html', 'sizes_html'));
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
                $product->sizes->each->delete();
                $product->colors->each->delete();

                foreach ($input['size_id'] as $sizeId) {
                    foreach ($input['color_id'] as $colorId) {
                        $detail['product_id'] = $product->id;
                        $detail['size_id'] = (integer)$sizeId;
                        $detail['color_id'] = (integer)$colorId;
                        $detail['quantity'] = $input['quantity'] ?? 0;

                        ProductDetail::create($detail);
                    }
                }

                DB::commit();
                return redirect()->route('admin.products.index')->with('success','Cập nhật sản phẩm thành công');
            } catch (\Exception $e) {
                DB::rollBack();
                dd($e);
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
