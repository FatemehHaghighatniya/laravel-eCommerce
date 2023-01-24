@extends('admin.layouts.admin')

@section('title')
index Orders
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="d-flex justify-content-between mb-4">
                <h5 class="font-weight-bold">لیست سفارشات ({{ $orders->total() }})</h5>
{{--                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.categories.create') }}">--}}
{{--                    <i class="fa fa-plus"></i>--}}
{{--                    ایجاد دسته بندی--}}
{{--                </a>--}}
            </div>

            <div>
                <table class="table table-bordered table-striped text-center">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام کاربر</th>
                            <th>وضعیت</th>
                            <th>مبلغ</th>
                            <th>نوع پرداخت</th>
                            <th>وضعیت پرداخت</th>
                            <th> عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <th>
                                    {{ $order->id }}
                                </th>
                                <th>
                                    {{ $order->user->name == null ? $order->user->cellphone : $order->user->name }}
                                </th>
                                <th>
                                    {{$order->status}}
                                </th>
                                <th>
                                    {{number_format($order->total_amount)}}
                                </th>
                                <th>
                                    {{$order->payment_type}}
                                </th>
                                <th>
                                    {{$order->payment_status}}
                                </th>
                                <th>
                                    <a class="btn btn-sm btn-outline-success"
                                       href="{{ route('admin.orders.show', ['order' => $order->id]) }}">نمایش</a>
                                    <a class="btn btn-sm btn-outline-info mr-3"
                                       href="{{ route('admin.orders.edit', ['order' => $order->id]) }}">ویرایش</a>
                                </th>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
