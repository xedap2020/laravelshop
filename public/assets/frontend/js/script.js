////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////// ADD TO CART
const addToCartButtons = document.querySelectorAll(".product_cart--button");

if (addToCartButtons) {
    addToCartButtons.forEach(button => {
        button.addEventListener("click", (e) => {
            e.preventDefault();

            let product_id = button.value;
            console.log(product_id);

            $.ajax({
                type: "GET",
                url: '/add-to-cart/' + product_id,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Thêm CSRF token vào header
                },
                success: function (data) {
                    let existingToast = document.querySelector(".toastify");
                    if (existingToast) {
                        existingToast.remove();
                    }
                    // kiểm tra nếu toast trước đó vẫn còn
                    Toastify({
                        text: "Thêm vào giỏ hàng thành công thành công",
                        duration: 2000,
                        close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "right", // `left`, `center` or `right`
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                        className: "toastify-custom toastify-success"
                    }).showToast();
                },
                error: function () {
                    let existingToast = document.querySelector(".toastify");
                    if (existingToast) {
                        existingToast.remove();
                    }
                    Toastify({
                        text: "Thêm thất bại",
                        duration: 2000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        className: "toastify-custom toastify-error"
                    }).showToast();
                }
            }).done(function () {
                setTimeout(function () {
                    $('#overlay').fadeOut(300)
                }, 500)
            });
        })
    })
}


//////////////////////////////// REMOVE FROM CART
$(document).ready(function () {
    $(document).ajaxSend(function () {
        $("#overlay").fadeIn(300);
    })
    // Sử dụng .on() để gắn sự kiện cho các phần tử có lớp .cart_item_product--remove
    $(document).on('click', '.cart_item_product--remove', function (e) {
        e.preventDefault();
        let product_id = $(this).val();

        $.ajax({
            type: "GET",
            url: '/remove-from-cart/' + product_id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                // Sau khi xóa thành công, cập nhật lại nội dung giỏ hàng
                $.ajax({
                    type: "GET",
                    url: '/cart',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        // Thay thế nội dung #cart-content bằng HTML mới từ endpoint /cart
                        $('#cart').load(location.href + ' #cart');
                    },
                    error: function () {
                        console.log("Error loading cart content");
                    }
                }).done(function () {
                    setTimeout(function () {
                        $('#overlay').fadeOut(300)
                    }, 500)
                });
            },
            error: function () {
                console.log("Error removing product from cart");
            }
        });
    });
});

///////////////////////////////// UPDATE CART ITEM
$(document).ready(function () {
    $(document).ajaxSend(function () {
        $("#overlay").fadeIn(300);
    });
    $(document).on('change', '.cart_item_product--change', function (event) {
        event.preventDefault();
        let quantity = $(this).val();
        let product_id = $(this).data('id')

        $.ajax({
            type: 'POST',
            url: '/update-to-cart/' + product_id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                quantity: quantity
            },
            success: function (data) {
                $.ajax({
                    type: 'GET',
                    url: '/cart',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#cart').load(location.href + ' #cart');
                    }
                }).done(function () {
                    setTimeout(function () {
                        $("#overlay").fadeOut(300);
                    }, 500)
                })
            },
            error: function () {
                console.log('error')
            }
        })
    })
});


//////////////////////////////// RESPONSIVE //////////////////////

document.querySelector('.menu-toggle').addEventListener('click', function () {
    const nav = document.querySelector('.header_nav--nav');
    nav.classList.add('open');
    console.log(nav.classList.contains('open'));
});

document.addEventListener('click', function (event) {
    const nav = document.querySelector('.header_nav--nav');
    const menuBtn = document.querySelector('.menu-toggle');
    const isClickInsideMenu = nav.contains(event.target);
    const isClickInsideButton = menuBtn.contains(event.target);

    if (!isClickInsideMenu && !isClickInsideButton) {
        nav.classList.remove('open');
    }
});

