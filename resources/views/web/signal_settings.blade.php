@extends('layouts.app')
@section('title')
    Редактирование сигналов ОЖД
@endsection
@section('side_menu')
    @include('include.side_menu')
@endsection
@section('content')
    @push('scripts')
        <script src="{{asset('assets/js/moment-with-locales.min.js')}}"></script>
        <script src="{{asset('assets/libs/changeable_td.js')}}"></script>
        <script src="{{asset('assets/libs/tooltip/popper.min.js')}}"></script>
        <script src="{{asset('assets/libs/tooltip/tippy-bundle.umd.min.js')}}"></script>

        <script src="{{asset('assets/libs/apexcharts.js')}}"></script>
        <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>

    @endpush

    @push('styles')
        <link rel="stylesheet" href="{{asset('assets/css/table.css')}}">
        <link rel="stylesheet" href="{{asset('assets/libs/tooltip/tooltip.css')}}">
    @endpush
    <div style="width: 99%">
        <table style="width: 100%; table-layout: fixed">
            <colgroup>
                <col style="width: 80%">
                <col style="width: 20%">
            </colgroup>
            <tbody>
                <tr>
                    <td>
                        <div id="content-header" style="display:inline-block;width: 92%"></div>
                    </td>
                    <td>
                        <div id="search_div" style="width: 100%; height: 100%; margin-bottom: 10px; float: right">
                            <input class="text-field__input" type="text" id="search_row" style="width: 100%" placeholder="Поиск...">
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>


    <div id="tableDiv" style="overflow-y: auto; height: calc(100% - 75px)">

        <table id="itemInfoTable" class="itemInfoTable" style="width: 100%; display: table; white-space: pre-line; float: left">
            <colgroup>
                <col style="width: 25%">
                <col style="width: 5%">
                <col style="width: 5%">
                <col style="width: 5%">
                <col style="width: 5%">
                <col style="width: 25%">
                <col style="width: 7%">
                <col style="width: 7%">
{{--                <col style="width: 7%">--}}
{{--		<col style="width: 7%">--}}
{{--                <col style="width: 5%">--}}
{{--                <col style="width: 5%">--}}
{{--                <col style="width: 5%">--}}
{{--                <col style="width: 18%">--}}
            </colgroup>
            <thead style="position: sticky; top: 0; z-index: 2">
            <tr>
                <th rowspan="2" style="text-align: center; padding: 0px"><h4>Наименование</h4></th>
                <th rowspan="2" style="text-align: center; padding: 0px"><h4>Ед.изм</h4></th>
                <th colspan="3"  style="text-align: center; padding: 0px; z-index:777"><h4>М АСДУ ЕСГ</h4></th>
{{--                <th colspan="2"  style="text-align: center; padding: 0px; z-index:777"><h4>ИУС ПД</h4></th>--}}
{{--                <th colspan="2"  style="text-align: center; padding: 0px; z-index:777"><h4>ГТЮ</h4></th>--}}
                <th rowspan="2" style="text-align: center; padding: 0px"><h4>Имя тега</h4></th>
                <th  colspan="2"  style="text-align: center; padding: 0px; z-index:777"><h4>Отображение в ОЖД</h4></th>
            </tr>
            <tr style="position:sticky;">
                <th  style="text-align: center; padding: 0px; position:sticky; top:32px; z-index:777" ><h4>РВ</h4></th>
                <th  style="text-align: center; padding: 0px; position:sticky; top:32px; z-index:777" ><h4>2 часа</h4></th>
                <th  style="text-align: center; padding: 0px; position:sticky; top:32px; z-index:777" ><h4>Сутки</h4></th>
{{--		<th  style="text-align: center; padding: 0px; position:sticky; top:32px; z-index:777" ><h4>Месяц</h4></th>--}}
{{--                <th  style="text-align: center; padding: 0px; position:sticky; top:32px; z-index:777" ><h4>2 часа</h4></th>--}}
{{--                <th  style="text-align: center; padding: 0px; position:sticky; top:32px; z-index:777" ><h4>Сутки</h4></th>--}}
{{--                <th  style="text-align: center; padding: 0px; position:sticky; top:32px; z-index:777" ><h4>2 часа</h4></th>--}}
{{--                <th  style="text-align: center; padding: 0px; position:sticky; top:32px; z-index:777" ><h4>Сутки</h4></th>--}}
{{--                <th  style="text-align: center; padding: 0px; position:sticky; top:32px; z-index:777" ><h4>РВ</h4></th>--}}
                <th  style="text-align: center; padding: 0px; position:sticky; top:32px; z-index:777" ><h4>Час</h4></th>
                <th style="text-align: center; padding: 0px; position:sticky; top:32px; z-index:777"  ><h4>Сутки</h4></th>
            </tr>
            </thead>
            <tbody id="tbody_for_search">
            @for($i=0; $i<count($data); $i++)
                @if($data[$i]['tag_name'])
                    <tr data-id="{{$data[$i]['hfrpok']}}">
                @else
                    <tr style="background-color: red" data-id="{{$data[$i]['hfrpok']}}">
                @endif

                    <td class="row" style="text-align: center; padding: 0px; margin: 0px"><textarea onchange="save_tr(this.parentNode.parentNode)" class="namepar1">{{$data[$i]['namepar1']}}</textarea></td>
                    <td class="row" style="text-align: center; padding: 0px; margin: 0px"><input style="width: 70%" onchange="save_tr(this.parentNode.parentNode)" class="shortname" type="text" value="{{$data[$i]['shortname']}}"></td>

                    <td class="row" style="text-align: center; padding: 0px; margin: 0px">@if ($data[$i]['guid_masdu_5min'])<label class="switch" style="padding: 0px; margin: 0px"><input class="guid_masdu_5min_{{$data[$i]['hfrpok']}}" onclick="click_on_slider(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_5min')" type="checkbox" checked><span class="slider"></span></label><img id="edit_guid_masdu_5min_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/edit.svg" style="width: 20px; margin-left: 20px" class="guid_masdu_5min_{{$data[$i]['hfrpok']}}" onclick="edit_guid(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_5min')"/><img id="save_guid_masdu_5min_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/arrow_bottom.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_masdu_5min_{{$data[$i]['hfrpok']}}" onclick="save_guid(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_5min')"/>@else<label class="switch" style="padding: 0px; margin: 0px"><input class="guid_masdu_5min_{{$data[$i]['hfrpok']}}" onclick="click_on_slider(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_5min')" type="checkbox"><span class="slider"></span></label><img id="edit_guid_masdu_5min_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/edit.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_masdu_5min_{{$data[$i]['hfrpok']}}" onclick="edit_guid(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_5min')"/><img id="save_guid_masdu_5min_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/arrow_bottom.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_masdu_5min_{{$data[$i]['hfrpok']}}" onclick="save_guid(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_5min')"/>@endif<textarea style="display: none" id="guid_masdu_5min_{{$data[$i]['hfrpok']}}">{{$data[$i]['guid_masdu_5min']}}</textarea></td>
                    <td class="row" style="text-align: center; padding: 0px; margin: 0px">@if ($data[$i]['guid_masdu_hours'])<label class="switch" style="padding: 0px; margin: 0px"><input class="guid_masdu_hours_{{$data[$i]['hfrpok']}}" onclick="click_on_slider(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_hours')" type="checkbox" checked><span class="slider"></span></label><img id="edit_guid_masdu_hours_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/edit.svg" style="width: 20px; margin-left: 20px" class="guid_masdu_hours{{$data[$i]['hfrpok']}}" onclick="edit_guid(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_hours')"/><img id="save_guid_masdu_hours_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/arrow_bottom.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_masdu_hours{{$data[$i]['hfrpok']}}" onclick="save_guid(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_hours')"/>@else<label class="switch" style="padding: 0px; margin: 0px"><input class="guid_masdu_hours_{{$data[$i]['hfrpok']}}" onclick="click_on_slider(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_hours')" type="checkbox"><span class="slider"></span></label><img id="edit_guid_masdu_hours_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/edit.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_masdu_5min_{{$data[$i]['hfrpok']}}" onclick="edit_guid(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_hours')"/><img id="save_guid_masdu_hours_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/arrow_bottom.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_masdu_5min_{{$data[$i]['hfrpok']}}" onclick="save_guid(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_hours')"/>@endif<textarea style="display: none" id="guid_masdu_hours_{{$data[$i]['hfrpok']}}">{{$data[$i]['guid_masdu_hours']}}</textarea></td>
                    <td class="row" style="text-align: center; padding: 0px; margin: 0px">@if ($data[$i]['guid_masdu_day'])<label class="switch" style="padding: 0px; margin: 0px"><input class="guid_masdu_day_{{$data[$i]['hfrpok']}}" onclick="click_on_slider(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_day')" type="checkbox" checked><span class="slider"></span></label><img id="edit_guid_masdu_day_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/edit.svg" style="width: 20px; margin-left: 20px" class="guid_masdu_day_{{$data[$i]['hfrpok']}}" onclick="edit_guid(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_day')"/><img id="save_guid_masdu_day_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/arrow_bottom.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_masdu_day_{{$data[$i]['hfrpok']}}" onclick="save_guid(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_day')"/>@else<label class="switch" style="padding: 0px; margin: 0px"><input class="guid_masdu_day_{{$data[$i]['hfrpok']}}" onclick="click_on_slider(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_day')" type="checkbox"><span class="slider"></span></label><img id="edit_guid_masdu_day_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/edit.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_masdu_day_{{$data[$i]['hfrpok']}}" onclick="edit_guid(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_day')"/><img id="save_guid_masdu_day_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/arrow_bottom.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_masdu_day_{{$data[$i]['hfrpok']}}" onclick="save_guid(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_day')"/>@endif<textarea style="display: none" id="guid_masdu_day_{{$data[$i]['hfrpok']}}">{{$data[$i]['guid_masdu_day']}}</textarea></td>
{{--                      <td class="row" style="text-align: center; padding: 0px; margin: 0px">@if ($data[$i]['guid_masdu_sut'])<label class="switch" style="padding: 0px; margin: 0px"><input class="guid_masdu_sut_{{$data[$i]['hfrpok']}}" onclick="click_on_slider(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_sut')" type="checkbox" checked><span class="slider"></span></label><img id="edit_guid_masdu_sut_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/edit.svg" style="width: 20px; margin-left: 20px" class="guid_masdu_sut_{{$data[$i]['hfrpok']}}" onclick="edit_guid(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_sut')"/><img id="save_guid_masdu_sut_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/arrow_bottom.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_masdu_sut_{{$data[$i]['hfrpok']}}" onclick="save_guid(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_sut')"/>@else<label class="switch" style="padding: 0px; margin: 0px"><input class="guid_masdu_sut_{{$data[$i]['hfrpok']}}" onclick="click_on_slider(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_sut')" type="checkbox"><span class="slider"></span></label><img id="edit_guid_masdu_sut_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/edit.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_masdu_sut_{{$data[$i]['hfrpok']}}" onclick="edit_guid(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_sut')"/><img id="save_guid_masdu_sut_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/arrow_bottom.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_masdu_sut_{{$data[$i]['hfrpok']}}" onclick="save_guid(this, {{$data[$i]['hfrpok']}}, 'guid_masdu_sut')"/>@endif<textarea style="display: none" id="guid_masdu_sut_{{$data[$i]['hfrpok']}}">{{$data[$i]['guid_masdu_sut']}}</textarea></td>--}}
{{--                    <td class="row" style="text-align: center; padding: 0px; margin: 0px">@if ($data[$i]['guid_ius_hours'])<label class="switch" style="padding: 0px; margin: 0px"><input class="guid_ius_hours_{{$data[$i]['hfrpok']}}" onclick="click_on_slider(this, {{$data[$i]['hfrpok']}}, 'guid_ius_hours')" type="checkbox" checked><span class="slider"></span></label><img id="edit_guid_ius_hours_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/edit.svg" style="width: 20px; margin-left: 20px" class="guid_ius_hours{{$data[$i]['hfrpok']}}" onclick="edit_guid(this, {{$data[$i]['hfrpok']}}, 'guid_ius_hours')"/><img id="save_guid_ius_hours_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/arrow_bottom.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_ius_hours{{$data[$i]['hfrpok']}}" onclick="save_guid(this, {{$data[$i]['hfrpok']}}, 'guid_ius_hours')"/>@else<label class="switch" style="padding: 0px; margin: 0px"><input class="guid_ius_hours_{{$data[$i]['hfrpok']}}" onclick="click_on_slider(this, {{$data[$i]['hfrpok']}}, 'guid_ius_hours')" type="checkbox"><span class="slider"></span></label><img id="edit_guid_ius_hours_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/edit.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_ius_5min_{{$data[$i]['hfrpok']}}" onclick="edit_guid(this, {{$data[$i]['hfrpok']}}, 'guid_ius_hours')"/><img id="save_guid_ius_hours_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/arrow_bottom.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_ius_5min_{{$data[$i]['hfrpok']}}" onclick="save_guid(this, {{$data[$i]['hfrpok']}}, 'guid_ius_hours')"/>@endif<textarea style="display: none" id="guid_ius_hours_{{$data[$i]['hfrpok']}}">{{$data[$i]['guid_ius_hours']}}</textarea></td>--}}
{{--                    <td class="row" style="text-align: center; padding: 0px; margin: 0px">@if ($data[$i]['guid_ius_day'])<label class="switch" style="padding: 0px; margin: 0px"><input class="guid_ius_day_{{$data[$i]['hfrpok']}}" onclick="click_on_slider(this, {{$data[$i]['hfrpok']}}, 'guid_ius_day')" type="checkbox" checked><span class="slider"></span></label><img id="edit_guid_ius_day_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/edit.svg" style="width: 20px; margin-left: 20px" class="guid_ius_day_{{$data[$i]['hfrpok']}}" onclick="edit_guid(this, {{$data[$i]['hfrpok']}}, 'guid_ius_day')"/><img id="save_guid_ius_day_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/arrow_bottom.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_ius_day_{{$data[$i]['hfrpok']}}" onclick="save_guid(this, {{$data[$i]['hfrpok']}}, 'guid_ius_day')"/>@else<label class="switch" style="padding: 0px; margin: 0px"><input class="guid_ius_day_{{$data[$i]['hfrpok']}}" onclick="click_on_slider(this, {{$data[$i]['hfrpok']}}, 'guid_ius_day')" type="checkbox"><span class="slider"></span></label><img id="edit_guid_ius_day_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/edit.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_ius_day_{{$data[$i]['hfrpok']}}" onclick="edit_guid(this, {{$data[$i]['hfrpok']}}, 'guid_ius_day')"/><img id="save_guid_ius_day_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/arrow_bottom.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_ius_day_{{$data[$i]['hfrpok']}}" onclick="save_guid(this, {{$data[$i]['hfrpok']}}, 'guid_ius_day')"/>@endif<textarea style="display: none" id="guid_ius_day_{{$data[$i]['hfrpok']}}">{{$data[$i]['guid_ius_day']}}</textarea></td>--}}
{{--                    <td class="row" style="text-align: center; padding: 0px; margin: 0px">@if ($data[$i]['guid_transgaz_hours'])<label class="switch" style="padding: 0px; margin: 0px"><input class="guid_transgaz_hours_{{$data[$i]['hfrpok']}}" onclick="click_on_slider(this, {{$data[$i]['hfrpok']}}, 'guid_transgaz_hours')" type="checkbox" checked><span class="slider"></span></label><img id="edit_guid_transgaz_hours_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/edit.svg" style="width: 20px; margin-left: 20px" class="guid_transgaz_hours{{$data[$i]['hfrpok']}}" onclick="edit_guid(this, {{$data[$i]['hfrpok']}}, 'guid_transgaz_hours')"/><img id="save_guid_transgaz_hours_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/arrow_bottom.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_transgaz_hours{{$data[$i]['hfrpok']}}" onclick="save_guid(this, {{$data[$i]['hfrpok']}}, 'guid_transgaz_hours')"/>@else<label class="switch" style="padding: 0px; margin: 0px"><input class="guid_transgaz_hours_{{$data[$i]['hfrpok']}}" onclick="click_on_slider(this, {{$data[$i]['hfrpok']}}, 'guid_transgaz_hours')" type="checkbox"><span class="slider"></span></label><img id="edit_guid_transgaz_hours_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/edit.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_transgaz_5min_{{$data[$i]['hfrpok']}}" onclick="edit_guid(this, {{$data[$i]['hfrpok']}}, 'guid_transgaz_hours')"/><img id="save_guid_transgaz_hours_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/arrow_bottom.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_transgaz_5min_{{$data[$i]['hfrpok']}}" onclick="save_guid(this, {{$data[$i]['hfrpok']}}, 'guid_transgaz_hours')"/>@endif<textarea style="display: none" id="guid_transgaz_hours_{{$data[$i]['hfrpok']}}">{{$data[$i]['guid_transgaz_hours']}}</textarea></td>--}}
{{--                    <td class="row" style="text-align: center; padding: 0px; margin: 0px">@if ($data[$i]['guid_transgaz_day'])<label class="switch" style="padding: 0px; margin: 0px"><input class="guid_transgaz_day_{{$data[$i]['hfrpok']}}" onclick="click_on_slider(this, {{$data[$i]['hfrpok']}}, 'guid_transgaz_day')" type="checkbox" checked><span class="slider"></span></label><img id="edit_guid_transgaz_day_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/edit.svg" style="width: 20px; margin-left: 20px" class="guid_transgaz_day_{{$data[$i]['hfrpok']}}" onclick="edit_guid(this, {{$data[$i]['hfrpok']}}, 'guid_transgaz_day')"/><img id="save_guid_transgaz_day_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/arrow_bottom.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_transgaz_day_{{$data[$i]['hfrpok']}}" onclick="save_guid(this, {{$data[$i]['hfrpok']}}, 'guid_transgaz_day')"/>@else<label class="switch" style="padding: 0px; margin: 0px"><input class="guid_transgaz_day_{{$data[$i]['hfrpok']}}" onclick="click_on_slider(this, {{$data[$i]['hfrpok']}}, 'guid_transgaz_day')" type="checkbox"><span class="slider"></span></label><img id="edit_guid_transgaz_day_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/edit.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_transgaz_day_{{$data[$i]['hfrpok']}}" onclick="edit_guid(this, {{$data[$i]['hfrpok']}}, 'guid_transgaz_day')"/><img id="save_guid_transgaz_day_{{$data[$i]['hfrpok']}}" alt="" src="assets/images/icons/arrow_bottom.svg" style="width: 20px; margin-left: 20px; display: none" class="guid_transgaz_day_{{$data[$i]['hfrpok']}}" onclick="save_guid(this, {{$data[$i]['hfrpok']}}, 'guid_transgaz_day')"/>@endif<textarea style="display: none" id="guid_transgaz_day_{{$data[$i]['hfrpok']}}">{{$data[$i]['guid_transgaz_day']}}</textarea></td>--}}
{{--                    <td class="row" style="text-align: center; padding: 0px; margin: 0px">@if ($data[$i]['min_param'])<label class="switch" style="padding: 0px; margin: 0px"><input onclick="save_tr(this.parentNode.parentNode.parentNode)" id="min_param_{{$data[$i]['hfrpok']}}" type="checkbox" checked><span class="slider"></span></label>@else<label class="switch"><input onclick="save_tr(this.parentNode.parentNode.parentNode)" id="min_param_{{$data[$i]['hfrpok']}}" type="checkbox"><span class="slider"></span></label>@endif</td>--}}
                        <td class="row" style="text-align: center; padding: 0px; margin: 0px"><textarea onchange="save_tr(this.parentNode.parentNode)" class="tag_name">{{$data[$i]['tag_name']}}</textarea></td>

                        <td class="row" style="text-align: center; padding: 0px; margin: 0px">@if ($data[$i]['hour_param'])<label class="switch" style="padding: 0px; margin: 0px"><input onclick="save_tr(this.parentNode.parentNode.parentNode)" id="hour_param_{{$data[$i]['hfrpok']}}" type="checkbox" checked><span class="slider"></span></label>@else<label class="switch"><input onclick="save_tr(this.parentNode.parentNode.parentNode)" id="hour_param_{{$data[$i]['hfrpok']}}" type="checkbox"><span class="slider"></span></label>@endif</td>
                    <td class="row" style="text-align: center; padding: 0px; margin: 0px">@if ($data[$i]['sut_param'])<label class="switch" style="padding: 0px; margin: 0px"><input onclick="save_tr(this.parentNode.parentNode.parentNode)" id="sut_param_{{$data[$i]['hfrpok']}}" type="checkbox" checked><span class="slider"></span></label>@else<label class="switch"><input onclick="save_tr(this.parentNode.parentNode.parentNode)" id="sut_param_{{$data[$i]['hfrpok']}}" type="checkbox"><span class="slider"></span></label>@endif</td>

                </tr>
            @endfor
            </tbody>
        </table>
    </div>
    <script>
        var header_content = 'Параметры ОЖД. ';
        function showMe(box){
            var vis = (box.checked) ? "block" : "none";
            document.getElementById(box.classList[0]).style.display = vis;
        }
        $(document).ready(function () {

        })
        function save_tr(tr){
            var hfrpok = tr.getAttribute('data-id')
            var namepar1 = tr.getElementsByClassName('namepar1')[0].value
            var shortname = tr.getElementsByClassName('shortname')[0].value
            var tag_name = tr.getElementsByClassName('tag_name')[0].value
            if (document.getElementsByClassName('guid_masdu_5min_'+hfrpok)[0].checked){
                var guid_masdu_5min = document.getElementById('guid_masdu_5min_'+hfrpok).value
            } else {
                var guid_masdu_5min = ''
            }
            if (document.getElementsByClassName('guid_masdu_hours_'+hfrpok)[0].checked){
                var guid_masdu_hours = document.getElementById('guid_masdu_hours_'+hfrpok).value
            } else {
                var guid_masdu_hours = ''
            }
            if (document.getElementsByClassName('guid_masdu_day_'+hfrpok)[0].checked){
                var guid_masdu_day = document.getElementById('guid_masdu_day_'+hfrpok).value
            } else {
                var guid_masdu_day = ''
            }
            if (document.getElementById('hour_param_'+hfrpok).checked){
                var hour_param = true
            } else {
                var hour_param = false
            }
            if (document.getElementById('sut_param_'+hfrpok).checked){
                var sut_param = true
            } else {
                var sut_param = false
            }
            var data = {hfrpok: hfrpok, namepar1: namepar1, shortname: shortname, tag_name: tag_name,
                hour_param: hour_param, sut_param: sut_param, guid_masdu_hours: guid_masdu_hours, guid_masdu_5min: guid_masdu_5min, guid_masdu_day: guid_masdu_day}
            console.log(data)
            $.ajax({
                url:'/signal_settings_store',
                type:'POST',
                data: data,
                success:(res)=>{
                    console.log(res)
                    //         if (typeof res === 'object'){
                    //             open_modal_ober('Данный сигнал повторяется с "'+res['name_param']+'"')
                    //         }
                    // console.log(res)
                },
                async: true
            });
        }
        function click_on_slider(slider, hfrpok, type){
            var edit = document.getElementById('edit_'+type+'_'+hfrpok)
            var save = document.getElementById('save_'+type+'_'+hfrpok)
            var guid = document.getElementById(type+'_'+hfrpok)
            if (slider.checked){    //если включили
                save.style.display = ''
                edit.style.display = 'none'
                guid.style.display = ''
            }else {    //если выключили
                save.style.display = 'none'
                edit.style.display = 'none'
                guid.style.display = 'none'
                guid.textContent = ''
                save_tr(slider.parentNode.parentNode.parentNode)
            }
        }
        function edit_guid(img, hfrpok, type){
            img.style.display = 'none'
            document.getElementById('save_'+type+'_'+hfrpok).style.display = ''
            img.parentNode.getElementsByTagName('textarea')[0].style.display = ''
        }
        function save_guid(img, hfrpok, type){
            save_tr(img.parentNode.parentNode)
            img.style.display = 'none'
            document.getElementById('edit_'+type+'_'+hfrpok).style.display = ''
            img.parentNode.getElementsByTagName('textarea')[0].style.display = 'none'
        }
