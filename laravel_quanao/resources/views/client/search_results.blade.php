@extends('client.layouts.master')

@section('content')

<section class="search-results p-t-30 p-b-80 bg0">
    <div class="container">

        <!-- Header kết quả tìm kiếm -->
        <div class="d-flex justify-content-between align-items-center mb-20">
            <h4 class="search-title m-0">
                Kết quả tìm kiếm cho: <span class="search-key">{{ $query }}</span>
            </h4>
            @if(count($sanpham) > 0)
                <span class="result-count text-muted">{{ count($sanpham) }} sản phẩm</span>
            @endif
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="row isotope-grid">
            @forelse($sanpham as $item)
                <div class="col-6 col-md-4 col-lg-3 mb-30 isotope-item">
                    <div class="card product-card border-0 shadow-sm h-100">
                        <div class="product-img position-relative">
                            <img src="{{ $item->anh_sanpham }}" class="card-img-top" alt="{{ $item->ten_san_pham }}">
                            <a href="{{ route('san_pham.showdetail', $item->ma_san_pham) }}"
                               class="btn btn-sm btn-primary position-absolute top-50 start-50 translate-middle">
                                Chi tiết
                            </a>
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <h6 class="card-title mb-2 text-truncate" title="{{ $item->ten_san_pham }}">
                                {{ $item->ten_san_pham }}
                            </h6>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted text-decoration-line-through">
                                    {{ number_format($item->gia) }} VNĐ
                                </span>
                                <span class="fw-bold text-danger">
                                    {{ number_format($item->gia * 0.9) }} VNĐ
                                </span>
                            </div>
                            <div class="mt-auto d-flex justify-content-end">
                                <i class="favorite-btn zmdi zmdi-favorite-outline"
                                   data-id="{{ $item->ma_san_pham }}"
                                   data-name="{{ $item->ten_san_pham }}"
                                   data-img="{{ $item->anh_sanpham }}"
                                   data-url="{{ route('san_pham.showdetail', $item->ma_san_pham) }}"
                                   style="font-size:22px; cursor:pointer;">
                                </i>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center text-muted fs-5 mt-5">
                        Không có sản phẩm nào phù hợp với từ khóa "<strong>{{ $query }}</strong>"
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- CSS thêm -->
<style>
.search-title {
    font-size: 20px;
    font-weight: 500;
}
.search-key {
    font-weight: 700;
    color: #e74c3c;
}
.result-count {
    font-size: 14px;
}
.product-card .product-img {
    overflow: hidden;
    border-radius: 8px;
}
.product-card .product-img img {
    transition: transform 0.3s ease;
}
.product-card .product-img:hover img {
    transform: scale(1.1);
}
.product-card .btn {
    opacity: 0;
    transition: opacity 0.3s ease;
}
.product-card .product-img:hover .btn {
    opacity: 1;
}
.card-title {
    font-size: 14px;
}
</style>

@endsection
