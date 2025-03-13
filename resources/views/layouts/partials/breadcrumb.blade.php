<nav class="breadcrumb-nav">
    <div class="nav-wrapper">
        <div class="col s12">
            <a href="{{ route('dashboard') }}" class="breadcrumb">
                <i class="material-icons">home</i>
            </a>
            @if(Route::is('financial.*'))
                <a href="#" class="breadcrumb">Financeiro</a>
            @endif
            @if(Route::is('financial.reports.*'))
                <a href="{{ route('financial.reports.index') }}" class="breadcrumb">Relatórios</a>
            @endif
        </div>
    </div>
</nav> 