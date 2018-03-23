

@extends('reports.master')
@section('reportName', 'Libro IVA Ventas por Concepto')

@section('data')
    <table class="u-full-width">
        <tbody>
            @foreach ($data->groupBy('costCenter') as $groupedRows)
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>RUC</th>
                        <th>Rázon Social</th>
                        <th class="number">Timbrado</th>
                        <th>Factura</th>
                        <th>Condición</th>
                        <th class="number">Gravada 10%</th>
                        <th class="number">IVA 10%</th>
                        <th class="number">Gravada 5%</th>
                        <th class="number">IVA 5%</th>
                        <th class="number">Exenta</th>
                        <th class="number">Total</th>
                    </tr>
                </thead>
                <tr class="group">
                    <td colspan="3"><b>{{ $groupedRows[0]->costCenter }}</b></td>
                    <td></td>
                    <td></td>
                    <td>Sub Total</td>
                    <td class="number"><b>{{ number_format($groupedRows->where('status', '!=', 3)->where('coeficient', '=', 0.1)->sum('vatValue'), 0, ',', '.') }}</b></td>
                    <td class="number"><b>{{ number_format(($groupedRows->where('status', '!=', 3)->where('coeficient', '=', 0.1)->sum('localCurrencyValue') - $groupedRows->where('status', '!=', 3)->where('coeficient', '=', 0.1)->sum('vatValue')), 0, ',', '.') }}</b></td>
                    <td class="number"><b>{{ number_format($groupedRows->where('status', '!=', 3)->where('coeficient', '=', 0.05)->sum('vatValue'), 0, ',', '.') }}</b></td>
                    <td class="number"><b>{{ number_format(($groupedRows->where('status', '!=', 3)->where('coeficient', '=', 0.05)->sum('localCurrencyValue') - $groupedRows->where('status', '!=', 3)->where('coeficient', '=', 0.05)->sum('vatValue')), 0, ',', '.') }}</b></td>
                    <td class="number"><b>{{ number_format($groupedRows->where('status', '!=', 3)->where('coeficient', '=', 0.00)->sum('vatValue'), 0, ',', '.') }}</b></td>
                    <td class="number"><b>{{ number_format($groupedRows->where('status', '!=', 3)->sum('vatValue'), 0, ',', '.') }}</b></td>
                </tr>
                @foreach ($groupedRows->groupBy('salesID') as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->first()->invoice_date)->format('d/m/Y')}}</td>

                        <td class="important">{{ $row->first()->customer_code }}</td>

                        <td class="text">{{ $row->first()->customer }}</td>

                        <td class="number">{{ $row->first()->invoice_code }}</td>

                        <td class="important">
                            <a href="/current/{{ (request()->route('company'))->id }}/sales/{{ $row->first()->salesID }}/edit" target="_blank">
                                {{ $row->first()->invoice_number }}
                            </a>
                        </td>

                        <td>{{ $row->first()->payment_condition > 0 ? 'Credito' : 'Contado' }}</td>

                        <td class="number important">
                            {{ number_format($row->where('coeficient', '=', 0.1)->sum('vatValue'), 0, ',', '.') }}
                        </td>

                        <td class="number important">
                            {{ number_format($row->where('coeficient', '=', 0.1)->sum('localCurrencyValue') - $row->where('coeficient', '=', 0.1)->sum('vatValue'), 0, ',', '.') }}
                        </td>

                        <td class="number important">
                            {{ number_format($row->where('coeficient', '=', 0.05)->sum('vatValue'), 0, ',', '.') }}
                        </td>

                        <td class="number important">
                            {{ number_format($row->where('coeficient', '=', 0.05)->sum('localCurrencyValue') - $row->where('coeficient', '=', 0.05)->sum('vatValue'), 0, ',', '.') }}
                        </td>

                        <td class="number important">
                            {{ number_format($row->where('coeficient', '=', 0)->sum('localCurrencyValue'), 0, ',', '.') }}
                        </td>

                        <td class="number important">{{ number_format($row->sum('localCurrencyValue'), 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endforeach
            <tr class="group">
                <td></td><td></td><td></td>
                <td></td>
                <td></td>
                <td>Gran Total</td>
                <td class="number"><b>{{ number_format($data->where('status', '!=', 3)->where('coeficient', '=', 0.1)->sum('vatValue'), 0, ',', '.') }}</b></td>
                <td class="number"><b>{{ number_format(($data->where('status', '!=', 3)->where('coeficient', '=', 0.1)->sum('localCurrencyValue') - $data->where('status', '!=', 3)->where('coeficient', '=', 0.1)->sum('vatValue')), 0, ',', '.') }}</b></td>
                <td class="number"><b>{{ number_format($data->where('status', '!=', 3)->where('coeficient', '=', 0.05)->sum('vatValue'), 0, ',', '.') }}</b></td>
                <td class="number"><b>{{ number_format(($data->where('status', '!=', 3)->where('coeficient', '=', 0.05)->sum('localCurrencyValue') - $data->where('status', '!=', 3)->where('coeficient', '=', 0.05)->sum('vatValue')), 0, ',', '.') }}</b></td>
                <td class="number"><b>{{ number_format($data->where('status', '!=', 3)->where('coeficient', '=', 0.00)->sum('vatValue'), 0, ',', '.') }}</b></td>
                <td class="number"><b>{{ number_format($data->where('status', '!=', 3)->sum('localCurrencyValue'), 0, ',', '.') }}</b></td>
            </tr>
        </tbody>
    </table>
@endsection