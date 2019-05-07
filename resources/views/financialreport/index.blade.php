@extends($theme)
@section('title', $title)
<style>
/* Table classes */
.table > tbody  > tr.info >  th
{
	background-color: #E7F3FC !important ;
	text-align: left;
}
.table > tbody  > tr >  th
{
	font-size: 14px;

}
.table > tbody > tr > td
{
	font-size: 11px;
}
.table-bordered > tbody > tr > td, .table-bordered > tbody > tr > td
{
    padding: 5px;
    font-size: 13px;
    border: 1px solid #ddd;
    text-align: center;
}
</style>
@section('content')
<div class="content-wrapper">
@include('financialreport.financialSearch')
@include('financialreport.financialList')
</div>
@endsection