// nav
document.addEventListener('DOMContentLoaded', function () {
    const currentPath = window.location.pathname;
    const savedPath = localStorage.getItem('activeNavPath');
    const navItems = document.querySelectorAll('.nav--item');

    navItems.forEach(item => {
        item.classList.remove('active');

        // Add 'active' class if the path matches
        if (item.getAttribute('data-path') === (savedPath || currentPath)) {
            item.classList.add('active');
        }

        item.addEventListener('click', function () {
            // Save the current path to local storage
            localStorage.setItem('activeNavPath', item.getAttribute('data-path'));
        });
    });
});


////////////////////////////////////////////////////////////////

// Feature xem thêm sản phẩm
$(document).ready(function () {
    $(document).ajaxSend(function () {
        $("#overlay").fadeIn(100);
    })
    $(document).on('click', '.product_more_view', function (e) {
        e.preventDefault();

        let num_product = $(this).val();
        console.log(num_product)

        $.ajax({
            type: 'GET',
            url: '/products',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                num_product: num_product
            },
            success: function (data) {
                let num_more_product = $(data).find('#num_more_product').html();
                $('#num_more_product').html(num_more_product);

                let product_list = $(data).find('.product_list').html();
                $('.product_list').html(product_list);
            }
        }).done(function () {
            setTimeout(function () {
                $('#overlay').fadeOut(100)
            }, 200)
        })
    })
})

$(document).ready(function () {
    $(document).ajaxSend(function () {
        $("#overlay").fadeIn(100);
    })
    $(document).on('click', '.product_more_view_detail', function (e) {
        e.preventDefault();

        let num_product = $(this).val();
        let product_id = $(this).data('id')
        console.log(num_product)

        $.ajax({
            type: 'GET',
            url: '/product_detail/' + product_id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                num_product: num_product
            },
            success: function (data) {
                let num_more_product = $(data).find('#num_more_product_detail').html();
                $('#num_more_product_detail').html(num_more_product);

                let product_list = $(data).find('.product_list').html();
                $('.product_list').html(product_list);
            }
        }).done(function () {
            setTimeout(function () {
                $('#overlay').fadeOut(100)
            }, 200)
        })
    })
})

// các category con khi được click sẽ tìm những sản pham liên quan đến nó
$(document).ready(function () {
    $(document).ajaxSend(function () {
        $("#overlay").fadeIn(100);
    })
    $(document).on('click', '#category_item_brand', function (e) {
        e.preventDefault();

        let slug = $(this).data('slug');
        let category = $(this).data('category');
        $.ajax({
            type: 'GET',
            url: '/products/' + slug,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                let num_more_product = $(data).find('#num_more_product').html();
                $('#num_more_product').html(num_more_product);

                let product_list = $(data).find('.product_list').html();
                $('.product_list').html(product_list);

                let detail_nav = $(data).find('.detail_nav').html();
                $('.detail_nav').html(detail_nav);

            }
        }).done(function () {
            setTimeout(function () {
                $('#overlay').fadeOut(100)
            }, 200)
        })
    })
})


// search global
$(document).ready(function () {
    $(document).ajaxSend(function () {
        $("#overlay2").fadeIn(10);
    })
    $('#search_product').donetyping(function () {
        let titlesearch = $(this).val().trim();
        $.ajax({
            type: 'GET',
            url: '/',
            data: {
                titlesearch: titlesearch
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                let search_list = $(data).find('#search_list').html();
                // $('#search_list').html(search_list);
                let items = $(data).find('#search_list a');

                if (items.length == 0) {
                    $('#search_list').html('Không tìm thấy sản phẩm phù hợp!');
                } else {
                    $('#search_list').html(search_list);
                }
            },
        }).done(function () {
            setTimeout(function () {
                $('#overlay2').fadeOut(10)
                console.log('Done')
            }, 10)
        });
        console.log('User finished typing:', titlesearch);

    }, 0.5e3);
})

