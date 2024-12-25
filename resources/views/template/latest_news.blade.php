@extends('layouts.template')

<main>
    @section('content')
        <!--ARTIKELS-->
    <section class="whats-news-area pt-50 pb-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row d-flex justify-content-between">
                        <div class="col-lg-3 col-md-3">
                            <div class="section-tittle mb-30">
                                <h3>ARTIKELS</h3>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-9">
                            <div class="properties__button"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <!-- Nav Card -->
                            <div class="tab-content" id="nav-tabContent">
                                <!-- card one -->
                                <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="whats-news-caption">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="single-what-news mb-100">
                                                    <div class="what-img">
                                                        <img src="assets/img/news/whatNews1.jpg" alt="">
                                                    </div>
                                                    <div class="what-cap">
                                                        <span class="color1">GEMPA TEKTONIK</span>
                                                        <h4><a href="#">gempa bumi yang disebabkan oleh pergerakan lempeng tektonik di bawah permukaan bumi</a></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="single-what-news mb-100">
                                                    <div class="what-img">
                                                        <img src="assets/img/news/whatNews2.jpg" alt="">
                                                    </div>
                                                    <div class="what-cap">
                                                        <span class="color1">SESAR</span>
                                                        <h4><a href="#">patahan atau retakan pada lapisan bumi yang mengalami pergerakan relatif antara dua blok batuan</a></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="single-what-news mb-100">
                                                    <div class="what-img">
                                                        <img src="assets/img/news/whatNews2.jpg" alt="">
                                                    </div>
                                                    <div class="what-cap">
                                                        <span class="color1">SESAR</span>
                                                        <h4><a href="#">patahan atau retakan pada lapisan bumi yang mengalami pergerakan relatif antara dua blok batuan</a></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="single-what-news mb-100">
                                                    <div class="what-img">
                                                        <img src="assets/img/news/whatNews2.jpg" alt="">
                                                    </div>
                                                    <div class="what-cap">
                                                        <span class="color1">SESAR</span>
                                                        <h4><a href="#">patahan atau retakan pada lapisan bumi yang mengalami pergerakan relatif antara dua blok batuan</a></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
    @endsection
</main>
