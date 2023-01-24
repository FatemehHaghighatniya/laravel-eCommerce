@extends('admin.layouts.admin')

@section('title')
    بخش نظرات
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="d-flex justify-content-between mb-4">
                <h5 class="font-weight-bold">لیست نظرات  ({{ $comments->total() }})</h5>

            </div>

            <div>
                <table class="table table-bordered table-striped text-center">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام کاربر</th>
                            <th>نام محصول</th>
                            <th>متن دیدگاه</th>
                            <th>وضعیت</th>
                            <th>تاریخ ایجاد</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comments as $comment)
                            <tr>
                                <th>
                                    {{ $comment->id }}
                                </th>
                                <th>
                                    {{ $comment->user->name == null ?  $comment->user->name : $comment->user->cellphone }}
                                </th>
                                <th>
                                        {{$comment->product->name}}
                                </th>
                                <th>
                                    {{\Illuminate\Support\Str::limit($comment->text,30)}}
                                </th>

                                <th class="{{$comment->getRawOriginal('approved') ? 'text-success' : 'text-danger'}}">
                                    {{$comment->approved}}

                                </th>
                                <th>
                                    {{verta($comment->created_at)}}
                                </th>

                                <th>
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="{{ route('admin.comments.show', ['comment' => $comment->id]) }}">نمایش</a>

                                    <form action="{{route('admin.comments.destroy',['comment'=>$comment->id])}}"  method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">حذف</button>
                                    </form>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{$comments->links()}}
            </div>
        </div>

    </div>
@endsection
