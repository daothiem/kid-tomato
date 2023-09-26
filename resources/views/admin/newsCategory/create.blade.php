@extends('admin.layouts.master')
@section('title')
    Quản lý tin tức
@endsection
@section('css')
    <link href="{{ URL::asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Quản lý danh mục tin tức</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="/admin/danh-muc-tin-tuc">Danh sách danh mục</a>
                        </li>
                        <li class="breadcrumb-item">
                            @if(isset($newsCategory))
                                <a>Chỉnh sửa</a>
                            @else
                                <a>Tạo mới</a>
                            @endif

                        </li>
                    </ol>
                </div>
            </div>

        </div>
    </div>
    <form method="POST" id="form-action"
          @if(isset($newsCategory))
              action="{{ route('admin.newsCategory.update', $newsCategory->id) }}"
          @else
              action="{{ route('admin.newsCategory.store') }}"
          @endif

    >
        @if(isset($newsCategory))
            @method('put')
        @endif
        @csrf
        <div class="mb-3 d-flex justify-content-end">
            <button class="btn btn-primary waves-effect waves-light">
                @if(isset($newsCategory))
                    <a>Chỉnh sửa</a>
                @else
                    <a>Thêm mới</a>
                @endif
            </button>
        </div>

        <div class="card card-body">
            <div class="d-flex gap-4">
                <div class="mb-3 w-50">
                    <label class="form-label" for="title">Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" @if(isset($newsCategory)) value="{{$newsCategory->title}}" @endif id="title" class="form-control" name="title" placeholder="Nhập tiêu đề" required>
                </div>
                <div class="mb-3 w-50">
                    <label class="form-label" for="alias">Đừng dẫn</label>
                    <input type="text" id="alias" class="form-control" @if(isset($newsCategory)) value="{{$newsCategory->alias}}" @endif name="alias" readonly required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-6">
                    <div class="mb-3 mb-lg-0">
                        <label for="parent_id" class="form-label">Danh mục cha</label>
                        <select class="form-select" name="parent_id" id="parent_id">
                            <option value="" disabled @if(!isset($newsCategory) || (isset($newsCategory) && ($newsCategory->parent_id === '' || $newsCategory->parent_id === null || $newsCategory->parent_id === 0))) selected @endif>Chọn danh mục cha</option>
                            @foreach($parentArray as $item)
                                <option @if(isset($newsCategory) && $item['id'] === $newsCategory->parent_id) selected @endif value="{{ $item['id'] }}">{{ $item['title'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3 mb-lg-0">
                        <label class="form-label" for="project-title-input">Số thứ tự</label>
                        <input maxlength="10" type="number" value="@if(isset($newsCategory)){{$newsCategory->ordering}}@else 0 @endif"  name="ordering" id="ordering" class="form-control"
                               placeholder="Nhập vị trí">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="project-title-input">Mô tả</label>
                <textarea type="text" class="form-control" id="description" name="description" placeholder="Nhập mô tả"
                          >@if(isset($newsCategory)){{$newsCategory->description}}@endif</textarea>
            </div>
        </div>
    </form>
@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/prismjs/prismjs.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/project-create.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/prismjs/prismjs.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/list.pagination.js/list.pagination.js.min.js') }}"></script>
    <script src="{{ URL::asset('backend/assets/js/checkUrl.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#title').focusout(async function (e) {
                let alias = ChangeToSlug($('#title').val());
                let res = await checkURL({
                    'alias': alias,
                    'module': 'NewsCategory'
                })
                if (!res) $('#alias').val(alias);
                else $('#alias').val(alias + `-${res}`)
            })
        })
    </script>
@endsection
