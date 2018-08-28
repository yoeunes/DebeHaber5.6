<?php

namespace App\Http\Controllers;

use App\AccountMovement;
//use App\JournalTransaction;
use App\Taxpayer;
use App\Cycle;
use App\Chart;
use App\Transaction;
use App\TransactionDetail;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class SalesController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Taxpayer $taxPayer, Cycle $cycle)
    {
        return view('/commercial/sales');
    }

    public function get_sales(Taxpayer $taxPayer, Cycle $cycle)
    {

        return TransactionResource::collection(
            Transaction::MySales()
            ->with('customer:name,id')
            ->with('currency')
            ->with('details')
            ->whereBetween('date', [$cycle->start_date, $cycle->end_date])
            ->orderBy('transactions.date', 'desc')
            ->paginate(50)
        );
    }

    public function getLastSale($partnerID)
    {
        $transaction = Transaction::MySales()
        ->join('taxpayers', 'taxpayers.id', 'transactions.customer_id')
        ->where('customer_id', $partnerID)
        ->with('details')
        ->orderBy('date', 'desc')
        ->select(DB::raw('transactions.id,taxpayers.name as Supplier
        ,supplier_id,document_id,currency_id,rate,payment_condition,chart_account_id,date
        ,number,transactions.code,code_expiry'))
        ->first();

        return response()->json($transaction, 200);
    }


    public function get_salesByID(Taxpayer $taxPayer, Cycle $cycle, $id)
    {

        $transaction = Transaction::MySales()->join('taxpayers', 'taxpayers.id', 'transactions.customer_id')
        ->where('transactions.id', $id)
        ->with('details')
        ->select(DB::raw('false as selected,transactions.id,
        taxpayers.name as customer,
        customer_id,
        document_id,
        currency_id,
        rate,
        payment_condition,
        chart_account_id,
        date,
        number,
        type,
        transactions.code,code_expiry'))
        ->get();

        return response()->json($transaction, 200);
    }

    public function get_lastDate($taxPayerID, Cycle $cycle)
    {
        $lastDate = Transaction::MySales()
        ->where('supplier_id', $taxPayerID)
        ->orderBy('created_at', 'desc')
        ->select(DB::raw('date'))
        ->first();

        if (isset($lastDate))
        { return response()->json($lastDate->date->format('Y-m-d')); }
        else
        { return response()->json(Carbon::now()->format('Y-m-d')); }
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        //
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request, Taxpayer $taxPayer,Cycle $cycle)
    {
        $transaction = $request->id == 0 ? new Transaction() : Transaction::where('id', $request->id)->first();

        if ($request->customer_id > 0)
        {
            $transaction->customer_id = $request->customer_id;
        }

        $transaction->supplier_id = $taxPayer->id;

        if ($request->document_id > 0)
        {
            $transaction->document_id = $request->document_id;
        }

        $transaction->currency_id = $request->currency_id;
        $transaction->rate = $request->rate;
        $transaction->payment_condition = $request->payment_condition;

        if ($request->chart_account_id > 0)
        {
            $transaction->chart_account_id = $request->chart_account_id;
        }

        $transaction->date = $request->date;
        $transaction->number = $request->number;
        $transaction->code = $request->code;
        $transaction->code_expiry = $request->code_expiry;
        $transaction->comment = $request->comment;
        $transaction->type = $request->type ?? 4;
        $transaction->save();

        foreach ($request->details as $detail)
        {
            if ($detail['id'] == 0)
            {
                $transactionDetail = new TransactionDetail();
            }
            else
            {
                $transactionDetail = TransactionDetail::where('id',$detail['id'])->first();
            }

            $transactionDetail->transaction_id = $transaction->id;
            $transactionDetail->chart_id = $detail['chart_id'];
            $transactionDetail->chart_vat_id = $detail['chart_vat_id'];
            $transactionDetail->value = $detail['value'];
            $transactionDetail->save();
        }

        return response()->json('ok', 200);
    }

    /**
    * Display the specified resource, in non-edit mode to prevent unauthorized changes.
    *
    * @param  \App\Transaction  $transaction
    * @return \Illuminate\Http\Response
    */
    public function show(Transaction $transaction, Taxpayer $taxPayer, Cycle $cycle)
    {
        return view('/commercial/sales/show')->with('transaction', $transaction);
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Transaction  $transaction
    * @return \Illuminate\Http\Response
    */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Transaction  $transaction
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Transaction  $transaction
    * @return \Illuminate\Http\Response
    */
    public function destroy(Taxpayer $taxPayer, Cycle $cycle,$transactionID)
    {
        try
        {
            //TODO: Run Tests to make sure it deletes all journals related to transaction
            AccountMovement::where('transaction_id', $transactionID)->delete();
            //JournalTransaction::where('transaction_id',$transactionID)->delete();
            Transaction::where('id',$transactionID)->delete();

            return response()->json('Ok', 200);
        }
        catch (\Exception $e)
        {
            return response()->json($e, 500);
        }
    }
    public function anull(Taxpayer $taxPayer, Cycle $cycle,$transactionID)
    {
        try
        {
            $transaction = Transaction::find($transactionID);

            if (isset($transaction))
            {
                $transaction->setStatus('Annul');
            }

            return response()->json('Ok', 200);
        }
        catch (\Exception $e)
        {
            return response()->json($e, 500);
        }
    }

    /**
    * Generates one journal for all sales in date range.
    */
    public function generate_Journals($startDate, $endDate, $taxPayer, $cycle)
    {
        \DB::connection()->disableQueryLog();

        $querySales = Transaction::MySalesForJournals($startDate, $endDate, $taxPayer->id)
        ->get();

        if ($querySales->where('journal_id', '!=', null)->count() > 0)
        {
            $arrJournalIDs = $querySales->where('journal_id', '!=', null)->pluck('journal_id');
            //## Important! Null all references of Journal in Transactions.
            Transaction::whereIn('journal_id', [$arrJournalIDs])
            ->update(['journal_id' => null]);

            //Delete the journals & details with id
            \App\JournalDetail::whereIn('journal_id', [$arrJournalIDs])
            ->forceDelete();
            \App\Journal::whereIn('id', [$arrJournalIDs])
            ->forceDelete();
        }

        $journal = new \App\Journal();
        $comment = __('accounting.SalesBookComment', ['startDate' => $startDate->toDateString(), 'endDate' => $endDate->toDateString()]);

        $journal->cycle_id = $cycle->id; //TODO: Change this for specific cycle that is in range with transactions
        $journal->date = $endDate;
        $journal->comment = $comment;
        $journal->is_automatic = 1;
        $journal->save();

        //Assign all transactions the new journal_id.
        //No need for If Count > 0, because if it was 0, it would not have gone in this function.
        Transaction::whereIn('id', $querySales->pluck('id'))
        ->update(['journal_id' => $journal->id]);

        //Sales Transactionsd done in cash. Must affect direct cash account.
        $salesInCash = Transaction::MySalesForJournals($startDate, $endDate, $taxPayer->id)
        ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
        ->groupBy('rate', 'chart_account_id')
        ->where('payment_condition', '=', 0)
        ->select(DB::raw('max(rate) as rate'),
        DB::raw('max(chart_account_id) as chart_account_id'),
        DB::raw('sum(transaction_details.value) as total'))
        ->get();

        //run code for cash sales (insert detail into journal)
        foreach($salesInCash as $row)
        {
            $ChartController = new ChartController();
            // search if chart exists, or else create it. we don't want an error causing all transactions not to be accounted.
            $accountChartID = $row->chart_account_id ?? $ChartController->createIfNotExists_CashAccounts($taxPayer, $cycle, $row->chart_account_id)->id;

            $value = $row->total * $row->rate;
            $detail = $journal->details->where('chart_id', $accountChartID)->first() ?? new \App\JournalDetail();
            $detail->debit = 0;
            $detail->credit += $value;
            $detail->chart_id = $accountChartID;
            $journal->details()->save($detail);
            $journal->load('details');

        }

        //2nd Query: Sales Transactions done in Credit. Must affect customer credit account.
        $creditSales = Transaction::MySalesForJournals($startDate, $endDate, $taxPayer->id)
        ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
        ->groupBy('rate')
        ->groupBy('customer_id')
        ->where('payment_condition', '>', 0)
        ->select(DB::raw('max(rate) as rate'),
        DB::raw('max(customer_id) as customer_id'),
        DB::raw('sum(transaction_details.value) as total'))
        ->get();

        //run code for credit sales (insert detail into journal)
        foreach($creditSales as $row)
        {
            $ChartController = new ChartController();
            $customerChartID = $ChartController->createIfNotExists_AccountsReceivables($taxPayer, $cycle, $row->customer_id)->id;

            $value = $row->total * $row->rate;

            $detail = $journal->details->where('chart_id', $customerChartID)->first() ?? new \App\JournalDetail();
            $detail->debit = 0;
            $detail->credit += $value;
            $detail->chart_id = $customerChartID;
            $journal->details()->save($detail);
            $journal->load('details');
        }

        //one detail query, to avoid being heavy for db. Group by fx rate, vat, and item type.
        $detailAccounts = Transaction::MySalesForJournals($startDate, $endDate, $taxPayer->id)
        ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
        ->join('charts', 'charts.id', '=', 'transaction_details.chart_vat_id')
        ->groupBy('rate', 'transaction_details.chart_id', 'transaction_details.chart_vat_id')
        ->select(DB::raw('max(rate) as rate'),
        DB::raw('max(charts.coefficient) as coefficient'),
        DB::raw('max(transaction_details.chart_vat_id) as chart_vat_id'),
        DB::raw('max(transaction_details.chart_id) as chart_id'),
        DB::raw('sum(transaction_details.value) as total'))
        ->get();

        //run code for credit sales (insert detail into journal)
        foreach($detailAccounts as $row)
        {
            $value = ($row->total - ($row->total / (1 + $row->coefficient))) * $row->rate;
            //
            $detail = $journal->details->where('chart_id', $row->chart_vat_id)->first() ?? new \App\JournalDetail();
            $detail->debit += $value;
            $detail->credit = 0;
            $detail->chart_id = $row->chart_vat_id;
            $journal->details()->save($detail);
            $journal->load('details');
        }

        //run code for credit sales (insert detail into journal)
        foreach($detailAccounts as $row)
        {
            $value = 0;

            $value += ($row->total / (1 + $row->coefficient)) * $row->rate;

            $detail = $journal->details->where('chart_id', $row->chart_id)->first() ?? new \App\JournalDetail();
            $detail->debit += $value;
            $detail->credit = 0;
            $detail->chart_id = $row->chart_id;
            $journal->details()->save($detail);
            $journal->load('details');
        }
    }
}
