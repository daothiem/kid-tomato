<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Product;
use App\Models\Tags;
use App\Models\Url;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (view()->exists($request->path())) {
            return view($request->path());
        }
        return abort(404);
    }

    public function root()
    {
        return view('index');
    }

    /*Language Translation*/
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }

    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $user = User::find($id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');

        if ($request->file('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = public_path('/images/');
            $avatar->move($avatarPath, $avatarName);
            $user->avatar = $avatarName;
        }

        $user->update();
        if ($user) {
            Session::flash('message', 'User Details Updated successfully!');
            Session::flash('alert-class', 'alert-success');
            // return response()->json([
            //     'isSuccess' => true,
            //     'Message' => "User Details Updated successfully!"
            // ], 200); // Status code here
            return redirect()->back();
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            // return response()->json([
            //     'isSuccess' => true,
            //     'Message' => "Something went wrong!"
            // ], 200); // Status code here
            return redirect()->back();

        }
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            return response()->json([
                'isSuccess' => false,
                'Message' => "Your Current password does not matches with the password you provided. Please try again."
            ], 200); // Status code
        } else {
            $user = User::find($id);
            $user->password = Hash::make($request->get('password'));
            $user->update();
            if ($user) {
                Session::flash('message', 'Password updated successfully!');
                Session::flash('alert-class', 'alert-success');
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Password updated successfully!"
                ], 200); // Status code here
            } else {
                Session::flash('message', 'Something went wrong!');
                Session::flash('alert-class', 'alert-danger');
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Something went wrong!"
                ], 200); // Status code here
            }
        }
    }

    public function checkURL(Request $request)
    {
        $input = $request->all();

        $url = Url::where('alias', $input['alias'])->where('module', $input['module'])->latest('created_at')->first();
        if (!isset($url)) return response([
            'success' => false,
            'data' => 0
        ]);
        $data = Url::latest('created_at')->first();
        return response([
            'success' => true,
            'data' => $data->id,
        ]);
    }

    public function getAllTags(Request $request, $module) {
        $tagsAll = Tags::where('id', '>=', 0)->select('id', 'name as text')->get()->toArray();

        if ($request->get('param') !== null) {
            $model = '\\App\Models\\'.ucfirst($module);
            $news = $model::find($request->get('param'));

            $tagIds = $news->tags()->select('tags.id as tas_id')->get()->toArray();

            foreach ($tagIds as $tagId) {
                $foundKey = array_search($tagId['tas_id'], array_column($tagsAll, 'id'));

                if ($foundKey >= 0) {
                    $tagsAll[$foundKey]['selected'] = true;
                }
            }
        }

        return response()->json(['data' => $tagsAll]);
    }

    public function storeTag(Request $request) {
        $totalInput = $request->all();
        $tag = Tags::where('name', $totalInput['text'])->get();
        if (count($tag) === 0) {
            $input['name'] = $totalInput['text'];
            Tags::create($input);
        }

        $temp = Tags::where('id', '>=', 0)->select('id', 'name as text')->get()->toArray();
        $dataTag = response([
            'success' => true,
            'data' => $temp,
        ]);

        return response([
            'success' => true,
            'data' => $dataTag,
        ]);
    }

    public function uploadImage(Request $request, $module): string
    {
        $findName = $request->file('gallery')[0]->getClientOriginalName();
        $listFindName = scandir(public_path().'/images/'.$module);
        $find = array_search($findName, $listFindName);

        if ($request->has('gallery') && $find === false) {
            $image = $request->file('gallery')[0];
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path('/images/'.$module. '/');
            $image->move($imagePath, $imageName);

            return '/images/products/'.$imageName;
        }

        return '/images/products/'.$findName;
    }

    public function getImageProduct(Request $request) {
        $product = Product::find($request->get('param'));

        $images = explode(',', $product->images);
        $imageFormats = [];
        foreach ($images as $image) {
            if (strlen($image) > 0) {
                $imageFormat = ['source' => $image, 'options' => ['type' => 'input']];
                $imageFormats[] = $imageFormat;
            }
        }

        return response()->json(['data' => $imageFormats]);
    }
}
