@extends($theme)
@section('title', $title)
@section('content')
@include('referfriend_report.referfriendSearch')
@include('referfriend_report.referfriendList')
@endsection