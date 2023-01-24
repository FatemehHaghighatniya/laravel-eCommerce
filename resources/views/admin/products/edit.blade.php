@extends('admin.layouts.admin')

@section('title')
    edit products
@endsection

@section('script')
    <script src="{{asset('files/jalalidatepicker/persian-date.min.js')}}"></script>
    <script src="{{asset('files/jalalidatepicker/persian-datepicker.min.js')}}"></script>

{{--    <script>--}}

{{--        $(document).ready(function() {--}}
{{--            // $('#test').persianDatepicker({--}}
{{--            //     format: 'yyyy/mm/dd'--}}
{{--            // });--}}

{{--            let variations = @json($productVariations);--}}
{{--            variations.forEach(variation => {--}}
{{--               if ($(`#variationInputDateOnSaleFromView-${variation.id}`).val==null){--}}
{{--                   $(`#variationInputDateOnSaleFromView-${variation.id}`).val=null;--}}
{{--               } $(`#variationInputDateOnSaleFromView-${variation.id}`).persianDatepicker({--}}
{{--                    format: 'YYYY/mm/dd HH:mm:ss',--}}
{{--                    altField: `#variation_values${variation.id}`--}}
{{--                });--}}

{{--                    $(`#variationInputDateOnSaleToView-${variation.id}`).persianDatepicker({--}}
{{--                        format: 'YYYY/mm/dd HH:mm:ss',--}}
{{--                        altField: `variation_values${variation.id}`--}}
{{--                    });--}}

{{--                });--}}
{{--        });--}}



{{--    </script>--}}



    <script>
        $('#brandSelect').selectpicker({
            'title': 'انتخاب برند'
        });
        $('#tagSelect').selectpicker({
            'title': 'انتخاب تگ'
        });

        {{--let variations = @json($productVariations);--}}
        {{--variations.forEach(variation => {--}}
        {{--const md = new mds.MdsPersianDateTimePicker(document.getElementById(`variationDateOnSaleFrom-${variation.id}`), {--}}
        {{--    textFormat: 'yyyy-MM-dd HH:mm:ss',--}}
        {{--});--}}



        {{--let variations = @json($productVariations);--}}
        {{--variations.forEach(variation => {--}}
        {{--    $(`#variationDateOnSaleFrom-${variation.id}`).MdsPersianDateTimePicker({--}}
        {{--        targetTextSelector: `#variationInputDateOnSaleFrom-${variation.id}`,--}}
        {{--        englishNumber: true,--}}
        {{--        enableTimePicker: true,--}}
        {{--        textFormat: 'yyyy-MM-dd HH:mm:ss',--}}
        {{--    });--}}

        {{--    $(`#variationDateOnSaleTo-${variation.id}`).MdsPersianDateTimePicker({--}}
        {{--        targetTextSelector: `#variationInputDateOnSaleTo-${variation.id}`,--}}
        {{--        englishNumber: true,--}}
        {{--        enableTimePicker: true,--}}
        {{--        textFormat: 'yyyy-MM-dd HH:mm:ss',--}}
        {{--    });--}}
        // });

    </script>
    <link rel="stylesheet" href="{{asset('files/jalalidatepicker/persian-datepicker.min.css')}}">
@endsection

@section('content')

    <!-- Content Row -->

        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="mb-4">
                <h5 class="font-weight-bold">ویرایش محصول {{ $product->name }}</h5>
            </div>
            <hr>

            @include('admin.sections.errors')

            <form action="{{ route('admin.products.update', ['product' => $product->id]) }}" method="POST">
                @csrf
                @method('put')
                <div class="form-row">

                    <div class="form-group col-md-3">
                        <label for="name">نام</label>
                        <input class="form-control" id="name" name="name" type="text" value="{{ $product->name }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="brand_id">برند</label>
                        <select id="brandSelect" name="brand_id" class="form-control" data-live-search="true">
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $brand->id == $product->brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="is_active">وضعیت</label>
                        <select class="form-control" id="is_active" name="is_active">
                            <option value="1" {{ $product->getRawOriginal('is_active') ? 'selected' : '' }}>فعال</option>
                            <option value="0" {{ $product->getRawOriginal('is_active') ? '' : 'selected' }}>غیرفعال</option>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="tag_ids">تگ</label>
                        <select id="tagSelect" name="tag_ids[]" class="form-control" multiple data-live-search="true">
{{--                            @php--}}
{{--                            $productTagIds = $product->tags()->pluck('id')->toArray()--}}
{{--                            @endphp--}}
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}" {{ in_array($tag->id, $productTagIds) ? 'selected' : '' }}>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="description">توضیحات</label>
                        <textarea class="form-control" id="description" name="description"
                            rows="4">{{ $product->description }}</textarea>
                    </div>

                    {{-- Delivery Section --}}
                    <div class="col-md-12">
                        <hr>
                        <p>هزینه ارسال : </p>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="delivery_amount">هزینه ارسال</label>
                        <input class="form-control" id="delivery_amount" name="delivery_amount" type="text"
                            value="{{ $product->delivery_amount }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="delivery_amount_per_product">هزینه ارسال به ازای محصول اضافی</label>
                        <input class="form-control" id="delivery_amount_per_product" name="delivery_amount_per_product"
                            type="text" value="{{ $product->delivery_amount_per_product }}">
                    </div>

                    {{-- Attributes & Variations --}}
                    <div class="col-md-12">
                        <hr>
                        <p>ویژگی ها : </p>
                    </div>
                    @foreach ($productAttributes as $productAttribute)
                        <div class="form-group col-md-3">
                            <label>{{ $productAttribute->attribute->name }}</label>
                            <input class="form-control" type="text" name="attribute_values[{{ $productAttribute->id }}]"
                                value="{{ $productAttribute->value }}">
                        </div>
                    @endforeach

                    @foreach ($productVariations as $variation)
                        <div class="col-md-12">
                            <hr>
                            <div class="d-flex">
                                <p class="mb-0"> قیمت و موجودی برای متغیر ( {{ $variation->value }} ) : </p>
                                <p class="mb-0 mr-3">
                                    <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse"
                                        data-target="#collapse-{{ $variation->id }}">
                                        نمایش
                                    </button>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="collapse mt-2" id="collapse-{{ $variation->id }}">
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label> قیمت </label>
                                            <input type="text" class="form-control"
                                                name="variation_values[{{ $variation->id }}][price]"
                                                value="{{ $variation->price }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label> تعداد </label>
                                            <input type="text" class="form-control"
                                                name="variation_values[{{ $variation->id }}][quantity]"
                                                value="{{ $variation->quantity }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label> sku </label>
                                            <input type="text" class="form-control"
                                                name="variation_values[{{ $variation->id }}][sku]"
                                                value="{{ $variation->sku }}">
                                        </div>

                                        {{-- Sale Section --}}
                                        <div class="col-md-12">
                                            <p> حراج : </p>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label> قیمت حراجی </label>
                                            <input type="text" name="variation_values[{{ $variation->id }}][sale_price]"
                                                value="{{ $variation->sale_price }}" class="form-control">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label> تاریخ شروع حراجی </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend order-2">
                                                    <span class="input-group-text" id="variationDateOnSaleFrom-{{ $variation->id }}">
                                                        <i class="fas fa-clock"></i>
                                                    </span>

                                                </div>
                                                <input type="text" class="d-none" name="variation_values[{{ $variation->id }}][date_on_sale_from]" id="variation_values{{ $variation->id }}">
                                                <input type="text" class="form-control" id="variationInputDateOnSaleFromView-{{ $variation->id }}"
                                                    name="variation_values[{{ $variation->id }}][date_on_sale_from]"
                                                    value="{{ $variation->date_on_sale_from == null ? null : verta($variation->date_on_sale_from) }}">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label> تاریخ پایان حراجی </label>

                                            <div class="input-group">
                                                <div class="input-group-prepend order-2">
                                                    <span class="input-group-text" id="variationDateOnSaleTo-{{ $variation->id }}">
                                                        <i class="fas fa-clock"></i>
                                                    </span>

{{--                                                    <span id="variationDateOnSaleTo-{{ $variation->id }}" class="input-group-text cursor-pointer" data-mds-dtp-guid="8b5e4bbc-939f-44c4-9ab5-13f9604cc178" data-bs-original-title="" title="" data-mds-dtp-group="group1" data-to-date="true">📅</span>--}}
                                                </div>
                                                <input type="text" class="d-none" name="variation_values[{{ $variation->id }}][date_on_sale_to]" id="variation_values{{ $variation->id }}">
                                                <input type="text" class="form-control" id="variationInputDateOnSaleToView-{{ $variation->id }}"

                                                    value="{{ $variation->date_on_sale_to == null ? null : verta($variation->date_on_sale_to) }}">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

                <button class="btn btn-outline-primary mt-5" type="submit">ویرایش</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>

    </div>

@endsection
