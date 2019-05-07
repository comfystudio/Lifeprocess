@extends($theme)
@section('title', $title)

<style type="text/css">
    .send{
        background-color: #82cd49;
        border: 0 none;
        border-radius: 0;
        color: #fff;
        padding: 10px 18px;
    }
    .cancel{
        padding: 10px 18px;
        border: 0 none;
        border-radius: 0;
    }
    .set-buttons{
        margin: 20px 0 0 0;
    }

</style>

@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">

        <div class="panel-heading">
            <h5 class="panel-title">{{ trans('comman.admin_coach_add_manual_transaction') }}</h5>
        </div>

        <div class="panel-body">

            <table class="table table-hover no-footer" style="margin: 0 0 30px 0;">
               <thead>
                    <tr>
                        <th style="border-bottom: 0px;padding: 18px 0 0 0;font-size: 14px;"> Client:{{ $coach_name }} </th>
                    </tr>
                </thead>
            </table>


            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {!! Form::model(['method' => 'POST','route' => ['add.client.manual.transaction'],'class' => 'form-horizontal']) !!}
                <table class="table table-bordered table-hover no-footer">

                    <tbody>
                        <tr>
                            <td style="width: 30%;"> Credit Balance($) </td>
                            <td> {!! Form::text('credit_balance','',['class' => 'form-control']) !!}</td>
                        </tr>
                        <tr>
                            <td> Debit Balance($) </td>
                            <td> {!! Form::text('debit_balance','',['class' => 'form-control']) !!} </td>
                        </tr>
                        <tr>
                            <td> Notes </td>
                            <td colspan="3"> {!! Form::textarea('transaction_detail','',['class' => 'form-control','rows'=>'4']) !!} </td>
                        </tr>
                    </tbody>

                </table>

                <div class="col-md-12 set-buttons">
                    <div style="float: right">
                        <input name="save" class="send" type="submit" value="Save">
                        <input name="save_exit" class="send" type="submit" value="Save &amp; Exit">
                        <a href="" class="btn btn-warning cancel" style="padding: 10px 18px 11px 20px;border: 0 none;border-radius: 0;">Cancel</a>
                    </div>
                </div>

            {!! Form::close() !!}

        </div>
    </div>
</div>
@endsection