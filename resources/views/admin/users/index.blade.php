@extends('admin.layouts.admin')

@section('title')
    index users
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="d-flex justify-content-between mb-4">
                <h5 class="font-weight-bold">لیست کاربران ({{ $users->total() }})</h5>
            </div>

            <div>
                <table class="table table-bordered table-striped text-center">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>آواتار</th>
                            <th>نام</th>
                            <th>ایمیل</th>
                            <th>شماره تلفن</th>
                            <th>نقش </th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                            <tr>
                                <th>
                                    {{ $users->firstItem() + $key }}
                                </th>
                                <th>
                                    {{ $user->avatar }}
                                </th>
                                <th>
                                    {{ $user->name }}
                                </th>
                                <th>
                                    {{ $user->email }}
                                </th>
                                <th>
                                    {{ $user->cellphone }}
                                </th>
                                <th>
                                    {{ $user->roles->pluck('name') }}
                                </th>

                                <th>
                                    <a class="btn btn-sm btn-outline-info mr-3"
                                        href="{{ route('admin.users.edit', ['user' => $user->id]) }}">ویرایش</a>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
