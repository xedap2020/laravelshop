@extends('admin.layouts.app')

@section('content')
    <!-- ======================= Cards ================== -->
    <div style="width: 100%;">
        <div class="cardBox">
            <a href="/admin/users" class="card">
                <div class="flex_dash">
                    <div class="numbers">{{$count_users}}</div>
                    <div class="cardName">Users</div>
                </div>
                <div class="card_icon">
                    <i class="fa-solid fa-user"></i>
                </div>
            </a>

            {{-- <a href="/admin/categories" class="card">
                <div>
                    <div class="numbers">{{$count_categories}}</div>
                    <div class="cardName">Categories</div>
                </div>
                <div class="card_icon">
                    <i class="fa-solid fa-chart-simple"></i>
                </div>
            </a> --}}

            {{-- <a href="/admin/products" class="card">
                <div>
                    <div class="numbers">{{$count_products}}</div>
                    <div class="cardName">Products</div>
                </div>
                <div class="card_icon">
                    <i class="fa-brands fa-product-hunt"></i>
                </div>
            </a> --}}

            <a href="/admin/posts" class="card">
                <div>
                    <div class="numbers">{{$count_posts}}</div>
                    <div class="cardName">Posts</div>
                </div>
                <div class="card_icon">
                    <i class="fa-solid fa-file"></i>
                </div>
            </a>
        </div>
        {{-- <div class="order_analysis">
            <canvas id="revenue"></canvas>
            <canvas id="order_total"></canvas>
        </div> --}}
    </div>
@endsection

{{-- @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('revenue').getContext('2d');
        const chartDataBar  = @json($dataBar_Revenue);
        const year = @json($year);
        new Chart(ctx, {
            type: 'bar',
            data: chartDataBar,
            options: {
                indexAxis: 'x',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    // legend: {
                    //     position: 'top',
                    // },
                    title: {
                        display: true,
                        text: 'Biểu đồ doanh thu theo tháng / ' + year
                    }
                }
            }
        });

        const ctx1 = document.getElementById('order_total').getContext('2d');
        const chartDataDoughNut  = @json($dataDoughNut_OrderTotal);
        new Chart(ctx1, {
            type: 'doughnut',
            data: chartDataDoughNut,
            options: {
                // indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'Biểu đồ số lượng sản phẩm được bán theo danh mục'
                    }
                }
            }
        });
    </script>
@endpush --}}