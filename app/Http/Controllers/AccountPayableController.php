<?php

namespace App\Http\Controllers;

use App\AccountMovement;
use App\JournalTransaction;
use App\Transaction;
use App\Taxpayer;
use App\Cycle;
use App\Chart;
use Illuminate\Http\Request;
use DB;

class AccountPayableController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Taxpayer $taxPayer, Cycle $cycle)
    {
        $chart=Chart::MoneyAccounts()->orderBy('name')
        ->select('name', 'id', 'sub_type')
        ->get();
        return view('/commercial/accounts-payable')->with('charts',$chart);
    }

    public function get_account_payable(Taxpayer $taxPayer, Cycle $cycle, $skip)
    {
        $transactions = Transaction::MyPurchases()
        ->join('taxpayers', 'taxpayers.id', 'transactions.supplier_id')
        ->join('currencies', 'transactions.currency_id','currencies.id')
        ->join('transaction_details as td', 'td.transaction_id', 'transactions.id')
        ->where('transactions.customer_id', $taxPayer->id)
        ->where('transactions.payment_condition', '>', 0)
        ->whereBetween('transactions.date', [$cycle->start_date, $cycle->end_date])
        ->groupBy('transactions.id')
        ->select(DB::raw('max(transactions.id) as id'),
        DB::raw('max(taxpayers.name) as Supplier'),
        DB::raw('max(taxpayers.taxid) as SupplierTaxID'),
        DB::raw('max(currencies.code) as currency_code'),
        DB::raw('max(transactions.payment_condition) as payment_condition'),
        DB::raw('max(transactions.date) as date'),
        DB::raw('DATE_ADD(max(transactions.date), INTERVAL max(transactions.payment_condition) DAY) as code_expiry'),
        DB::raw('max(transactions.number) as number'),
        DB::raw('(select ifnull(sum(account_movements.debit * account_movements.rate), 0)  from account_movements where `transactions`.`id` = `account_movements`.`transaction_id`) as Paid'),
        DB::raw('sum(td.value * transactions.rate) as Value'),
        DB::raw('(sum(td.value * transactions.rate)
        - (select
        ifnull(sum(account_movements.debit * account_movements.rate), 0)
        from account_movements
        where transactions.id = account_movements.transaction_id))
        as Balance')
        )
        ->orderByRaw('DATE_ADD(max(transactions.date), INTERVAL max(transactions.payment_condition) DAY)', 'desc')
        ->orderByRaw('max(transactions.number)', 'desc')
        ->skip($skip)
        ->take(100)
        ->get();

        return response()->json($transactions);
    }

    public function get_account_payableByID(Taxpayer $taxPayer, Cycle $cycle,$id)
    {
        $accountMovement = Transaction::MyPurchases()
        ->join('taxpayers', 'taxpayers.id', 'transactions.supplier_id')
        ->join('currencies', 'transactions.currency_id','currencies.id')
        ->join('transaction_details as td', 'td.transaction_id', 'transactions.id')
        ->where('transactions.customer_id', $taxPayer->id)
        ->where('transactions.id', $id)
        ->where('transactions.payment_condition', '>', 0)
        ->groupBy('transactions.id')
        ->select(DB::raw('max(transactions.id) as id'),
        DB::raw('max(taxpayers.name) as Supplier'),
        DB::raw('max(taxpayers.taxid) as SupplierTaxID'),
        DB::raw('max(currencies.code) as currency_code'),
        DB::raw('max(transactions.payment_condition) as payment_condition'),
        DB::raw('max(transactions.date) as date'),
        DB::raw('DATE_ADD(max(transactions.date), INTERVAL max(transactions.payment_condition) DAY) as code_expiry'),
        DB::raw('max(transactions.number) as number'),
        DB::raw('(select ifnull(sum(account_movements.debit * account_movements.rate), 0)  from account_movements where `transactions`.`id` = `account_movements`.`transaction_id`) as Paid'),
        DB::raw('sum(td.value * transactions.rate) as Value'),
        DB::raw('(sum(td.value * transactions.rate)
        - (select
        ifnull(sum(account_movements.debit * account_movements.rate), 0)
        from account_movements
        where transactions.id = account_movements.transaction_id))
        as Balance')
        )
        ->get();

        return response()->json($accountMovement);
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
    public function store(Request $request)
    {
        if ($request->payment_value > 0)
        {
            $accountMovement = new AccountMovement();
            $accountMovement->taxpayer_id = $request->taxpayer_id;
            $accountMovement->chart_id =$request->chart_account_id ;
            $accountMovement->date = $request->date;

            $accountMovement->transaction_id = $request->id != '' ? $request->id : null;
            $accountMovement->currency_id = $request->currency_id;
            $accountMovement->rate = $request->rate;
            $accountMovement->debit = $request->payment_value != '' ? $request->payment_value : 0;
            $accountMovement->comment = $request->comment;

            $accountMovement->save();

            return response()->json('ok', 200);
        }

        return response()->json('no value', 403);
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\AccountMovement  $accountMovement
    * @return \Illuminate\Http\Response
    */
    public function show(AccountMovement $accountMovement)
    {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\AccountMovement  $accountMovement
    * @return \Illuminate\Http\Response
    */
    public function edit(AccountMovement $accountMovement)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\AccountMovement  $accountMovement
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, AccountMovement $accountMovement)
    {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\AccountMovement  $accountMovement
    * @return \Illuminate\Http\Response
    */
    public function destroy(Taxpayer $taxPayer, Cycle $cycle, $transactionID)
    {
        // try
        // {
        //     //TODO: Run Tests to make sure it deletes all journals related to transaction
        //     AccountMovement::where('transaction_id', $transactionID)->delete();
        //     JournalTransaction::where('transaction_id', $transactionID)->delete();
        //     Transaction::where('id', $transactionID)->delete();
        //
        //     return response()->json('ok', 200);
        // }
        // catch (\Exception $e)
        // {
        //     return response()->json($e, 500);
        // }
    }

    public function generate_Journals($startDate, $endDate, $taxPayer, $cycle)
    {
        \DB::connection()->disableQueryLog();

        $queryCreditNotes = Transaction::MyCreditNotesForJournals($startDate, $endDate, $taxPayer->id)
        ->get();

        if ($queryCreditNotes->where('journal_id', '!=', null)->count() > 0)
        {
            $arrJournalIDs = $queryCreditNotes->where('journal_id', '!=', null)->pluck('journal_id');
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
        $comment = __('accounting.CreditNoteComment', ['startDate' => $startDate->toDateString(), 'endDate' => $endDate->toDateString()]);

        $journal->cycle_id = $cycle->id; //TODO: Change this for specific cycle that is in range with transactions
        $journal->date = $endDate;
        $journal->comment = $comment;
        $journal->is_automatic = 1;
        $journal->save();

        //Assign all transactions the new journal_id.
        //No need for If Count > 0, because if it was 0, it would not have gone in this function.
        Transaction::whereIn('id', $queryCreditNotes->pluck('id'))
        ->update(['journal_id' => $journal->id]);

        $ChartController= new ChartController();

        //1st Query: Sales Transactions done in Credit. Must affect customer credit account.
        $listOfCreditNotes = Transaction::MyCreditNotesForJournals($startDate, $endDate, $taxPayer->id)
        ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
        ->groupBy('rate', 'customer_id')
        ->select(DB::raw('max(rate) as rate'),
        DB::raw('max(customer_id) as customer_id'),
        DB::raw('sum(transaction_details.value) as total'))
        ->get();

        //run code for credit purchase (insert detail into journal)
        foreach($listOfCreditNotes as $row)
        {
            $customerChartID = $ChartController->createIfNotExists_AccountsReceivables($taxPayer, $cycle, $row->customer_id)->id;
            $value = $row->total * $row->rate;

            $detail = $journal->details->where('chart_id', $customerChartID)->first() ?? new \App\JournalDetail();
            $detail->credit = 0;
            $detail->debit += $value;
            $detail->chart_id = $customerChartID;
            $journal->details()->save($detail);
        }

        //one detail query, to avoid being heavy for db. Group by fx rate, vat, and item type.
        $detailAccounts = Transaction::MyCreditNotesForJournals($startDate, $endDate, $taxPayer->id)
        ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
        ->join('charts', 'charts.id', '=', 'transaction_details.chart_vat_id')
        ->groupBy('rate', 'transaction_details.chart_id', 'transaction_details.chart_vat_id')
        ->select(DB::raw('max(rate) as rate'),
        DB::raw('max(charts.coefficient) as coefficient'),
        DB::raw('max(transaction_details.chart_vat_id) as chart_vat_id'),
        DB::raw('max(transaction_details.chart_id) as chart_id'),
        DB::raw('sum(transaction_details.value) as total'))
        ->get();

        //run code for credit purchase (insert detail into journal)
        foreach($detailAccounts->where('coefficient', '>', 0)->groupBy('chart_vat_id') as $groupedRow)
        {
            $groupTotal = $groupedRow->sum('total');
            $value = ($groupTotal - ($groupTotal / (1 + $groupedRow->first()->coefficient))) * $groupedRow->first()->rate;

            $detail = $journal->details->where('chart_id', $groupedRow->first()->chart_vat_id)->first() ?? new \App\JournalDetail();
            $detail->credit += $value;
            $detail->debit = 0;
            $detail->chart_id = $groupedRow->first()->chart_vat_id;
            $journal->details()->save($detail);
        }

        //run code for credit purchase (insert detail into journal)
        foreach($detailAccounts->groupBy('chart_id') as $groupedRow)
        {
            $value = 0;

            //Discount Vat Value for these items.
            foreach($groupedRow->groupBy('coefficient') as $row)
            {
                $value += ($row->sum('total') / (1 + $row->first()->coefficient)) * $row->first()->rate;
            }

            $detail = $journal->details->where('chart_id', $groupedRow->first()->chart_id)->first() ?? new \App\JournalDetail();
            $detail->credit += $value;
            $detail->debit = 0;
            $detail->chart_id = $groupedRow->first()->chart_id;
            $journal->details()->save($detail);
        }
    }
}
