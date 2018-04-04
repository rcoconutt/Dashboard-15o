@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="mt-lg-5">
            <div class="row">
                <div class="col-xl-6 col-md-4">
                    <div class="card card-cascade cascading-admin-card">
                        <div class="admin-up">
                            <i class="fa fa-money primary-color"></i>
                            <div class="data">
                                <p>Bartender con más ventas</p>
                                <h4 class="font-weight-bold dark-grey-text">2000$</h4>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="progress">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <!--Text-->
                            <p class="card-text">Better than last week (25%)</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-4">
                    <div class="card card-cascade cascading-admin-card">
                        <div class="admin-up">
                            <i class="fa fa-line-chart warning-color"></i>
                            <div class="data">
                                <p>Centro de consumo</p>
                                <h4 class="font-weight-bold dark-grey-text">200</h4>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="progress">
                                <div class="progress-bar red accent-2" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="card-text">Worse than last week (25%)</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div style="height: 5px"></div>

        <section class="mb-5">
            <div class="card card-cascade narrower">
                <section>

                    <!--Grid row-->
                    <div class="row">

                        <!--Grid column-->
                        <div class="col-xl-5 col-lg-12 mr-0">

                            <!--Card image-->
                            <div class="view gradient-card-header light-blue lighten-1">
                                <h2 class="h2-responsive mb-0">Sales</h2>
                            </div>
                            <!--/Card image-->

                            <!--Card content-->
                            <div class="card-body pb-0">

                                <!--Panel data-->
                                <div class="row card-body pt-3">

                                    <!--First column-->
                                    <div class="col-md-6">

                                        <!--Date select-->
                                        <p class="lead"><span class="badge info-color p-2">Data range</span></p>
                                        <select class="mdb-select colorful-select dropdown-info">
                                            <option value="" disabled selected>Choose time period</option>
                                            <option value="1">Today</option>
                                            <option value="2">Yesterday</option>
                                            <option value="3">Last 7 days</option>
                                            <option value="3">Last 30 days</option>
                                            <option value="3">Last week</option>
                                            <option value="3">Last month</option>
                                        </select>

                                        <!--Date pickers-->
                                        <p class="lead mt-5"><span class="badge info-color p-2">Custom date</span></p>
                                        <br>
                                        <div class="md-form">
                                            <input placeholder="Selected date" type="text" id="from" class="form-control datepicker">
                                            <label for="date-picker-example">From</label>
                                        </div>
                                        <div class="md-form">
                                            <input placeholder="Selected date" type="text" id="to" class="form-control datepicker">
                                            <label for="date-picker-example">To</label>
                                        </div>

                                    </div>
                                    <!--/First column-->

                                    <!--Second column-->
                                    <div class="col-md-6 text-center">

                                        <!--Summary-->
                                        <p>Total sales: <strong>2000$</strong>
                                            <button type="button" class="btn btn-info btn-sm p-2" data-toggle="tooltip" data-placement="top" title="Total sales in the given period"><i class="fa fa-question"></i></button>
                                        </p>
                                        <p>Average sales: <strong>100$</strong>
                                            <button type="button" class="btn btn-info btn-sm p-2" data-toggle="tooltip" data-placement="top" title="Average daily sales in the given period"><i class="fa fa-question"></i></button>
                                        </p>

                                        <!--Change chart-->
                                        <span class="min-chart my-4" id="chart-sales" data-percent="76"><span class="percent"></span></span>
                                        <h5>
                                            <span class="badge red accent-2 p-2">Change <i class="fa fa-arrow-circle-up ml-1"></i></span>
                                            <button type="button" class="btn btn-info btn-sm p-2" data-toggle="tooltip" data-placement="top" title="Percentage change compared to the same period in the past"><i class="fa fa-question"></i>
                                            </button>
                                        </h5>
                                    </div>
                                    <!--/Second column-->

                                </div>
                                <!--/Panel data-->

                            </div>
                            <!--/.Card content-->

                        </div>
                        <!--Grid column-->

                        <!--Grid column-->
                        <div class="col-xl-7 col-lg-12 mb-4">

                            <!--Card image-->
                            <div class="view gradient-card-header blue-gradient">

                                <!-- Chart -->
                                <canvas id="lineChart" height="175"></canvas>

                            </div>
                            <!--/Card image-->

                        </div>
                        <!--Grid column-->

                    </div>
                    <!--Grid row-->

                </section>
    </div>
@endsection