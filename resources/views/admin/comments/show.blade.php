@extends('admin.layouts.admin')

@section('title')
    بخش نمایش نظرات
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="mb-4">
                <h5 class="font-weight-bold">نظرات</h5>
            </div>
            <hr>

            <div class="row">
                <div class="form-group col-md-3">
                    <label> نام کاربر </label>
                    <input class="form-control" type="text" value="{{ $comment->user->name == null ?  $comment->user->name : $comment->user->cellphone }}
                        " disabled>
                </div>
                <div class="form-group col-md-3">
                    <label> نام محصول </label>
                    <input class="form-control" type="text" value="{{ $comment->product->name }}" disabled>
                </div>
                <div class="form-group col-md-3">
                    <label> تاریخ ایجاد </label>
                    <input class="form-control" type="text" value="{{ verta($comment->created_at)}}" disabled>
                </div>
                <div class="form-group col-md-3">
                    <label> وضعیت </label>
                    <input class="{{$comment->getRawOriginal('approved') ? 'text-success' : 'text-danger'}} form-control"
                     type="text" value="{{ $comment->approved }}" disabled>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6 mt-3">
                    <label> متن دیدگاه </label></div>
                    <textarea class="form-control" disabled>{{$comment->text}}</textarea>
            </div>
            </div>

        <div class="row mr-5">
            <a href="{{ route('admin.comments.index') }}" class="btn btn-dark mt-5">بازگشت</a>
            <a href="{{ route('home.comments.change-approve',['comment'=>$comment->id]) }}" class="btn btn-success mt-5 mr-3">تغییر وضعیت</a>
        </div>
        </div>

    </div>

@endsection
