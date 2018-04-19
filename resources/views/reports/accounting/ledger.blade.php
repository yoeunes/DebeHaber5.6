@extends('reports.master')

@section('reportName', __('accounting.Ledger'))

@section('data')
    <table class="u-full-width">
        <tbody>
            <thead>
                <tr>
                    <th>@lang('global.Code')</th>
                    <th>@lang('accounting.Account')</th>
                    <th>@lang('global.Comment')</th>
                    <th class="number">@lang('accounting.Debit')</th>
                    <th class="number">@lang('accounting.Credit')</th>
                </tr>
            </thead>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row->date }}</td>
                    <td>{{ $row->chartName }}</td>
                    <td>{{ $row->Comment }}</td>
                    <td>{{ $row->debit }}</td>
                    <td>{{ $row->credit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection