<?php

use App\Http\Controllers\Financial\BudgetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;  // Adicionado
use App\Http\Controllers\FinancialCategoryController;
use App\Http\Controllers\FinancialAccountController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\FinancialForecastController;
use App\Http\Controllers\FinancialReconciliationController;
use App\Http\Controllers\CostCenterController;
use App\Http\Controllers\CashFlowProjectionController;
use App\Http\Controllers\FinancialGoalController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\Admin\ApprovalLogController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Settings\BackupController;
use App\Http\Controllers\Financial\ReportController;
use App\Http\Controllers\Financial\PaymentMethodController;
use App\Http\Controllers\Financial\FinancialRegistrationController;
use App\Http\Controllers\Financial\PaymentPlanController;
use App\Http\Controllers\Financial\AgentController;
use App\Http\Controllers\Financial\Reports\FinancialAgentReportController;

Route::get('/', function () {
    return view('welcome');
});

// Rotas de autenticação
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Rotas protegidas
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rotas de clientes
    Route::resource('clients', ClientController::class);
    
    // Rotas de produtos
    Route::get('/products/generate-code/{type}', [ProductController::class, 'generateCode'])
        ->name('products.generate-code');
    Route::resource('products', ProductController::class);  // Adicionado
    
    // Rotas financeiras
    Route::prefix('financial')->name('financial.')->middleware(['auth'])->group(function () {
        // Cadastros Financeiros
        Route::prefix('registration')->name('registration.')->group(function () {
            // Formas de Pagamento
            Route::get('payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods.index');
            Route::get('payment-methods/create', [PaymentMethodController::class, 'create'])->name('payment-methods.create');
            Route::post('payment-methods', [PaymentMethodController::class, 'store'])->name('payment-methods.store');
            Route::get('payment-methods/{paymentMethod}/edit', [PaymentMethodController::class, 'edit'])->name('payment-methods.edit');
            Route::put('payment-methods/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('payment-methods.update');
            Route::delete('payment-methods/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');
            
            // Planos de Pagamento
            Route::resource('payment-plans', PaymentPlanController::class);
            Route::post('payment-plans/{paymentPlan}/simulate', [PaymentPlanController::class, 'simulate'])
                ->name('payment-plans.simulate');

            // Agentes Financeiros
            Route::resource('agents', AgentController::class);
            Route::get('agents/export', [AgentController::class, 'export'])->name('agents.export');
            Route::post('agents/import', [AgentController::class, 'import'])->name('agents.import');
        });

        // Contas Financeiras
        Route::resource('accounts', FinancialAccountController::class);
        Route::post('accounts/{account}/pay', [FinancialAccountController::class, 'pay'])
            ->name('accounts.pay');

        // Relatórios Financeiros
        Route::prefix('reports')->name('reports.')->group(function () {
            // Rotas para relatório de agentes financeiros
            Route::get('agents', [FinancialAgentReportController::class, 'index'])->name('agents');
            Route::get('agents/export', [FinancialAgentReportController::class, 'export'])->name('agents.export');
            Route::get('agents/pdf', [FinancialAgentReportController::class, 'pdf'])->name('agents.pdf');
            
            // Rotas para importação de agentes financeiros
            Route::get('agents/import', [FinancialAgentReportController::class, 'showImportForm'])->name('agents.import.form');
            Route::post('agents/import', [FinancialAgentReportController::class, 'import'])->name('agents.import');
            Route::get('agents/template', [FinancialAgentReportController::class, 'exportTemplate'])->name('agents.template');
            
            // Rotas para exportação de relatórios financeiros
            Route::get('export/pdf', [ReportController::class, 'exportPDF'])->name('export.pdf');
            Route::get('export/excel', [ReportController::class, 'exportExcel'])->name('export.excel');
        });
        
        // Adiciona a rota principal de relatórios
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

        // Categorias Financeiras
        Route::resource('categories', FinancialCategoryController::class);
        
        // Previsão Financeira
        Route::get('forecast', [FinancialForecastController::class, 'index'])
            ->name('forecast.index');

        // Reconciliação Bancária
        Route::get('reconciliation', [FinancialReconciliationController::class, 'index'])
            ->name('reconciliation.index');
        Route::post('reconciliation', [FinancialReconciliationController::class, 'update'])
            ->name('reconciliation.update');
        Route::get('reconciliation/import', [FinancialReconciliationController::class, 'importForm'])
            ->name('reconciliation.import');
        Route::post('reconciliation/import', [FinancialReconciliationController::class, 'import'])
            ->name('reconciliation.process');

        // Centro de Custos
        Route::resource('cost-centers', CostCenterController::class);

        // Fluxo de Caixa Projetado
        Route::get('projections', [CashFlowProjectionController::class, 'index'])
            ->name('projections.index');

        // Metas Financeiras
        Route::resource('goals', FinancialGoalController::class);
        Route::post('goals/update-status', [FinancialGoalController::class, 'updateStatus'])
            ->name('goals.update-status');

        // Rotas para Orçamentos
        Route::prefix('budgets')->name('budgets.')->group(function () {
            Route::get('/', [BudgetController::class, 'index'])->name('index');
            Route::get('/create', [BudgetController::class, 'create'])->name('create');
            Route::post('/', [BudgetController::class, 'store'])->name('store');
            Route::get('/{budget}', [BudgetController::class, 'show'])->name('show');
            Route::get('/{budget}/edit', [BudgetController::class, 'edit'])->name('edit');
            Route::put('/{budget}', [BudgetController::class, 'update'])->name('update');
            Route::delete('/{budget}', [BudgetController::class, 'destroy'])->name('destroy');
            Route::get('/{budget}/pdf', [BudgetController::class, 'generatePdf'])->name('pdf');
            Route::get('/{budget}/print', [BudgetController::class, 'printView'])->name('print');
            Route::post('/{budget}/approve', [BudgetController::class, 'approve'])->name('approve');
            Route::post('/{budget}/reject', [BudgetController::class, 'reject'])->name('reject');
            Route::get('/{budget}/convert-receivable', [BudgetController::class, 'convertToReceivable'])->name('convert-receivable');
            Route::post('/{budget}/undo-convert-receivable', [BudgetController::class, 'undoConvertToReceivable'])->name('undo-convert-receivable');
        });
    });

    Route::get('/materials/search', [MaterialController::class, 'search'])->name('materials.search');

    // Adicione esta rota dentro do grupo de rotas protegidas
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::resource('materials', MaterialController::class);
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('approval-logs', [ApprovalLogController::class, 'index'])->name('approval-logs.index');
        Route::resource('company', CompanyController::class);
    });

    // Fornecedores
    // Route::resource('suppliers', SupplierController::class);

    // Funcionários
    Route::resource('employees', EmployeeController::class);

    // Empresas
    Route::resource('companies', CompanyController::class);

    // Rota para busca de CNPJ
    Route::get('/api/companies/find-cnpj/{cnpj}', [CompanyController::class, 'findByCNPJ'])->name('companies.find-cnpj');

    // Rota para busca de CNPJ
    // Route::get('/suppliers/find-cnpj/{cnpj}', [SupplierController::class, 'findByCNPJ'])->name('suppliers.find-cnpj');

    // Rotas de configurações
    Route::prefix('settings')->name('settings.')->group(function () {
        // Rotas de backup
        Route::get('/backup', [BackupController::class, 'index'])
            ->name('backup.index');
        Route::post('/backup', [BackupController::class, 'store'])
            ->name('backup.store');
        Route::post('/backup/create', [BackupController::class, 'createBackup'])
            ->name('backup.create');
        Route::get('/backup/download/{filename}', [BackupController::class, 'download'])
            ->name('backup.download');
        Route::get('/backup/delete/{filename}', [BackupController::class, 'delete'])
            ->name('backup.delete');
    });

    Route::post('/products/{product}/ajustar-estoque', [ProductController::class, 'ajustarEstoque'])
        ->name('products.ajustar-estoque');
});

// Rotas de diagnóstico do sistema
Route::get('diagnostic', [App\Http\Controllers\DiagnosticController::class, 'index'])->name('diagnostic.index');
Route::post('diagnostic/fix', [App\Http\Controllers\DiagnosticController::class, 'fix'])->name('diagnostic.fix');

// Rotas de diagnóstico (temporárias)
Route::get('/teste-diagnostico', function () {
    return view('test');
})->name('teste.diagnostico');

Route::get('/diagnostico-detalhado', function () {
    return view('diagnostic');
})->name('diagnostico.detalhado');

Route::get('/novo-orcamento-v2', function () {
    $numero = 'ORC-' . date('Y') . str_pad(\App\Models\Budget::count() + 1, 4, '0', STR_PAD_LEFT);
    $clients = \App\Models\Client::where('ativo', true)->get();
    $materiais = \App\Models\Product::where('ativo', true)->get();
    return view('financial.budgets.create_new', compact('numero', 'clients', 'materiais'));
})->name('novo.orcamento.v2'); 