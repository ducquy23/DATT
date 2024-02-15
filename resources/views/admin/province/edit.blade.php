@extends('layouts.admin')
@section('content')
@if($errors ->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{$error}}</li>
        @endforeach
    </ul>

</div>
@endif
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Cập nhật khu vực</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <!-- <h6 class="m-0 font-weight-bold text-primary"></h6> -->
        </div>
        <div class="card-body">
            <form method="post" action="{{route('province.update', $province->id)}}" enctype="multipart/form-data">
                @csrf
                <!-- Đoạn mã HTML -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên khu vực</label>
                            <input type="text" class="form-control" name="name" id="name" oninput="generateSlug()" value="{{$province->name}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" name="slug" id="slug" value="{{$province->slug}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="image">Ảnh</label> <br>
                            <input name="image" type="file" id="image_url" style="display: none" value="{{$province->image}}">
                            <img src="{{ asset('images/image-not-found.jpg') }}" width="150" height="130" id="image_preview" class="mt-1" alt="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Thông tin</label>
                            <input type="text" class="form-control" name="description" id="description" value="{{$province->description}}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status" style="height:50px;">
                            <option value="">--Chọn--</option>
                            <option value="1" {{ $province->status == 1 ? 'selected' : '' }}>Duyệt</option>
                            <option value="2" {{ $province->status == 2 ? 'selected' : '' }}>Chưa duyệt</option>
                        </select>
                    </div>
                </div>

                <div class="submit-button-container">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('province.index') }}"><button class="btn btn-success" type="button">Danh sách</button></a>
                    <button type="reset" class="btn btn-warning">Làm Lại</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
@push('styles')
<style>
    /* CSS cho label */
    label.form-label {
        font-weight: bold;
        color: #333;
        /* Màu chữ */
        font-size: 16px;
        margin-bottom: 10px;
        /* Khoảng cách giữa label và select */
    }

    /* CSS cho select */
    select.form-select {
        width: 100%;
        /* Độ rộng tối đa */
        height: 80px;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        /* Đường viền */
        border-radius: 5px;
        background-color: #fff;
        /* Màu nền */
        color: #333;
        /* Màu chữ */
    }

    /* Hiệu ứng hover cho select */
    select.form-select:hover {
        border-color: #999;
    }

    /* Hiệu ứng focus cho select */
    select.form-select:focus {
        border-color: #007bff;
        /* Màu viền khi select được focus */
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        /* Hiệu ứng bóng đổ */
    }

    /* Định dạng option trong select */
    select.form-select option {
        font-size: 14px;
        padding: 5px;
        background-color: #fff;
        /* Màu nền */
        color: #333;
        /* Màu chữ */
    }

    /* Hiệu ứng hover cho option */
    select.form-select option:hover {
        background-color: #007bff;
        /* Màu nền khi hover */
        color: #fff;
        /* Màu chữ khi hover */
    }

    .submit-button-container {
        margin-top: 20px;
        /* Đặt khoảng cách 20px giữa nút và cột */
    }

    /* Định dạng cơ bản cho danh mục */
    .post_type {
        margin: 20px;
    }

    /* Định dạng danh mục cha */
    .post_type ul {
        list-style: none;
        padding: 0;
    }

    .post_type ul li {
        margin-bottom: 10px;
    }

    /* Định dạng danh mục con */
    .post_type ul ul {
        margin-left: 20px;
        display: none;
    }

    /* Hiển thị danh mục con khi checkbox được kiểm tra */
    .post_type input[type="checkbox"]:checked+label+ul {
        display: block;
    }

    /* Tùy chỉnh màu và kiểu hiển thị của liên kết */
    .post_type label {
        cursor: pointer;
        color: #333;
    }

    .post_type label:hover {
        color: #007bff;
    }
</style>

@endpush
@push('scripts')
<script src="{{ asset('upload_file/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('upload_file/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('upload_file/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/unidecode@0.2.5/unidecode.min.js"></script>

<script>
    const imagePreview = document.getElementById('image_preview');
    const imageUrlInput = document.getElementById('image_url');

    $(function() {
        function readURL(input, selector) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function(e) {
                    $(selector).attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#image_url").change(function() {
            readURL(this, '#image_preview');
        });

    });
    imagePreview.addEventListener('click', function() {
        imageUrlInput.click();
    });

    imageUrlInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>

<script>
    function generateSlug() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        // Lấy giá trị từ ô input tên
        const name = nameInput.value;

        // Sử dụng unidecode để chuyển đổi thành slug
        const slug = unidecode(name).toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');

        // Gán giá trị slug vào ô input "slug" và thuộc tính "value"
        slugInput.value = slug;
    }
</script>


<script>
    var editor = CKEDITOR.replace('contents');
    CKFinder.setupCKEditor(editor);
</script>
@endpush




@endsection