// donetyping
;(function($){
    $.fn.extend({
        donetyping: function(callback,timeout){
            timeout = timeout || 1e3; // 1 second default timeout
            var timeoutReference,
                doneTyping = function(el){
                    if (!timeoutReference) return;
                    timeoutReference = null;
                    callback.call(el);
                };
            return this.each(function(i,el){
                var $el = $(el);
                // Chrome Fix (Use keyup over keypress to detect backspace)
                // thank you @palerdot
                $el.is(':input') && $el.on('keyup keypress paste',function(e){
                    // This catches the backspace and DEL button in chrome, but also prevents
                    // the event from triggering too preemptively. Without this line,
                    // using tab/shift+tab will make the focused element fire the callback.
                    if (e.type=='keyup' && !([8,46].includes(e.keyCode))){return;}

                    // Check if timeout has been set. If it has, "reset" the clock and
                    // start over again.
                    if (timeoutReference) clearTimeout(timeoutReference);
                    timeoutReference = setTimeout(function(){
                        // if we made it here, our timeout has elapsed. Fire the
                        // callback
                        doneTyping(el);
                    }, timeout);
                }).on('blur',function(){
                    // If we can, fire the event since we're leaving the field
                    doneTyping(el);
                });
            });
        }
    });
})(jQuery);

document.addEventListener('DOMContentLoaded', function () {
    var inputField = document.getElementById('search_product');
    var searchList = document.getElementById('search_list');

    // Show search list when clicking on input field
    inputField.addEventListener('click', function (event) {
        searchList.style.display = 'flex';
        event.stopPropagation(); // Ngăn sự kiện lan ra ngoài
    });

    // Hide search list when clicking outside
    document.addEventListener('click', function (event) {
        if (!inputField.contains(event.target) && !searchList.contains(event.target)) {
            searchList.style.display = 'none';
        }
    });
});

$(document).ready(function () {
    $('#search_list').on('scroll', function () {
        let scrollTop = $(this).scrollTop();
        let elementHeight = $(this).prop('scrollHeight');
        let viewHeight = $(this).innerHeight();

        $(document).ajaxSend(function () {
            $("#overlay1").fadeIn(300);
        })

        // Kiểm tra nếu thanh cuộn đã tới cuối phần tử
        if (scrollTop + viewHeight >= elementHeight) {

            $.ajax({
                type: 'GET',
                url: '/',
                data: {
                    load_more: 6
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    console.log('Đã cộn đến cuối')
                    let search_list = $(data).find('#search_list').html();
                    $('#search_list').html(search_list);
                }

            }).done(function () {
                setTimeout(function () {
                    $('#overlay1').fadeOut(300)
                }, 400)
            })
        }
    });
});

// $(document).ready(function() {
//     $('.banner_category--item').hover(
//         function() {
//             $(this).find('.banner_category--subitem--icon-left').css('display', 'flex');
//         },
//         function() {
//             $(this).find('.banner_category--subitem--icon-left').css('opacity', 1);
//         }
//     );
// });

$(document).ready(function () {
    $(document).on('click', '.category_parent--item', function (e) {
        e.preventDefault();
        let slug = $(this).data('slug')
        console.log(slug)
        $.ajax({
            type: 'GET',
            url: '/categories/' + slug,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                let category_child = $(data).find('#category_child').html();
                $('#category_child').html(category_child)
            }
        })
    })
})


// Khi tài liệu được tải hoàn tất
document.addEventListener('DOMContentLoaded', function() {
    var scrollToTopBtn = document.querySelector('#scrollToTopBtn');

    // Hiển thị nút khi người dùng cuộn xuống 300px
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 500) {
            scrollToTopBtn.style.display = 'block';
        } else {
            scrollToTopBtn.style.display = 'none';
        }
    });

    // Gán hàm scrollToTop cho sự kiện click của nút
    scrollToTopBtn.addEventListener('click', function () {
        window.scrollTo({
            top: 0,
            behavior: 'smooth' // Cuộn mượt
        });
    });
});

