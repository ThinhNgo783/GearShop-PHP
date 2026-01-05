@extends('layouts.frontend')
@section('title', 'Sản phẩm phân loại')
@section('content')

<div class="container pb-5 mb-2 mb-md-4">
    <h2 class="mb-4">Sản phẩm: {{ $loaisanpham->tenloai }}</h2>
    <div class="row pt-3 mx-n2">
    @foreach($sanpham as $sp)
    <div class="col-lg-3 col-md-4 col-sm-6 px-2 mb-4">
        <div class="card product-card">
            <img src="{{ asset('storage/app/' . $sp->hinhanh) }}" />
            <div class="card-body py-2">
                <h3 class="product-title fs-sm">
                    <a href="frontend.sanpham.chitiet">{{ $sp->tensanpham }}</a>
                </h3>
                <div class="d-flex justify-content-between">
                    <div class="product-price">
                        <span class="text-accent">{{ number_format($sp->dongia) }}<small>đ</small></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeac