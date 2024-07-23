@extends('client.layouts.master')
 @section('title')
     Chi tiết sản phẩm
 @endsection
 @section('content')
     <!-- Shop Detail Start -->
     <div class="container-fluid py-5">
         <div class="row px-xl-5">
             <div class="col-lg-5 pb-5">
                     <img class="w-100 h-100" src="{{ $product->image_thumbnail }}" alt="Image">
             </div>
             <div class="col-lg-7 pb-5">
                 <h3 class="font-weight-semi-bold">{{ $product->name }}</h3>
                 <div class="d-flex mb-3">
                     <small class="pt-1">CategoryName: {{ $categoryName }}</small>
                     <small class="ml-5 pt-1">view: {{ $product->view }}</small>
                 </div>
                 <div class="d-flex">
                     @if ($product->price_sale)
                         <h3 class="font-weight-semi-bold mb-4">${{ $product->price_sale }}</h3>
                         <h3 class="text-muted ml-2"><del>${{ $product->price_regular }}</del></h6>
                         @else
                             <h3 class="font-weight-semi-bold mb-4">${{ $product->price_regular }}</h6>
                     @endif
                 </div>
                 <div class="d-flex mb-3">
                     <p class="text-dark font-weight-medium mb-0 mr-3"> Description: </p>
                     <span>{{ $product->description }}</span>
                 </div>
                 <div class="d-flex mb-3">
                     <p class="text-dark font-weight-medium mb-0 mr-3"> Material: </p>
                     <span>{{ $product->material }}</span>
                 </div>

                 <div class="d-flex align-items-center mb-4 pt-2">
                     <div class="input-group quantity mr-3" style="width: 130px;">
                         <div class="input-group-btn">
                             <button class="btn btn-primary btn-minus">
                                 <i class="fa fa-minus"></i>
                             </button>
                         </div>
                         <input type="text" class="form-control bg-secondary text-center" value="1">
                         <div class="input-group-btn">
                             <button class="btn btn-primary btn-plus">
                                 <i class="fa fa-plus"></i>
                             </button>
                         </div>
                     </div>
                     <button class="btn btn-primary px-3"><i class="fa fa-shopping-cart mr-1"></i> Add To Cart</button>
                 </div>
             </div>
         </div>
     <!-- Shop Detail End -->

     <!-- Products Start -->
     <div class="container-fluid py-5">
         <div class="text-center mb-4">
             <h2 class="section-title px-5"><span class="px-2">You May Also Like</span></h2>
         </div>
         <div class="row px-xl-5">
             <div class="col">
                 <div class="owl-carousel related-carousel">
@foreach ($productView as $product)
                         <div class="card product-item border-0">
                             <div
                                 class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                 <img class="img-fluid w-100" src="{{ $product->image_thumbnail }}" alt="">
                             </div>
                             <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                 <h6 class="text-truncate mb-3">{{ $product->name }}</h6>
                                 <div class="d-flex justify-content-center">
                                     <h6>${{ $product->price_sale }}</h6>
                                     <h6 class="text-muted ml-2"><del>${{ $product->price_regular }}</del></h6>
                                 </div>
                             </div>
                             <div class="card-footer d-flex justify-content-between bg-light border">
                                 <a href="{{ route('detail', $product->id) }}" class="btn btn-sm text-dark p-0"><i
                                         class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                                 <a href="" class="btn btn-sm text-dark p-0"><i
                                         class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a>
                             </div>
                         </div>
                     @endforeach
                 </div>
             </div>
         </div>
     </div>
     <!-- Products End -->
 @endsection
