
@php
$currentTeam = Auth::user()->currentTeam->name;
@endphp

@extends('spark::layouts.dashboard')

@section('title', __('global.Dashboard',['team' => $currentTeam]))

@section('content')

    <div class="row">
        <div class="col-xl-6">
            <!--begin:: Widgets/Top Products-->
            <div class="m-portlet m-portlet--full-height m-portlet--fit ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Contribuyentes del equipo, {{ $currentTeam }}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <a href="{{ route('taxpayer.create') }}" class="btn btn--sm m-btn--pill btn-secondary m-btn m-btn--label-brand">
                            <span>
                                Crear un Contribuyente
                            </span>
                        </a>
                    </div>
                </div>

                <div class="m-portlet__body">
                    <!--begin::Widget5-->
                    <div class="m-widget4 m-widget4--chart-bottom">
                        @if(isset($taxPayerIntegrations))
                            @foreach($taxPayerIntegrations->sortBy('taxpayer.name') as $integration)
                                <div class="m-widget4__item">
                                    <div class="m-widget4__img m-widget4__img--logo">
                                        {{-- {{ $integration->taxPayer->image }} --}}
                                        <img src="/photo/" alt="" onerror="this.src='/img/icons/cloud.jpg';">
                                    </div>

                                    @if ($integration->status == 1)
                                        <div class="m-widget4__info">
                                            <span class="m-widget4__title">
                                                {{ $integration->taxpayer->name }}
                                            </span>
                                            <br>
                                            <span class="m-widget4__sub">
                                                Awaiting Approval
                                            </span>
                                        </div>
                                    @else
                                        <div class="m-widget4__info">
                                            <span class="m-widget4__title">
                                                <a href="{{ url('selectTaxPayer', $integration->taxpayer) }}">
                                                    {{ $integration->taxpayer->name }}
                                                </a>
                                            </span>
                                            <br>
                                            <span class="m-widget4__sub">
                                                {{ $integration->taxpayer->alias }} | {{ $integration->taxpayer->taxid }}
                                            </span>
                                        </div>

                                        <div class="m-btn-group m-btn-group--pill btn-group" role="group" aria-label="...">
                                            <a href="{{ route('taxpayer.show', $integration->taxpayer) }}" class="m-btn btn btn-secondary">
                                                <i class="la la-pencil text-info"></i>
                                            </a>

                                            @if ($integration->is_owner == 1)
                                                {{-- onclick="addFavorite({{ $integration->taxpayer_id }}, 0)" --}}
                                                <a href="#" class="m-btn btn btn-secondary">
                                                    <i class="la la-star text-warning"></i>
                                                </a>
                                            @else
                                                <a href="#" class="m-btn btn btn-secondary">
                                                    <i class="la la-star-o text-warning"></i>
                                                </a>
                                            @endif
                                        </div>

                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <!--begin:: Invitations-->
            <div class="m-portlet m-portlet--bordered-semi m-portlet--fit">
                <div class="row justify-content-center padding-40-5">
                    <div class="col-3">
                        <img src="/img/icons/invitation.svg" class="" alt="" width="135">
                    </div>
                    <div class="col-9">
                        <h3>Invitar a Alguien</h3>
                        <p>
                            <br>
                            ¿Quieres invitar alguien para que forme parte del equipo?
                            <br>
                            Miembros tienen accesso a todos los contribuyentes del equipo.
                        </p>
                        <form class="" action="index.html" method="post">
                            <div class="m-input-icon m-input-icon--left m-input-icon--right">
                                <input type="text" class="form-control m-input m-input--pill m-input--air" name="email" value="" placeholder="Correo Electronico del Invitado">
                                <span class="m-input-icon__icon m-input-icon__icon--left">
                                    <span>
                                        <i class="la la-envelope"></i>
                                    </span>
                                </span>
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span>
                                        <button class="btn btn-outline-success m-btn m-btn--icon m-btn--icon-only m-btn--pill btn-inline-input" type="button" name="button">
                                            <i class="la la-send"></i>
                                        </button>
                                    </span>
                                </span>
                            </div>
                        </form>

                        <ul>
                            @foreach ($integrationInvites as $invite)
                                <li>
                                    {{ $invite->taxpayer }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <!--begin:: Widgets/Outbound Bandwidth-->
            <div class="m-portlet m-portlet--bordered-semi m-portlet--half-height m-portlet--fit " style="min-height: 400px">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                @lang('teams.team_members')
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-widget4">

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
