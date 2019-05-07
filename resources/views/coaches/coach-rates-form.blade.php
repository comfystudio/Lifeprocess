
<style type="text/css">
    .table-lg>tbody>tr>td {
        padding: 10px 20px;
    }
    .table > tbody > tr > td {
        vertical-align: middle;
    }
</style>
<div class="table-responsive">
    <table class="table table-bordered table-lg">
        <tbody>
            @if(isset($coach_programs) && count($coach_programs) > 0)
                @php
                    $program_counter = 1;
                @endphp
                @foreach($coach_programs as $coach_prg)
                <tr class="border-double">
                        <th colspan="2"> Program - {{ $program_counter++ }}: {{ $coach_prg->coach_program_detail->program_name }} </th>
                </tr>
                @php
                $programs = $coach_prg->coach_program_detail->program_name;
                @endphp
                @if(count($coach_prg->coach_program_detail->modules) > 0)
                    <tr>
                            <th>Module</td>
                            <th>Rate</td>
                        </tr>

                 @foreach($coach_prg->coach_program_detail->modules as $module)
                        <tr>
                                <td class="col-md-5 left-padding-20">
                                    Module - {{ $module->module_no }}: {{ $module->module_title }}
                                </td>
                                <td class="col-sm-3 {{ $errors->first('module.'.$coach_prg->coach_program_detail->id.'.'.$module->id, 'has-error') }}" >
                                    {!! Form::text('module['. $coach_prg->coach_program_detail->id .']['. $module->id .']', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('module.'.$coach_prg->coach_program_detail->id.'.'.$module->id, '<span class="help-block">:message</span>') !!}
                                    {{-- @if (isset($module_rates['module_id'][$program->id][$module->id]))
                                        {!! Form::hidden('module_id['.$program->id.']['.$module->id.']', $module_rates['module_id'][$program->id][$module->id]) !!}
                                    @endif --}}
                                </td>
                            </tr>

                        @endforeach
                    @endif
                @endforeach
            @endif
        </tbody>
    </table>
</div>