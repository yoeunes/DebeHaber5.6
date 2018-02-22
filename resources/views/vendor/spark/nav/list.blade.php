<spark-navbar
:user="user"
:teams="teams"
:current-team="currentTeam"
:unread-announcements-count="unreadAnnouncementsCount"
:unread-notifications-count="unreadNotificationsCount"
inline-template>

<header class="m-grid__item m-header" data-minimize="minimize" data-minimize-mobile="minimize" data-minimize-offset="200" data-minimize-mobile-offset="200" >
    <div class="m-container m-container--fluid m-container--full-height">
        <div class="m-stack m-stack--ver m-stack--desktop m-header__wrapper">
            <!-- BEGIN: Brand -->
            <div class="m-stack__item m-brand m-brand--mobile">
                <div class="m-stack m-stack--ver m-stack--general">
                    <div class="m-stack__item m-stack__item--middle m-brand__logo">
                        <a href="index.html" class="m-brand__logo-wrapper">
                            <img alt="debehaber logo" src="/img/logos/debehaber.svg" width="128" width="128"/>
                        </a>
                    </div>
                    <div class="m-stack__item m-stack__item--middle m-brand__tools">
                        <!-- BEGIN: Responsive Header Menu Toggler -->
                        <a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler">
                            <span></span>
                        </a>
                        <!-- END -->
                        <!-- BEGIN: Topbar Toggler -->
                        <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon">
                            <i class="flaticon-more"></i>
                        </a>
                        <!-- BEGIN: Topbar Toggler -->
                    </div>
                </div>
            </div>
            <!-- END: Brand -->
            <div class="m-stack__item m-stack__item--middle m-stack__item--left m-header-head" id="m_header_nav">
                <div class="m-stack m-stack--ver m-stack--desktop">
                    <div class="m-stack__item m-stack__item--fluid">
                        <!-- BEGIN: Horizontal Menu -->
                        <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark " id="m_aside_header_menu_mobile_close_btn">
                            <i class="la la-close"></i>
                        </button>

                        @if (request()->route('taxPayer'))
                            @include('spark::nav.auth.taxPayer')
                        @else
                            @include('spark::nav.auth.team')
                        @endif
                        <!-- END: Horizontal Menu -->
                    </div>
                </div>
            </div>
            <div class="m-stack__item m-stack__item--middle m-stack__item--center">
                <!-- BEGIN: Brand -->
                <a href="index.html" class="m-brand m-brand--desktop">
                    <img alt="" src="/img/logos/debehaber.svg" width="128"/>
                </a>
                <!-- END: Brand -->
            </div>
            <div class="m-stack__item m-stack__item--right">
                <!-- BEGIN: Topbar -->
                <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
                    <div class="m-stack__item m-topbar__nav-wrapper">
                        <ul class="m-topbar__nav m-nav m-nav--inline">
                            <li class="m-nav__item m-nav__item--focus m-dropdown m-dropdown--large m-dropdown--arrow m-dropdown--align-center m-dropdown--mobile-full-width m-dropdown--skin-light	m-list-search m-list-search--skin-light"
                            data-dropdown-toggle="click" data-dropdown-persistent="true" id="m_quicksearch" data-search-type="dropdown">
                            <a href="#" class="m-nav__link m-dropdown__toggle">
                                <span class="m-nav__link-icon">
                                    <span class="m-nav__link-icon-wrapper">
                                        <i class="flaticon-search-1"></i>
                                    </span>
                                </span>
                            </a>
                            <div class="m-dropdown__wrapper">
                                <span class="m-dropdown__arrow m-dropdown__arrow--center"></span>
                                <div class="m-dropdown__inner ">
                                    <div class="m-dropdown__header">
                                        <form  class="m-list-search__form">
                                            <div class="m-list-search__form-wrapper">
                                                <span class="m-list-search__form-input-wrapper">
                                                    <input id="m_quicksearch_input" autocomplete="off" type="text" name="q" class="m-list-search__form-input" value="" placeholder="@lang('global.Search')">
                                                </span>
                                                <span class="m-list-search__form-icon-close" id="m_quicksearch_close">
                                                    <i class="la la-remove"></i>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="m-dropdown__body">
                                        <div class="m-dropdown__scrollable m-scrollable" data-max-height="300" data-mobile-max-height="200">
                                            <div class="m-dropdown__content"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="m-nav__item m-nav__item--accent m-dropdown m-dropdown--large m-dropdown--arrow m-dropdown--align-center 	m-dropdown--mobile-full-width" data-dropdown-toggle="click" data-dropdown-persistent="true">
                            <a href="#" @click="showNotifications" class="m-nav__link m-dropdown__toggle" id="m_topbar_notification_icon">
                                <span class="m-nav__link-badge m-badge m-badge--dot m-badge--dot-small m-badge--danger"></span>
                                <span class="m-nav__link-icon">
                                    <span class="m-nav__link-icon-wrapper">
                                        <i class="flaticon-music-2"></i>
                                    </span>
                                </span>
                            </a>
                        </li>
                        <li class="m-nav__item m-nav__item--danger m-dropdown m-dropdown--skin-light m-dropdown--large m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push m-dropdown--mobile-full-width m-dropdown--skin-light"  data-dropdown-toggle="click">
                            <a href="#" class="m-nav__link m-dropdown__toggle">
                                <span class="m-nav__link-badge m-badge m-badge--dot m-badge--info m--hide"></span>
                                <span class="m-nav__link-icon">
                                    <span class="m-nav__link-icon-wrapper">
                                        <i class="flaticon-share"></i>
                                    </span>
                                </span>
                            </a>
                            <div class="m-dropdown__wrapper">
                                <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                <div class="m-dropdown__inner">
                                    <div class="m-dropdown__header m--align-center">
                                        <span class="m-dropdown__header-title">
                                            {{__('Quick Actions')}}
                                        </span>
                                        <span class="m-dropdown__header-subtitle">
                                            @lang('global.Short Cuts')
                                        </span>
                                    </div>
                                    <div class="m-dropdown__body m-dropdown__body--paddingless">
                                        <div class="m-dropdown__content">
                                            <div class="m-scrollable" data-scrollable="false" data-max-height="380" data-mobile-max-height="200">
                                                <div class="m-nav-grid m-nav-grid--skin-light">
                                                    <div class="m-nav-grid__row">
                                                        <a href="#" class="m-nav-grid__item">
                                                            <i class="m-nav-grid__icon flaticon-file"></i>
                                                            <span class="m-nav-grid__text">
                                                                Create Sales Invoice
                                                            </span>
                                                        </a>
                                                        <a href="#" class="m-nav-grid__item">
                                                            <i class="m-nav-grid__icon flaticon-time"></i>
                                                            <span class="m-nav-grid__text">
                                                                Create Purchase Invoice
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="m-nav-grid__row">
                                                        <a href="#" class="m-nav-grid__item">
                                                            <i class="m-nav-grid__icon flaticon-folder"></i>
                                                            <span class="m-nav-grid__text">
                                                                Create Journal Entry
                                                            </span>
                                                        </a>
                                                        <a href="#" class="m-nav-grid__item">
                                                            <i class="m-nav-grid__icon flaticon-clipboard"></i>
                                                            <span class="m-nav-grid__text">
                                                                View Journals
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="m-nav__item m-dropdown m-dropdown--medium m-dropdown--arrow  m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click">
                            <a href="#" class="m-nav__link m-dropdown__toggle">
                                <span class="m-topbar__username m--hidden-mobile">
                                    @{{ user.name }}
                                </span>
                                <span class="m-topbar__userpic">
                                    <img :src="user.photo_url" class="m--img-rounded m--marginless m--img-centered" alt=""/>
                                </span>
                                <span class="m-nav__link-icon m-topbar__usericon m--hide">
                                    <span class="m-nav__link-icon-wrapper">
                                        <i class="flaticon-user-ok"></i>
                                    </span>
                                </span>
                            </a>
                            <div class="m-dropdown__wrapper">
                                <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                <div class="m-dropdown__inner">
                                    <div class="m-dropdown__header m--align-center">
                                        <div class="m-card-user m-card-user--skin-light">
                                            <div class="m-card-user__pic">
                                                <img :src="user.photo_url" class="m--img-rounded m--marginless" alt=""/>
                                            </div>
                                            <div class="m-card-user__details">
                                                <span class="m-card-user__name m--font-weight-500">
                                                    @{{ user.name }}
                                                </span>
                                                <a href="" class="m-card-user__email m--font-weight-300 m-link">
                                                    @{{ user.email }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-dropdown__body">
                                        <div class="m-dropdown__content">
                                            <ul class="m-nav m-nav--skin-light">
                                                <li class="m-nav__section m--hide">
                                                    <span class="m-nav__section-text">
                                                        Section
                                                    </span>
                                                </li>
                                                <li class="m-nav__item">
                                                    <a href="/settings" class="m-nav__link">
                                                        <i class="m-nav__link-icon flaticon-profile-1"></i>
                                                        <span class="m-nav__link-title">
                                                            <span class="m-nav__link-wrap">
                                                                <span class="m-nav__link-text">
                                                                    My Profile
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="m-nav__item">
                                                    <a href="profile.html" class="m-nav__link">
                                                        <i class="m-nav__link-icon flaticon-share"></i>
                                                        <span class="m-nav__link-text">
                                                            Activity
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="m-nav__item">
                                                    <a href="profile.html" class="m-nav__link">
                                                        <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                        <span class="m-nav__link-text">
                                                            Messages
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="m-nav__separator m-nav__separator--fit"></li>
                                                <li class="m-nav__item">
                                                    <a href="profile.html" class="m-nav__link">
                                                        <i class="m-nav__link-icon flaticon-info"></i>
                                                        <span class="m-nav__link-text">
                                                            FAQ
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="m-nav__item">
                                                    <a href="https://soporte.debehaber.com" class="m-nav__link">
                                                        <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                        <span class="m-nav__link-text">
                                                            Support
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="m-nav__separator m-nav__separator--fit"></li>
                                                <li class="m-nav__item">
                                                    <a href="/logout" class="btn m-btn--pill    btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
                                                        {{__('Logout')}}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        {{-- <li id="m_quick_sidebar_toggle" class="m-nav__item m-nav__item--info m-nav__item--qs">
                            <a href="#" class="m-nav__link m-dropdown__toggle">
                                <span class="m-nav__link-icon m-nav__link-icon-alt">
                                    <span class="m-nav__link-icon-wrapper">
                                        <i class="flaticon-grid-menu"></i>
                                    </span>
                                </span>
                            </a>
                        </li> --}}
                    </ul>
                </div>
            </div>
            <!-- END: Topbar -->
        </div>
    </div>
</div>
</header>
</spark-navbar>
