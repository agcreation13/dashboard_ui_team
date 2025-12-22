{{-- Active, Standby, Completed, Unupdated --}}



<div class="row p-2">

                    <a href="{{route('siteStage.Status','active')}}" class="col-6 col-md-3 mb-3 pr-0">

                        <div class="card-box card_1 widget-style2 text-center">

                            {{-- <div class="d-flex flex-wrap align-items-center"> --}}

                                {{-- <div class="progress-data">

                                    <div id="chart"></div>

                                </div> --}}

                                <div class="widget-data">

                                    {{-- <div class="h3 mb-0">{{$SiteDetails->where('status', 'active')->count()}}</div> --}}

                                    {{-- <div class="h3 mb-0">{{$activeSites->count()}}</div>  --}}

                                    <div class="h3 mb-0">    {{ $SiteDetails->whereIn('status', ['active', 'unupdated'])->count() }}</div>

                                     <div class="weight-600 dashboard_tite font-15">Active Site</div>

                                    {{-- <div class="weight-600 dashboard_tite font-15">Updated/Active Site</div> --}}

                                {{-- </div> --}}

                            </div>

                        </div>

                    </a>

                   <a href="{{route('siteStage.Status','standby')}}" class="col-6 col-md-3 mb-3">

                         <div class="card-box card_2 widget-style2 text-center">

                           {{-- <div class="d-flex flex-wrap align-items-center">

                                {{-- <div class="progress-data">

                                    <div id="chart2"></div>

                                </div> --}}

                                <div class="widget-data">

                                    <div class="h3 mb-0">{{$SiteDetails->where('status', 'standby')->count()}}</div>

                                    <div class="weight-600 dashboard_tite font-15">Standby Site</div>

                                </div>

                            {{-- </div> --}}

                        </div>

                    </a>

                     <a href="{{route('siteStage.Status','completed')}}" class="col-6 col-md-3 mb-3 pr-0">

                         <div class="card-box card_3 widget-style2 text-center">

                          {{--  <div class="d-flex flex-wrap align-items-center">

                                 <div class="progress-data">

                                    <div id="chart4"></div>

                                </div> --}}

                                <div class="widget-data">

                                   <div class="h3 mb-0">{{$SiteDetails->where('status', 'completed')->count()}}</div>

                                    <div class="weight-600 dashboard_tite font-15">Completed Site</div>

                                </div>

                            {{-- </div> --}}

                        </div>

                    </a>

                       <a href="{{route('siteStage.Status','close')}}" class="col-6 col-md-3 mb-3">

                         <div class="card-box card_5 widget-style2 text-center">

                            {{-- <div class="d-flex flex-wrap align-items-center">

                                <div class="progress-data">

                                    <div id="chart3"></div>

                                </div> --}}

                                <div class="widget-data">

                                    <div class="h3 mb-0">{{$SiteDetails->where('status', 'close')->count()}}</div>

                                    <div class="weight-600 dashboard_tite font-15">Close Site</div>

                                </div>

                            {{-- </div> --}}

                        </div>

                    </a>

                      <a href="{{route('siteStage.Status','unupdated')}}" class="col-6 col-md-3 mb-3 pr-0">

                         <div class="card-box card_4 widget-style2 text-center">

                                <div class="widget-data">

                                    <div class="h3 mb-0">{{$SiteDetails->where('status', 'unupdated')->count()}}</div>

                                    <div class="weight-600 dashboard_tite font-15">Unupdated Site</div>

                                </div>

                        </div>

                    </a>

                </div>

        

            <style>

                .card_1{

                    background-color: #8bf8ae75;

                }

                .card_2{

                    background-color: #7dccf18f;

                }

                .card_3{

                    background-color: #97f893;

                }

                .card_4{

                    background-color: #ffc1078a;

                }

                .card_5{

                    background-color: #fbc0bc;

                }

                .card_6{

                    background-color: #a7eee7;

                }

            </style>