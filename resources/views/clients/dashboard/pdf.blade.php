 @extends($theme)
 @section('title',$title)
 @section('content')
<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12" style="height:680px;padding:5px; text-align:center; border: 10px solid #0062A8">
                    <div class="col-md-12" style="height:650px;padding:20px; border: 5px solid #157cd2">
                        <div class="logo-middle">
                            <img src="{{ asset("images/logo-lpap.png") }}" alt="Life Process Alcohol Program" style="background-color:black;">
                        </div>
                        <span style="font-size:50px; font-weight:bold;color: #e06f00;font-style: italic;">Certificate of Completion</span>
                        <br><br>
                        <span style="font-size:25px"><i>This is to certify that</i></span>
                        <br><br>
                        <span style="font-size:30px;font-style: italic;border-bottom:2px solid"><b>{{$client_name}}</b></span><br/><br/>
                        <span style="font-size:25px"><i>has completed the course</i></span> <br/><br/>
                        <span style="font-size:30px">{{$program_name}}</span> <br/><br/>
                        <span style="font-size:20px">with score of <b>10</b></span><br/><br/>
                        <div class="col-md-3 pull-left">
                                    <h6>Authorized person</h6>
                                    <div class="mb-15 mt-15">
                                        <img src="{{ asset("images/signature.png") }}" class="display-block" style="width: 150px;" alt="">
                                    </div>
                                    <ul class="list-condensed list-unstyled text-muted">
                                        <li>{{$coach_name}}</li>
                                    </ul>
                        </div>
                        <div class="col-md-3 pull-right">
                            <h6>Completed At</h6>
                            <ul class="list-condensed list-unstyled text-muted">
                                        <li>{{$review_date}}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 @endsection