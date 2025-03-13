<li class="nav-item">
    <a href="#" class="nav-link" onclick="toggleSubmenu('financialSubmenu')">
        <i class="material-icons">attach_money</i>
        <span>Financeiro</span>
        <i class="material-icons right">arrow_drop_down</i>
    </a>
    <ul id="financialSubmenu" class="submenu">
        <!-- ... existing items ... -->
        <li>
            <a href="{{ route('financial.cadastro') }}" class="waves-effect">
                <i class="material-icons">add_circle</i>
                <span>Cadastro</span>
            </a>
        </li>
        <li>
            <a href="{{ route('financial.reports.index') }}" class="waves-effect">
                <i class="material-icons">assessment</i>
                <span>Relatórios</span>
            </a>
        </li>
    </ul>
</li> 