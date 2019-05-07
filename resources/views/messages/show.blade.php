@extends($theme)
@section('content')
<div class="inner-box">
    <h1>{{ $title }}</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Lang</th><th>Message</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $message->id }}</td> <td> {{ $message->lang }} </td><td> {{ $message->country }} </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection