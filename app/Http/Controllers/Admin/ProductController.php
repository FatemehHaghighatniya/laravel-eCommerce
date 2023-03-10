<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products=Product::latest()->paginate(20);
        return view('admin.products.index',compact(['products']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories=Category::where('parent_id','!=',0)->get();

        $tags=Tag::all();
        $brands=Brand::all();
        return view('admin.products.create',compact(['categories','tags','brands']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'brand_id' => 'required',
            'is_active' => 'required',
            'tag_ids' => 'required',
            'description' => 'required',
            'primary_image' => 'required|mimes:jpg,jpeg,png,svg',
            'images' => 'required',
            'images.*' => 'mimes:jpg,jpeg,png,svg',
            'category_id' => 'required',
            'attribute_ids' => 'required',
            'attribute_ids.*' => 'required',
            'variation_values' => 'required',
            'variation_values.*.*' => 'required',
            'variation_values.price.*' => 'integer',
            'variation_values.quantity.*' => 'integer',
            'delivery_amount' => 'required|integer',
            'delivery_amount_per_product' => 'nullable|integer',
        ]);
        try {
            DB::beginTransaction();

            $productImageCoctroller = new ProductImageController();
            $fileNameImages = $productImageCoctroller->upload($request->primary_image, $request->images);

            $product = Product::create([
                'name' => $request->name,
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'primary_image' => $fileNameImages['fileNamePrimaryImage'],
                'description' => $request->description,
                'is_active' => $request->is_active,
                'delivery_amount' => $request->delivery_amount,
                'delivery_amount_per_product' => $request->delivery_amount_per_product,

            ]);
            foreach ($fileNameImages['fileNameImages'] as $fileNameImage) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $fileNameImage
                ]);
            }
            $productAttributeController = new ProductAttributeController();
            $productAttributeController->store($request->attribute_ids, $product);


            $category = Category::find($request->category_id);
            $productVariationController = new ProductVariationController();
            $productVariationController->store($request->variation_values, $category->attributes()->where('is_variation', 1)->first()->id, $product);
            $product->tags()->attach($request->tag_ids);
            DB::commit();
            }catch(\Exception $ex){
            DB::rollBack();
            alert()->error('???????? ???? ?????????? ??????????',$ex->getMessage())->persistent('??????');
            return redirect()->back();
        }
        alert()->success('?????????? ???? ???????????? ?????????? ????','????????');
        return redirect()->route('admin.products.index');
        }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $productAttributes=$product->attributes()->with('attribute')->get();
        $productVariations=ProductVariation::where('product_id',$product->id)->get();
        $images=$product->images;
        return view('admin.products.show',compact(['productAttributes','productVariations','images','product']));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $tags=Tag::all();
        $brands=Brand::all();
        $productTagIds = $product->tags()->pluck('id')->toArray();
        $productVariations=ProductVariation::where('product_id',$product->id)->get();
        $productAttributes=$product->attributes()->with('attribute')->get();
        return view('admin.products.edit', compact(['tags','brands','productTagIds','product','productVariations','productAttributes']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
           'name'=>'required',
           'brand_id'=>'required|exists:brands,id',
           'is_active'=>'required',
           'tag_ids'=>'required',
           'tag_ids.*'=>'exists:tags,id',
            'description'=>'required',
            'delivery_amount' => 'required|integer',
            'delivery_amount_per_product' => 'nullable|integer',
            'attribute_values'=>'required',
            'variation_values'=>'required',
            'variation_values.*.price'=>'required|integer',
            'variation_values.*.quantity'=>'required|integer',
            'variation_values.*.sku'=>'required',
            'variation_values.*.sale_price'=>'nullable|integer',
            'variation_values.*.date_on_sale_from'=>'nullable|date',
            'variation_values.*.date_on_sale_to'=>'nullable|date',
        ]);
          try {
            DB::beginTransaction();


            $product->update([
                'name'=>$request->name,
                'brand_id'=>$request->brand_id,
                'is_active'=>$request->is_active,
                'description'=>$request->description,
                'delivery_amount' =>$request->delivery_amount,
                'delivery_amount_per_product' =>$request->delivery_amount_per_product,
            ]);
                $productAttributeController=new ProductAttributeController();
                $productAttributeController->update($request->attribute_values);

                $productVariationController=new ProductVariationController();
                $productVariationController->update($request->variation_values);


                $product->tags()->sync($request->tag_ids);

              DB::commit();
            }catch (\Exception $ex){
              DB::rollBack();
              alert()->error('???????? ???? ???????????? ??????????', $ex->getMessage())->persistent('??????');
              return redirect()->back();
          }
          alert()->success('?????????? ???????? ?????? ???????????? ????','????????');
          return redirect()->route('admin.products.index');



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function editCategory(Request $request,Product $product){
        $categories=Category::where('parent_id','!=',0)->get();
        return view('admin.products.edit_category',compact(['product','categories']));
    }


    public function updateCategory(Request $request,Product $product){
        $request->validate([
            'category_id' => 'required',
            'attribute_ids' => 'required',
            'attribute_ids.*' => 'required',
            'variation_values' => 'required',
            'variation_values.*.*' => 'required',
            'variation_values.price.*' => 'integer',
            'variation_values.quantity.*' => 'integer',
                   ]);
        try {
            DB::beginTransaction();


            $productAttributeController = new ProductAttributeController();
            $productAttributeController->change($request->attribute_ids, $product);


            $category = Category::find($request->category_id);
            $productVariationController = new ProductVariationController();
            $productVariationController->change($request->variation_values, $category->attributes()->where('is_variation', 1)->first()->id, $product);

            DB::commit();
        }catch(\Exception $ex){
            DB::rollBack();
            alert()->error('???????? ???? ???????????? ??????????',$ex->getMessage())->persistent('??????');
            return redirect()->back();
        }
        alert()->success('?????????? ???? ???????????? ???????????? ????','????????');
        return redirect()->route('admin.products.index');
    }


}
