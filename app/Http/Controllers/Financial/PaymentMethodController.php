<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\FinancialCategory;
use App\Models\FinancialAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    public function index()
    {
        try {
            $paymentMethods = PaymentMethod::with(['categoriaFinanceira', 'financialAgent'])
                ->orderBy('nome')
                ->paginate(10);

            return view('financial.registration.payment-methods.index', compact('paymentMethods'));
        } catch (\Exception $e) {
            Log::error('Erro ao listar formas de pagamento: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao listar formas de pagamento.');
        }
    }

    public function create()
    {
        try {
            $financialAgents = FinancialAgent::ativos()
                ->orderBy('nome')
                ->get();
                
            $categories = FinancialCategory::orderBy('nome')->get();

            return view('financial.registration.payment-methods.form', compact('financialAgents', 'categories'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar formulário de forma de pagamento: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao carregar formulário.');
        }
    }

    public function store(Request $request)
    {
        try {
            // Log de depuração - dados recebidos
            Log::info('Dados recebidos para nova forma de pagamento:', $request->all());
            
            // Se o tipo não estiver preenchido mas especie_documento sim, usa o valor de especie_documento
            if (!$request->filled('tipo') && $request->filled('especie_documento')) {
                $request->merge(['tipo' => $request->especie_documento]);
                Log::info('Tipo definido a partir de especie_documento:', ['tipo' => $request->tipo]);
            }
            
            // Converte campos checkbox "on" para boolean
            $booleanFields = [
                'ativo', 'gera_financeiro', 'permite_troco', 'controle_cartao',
                'movimenta_conta_corrente', 'emite_comprovantes_vinculados', 'envia_pdv',
                'sangria_automatica', 'pagamento', 'pin_pad'
            ];
            
            foreach ($booleanFields as $field) {
                if ($request->has($field)) {
                    $request->merge([$field => $request->input($field) === 'on']);
                } else {
                    $request->merge([$field => false]);
                }
            }
            
            Log::info('Dados após conversão de campos booleanos:', $request->all());
            
            $validated = $request->validate([
                'nome' => 'required|max:100',
                'codigo' => 'nullable|max:10',
                'descricao' => 'nullable|max:500',
                'tipo' => 'required|string|max:50',
                'categoria_financeira_id' => 'required|exists:financial_categories,id',
                'financial_agent_id' => 'nullable|exists:financial_agents,id',
                'parcelas_padrao' => 'required|integer|min:1',
                'taxa_padrao' => 'required|numeric|min:0',
                'emite_comprovantes_vinculados' => 'boolean',
                'envia_pdv' => 'boolean',
                'especie_pdv' => 'nullable|string|max:50',
                'tipo_cliente' => 'nullable|string|max:50',
                'prazo' => 'nullable|integer|min:0',
                'identificador' => 'nullable|string|max:50',
                'contas_corrente' => 'nullable|string'
            ], [
                'nome.required' => 'O nome da forma de pagamento é obrigatório.',
                'tipo.required' => 'O tipo da forma de pagamento é obrigatório.',
                'categoria_financeira_id.required' => 'A categoria financeira é obrigatória.',
                'parcelas_padrao.required' => 'O número de parcelas padrão é obrigatório.',
                'taxa_padrao.required' => 'A taxa padrão é obrigatória.'
            ]);

            // Log de depuração - dados validados
            Log::info('Dados validados para forma de pagamento:', $validated);
            
            DB::beginTransaction();

            // Se for dinheiro ou pix, força pagamento à vista
            if (in_array($validated['tipo'], ['dinheiro', 'pix'])) {
                $validated['parcelas_padrao'] = 1;
                $validated['taxa_padrao'] = 0;
            }

            // Gera o código automaticamente se não for fornecido
            if (empty($validated['codigo'])) {
                Log::info('Gerando código automático para forma de pagamento. Tipo: ' . $validated['tipo'] . ', Nome: ' . $validated['nome']);
                $validated['codigo'] = $this->gerarCodigoFormaPagamento($validated['tipo'], $validated['nome']);
                Log::info('Código gerado: ' . $validated['codigo']);
            } else {
                // Verifica se o código fornecido excede o tamanho máximo
                if (strlen($validated['codigo']) > 10) {
                    $validated['codigo'] = substr($validated['codigo'], 0, 10);
                    Log::info('Código fornecido truncado para respeitar limite de 10 caracteres: ' . $validated['codigo']);
                }
            }

            // Verifica se o código já existe
            if (PaymentMethod::where('codigo', $validated['codigo'])->exists()) {
                Log::info('Código já existe, gerando novo código: ' . $validated['codigo']);
                $validated['codigo'] = $this->gerarCodigoFormaPagamento($validated['tipo'], $validated['nome'], true);
                Log::info('Novo código gerado: ' . $validated['codigo']);
            }

            // Log dos dados finais antes de criar
            Log::info('Dados finais para criar forma de pagamento:', $validated);
            
            $paymentMethod = PaymentMethod::create($validated);

            // Processa as contas correntes se existirem
            $contasCorrentes = null;
            if (isset($validated['contas_corrente'])) {
                $contasCorrentes = json_decode($validated['contas_corrente'], true);
                unset($validated['contas_corrente']);
            }

            if ($contasCorrentes && is_array($contasCorrentes)) {
                foreach ($contasCorrentes as $key => $conta) {
                    if (isset($conta['lojaId']) && isset($conta['contaId'])) {
                        $paymentMethod->contasCorrentes()->create([
                            'payment_method_id' => $paymentMethod->id,
                            'store_id' => $conta['lojaId'],
                            'current_account_id' => $conta['contaId'],
                            'ativo' => true,
                            'principal' => ($key === 0) // A primeira conta é definida como principal
                        ]);
                        
                        Log::info('Conta corrente adicionada à forma de pagamento', [
                            'forma_pagamento_id' => $paymentMethod->id,
                            'loja_id' => $conta['lojaId'],
                            'conta_id' => $conta['contaId']
                        ]);
                    }
                }
            }

            DB::commit();

            Log::info('Forma de pagamento cadastrada com sucesso', [
                'id' => $paymentMethod->id,
                'nome' => $paymentMethod->nome,
                'codigo' => $paymentMethod->codigo
            ]);

            return redirect()
                ->route('financial.registration.payment-methods.index')
                ->with('success', 'Forma de pagamento cadastrada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao cadastrar forma de pagamento: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar forma de pagamento. ' . $e->getMessage());
        }
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        try {
            $financialAgents = FinancialAgent::ativos()
                ->orderBy('nome')
                ->get();
            
            $categories = FinancialCategory::orderBy('nome')->get();
            
            return view('financial.registration.payment-methods.form', compact('paymentMethod', 'financialAgents', 'categories'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar formulário de edição: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao carregar formulário de edição.');
        }
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        try {
            // Log de depuração - dados recebidos
            Log::info('Dados recebidos para atualizar forma de pagamento:', $request->all());
            
            // Se o tipo não estiver preenchido mas especie_documento sim, usa o valor de especie_documento
            if (!$request->filled('tipo') && $request->filled('especie_documento')) {
                $request->merge(['tipo' => $request->especie_documento]);
                Log::info('Tipo definido a partir de especie_documento:', ['tipo' => $request->tipo]);
            }
            
            // Converte campos checkbox "on" para boolean
            $booleanFields = [
                'ativo', 'gera_financeiro', 'permite_troco', 'controle_cartao',
                'movimenta_conta_corrente', 'emite_comprovantes_vinculados', 'envia_pdv',
                'sangria_automatica', 'pagamento', 'pin_pad'
            ];
            
            foreach ($booleanFields as $field) {
                if ($request->has($field)) {
                    $request->merge([$field => $request->input($field) === 'on']);
                } else {
                    $request->merge([$field => false]);
                }
            }
            
            Log::info('Dados após conversão de campos booleanos:', $request->all());
            
            $validated = $request->validate([
                'nome' => 'required|max:100',
                'codigo' => [
                    'nullable',
                    'max:10',
                    Rule::unique('payment_methods')->ignore($paymentMethod->id)
                ],
                'descricao' => 'nullable|max:500',
                'tipo' => 'required|string|max:50',
                'categoria_financeira_id' => 'required|exists:financial_categories,id',
                'financial_agent_id' => 'nullable|exists:financial_agents,id',
                'parcelas_padrao' => 'required|integer|min:1',
                'taxa_padrao' => 'required|numeric|min:0',
                'emite_comprovantes_vinculados' => 'boolean',
                'envia_pdv' => 'boolean',
                'especie_pdv' => 'nullable|string|max:50',
                'tipo_cliente' => 'nullable|string|max:50',
                'prazo' => 'nullable|integer|min:0',
                'identificador' => 'nullable|string|max:50'
            ], [
                'nome.required' => 'O nome da forma de pagamento é obrigatório.',
                'tipo.required' => 'O tipo da forma de pagamento é obrigatório.',
                'categoria_financeira_id.required' => 'A categoria financeira é obrigatória.',
                'parcelas_padrao.required' => 'O número de parcelas padrão é obrigatório.',
                'taxa_padrao.required' => 'A taxa padrão é obrigatória.'
            ]);

            DB::beginTransaction();

            // Se for dinheiro ou pix, força pagamento à vista
            if (in_array($validated['tipo'], ['dinheiro', 'pix'])) {
                $validated['parcelas_padrao'] = 1;
                $validated['taxa_padrao'] = 0;
            }

            // Gera o código automaticamente se não for fornecido
            if (empty($validated['codigo'])) {
                $validated['codigo'] = $this->gerarCodigoFormaPagamento($validated['tipo'], $validated['nome']);
            } else {
                // Verifica se o código fornecido excede o tamanho máximo
                if (strlen($validated['codigo']) > 10) {
                    $validated['codigo'] = substr($validated['codigo'], 0, 10);
                    Log::info('Código fornecido truncado para respeitar limite de 10 caracteres: ' . $validated['codigo']);
                }
            }

            // Verifica se o código já existe (apenas se foi alterado)
            if ($validated['codigo'] !== $paymentMethod->codigo && 
                PaymentMethod::where('codigo', $validated['codigo'])->exists()) {
                $validated['codigo'] = $this->gerarCodigoFormaPagamento($validated['tipo'], $validated['nome'], true);
            }

            $paymentMethod->update($validated);

            DB::commit();

            Log::info('Forma de pagamento atualizada com sucesso', [
                'id' => $paymentMethod->id,
                'nome' => $paymentMethod->nome,
                'codigo' => $paymentMethod->codigo
            ]);

            return redirect()
                ->route('financial.registration.payment-methods.index')
                ->with('success', 'Forma de pagamento atualizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar forma de pagamento: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar forma de pagamento. ' . $e->getMessage());
        }
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        try {
            DB::beginTransaction();

            $paymentMethod->delete();

            DB::commit();

            Log::info('Forma de pagamento removida com sucesso', [
                'id' => $paymentMethod->id,
                'nome' => $paymentMethod->nome
            ]);

            return redirect()
                ->route('financial.registration.payment-methods.index')
                ->with('success', 'Forma de pagamento removida com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao remover forma de pagamento: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Erro ao remover forma de pagamento. ' . $e->getMessage());
        }
    }

    /**
     * Gera um código único para a forma de pagamento baseado no tipo e/ou nome
     * 
     * @param string $tipo Tipo da forma de pagamento
     * @param string|null $nome Nome da forma de pagamento
     * @param bool $forceNew Força a geração de um novo código
     * @return string
     */
    private function gerarCodigoFormaPagamento($tipo, $nome = null, $forceNew = false)
    {
        // Log do início da geração do código
        Log::info('Iniciando geração de código. Tipo: ' . $tipo . ', Nome: ' . $nome . ', ForceNew: ' . ($forceNew ? 'true' : 'false'));
        
        // Prefixos para os tipos comuns
        $prefixos = [
            'dinheiro' => 'DIN',
            'pix' => 'PIX',
            'cartao' => 'CAR',
            'boleto' => 'BOL',
            'outros' => 'OUT'
        ];

        // Se tiver um prefixo definido para o tipo, usa ele
        if (isset($prefixos[$tipo])) {
            $prefix = $prefixos[$tipo];
            Log::info('Usando prefixo baseado no tipo: ' . $prefix);
        } 
        // Caso contrário, tenta usar as iniciais do nome
        elseif ($nome && !empty(trim($nome))) {
            // Pega as iniciais do nome (até 3 caracteres)
            $palavras = explode(' ', preg_replace('/\s+/', ' ', trim($nome)));
            $prefix = '';
            
            Log::info('Palavras do nome: ' . implode(', ', $palavras));
            
            foreach ($palavras as $palavra) {
                if (strlen($prefix) < 3 && !empty(trim($palavra))) {
                    $prefix .= strtoupper(substr(trim($palavra), 0, 1));
                }
            }
            
            Log::info('Prefixo inicial baseado nas iniciais: ' . $prefix);
            
            // Se não conseguiu 3 caracteres, completa com o início da primeira palavra
            if (strlen($prefix) < 3 && !empty($palavras[0])) {
                $complemento = strtoupper(substr($palavras[0], 1, 3 - strlen($prefix)));
                $prefix .= $complemento;
                Log::info('Complementando prefixo com: ' . $complemento);
            }
            
            // Se ainda não tem 3 caracteres, completa com X
            while (strlen($prefix) < 3) {
                $prefix .= 'X';
            }
            
            Log::info('Prefixo final baseado no nome: ' . $prefix);
        } 
        // Último caso, usa 'OUT' de outros
        else {
            $prefix = 'OUT';
            Log::info('Usando prefixo padrão: ' . $prefix);
        }

        // Limita a 3 caracteres
        $prefix = substr($prefix, 0, 3);

        // Busca o último código com este prefixo
        $ultimoCodigo = PaymentMethod::where('codigo', 'like', $prefix . '%')
            ->orderBy('codigo', 'desc')
            ->value('codigo');
        
        Log::info('Último código encontrado com este prefixo: ' . ($ultimoCodigo ?: 'nenhum'));
        
        if ($ultimoCodigo && !$forceNew) {
            // Extrai a parte numérica - assume que os últimos caracteres são números
            preg_match('/(\d+)$/', $ultimoCodigo, $matches);
            $numero = isset($matches[1]) ? (int)$matches[1] : 0;
            // Garante que o número sequencial não ultrapasse o tamanho máximo (10 - 3 = 7 caracteres)
            $maxSequencial = min($numero + 1, 9999999);
            $novoCodigo = $prefix . str_pad($maxSequencial, 2, '0', STR_PAD_LEFT);
            Log::info('Número extraído: ' . $numero . ', Novo código sequencial: ' . $novoCodigo);
        } else {
            // Primeiro código com este prefixo ou forçando novo
            $novoCodigo = $prefix . '01';
            Log::info('Criando primeiro código para o prefixo: ' . $novoCodigo);
        }

        // Verifica se o código gerado existe e incrementa até encontrar um disponível
        $tentativas = 1;
        $codigoOriginal = $novoCodigo;
        
        while (PaymentMethod::where('codigo', $novoCodigo)->exists() && $tentativas < 100) {
            $tentativas++;
            // Extrai o prefixo e o número
            $prefixo = substr($novoCodigo, 0, 3);
            preg_match('/(\d+)$/', $novoCodigo, $matches);
            $numero = isset($matches[1]) ? (int)$matches[1] : 0;
            
            // Incrementa o número
            $novoCodigo = $prefixo . str_pad($numero + 1, 2, '0', STR_PAD_LEFT);
            Log::info('Código já existe, tentativa ' . $tentativas . ': ' . $novoCodigo);
        }
        
        if ($novoCodigo !== $codigoOriginal) {
            Log::info('Código final após verificações: ' . $novoCodigo);
        }

        // Garantir que o código não exceda 10 caracteres
        if (strlen($novoCodigo) > 10) {
            $novoCodigo = substr($novoCodigo, 0, 10);
            Log::info('Código truncado para respeitar limite de 10 caracteres: ' . $novoCodigo);
        }

        return $novoCodigo;
    }
} 