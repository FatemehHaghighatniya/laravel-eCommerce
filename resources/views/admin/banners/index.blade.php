@extends('admin.layouts.admin')

@section('title')
    index banners
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="d-flex justify-content-between mb-4">
                <h5 class="font-weight-bold">لیست بنرها </h5>
                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.banners.create') }}">
                    <i class="fa fa-plus"></i>
                    ایجاد بنر
                </a>
            </div>

            <div>
                <table class="table table-bordered table-striped text-center">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>تصویر</th>
                            <th>عنوان</th>
                            <th>متن</th>
                            <th>اولویت</th>
                            <th>وضعیت</th>
                            <th>نوع</th>
                            <th>متن دکمه</th>
                            <th>لینک دکمه</th>
                            <th>آیکن دکمه</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($banners as $key => $banner)
                            <tr>
                                <th>
                                    {{ $banners->firstItem() + $key }}
                                </th>
                                <th>
                                    {{\Illuminate\Support\Str::limit($banner->image,10) }}
                                </th>
                                <th>
                                       {{$banner->title}}
                                </th>
                                <th>
                                    {{Illuminate\Support\Str::limit($banner->text,10
)}}
                                </th>
                                <th>
                                    {{$banner->priority}}
                                </th>
                                <th>
                                    {{$banner->is_active}}
                                </th>
                                <th>
                                    {{$banner->type}}
                                </th>
                                <th>
                                    {{$banner->button_text}}
                                </th>

                                <th>
                                    {{$banner->button_link}}
                                </th>
                                <th>
                                    {{$banner->button_icon}}
                                </th>
                                <th>
                                <div class="row justify-content-center">

                                    <a href="{{route('admin.banners.edit',['banner'=>$banner->id])}}" class="btn btn-outline-primary btn-sm mt-2 mr-2">ویرایش</a>

                                    <form action="{{route('admin.banners.destroy',['banner'=>$banner->id])}}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <input type="hidden" name="banner_id" value="{{$banner->id}}">
                                        <button class="btn btn-sm btn-outline-danger mt-2 mr-2" type="submit">حذف</button>
                                    </form>
                                </div>




                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <a href="{{ route('admin.banners.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
                </form>
            </div>
        </div>
        </div>
    @endsection
