@extends('admin.layouts.master')
@section('title')
    Quản lý tin tức
@endsection
@section('css')
    <link href="{{ URL::asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endsection
@section('content')
<div class="card rounded-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Danh sách danh mục tin tức</h6>
        <a href="/admin/them-moi/danh-muc-tin-tuc" class="btn btn-dark waves-effect waves-light w-sm pt-2 pb-2">Thêm mới</a>
    </div>
</div>
<div class="mt-2" style="padding: 0 !important;">
    @if (session('success'))
        <div class="alert alert-success" style="margin-bottom: 0.2rem !important;">
            @lang(session('success'))
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            @lang(session('error'))
        </div>
    @endif
</div>
<div class="card mt-2">
    <div class="card-body">
        <div id="customerList">
            <div class="table-responsive table-card mt-3 mb-1">
                <table class="table align-middle table-nowrap" id="customerTable">
                    <thead class="table-light">
                    <tr>
                        <th class="sort">#</th>
                        <th class="sort" data-sort="customer_name">Tiêu đề</th>
                        <th class="sort" data-sort="address">Đường dẫn</th>
                        <th class="sort" data-sort="phone">Danh mục cha</th>
                        <th class="sort" data-sort="date">Người tạo</th>
                        <th class="sort" data-sort="action">Action</th>
                    </tr>
                    </thead>
                    <tbody class="list form-check-all">
                    @foreach($data as $index => $item)
                        <tr>
                            <td class="customer_name">{{ ++$index }}</td>
                            <td class="customer_name">{{ $item->title }}</td>
                            <td class="customer_name">{{ $item->alias }}</td>
                            <td class="customer_name">{{ $item->parent?->title}}</td>
                            <td class="customer_name">{{ $item->createdBy->email }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <div class="edit">
                                        <button onclick="window.location='/admin/{{$item->id}}/danh-muc-tin-tuc'" class="btn btn-sm btn-success edit-item-btn" >Edit</button>
                                    </div>
                                    <div class="remove">
                                        <button
                                            value="{{ $item->id }}"
                                            class="btn btn-sm btn-danger remove-item-btn"
                                        >Remove</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="noresult" style="@if(isset($data) && count($data) === 0) display: grid @else display: none @endif">
                    <div class="text-center">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                   colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                        </lord-icon>
                        <h5 class="mt-2">Result loading ...</h5>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-right">
                {!! $data->links('pagination::bootstrap-4') !!}
            </div>

        </div>
    </div><!-- end card -->
</div>

<!-- Modal delete -->
<div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="btn-close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form-delete">
                    @method('delete')
                    @csrf
                    <input type="hidden" id="record_id">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                   colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-4">
                            <h4>Are you Sure ?</h4>
                            <p class="text-muted mx-4 mb-0">Are you Sure You want to Remove this Record ?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-danger " id="delete-record">Yes, Delete It!</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end modal delete -->
@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/prismjs/prismjs.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/project-create.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/prismjs/prismjs.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/list.pagination.js/list.pagination.js.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.5.11/full-all/ckeditor.js"></script>
    <script src="{{ URL::asset('backend/assets/js/checkUrl.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.remove-item-btn').click(function (e) {
                e.preventDefault();
                $('#record_id').val($(this).val())
                $('#deleteRecordModal').modal('show');
            });

            $('#delete-record').click(function () {
                const item_id = $('#record_id').val()
                const form = $('#form-delete');
                form.attr('action', '/admin/'+ item_id+'/danh-muc-tin-tuc');
                form.submit();
            });
        })
    </script>
@endsection
