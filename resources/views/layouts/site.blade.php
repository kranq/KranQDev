@include('layouts.site_header')

<body id="page1">
    <!--==============================header=================================-->
    <header>
        @include('layouts.site_menu')
        @if ((Request::segment(1) != "services") && (Request::segment(1) != "contact"))
        <div class="row-bot">
            <div class="slider zerogrid">
                <div class="rslides_container">
                    <ul class="rslides" id="slider">
                        <li><img src="{{ URL::to('/')}}/site/images/slider-1.jpg" alt="" /></li>
                        <li><img src="{{ URL::to('/')}}/site/images/slider-2.jpg" alt="" /></li>
                        <li><img src="{{ URL::to('/')}}/site/images/slider-3.jpg" alt="" /></li>
                        <li><img src="{{ URL::to('/')}}/site/images/slider-4.jpg" alt="" /></li>
                    </ul>
                </div>
            </div>
        </div>
        @endif
    </header>
    <!--==============================content================================-->
    @yield('content')
    <!--==============================footer=================================-->
    @include('layouts.site_footer')
    <?php //include_once 'footer.php';?>
    @yield('page_js')
    <script type="text/javascript"> Cufon.now();</script>
    <script type="text/javascript">
        $(window).load(function () {
            $('.slider')._TMS({
                duration: 1000,
                easing: 'easeOutQuint',
                preset: 'diagonalFade',
                slideshow: 7000,
                banners: false,
                pauseOnHover: true,
                pagination: true,
                pagNums: false
            });
        });
    </script>
</body>
</html>
