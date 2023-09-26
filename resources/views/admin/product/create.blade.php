@extends('admin.layouts.master')
@section('title')
    Quản lý sản phẩm
@endsection
@section('css')
    <link href="{{ URL::asset('assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/assets/css/select2/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

    <link href="{{ URL::asset('assets/libs/filepond/filepond.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Quản lý sản phẩm</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="/admin/san-pham">Danh sách sản phẩm</a>
                        </li>
                        <li class="breadcrumb-item">
                            @if(isset($product))
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
    <form method="POST" id="form-action" enctype="multipart/form-data"
          @if(isset($product))
              action="{{ route('admin.products.update', $product->id) }}"
          @else
              action="{{ route('admin.products.store') }}"
        @endif
    >
        @if(isset($product))
            @method('put')
        @endif
        @csrf
            <div class="mb-3 d-flex justify-content-end">
                <button class="btn btn-primary waves-effect waves-light">
                    @if(isset($product))
                        <a>Chỉnh sửa</a>
                    @else
                        <a>Thêm mới</a>
                    @endif
                </button>
            </div>
            <div class="card form-style">
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom nav-success nav-justified mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#home1" role="tab">
                                Thông tin cơ bản
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#profile1" role="tab">
                                Cấu hình SEO
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content text-muted">
                        <div class="tab-pane active" id="home1" role="tabpanel">
                            <input type="hidden" id="product_id" name="product_id" value="@if(isset($product)){{$product->id}}@endif">
                            <input type="hidden" name="created_by" value='{{\Auth::user()->id}}'>
                            <div class="col-lg-12">
                                <label class="form-label p-2">Ảnh đại diện sản phẩm</label>
                                <div class="card-body p-4">
                                    <div class="text-center">
                                        <div class="profile-user position-relative d-inline-block mx-auto h-100 w-100 mb-4" style="max-height: 200px">
                                            <img src="@if(isset($product)) {{ URL::asset($product->avatar) }} @else {{ URL::asset('assets/images/verification-img.png') }} @endif "
                                                 class="h-100 img-thumbnail user-profile-image" alt="user-profile-image"
                                                 style="max-height: 200px"
                                            >
                                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                                <input id="profile-img-file-input" name="thumbnail" type="file" class="profile-img-file-input" accept="image/png, image/gif, image/jpeg">
                                                <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                        <span class="avatar-title rounded-circle bg-light text-body">
                                            <i class="ri-camera-fill"></i>
                                        </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <label class="form-label p-2">Gallery</label>
                                 <div class="row">
                                     <div class="col-lg-8 offset-lg-2">
                                         <input
                                             type="file" name="gallery[]" multiple accept="image/png, image/gif, image/jpeg"
                                         />
                                         <p class="help-block">{{ $errors->first('gallery.*') }}</p>
                                     </div>
                                 </div>--}}

                            </div>
                            <div class="row">
                                <div class="col-lg-8 offset-lg-2">
                                    <input
                                        type="file" name="gallery[]" multiple accept="image/png, image/gif, image/jpeg"
                                    />
                                    <p class="help-block">{{ $errors->first('gallery.*') }}</p>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="name">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" id="name" class="form-control" value="@if(isset($product)){{$product->name}}@endif" name="name" placeholder="Nhập tiêu đề" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="alias">Đường dẫn</label>
                                <input type="text" id="alias" value="@if(isset($product)){{$product->alias}}@endif" class="form-control" name="alias" readonly required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="project-title-input">Mô tả</label>
                                <textarea type="text" class="form-control" id="description" name="description" placeholder="Nhập mô tả" required>@if(isset($product)){!! $product->description !!}@endif</textarea>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <div class="mb-3 mb-lg-0">
                                        <label for="choices-sex-input" class="form-label">Danh mục</label>
                                        <select class="form-select select2_select-categories" multiple="multiple" name="category_id[]" id="category_id" required>
                                            {!! $category_html !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div>
                                        <label for="datepicker-deadline-input" class="form-label">Từ khóa</label>
                                        <select id="select-tags" class="form-control select2_select-tags" multiple="multiple" name="tags[]"></select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3 mb-lg-0">
                                        <label class="form-label" for="project-title-input">Vị trí</label>
                                        <input maxlength="10" type="number" name="ordering" id="ordering" value="@if(isset($product)){{$product->ordering}}@endif" class="form-control" placeholder="Nhập vị trí">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="content">Nội dung</label>
                                <textarea type="text" class="form-control" id="content" name="content" placeholder="Nhập nội dung">@if(isset($product)) {!! $product->content !!} @endif</textarea>
                            </div>
                        </div>
                        <div class="tab-pane" id="profile1" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label" for="project-title-input">Meta Title</label>
                                <textarea type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Nhập mô tả">@if(isset($product)){{$product->meta_title}}@endif</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="project-title-input">Meta description</label>
                                <textarea type="text" class="form-control" id="meta_description" name="meta_description" placeholder="Nhập mô tả">@if(isset($product)){!!$product->meta_description!!}@endif</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="project-title-input">Meta key word</label>
                                <textarea type="text" class="form-control" id="meta_key_word" name="meta_key_word" placeholder="Nhập mô tả">@if(isset($product)){!! $product->meta_key_word !!}@endif</textarea>
                            </div>
                        </div>
                    </div>
                </div><!-- end card-body -->
            </div><!-- end card -->
    </form>


@endsection
@section('script')

    <script src="{{ URL::asset('/assets/js/pages/profile-setting.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
    <script src="https://cdn.ckeditor.com/4.5.11/full-all/ckeditor.js"></script>
    <script src="{{ URL::asset('/assets/js/checkUrl.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ URL::asset('assets/js/filepond/filepond-plugin-image-resize.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/filepond/filepond.min.js') }}"></script>

    <script type="text/javascript">


        $(document).ready(function () {

            var options = {
                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
                filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
            };

            CKEDITOR.replace('content', options);

            $('#name').focusout(async function (e) {
                let alias = ChangeToSlug($('#name').val());
                let res = await checkURL({
                    'alias': alias,
                    'module': 'Products'
                })
                if (!res) $('#alias').val(alias);
                else $('#alias').val(alias + `-${res}`)
            });

            const productId = $('#product_id').val();
            let dataProduct = ''
            getData('/api/options/Product/tags', productId).then((data) => {
                dataProduct = data
            });

            setTimeout(() => {
                $('.select2_select-tags').select2({
                    tags: true,
                    placeholder: 'Select multi tags',
                    data: dataProduct['data'],

                    insertTag: async function (data, tag) {
                        //data = tags.data['data'];
                        data.push(tag);
                    }
                });
            }, 100)

            $('.select2_select-tags').on('select2:select', async function (e) {
                if (typeof e.params.data['disabled'] === 'undefined') {
                    const tags = await insertTag(e.params.data);
                    dataProduct = tags['data'];
                }
            });

            $('.select2_select-categories').select2({
                multiple: true,
                placeholder: 'Chọn danh mục sản phẩm',
            });

        })
    </script>
    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);
        //FilePond.create(document.querySelector('input[name="gallery[]"]'), {chunkUploads: true});
        $(document).ready(function () {
            const productId = $('#product_id').val();
            let dataImage = '';
            if (productId.length) {
                getImagesProduct(productId).then((data) => {
                    dataImage = data;
                });


            }
            setTimeout(() => {
                FilePond.create(
                    document.querySelector('input[name="gallery[]"]'),
                    {
                        multiple: true,
                        chunkUploads: true,
                        labelIdle: 'Drag & Drop your picture or Browse',
                        imagePreviewHeight: 170,
                        imageCropAspectRatio: 0.5,
                        imageResizeTargetWidth: 200,
                        imageResizeTargetHeight: 200,
                        styleLoadIndicatorPosition: 'center bottom',
                        styleProgressIndicatorPosition: 'right bottom',
                        styleButtonRemoveItemPosition: 'left bottom',
                        styleButtonProcessItemPosition: 'right bottom',
                        maxFileSize: '7MB',
                        acceptedFileTypes: ['image/png', 'image/jpeg'],
                        files: dataImage['data'],
                    }
                );
            }, 200)
            FilePond.setOptions({
                server: {
                    //load: '/api/upload-image/products',
                    url: "/api/upload-image/products",
                    headers: {
                        'X-CSRF-TOKEN': "{{ @csrf_token() }}",
                    },
                }
            });
        })
    </script>
@endsection
