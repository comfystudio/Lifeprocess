@extends($theme)
@section('content')
<div class="inner-box">
    <h1>
        {{ $title }}
    </h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>
                        ID.
                    </th>
                    <th>
                        Lang
                    </th>
                    <th>
                        Country Id
                    </th>
                    <th>
                        State
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        {{ $state->id }}
                    </td>
                    <td>
                        {{ $state->lang }}
                    </td>
                    <td>
                        {{ $state->country_id }}
                    </td>
                    <td>
                        {{ $state->state }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
