<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Models\PaymentPlan;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentPlanController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentPlan::with('paymentMethod');

        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('status')) {
            $query->where('ativo', $request->status === 'ativo');
        }
        if ($request->filled('search')) {
            $query->where('nome', 'like', "%{$request->search}%");
        }

        $paymentPlans = $query->orderBy('nome')->paginate(10);
        return view('financial.registration.payment-plans.index', compact('paymentPlans'));
    }

    public function create()
    {
        $paymentMethods = PaymentMethod::where('ativo', true)->orderBy('nome')->get();
        return view('financial.registration.payment-plans.form', compact('paymentMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|max:100',
            'descricao' => 'nullable',
            'tipo' => 'required|in:venda,compra',
            'parcelas' => 'required|integer|min:1',
            'intervalo_dias' => 'required|integer|min:1',
            'carencia_dias' => 'required|integer|min:0',
            'taxa' => 'required|numeric|min:0',
            'multa_atraso' => 'required|numeric|min:0',
            'juros_atraso' => 'required|numeric|min:0',
            'permite_entrada' => 'boolean',
            'percentual_minimo_entrada' => 'required|numeric|min:0|max:100',
            'valor_minimo_parcela' => 'required|numeric|min:0',
            'limite_credito' => 'required|numeric|min:0',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'codigo_xml' => 'nullable|max:50',
            'disponivel_pdv' => 'boolean',
            'ordem_exibicao' => 'required|integer|min:0',
            'ativo' => 'boolean',
            'requer_aprovacao' => 'boolean',
            'restricoes' => 'nullable|array'
        ]);

        DB::beginTransaction();
        try {
            $paymentPlan = PaymentPlan::create($validated);

            // Salva as restrições
            if (!empty($validated['restricoes'])) {
                foreach ($validated['restricoes'] as $restricao) {
                    $paymentPlan->restrictions()->create($restricao);
                }
            }

            DB::commit();
            return redirect()
                ->route('financial.registration.payment-plans.index')
                ->with('success', 'Plano de pagamento cadastrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar plano de pagamento: ' . $e->getMessage());
        }
    }

    public function edit(PaymentPlan $paymentPlan)
    {
        $paymentPlan->load('restrictions');
        $paymentMethods = PaymentMethod::where('ativo', true)->orderBy('nome')->get();
        return view('financial.registration.payment-plans.form', compact('paymentPlan', 'paymentMethods'));
    }

    public function update(Request $request, PaymentPlan $paymentPlan)
    {
        $validated = $request->validate([
            'nome' => 'required|max:100',
            'descricao' => 'nullable',
            'tipo' => 'required|in:venda,compra',
            'parcelas' => 'required|integer|min:1',
            'intervalo_dias' => 'required|integer|min:1',
            'carencia_dias' => 'required|integer|min:0',
            'taxa' => 'required|numeric|min:0',
            'multa_atraso' => 'required|numeric|min:0',
            'juros_atraso' => 'required|numeric|min:0',
            'permite_entrada' => 'boolean',
            'percentual_minimo_entrada' => 'required|numeric|min:0|max:100',
            'valor_minimo_parcela' => 'required|numeric|min:0',
            'limite_credito' => 'required|numeric|min:0',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'codigo_xml' => 'nullable|max:50',
            'disponivel_pdv' => 'boolean',
            'ordem_exibicao' => 'required|integer|min:0',
            'ativo' => 'boolean',
            'requer_aprovacao' => 'boolean',
            'restricoes' => 'nullable|array'
        ]);

        DB::beginTransaction();
        try {
            $paymentPlan->update($validated);

            // Atualiza as restrições
            $paymentPlan->restrictions()->delete();
            if (!empty($validated['restricoes'])) {
                foreach ($validated['restricoes'] as $restricao) {
                    $paymentPlan->restrictions()->create($restricao);
                }
            }

            DB::commit();
            return redirect()
                ->route('financial.registration.payment-plans.index')
                ->with('success', 'Plano de pagamento atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar plano de pagamento: ' . $e->getMessage());
        }
    }

    public function destroy(PaymentPlan $paymentPlan)
    {
        try {
            $paymentPlan->delete();
            return redirect()
                ->route('financial.registration.payment-plans.index')
                ->with('success', 'Plano de pagamento removido com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao remover plano de pagamento: ' . $e->getMessage());
        }
    }

    public function simulate(Request $request, PaymentPlan $paymentPlan)
    {
        $request->validate([
            'valor' => 'required|numeric|min:0'
        ]);

        $parcelas = $paymentPlan->calcularParcelas($request->valor);
        
        if (!$parcelas) {
            return response()->json([
                'error' => 'Valor mínimo da parcela não atingido'
            ], 422);
        }

        return response()->json($parcelas);
    }
} 