<?php

namespace App\Http\Controllers;

use App\TaxpayerIntegration;
use App\TaxpayerSetting;
use App\Taxpayer;
use Illuminate\Http\Request;

class TaxpayerIntegrationController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index($teamID, $userID)
    {
        $taxPayerIntegration = TaxpayerIntegration::MyTaxPayers($teamID)
        ->leftJoin('taxpayer_favs', 'taxpayer_favs.taxpayer_id', 'taxpayers.id')
        ->select('taxpayer_integrations.id as id',
        'taxpayers.country',
        'taxpayers.name',
        'taxpayers.alias',
        'taxpayers.taxid',
        'taxpayer_favs.id as is_favorite')
        ->get();

        return $taxPayerIntegration;
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
    * Display the specified resource.
    *
    * @param  \App\TaxpayerIntegration  $taxpayerIntegration
    * @return \Illuminate\Http\Response
    */
    public function show($taxpayerIntegrationID)
    {
        $taxPayerIntegration = TaxpayerIntegration::where('id', $taxpayerIntegrationID)
        ->with(['taxpayer', 'taxpayer.setting'])
        ->get();

        return view('taxpayer/profile')->with('taxPayerIntegration', $taxPayerIntegration);
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\TaxpayerIntegration  $taxpayerIntegration
    * @return \Illuminate\Http\Response
    */
    public function edit(TaxpayerIntegration $taxpayerIntegration)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\TaxpayerIntegration  $taxpayerIntegration
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, TaxpayerIntegration $taxPayerIntegration)
    {
        if (isset($taxPayerIntegration))
        {
            $taxPayer = TaxPayer::where('id', $taxPayerIntegration->taxpayer_id)->with('setting')->first();

            if (isset($taxPayer))
            {
                $taxPayer->alias = $request->alias;
                $taxPayer->address = $request->address;
                $taxPayer->telephone = $request->telephone;
                $taxPayer->email = $request->email;
                $taxPayer->save();

                if ($request->setting_inventory) {
                    $isinventory=1;
                }
                else {
                    $isinventory=0;
                }
                if ($request->setting_production) {
                    $isproduction=1;
                }
                else {
                    $isproduction=0;
                }
                if ($request->setting_fixedasset) {
                    $isasset=1;
                }
                else {
                    $isasset=0;
                }
                if ($request->setting_is_company) {
                    $iscompany=1;
                }
                else {
                    $iscompany=0;
                }

                $taxPayer->setting()->update([
                    'regime_type' => $request->setting_regime ?? 0,
                    'agent_name' => $request->setting_agent,
                    'agent_taxid' => $request->setting_agenttaxid,
                    'show_inventory' => $isinventory,
                    'show_production' => $isproduction,
                    'show_fixedasset' => $isasset,
                    'is_company' => $iscompany,
                ]);
                return response()->json('Ok', 200);
            }
        }

        return response()->json('Resource not found', 404);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\TaxpayerIntegration  $taxpayerIntegration
    * @return \Illuminate\Http\Response
    */
    public function destroy(TaxpayerIntegration $taxpayerIntegration)
    {
        //
    }
}
