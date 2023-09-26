<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Product;
use App\Models\TagNews;
use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\Promise\all;

class NewsController extends Controller
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
        $data = News::where('id', '>=', 0)->orderBy('ordering', "ASC")->paginate($limit);

        return view('admin.news.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $news_category = NewsCategory::all();
        $news_category_html = \App\Helper\StringHelper::getSelectOption($news_category);

        return view('admin.news.create', compact('news_category_html'));
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
        $thumbnailPath = public_path('/images/news');

        if ($request->has('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailName = time() . '.' . $thumbnail->getClientOriginalExtension();
            $thumbnail->move($thumbnailPath, $thumbnailName);
        }

        $input['avatar'] ='/images/news/' . $thumbnailName;

        DB::beginTransaction();
        try {
            $news = News::create($input);
            foreach ($input['tags'] as $tagId) {
                $inputTagNews['tag_id'] = (int)$tagId;
                $inputTagNews['news_id'] = $news->id;

                TagNews::create($inputTagNews);
            }
            $url['module'] = 'News';
            $url['alias'] = $input['alias'];
            Url::create($url);

            DB::commit();
            return redirect()->route('admin.news.index')->with('success','Thêm mới thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.news.index')->with('error','Thêm mới không thành công');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $news = News::find($id);
        if (isset($news)) {
            $news_category = NewsCategory::all();
            $news_category_html = \App\Helper\StringHelper::getSelectOption($news_category, $news->category_id);
            return view('admin.news.create', compact('news', 'news_category_html'));

        } else {
            return redirect()->route('admin.news.index')->with('error','Tin tức không tồn tại');
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
        $news = News::find($id);
        if ($news) {
            $input = $request->all();
            $imageOld = $news->avatar;
            $thumbnailName = explode('/', $imageOld)[count(explode('/', $imageOld)) - 1];
            $thumbnailPath = public_path('/images/news');

            if ($request->has('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $thumbnailName = time() . '.' . $thumbnail->getClientOriginalExtension();
                $thumbnail->move($thumbnailPath, $thumbnailName);
            }

            $input['avatar'] ='/images/news/' . $thumbnailName;
            DB::beginTransaction();
            try {
                $news->update($input);
                $news->tags()->sync($input['tags']);
                DB::commit();
                return redirect()->route('admin.news.index')->with('success','Cập nhật thành công');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('admin.news.index')->with('error','Cập nhật không thành công');
            }
        } else {
            return redirect()->route('admin.news.index')->with('error','Tin tức không tồn tại');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $news = News::find($id);

        if ($news) {
            DB::beginTransaction();
            try {
                $news->middleTags->each->delete();
                $news->delete();

                DB::commit();
                return redirect()->route('admin.news.index')->with('success','Xoá thành công');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('admin.news.index')->with('error','Xoá không thành công');
            }
        } else {
            return redirect()->route('admin.news.index')->with('error','Tin tức không tồn tại');
        }
    }
}
