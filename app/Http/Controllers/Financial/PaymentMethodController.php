<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\FinancialCategory;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('nome')->paginate(10);
        return view('financial.registration.payment-methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        $categories = FinancialCategory::where('tipo', 'receita')
            ->where('ativo', true)
            ->orderBy('nome')
            ->get();
        return view('financial.registration.payment-methods.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|max:100',
            'tipo' => 'required|in:dinheiro,pix,cartao,boleto,outros',
            'categoria_financeira_id' => 'required|exists:financial_categories,id',
            'ativo' => 'boolean',
            'parcelas_padrao' => 'required|integer|min:1',
            'taxa_padrao' => 'required|numeric|min:0'
        ]);

        // Se for dinheiro ou pix, força pagamento à vista
        if (in_array($validated['tipo'], ['dinheiro', 'pix'])) {
            $validated['parcelas_padrao'] = 1;
            $validated['taxa_padrao'] = 0;
        }

        PaymentMethod::create($validated);
        return redirect()
            ->route('financial.registration.payment-methods.index')
            ->with('success', 'Forma de pagamento cadastrada com sucesso!');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        $categories = FinancialCategory::where('tipo', 'receita')
            ->where('ativo', true)
            ->orderBy('nome')
            ->get();
        return view('financial.registration.payment-methods.form', compact('paymentMethod', 'categories'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'nome' => 'required|max:100',
            'tipo' => 'required|in:dinheiro,pix,cartao,boleto,outros',
            'categoria_financeira_id' => 'required|exists:financial_categories,id',
            'ativo' => 'boolean',
            'parcelas_padrao' => 'required|integer|min:1',
            'taxa_padrao' => 'required|numeric|min:0'
        ]);

        // Se for dinheiro ou pix, força pagamento à vista
        if (in_array($validated['tipo'], ['dinheiro', 'pix'])) {
            $validated['parcelas_padrao'] = 1;
            $validated['taxa_padrao'] = 0;
        }

        $paymentMethod->update($validated);
        return redirect()
            ->route('financial.registration.payment-methods.index')
            ->with('success', 'Forma de pagamento atualizada com sucesso!');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return redirect()
            ->route('financial.registration.payment-methods.index')
            ->with('success', 'Forma de pagamento removida com sucesso!');
    }
} 