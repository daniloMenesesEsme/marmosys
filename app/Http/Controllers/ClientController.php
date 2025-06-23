<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Location;
use App\Models\EstablishmentType;
use Illuminate\Http\Request;
use App\Rules\ValidCpfCnpj;
use App\Rules\ValidPhone;
use App\Rules\ValidCep;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Display a listing of clients with pagination
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $clients = Client::with(['location', 'establishmentType'])
            ->orderBy('nome')
            ->paginate(10);

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            Log::info('Acessando formulário de criação de cliente');
            
            $locations = Location::active()
                ->orderBy('name')
                ->get(['id', 'name']);
                
            $establishmentTypes = EstablishmentType::active()
                ->orderBy('name')
                ->get(['id', 'name']);

            return view('clients.create', compact('locations', 'establishmentTypes'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar formulário de cliente', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->route('clients.index')
                ->with('error', 'Erro ao carregar formulário. Tente novamente.');
        }
    }

    /**
     * Store a newly created client
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Log::info('Iniciando criação de novo cliente', ['dados' => $request->all()]);

        try {
            // Validação principal
            $validated = $request->validate([
                'cpf_cnpj' => ['required', 'string', new ValidCpfCnpj],
                'nome' => ['required', 'string', 'max:255'],
                'empresa' => ['nullable', 'string', 'max:255'],
                'email' => ['nullable', 'email', 'max:255'],
                'telefone' => ['required', 'string', new ValidPhone],
                'contato' => ['nullable', 'string', 'max:255'],
                'cep' => ['required', 'string', new ValidCep],
                'endereco' => ['required', 'string', 'max:255'],
                'numero' => ['required', 'string', 'max:20'],
                'complemento' => ['nullable', 'string', 'max:255'],
                'bairro' => ['required', 'string', 'max:255'],
                'cidade' => ['required', 'string', 'max:255'],
                'estado' => [
                    'required', 
                    'string', 
                    'size:2',
                    Rule::in(['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'])
                ],
                'latitude' => ['required', 'numeric', 'between:-90,90'],
                'longitude' => ['required', 'numeric', 'between:-180,180'],
                'location_id' => ['required', 'exists:locations,id'],
                'establishment_type_id' => ['required', 'exists:establishment_types,id'],
                'observacao' => ['nullable', 'string', 'max:1000']
            ]);

            Log::info('Dados validados com sucesso', ['validated' => $validated]);

            // Processamento do CNPJ
            if (strlen(preg_replace('/\D/', '', $validated['cpf_cnpj'])) === 14) {
                $cnpjData = $this->consultarCNPJ($validated['cpf_cnpj']);
                if ($cnpjData && isset($cnpjData['status']) && $cnpjData['status'] === 'OK') {
                    $this->validarDadosCNPJ($cnpjData, $validated);
                }
            }

            // Processamento do CEP
            $cepData = $this->consultarCEP($validated['cep']);
            if ($cepData && !isset($cepData['erro'])) {
                $this->validarDadosCEP($cepData, $validated);
            }

            // Criação do cliente com dados sanitizados
            $client = Client::create(array_merge($validated, [
                'cpf_cnpj' => preg_replace('/\D/', '', $validated['cpf_cnpj']),
                'telefone' => preg_replace('/\D/', '', $validated['telefone']),
                'cep' => preg_replace('/\D/', '', $validated['cep']),
                'ativo' => true
            ]));

            Log::info('Cliente criado com sucesso', ['client' => $client->toArray()]);

            return redirect()
                ->route('clients.index')
                ->with('success', 'Cliente cadastrado com sucesso!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erro de validação', [
                'errors' => $e->errors(),
                'data' => $request->all()
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Erro ao criar cliente', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar cliente: ' . $e->getMessage());
        }
    }

    /**
     * Consulta dados do CNPJ na API
     *
     * @param string $cnpj
     * @return array|null
     */
    private function consultarCNPJ($cnpj)
    {
        try {
            $cnpj = preg_replace('/\D/', '', $cnpj);
            Log::info('Consultando CNPJ', ['cnpj' => $cnpj]);

            $response = Http::timeout(5)
                ->retry(2, 100)
                ->get("https://receitaws.com.br/v1/cnpj/{$cnpj}");

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Resposta da API de CNPJ', ['data' => $data]);
                return $data;
            }

            Log::warning('Falha na consulta do CNPJ', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('Erro ao consultar CNPJ', [
                'error' => $e->getMessage(),
                'cnpj' => $cnpj
            ]);
            return null;
        }
    }

    /**
     * Consulta dados do CEP na API
     *
     * @param string $cep
     * @return array|null
     */
    private function consultarCEP($cep)
    {
        try {
            $cep = preg_replace('/\D/', '', $cep);
            Log::info('Consultando CEP', ['cep' => $cep]);

            $response = Http::timeout(5)
                ->retry(2, 100)
                ->get("https://viacep.com.br/ws/{$cep}/json/");

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Resposta da API de CEP', ['data' => $data]);
                return $data;
            }

            Log::warning('Falha na consulta do CEP', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('Erro ao consultar CEP', [
                'error' => $e->getMessage(),
                'cep' => $cep
            ]);
            return null;
        }
    }

    /**
     * Valida dados retornados da API de CNPJ
     *
     * @param array $cnpjData
     * @param array $validated
     * @return void
     */
    private function validarDadosCNPJ($cnpjData, &$validated)
    {
        if ($cnpjData['situacao'] !== 'ATIVA') {
            Log::warning('CNPJ não está ativo', ['cnpj' => $cnpjData['cnpj']]);
            throw new \Exception('CNPJ não está ativo na Receita Federal');
        }

        // Verifica inconsistências nos dados
        if ($validated['nome'] !== $cnpjData['nome']) {
            Log::warning('Divergência na razão social', [
                'informado' => $validated['nome'],
                'receita' => $cnpjData['nome']
            ]);
        }

        if ($validated['empresa'] !== $cnpjData['fantasia']) {
            Log::warning('Divergência no nome fantasia', [
                'informado' => $validated['empresa'],
                'receita' => $cnpjData['fantasia']
            ]);
        }
    }

    /**
     * Valida dados retornados da API de CEP
     *
     * @param array $cepData
     * @param array $validated
     * @return void
     */
    private function validarDadosCEP($cepData, &$validated)
    {
        // Verifica inconsistências nos dados
        if ($validated['cidade'] !== $cepData['localidade']) {
            Log::warning('Divergência na cidade', [
                'informado' => $validated['cidade'],
                'correios' => $cepData['localidade']
            ]);
        }

        if ($validated['estado'] !== $cepData['uf']) {
            Log::warning('Divergência no estado', [
                'informado' => $validated['estado'],
                'correios' => $cepData['uf']
            ]);
        }

        if ($validated['bairro'] !== $cepData['bairro']) {
            Log::warning('Divergência no bairro', [
                'informado' => $validated['bairro'],
                'correios' => $cepData['bairro']
            ]);
        }
    }

    /**
     * Display the specified client
     *
     * @param Client $client
     * @return \Illuminate\View\View
     */
    public function show(Client $client)
    {
        $client->load(['location', 'establishmentType']);
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified client
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $client = Client::with(['location', 'establishmentType'])
                ->findOrFail($id);
            
            $locations = Location::active()
                ->orderBy('name')
                ->get(['id', 'name']);
                
            $establishmentTypes = EstablishmentType::active()
                ->orderBy('name')
                ->get(['id', 'name']);

            return view('clients.edit', compact('client', 'locations', 'establishmentTypes'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar formulário de edição', [
                'error' => $e->getMessage(),
                'client_id' => $id
            ]);
            
            return redirect()
                ->route('clients.index')
                ->with('error', 'Cliente não encontrado.');
        }
    }

    /**
     * Update the specified client
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        Log::info('Iniciando atualização de cliente', [
            'client_id' => $id,
            'dados' => $request->all()
        ]);

        try {
            $client = Client::findOrFail($id);

            // Validação principal
            $validated = $request->validate([
                'nome' => ['required', 'string', 'max:255'],
                'empresa' => ['nullable', 'string', 'max:255'],
                'email' => ['nullable', 'email', 'max:255'],
                'telefone' => ['required', 'string', new ValidPhone],
                'contato' => ['nullable', 'string', 'max:255'],
                'cep' => ['required', 'string', new ValidCep],
                'endereco' => ['required', 'string', 'max:255'],
                'numero' => ['required', 'string', 'max:20'],
                'complemento' => ['nullable', 'string', 'max:255'],
                'bairro' => ['required', 'string', 'max:255'],
                'cidade' => ['required', 'string', 'max:255'],
                'estado' => [
                    'required', 
                    'string', 
                    'size:2',
                    Rule::in(['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'])
                ],
                'latitude' => ['required', 'numeric', 'between:-90,90'],
                'longitude' => ['required', 'numeric', 'between:-180,180'],
                'location_id' => ['required', 'exists:locations,id'],
                'establishment_type_id' => ['required', 'exists:establishment_types,id'],
                'observacao' => ['nullable', 'string', 'max:1000']
            ]);

            // Atualiza o cliente com dados sanitizados
            $client->update(array_merge($validated, [
                'telefone' => preg_replace('/\D/', '', $validated['telefone']),
                'cep' => preg_replace('/\D/', '', $validated['cep'])
            ]));

            Log::info('Cliente atualizado com sucesso', ['client' => $client->toArray()]);

            return redirect()
                ->route('clients.index')
                ->with('success', 'Cliente atualizado com sucesso!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erro de validação na atualização', [
                'errors' => $e->errors(),
                'data' => $request->all()
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar cliente', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'client_id' => $id,
                'data' => $request->all()
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar cliente: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified client
     *
     * @param Client $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Client $client)
    {
        try {
            Log::info('Iniciando remoção de cliente', ['client_id' => $client->id]);
            
            $client->delete();
            
            Log::info('Cliente removido com sucesso', ['client_id' => $client->id]);

            return redirect()
                ->route('clients.index')
                ->with('success', 'Cliente removido com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao remover cliente', [
                'error' => $e->getMessage(),
                'client_id' => $client->id
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Erro ao remover cliente: ' . $e->getMessage());
        }
    }
} 