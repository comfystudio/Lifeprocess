@extends($theme)
@section('title', $title)
<style type="text/css">
    div.panel-body > div.proof{
        padding-bottom:10px;
    }
    hr {
        margin: 0 !important;
    }
</style>
@section('content')
<div class="tab-content">
    <div class="tab-pane fade in active">
        <div class="col-md-12">
        
            {!! Form::model($client, ['method' => 'POST','route' => ['clients.store.profile', $client['id']],'class' => 'form-horizontal','files'=>'true']) !!}
            
                <div class="tab-title">
                        <h1 class="no-margin">User Profile</h1>
                </div>
                <div class="client-msg">
                    
                    
                    @include('clients.dashboard.profile_form')
                    
                </div>
                
            
            {!! Form::close() !!}
        </div>
    </div>
</div>
@push('scripts')

<script type="text/javascript">
    
    function readUserURL(input){
        $.imageChanger(input,"staff");
    } 
    $(document).ready(function() {

        $('#date_of_birth').datetimepicker({
            timepicker:false,
            format:'d/m/Y'
            
        });
    });   
</script>
{!! ajax_fill_dropdown('country_id','state_id',route('ajax.allstate')) !!}
@endpush
{{-- Popup File --}}
@include('users.popup')
@endsection
