<!-- HEADER -->
<div class="top-bar">
    <div class="content-topbar flex-sb-m h-full container">
        <div class="left-top-bar">
            Hè năng động cùng Coolmate tặng ngay voucher 299k
        </div>

        <div class="right-top-bar flex-w h-full">
            <a href="{{ route('help') }}" class="flex-c-m trans-04 p-lr-25">Trợ giúp</a>

            <li class="nav-item dropdown no-arrow flex-c-m trans-04 p-lr-25">
                <a href="#" class="nav-link dropdown-toggle" id="userDropdown" data-toggle="dropdown">
                    Tài khoản
                </a>

                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" style="z-index:9999; position:relative;">
                    @if(Auth::user())
                        <a class="dropdown-item"><span>{{ Auth::user()->name }}</span></a>
                        <a class="dropdown-item" href="/thongtindonhang">Đơn hàng của tôi</a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST">@csrf @method('DELETE')
                            <button class="dropdown-item">Đăng xuất</button>
                        </form>
                    @else
                        <form action="{{ route('login') }}" method="GET">@csrf
                            <button type="submit" class="dropdown-item">Đăng nhập</button>
                        </form>
                        <form action="{{ route('register') }}" method="GET">@csrf
                            <button type="submit" class="dropdown-item">Đăng ký</button>
                        </form>
                    @endif
                </div>
            </li>

            <a href="#" class="flex-c-m trans-04 p-lr-25">Ngôn ngữ</a>
        </div>
    </div>
</div>

<div class="wrap-menu-desktop">
    <nav class="limiter-menu-desktop container">

        <!-- Logo -->
        <a href="/" class="logo">
            <img src="https://www.coolmate.me/images/logo-coolmate-new.svg?v=1" alt="IMG-LOGO">
        </a>

        <!-- Menu -->
        <div class="menu-desktop">
            <ul class="main-menu" style="margin-left:-50px">
                <li><a href="/trangchu">Trang chủ</a></li>
                <li>
                    <a href="/trangchu">Danh mục</a>
                    <ul class="sub-menu">
                        @foreach($showdanhmuc as $item)
                            <li><a href="#">{{ $item->ten_danh_muc }}</a></li>
                        @endforeach
                    </ul>
                </li>
                <li><a href="/sanphamuser">Sản phẩm</a></li>
                <li><a href="/giohang">Ưu đãi</a></li>
                <li><a href="{{ route('userblog') }}">Blog</a></li>
                <li><a href="/about">Tìm hiểu</a></li>
                <li><a href="/lienhe">Liên hệ</a></li>
            </ul>
        </div>

        <!-- ICONS -->
        <div class="wrap-icon-header flex-w flex-r-m">

            <!-- Search -->
            <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
                <i class="zmdi zmdi-search"></i>
            </div>

            <!-- Cart -->
            <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart"
                data-notify="{{ count((array) session('cart')) }}">
                <i class="zmdi zmdi-shopping-cart"></i>
            </div>

            <!-- Wishlist -->
            <a id="wishlist-icon"
                href="javascript:void(0)"
                class="dis-block icon-header-item cl2 hov-cl1 trans-04 icon-header-noti"
                data-notify="0">
                    <i class="zmdi zmdi-favorite-outline"></i>
                </a>

        </div>
    </nav>
</div>


<!-- ============================
        WISHLIST MODAL
=============================== -->
<style>
    #wishlist-modal {
        position: fixed;
        top: 0;
        right: -350px;
        width: 350px;
        height: 100%;
        background: #fff;
        z-index: 2000;
        padding: 20px;
        overflow-y: auto;
        transition: 0.4s ease;
        box-shadow: 0 0 15px rgba(0,0,0,0.25);
    }

    #wishlist-modal.show {
        right: 0;
    }
</style>

<div id="wishlist-modal">
    <h4 class="mtext-109 cl2 p-b-30 flex-sb-m">
        <span>Danh sách yêu thích</span>
        <span id="close-wishlist" style="cursor:pointer;font-size:22px;">✕</span>
    </h4>

    <ul id="wishlist-items" class="header-cart-wrapitem w-full"></ul>
</div>


<!-- ============================
            SCRIPT
=============================== -->
<script>
document.addEventListener("DOMContentLoaded", () => {

    let wishlist = JSON.parse(localStorage.getItem("wishlist") || "[]");

    updateWishlistCount();
    renderWishlist();

    // mở wishlist
    document.getElementById("wishlist-icon").onclick = (e) => {
        e.preventDefault();
        document.getElementById("wishlist-modal").classList.add("show");
    };

    // đóng
    document.getElementById("close-wishlist").onclick = () => {
        document.getElementById("wishlist-modal").classList.remove("show");
    };

    // nút trái tim sản phẩm
    document.querySelectorAll(".favorite-btn").forEach(btn => {
        btn.onclick = function () {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const img = this.dataset.img;
            const url = this.dataset.url;   // *** quan trọng ***

            // kiểm tra tồn tại
            let exists = wishlist.find(i => i.id == id);

            if (!exists) {
                wishlist.push({ id, name, img, url });
                this.classList.add("active");
            } else {
                wishlist = wishlist.filter(i => i.id != id);
                this.classList.remove("active");
            }

            localStorage.setItem("wishlist", JSON.stringify(wishlist));
            updateWishlistCount();
            renderWishlist();
        };
    });

    function updateWishlistCount() {
        document.getElementById("wishlist-icon")
            .setAttribute("data-notify", wishlist.length);
    }

    // ⭐⭐⭐ HÀM CHÍNH – chỉ giữ 1 hàm duy nhất ⭐⭐⭐
    function renderWishlist() {
        let container = document.getElementById("wishlist-items");
        container.innerHTML = "";

        if (wishlist.length === 0) {
            container.innerHTML = "<p>Chưa có sản phẩm yêu thích.</p>";
            return;
        }

        wishlist.forEach(item => {
            container.innerHTML += `
                <li class="header-cart-item flex-w flex-t m-b-12">

                    <div class="header-cart-item-img">
                        <a href="${item.url}">
                            <img src="${item.img}" alt="${item.name}">
                        </a>
                    </div>

                    <div class="header-cart-item-txt p-t-8">
                        <a href="${item.url}" 
                           class="header-cart-item-name m-b-18 hov-cl1 trans-04"
                           style="font-size:16px;">
                            ${item.name}
                        </a>

                        <a href="${item.url}" class="stext-103 cl3 hov-cl1">
                            Xem chi tiết →
                        </a>
                    </div>

                </li>
            `;
        });
    }

}); // end DOMContentLoaded
</script>
<style>
    #wishlist-icon {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 40px;
    height: 40px;
    position: relative;
    padding: 0 !important;
}

/* Fix luôn icon bên trong */
#wishlist-icon i {
    font-size: 22px;
    line-height: 1;
    margin: 0;
    padding: 0;
}
</style>