// Для поисковика скрипт
        var input = document.getElementById('search_row')
        input.oninput = function() {
            search_object()
        };
        function search_object(){
            var body = document.getElementById('tbody_for_search')
            var all_tr = body.getElementsByTagName('tr')
            var search_text = new RegExp(document.getElementById('search_row').value, 'i');

            for(var i=0; i<all_tr.length; i++){
                if (!all_tr[i].classList.contains('hidden_rows')){
                    if (all_tr[i].getElementsByClassName('namepar1')[0].value.match(search_text) || all_tr[i].getElementsByClassName('shortname')[0].value.match(search_text) || all_tr[i].getElementsByClassName('tag_name')[0].value.match(search_text)){
                        all_tr[i].style.display = ''
                        all_tr[i].style.display = ''
                    }else {
                        all_tr[i].style.display = 'none'
                        all_tr[i].style.display = 'none'
                    }
                }
            }
        }
    </script>

    <style>
        input:checked + .slider{
            background-color: #2FD059;
        }
        h4{
            margin-bottom: 7px;
            margin-top: 7px;
        }
        textarea{
            width: 95%;
            height: 100%;
        }
        .row{
            padding-top: 10px;
            padding-bottom: 10px;

        }
        .button {
            background-color: #4CAF50;
            border: none;
            border-radius: 6px;
            color: white;
            height: 5%;
            padding: 6px 12px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 13px;
            margin: 4px 2px;
            -webkit-transition-duration: 0.4s; /* Safari */
            transition-duration: 0.4s;
            cursor: pointer;
        }

        .button1 {
            background-color: white;
            color: black;
            border: 2px solid #008CBA;
        }

        .button1:hover {
            background-color: #008CBA;
            color: white;
        }

        textarea { font-family: HeliosCond; }
        input[type=text] { font-family: HeliosCond; }

        .itemInfoTable thead th{
            width: auto;
        }


        /*Для поисковика и инпута*/
        .text-field__input,
        input[type="text"], textarea {
            display: block;
            padding: 0.375rem 0.75rem;
            font-family: inherit;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #bdbdbd;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        textarea{
            padding: 0px;
            padding-top: 3px;
            padding-left: 3px;
            width: 98%;
        }

        .text-field__input::placeholder,
        input[type="text"]::placeholder,
        textarea::placeholder{
            color: #212529;
            opacity: 0.4;
        }

        .text-field__input:focus,
        input[type="text"]:focus,
        textarea:focus{
            color: #212529;
            background-color: #fff;
            border-color: #bdbdbd;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(158, 158, 158, 0.25);
        }

        .text-field__input:disabled,
        .text-field__input[readonly],
        input[type="text"]:disabled,
        input[type="text"][readonly],
        textarea:disabled,
        textarea[readonly]{
            background-color: #f5f5f5;
            opacity: 1;
        }
    </style>

@endsection
