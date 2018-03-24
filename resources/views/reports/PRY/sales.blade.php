@extends('reports.master')
@section('reportName', 'Libro IVA Ventas')

@section('data')
    <table class="u-full-width">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>RUC</th>
                <th>Rázon Social</th>
                <th class="number">Timbrado</th>
                <th>Factura</th>
                <th class="number">Gravada 10%</th>
                <th class="number">IVA 10%</th>
                <th class="number">Gravada 5%</th>
                <th class="number">IVA 5%</th>
                <th class="number">Exenta</th>
                <th class="number">Total</th>
                <th>Concepto</th>
                <th>Condición</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->groupBy('salesID') as $group)
                <tr @if ($group->first()->status == 3) class="danger" @endif>
                    <td>{{ \Carbon\Carbon::parse($group->first()->date)->format('d/m/Y')}}</td>

                    <td class="important">{{ $group->first()->customer_code }}</td>

                    <td class="text">{{ $group->first()->customer }}</td>

                    <td class="number">{{ $group->first()->code }}</td>

                    <td class="important">
                        {{----}}
                        <a href="{{route('sales.edit', [request()->route('taxPayer')->id, request()->route('cycle')->id, $group->first()->salesID])}}"
                            target="_blank">
                            {{ $group->first()->number }}
                        </a>
                    </td>

                    @php
                    $vat10 = $group->where('coefficient', '=', 0.1)->sum('vatValue');
                    $vat5 = $group->where('coefficient', '=', 0.05)->sum('vatValue');

                    $base10 = $group->where('coefficient', '=', 0.1)->sum('localCurrencyValue');
                    $base5 = $group->where('coefficient', '=', 0.05)->sum('localCurrencyValue');
                    $exe = $group->where('coefficient', '=', 0)->sum('localCurrencyValue');
                    @endphp

                    @if ($group->first()->status != 3)

                        <td class="number important">
                            {{ number_format($vat10, 0, ',', '.') }}
                        </td>

                        <td class="number important">
                            {{ number_format($base10 - $vat10, 0, ',', '.') }}
                        </td>

                        <td class="number important">
                            {{ number_format($vat5, 0, ',', '.') }}
                        </td>

                        <td class="number important">
                            {{ number_format($base5 - $vat5, 0, ',', '.') }}
                        </td>

                        <td class="number important">
                            {{ number_format($exe, 0, ',', '.') }}
                        </td>

                        <td class="number important">{{ number_format($group->sum('localCurrencyValue'), 0, ',', '.') }}</td>

                    @else

                        <td class="number important">{{ number_format(0, 0, ',', '.') }}</td>
                        <td class="number important">{{ number_format(0, 0, ',', '.') }}</td>
                        <td class="number important">{{ number_format(0, 0, ',', '.') }}</td>
                        <td class="number important">{{ number_format(0, 0, ',', '.') }}</td>
                        <td class="number important">{{ number_format(0, 0, ',', '.') }}</td>
                        <td class="number important">{{ number_format(0, 0, ',', '.') }}</td>

                    @endif

                    <td class="text">
                        @foreach ($group as $detail)
                            {{ $detail->costCenter }},
                        @endforeach
                    </td>

                    @if ($group->first()->status != 3)

                        <td>{{ $group->first()->payment_condition > 0 ? 'Credito' : 'Contado' }}</td>

                    @else

                        <td>Anulado</td>

                    @endif

                </tr>
            @endforeach
            <tr class="group">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Gran Total</td>
                <td class="number">
                    <b>{{ number_format($data->where('status', '!=', 3)->where('coefficient', '=', 0.1)->sum('vatValue'), 0, ',', '.') }}</b></td>
                    <td class="number">
                        <b>{{ number_format(($data->where('status', '!=', 3)->where('coefficient', '=', 0.1)->sum('localCurrencyValue') - $data->where('status', '!=', 3)->where('coefficient', '=', 0.1)->sum('vatValue')), 0, ',', '.') }}</b>
                    </td>
                    <td class="number">
                        <b>{{ number_format($data->where('status', '!=', 3)->where('coefficient', '=', 0.05)->sum('vatValue'), 0, ',', '.') }}</b></td>
                        <td class="number">
                            <b>{{ number_format(($data->where('status', '!=', 3)->where('coefficient', '=', 0.05)->sum('localCurrencyValue') - $data->where('status', '!=', 3)->where('coefficient', '=', 0.05)->sum('vatValue')), 0, ',', '.') }}</b>
                        </td>
                        <td class="number">
                            <b>{{ number_format($data->where('status', '!=', 3)->where('coefficient', '=', 0.00)->sum('vatValue'), 0, ',', '.') }}</b></td>
                            <td class="number"><b>{{ number_format($data->where('status', '!=', 3)->sum('localCurrencyValue'), 0, ',', '.') }}</b></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            @endsection
