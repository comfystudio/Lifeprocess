@extends($theme)
@section('title',$title)
@section('content')
<div class="content-wrapper">
    <div class="panel panel-white">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12" style="padding:5px; text-align:center; border: 10px solid #0062A8">
                    <div class="col-md-12" style="padding:20px; border: 5px solid #157cd2">
                        <div class="logo-middle">
                            <img src="{{ asset("images/logo-lpap.png") }}" alt="Life Process Alcohol Program" style="background-color:black;">
                        </div>
                        <span style="font-size:50px; font-weight:bold;color: #e06f00;font-style: italic;">Certificate of Completion</span>
                        <br><br>
                        <span style="font-size:25px"><i>This is to certify that</i></span>
                        <br><br>
                        <span style="font-size:30px;font-style: italic;border-bottom:2px solid"><b>Nishant Kotak</b></span><br/><br/>
                        <span style="font-size:25px"><i>has completed the course</i></span> <br/><br/>
                        <span style="font-size:30px">Gambling program</span> <br/><br/>
                        <span style="font-size:20px">with score of <b>10</b></span><br/><br/>
                        <div class="col-md-2">
                                    <h6>Authorized person</h6>
                                    <div class="mb-15 mt-15">
                                        <img src="{{ asset("images/signature.png") }}" class="display-block" style="width: 150px;" alt="">
                                    </div>
                                    <ul class="list-condensed list-unstyled text-muted">
                                        <li>Dr. Dinesh Rabara</li>
                                        <li>M.D</li>
                                    </ul>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-white">
        <div class="panel-body">
            <div class="col-md-2">

                        <!-- /calendar widget -->
            </div>
        </div>
    </div>
</div>
@endsection