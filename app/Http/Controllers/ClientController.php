<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Rules\ValidCpfCnpj;
use App\Rules\ValidPhone;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('nome')->paginate(10);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => ['nullable', 'max:20', new ValidPhone],
            'cpf_cnpj' => ['nullable', 'max:20', new ValidCpfCnpj],
            'rg_ie' => 'nullable|max:20',
            'endereco' => 'nullable|max:255',
            'numero' => 'nullable|max:20',
            'complemento' => 'nullable|max:255',
            'bairro' => 'nullable|max:255',
            'cidade' => 'nullable|max:255',
            'estado' => 'nullable|max:2',
            'cep' => 'nullable|max:9',
            'observacoes' => 'nullable',
            'ativo' => 'boolean'
        ]);

        if (isset($validated['telefone'])) {
            $validated['telefone'] = preg_replace('/[^0-9]/', '', $validated['telefone']);
        }
        if (isset($validated['cpf_cnpj'])) {
            $validated['cpf_cnpj'] = preg_replace('/[^0-9]/', '', $validated['cpf_cnpj']);
        }
        if (isset($validated['cep'])) {
            $validated['cep'] = preg_replace('/[^0-9]/', '', $validated['cep']);
        }

        Client::create($validated);

        return redirect()->route('clients.index')
                        ->with('success', 'Cliente cadastrado com sucesso!');
    }

    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'nome' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => ['nullable', 'max:20', new ValidPhone],
            'cpf_cnpj' => ['nullable', 'max:20', new ValidCpfCnpj],
            'rg_ie' => 'nullable|max:20',
            'endereco' => 'nullable|max:255',
            'numero' => 'nullable|max:20',
            'complemento' => 'nullable|max:255',
            'bairro' => 'nullable|max:255',
            'cidade' => 'nullable|max:255',
            'estado' => 'nullable|max:2',
            'cep' => 'nullable|max:9',
            'observacoes' => 'nullable',
            'ativo' => 'boolean'
        ]);

        if (isset($validated['telefone'])) {
            $validated['telefone'] = preg_replace('/[^0-9]/', '', $validated['telefone']);
        }
        if (isset($validated['cpf_cnpj'])) {
            $validated['cpf_cnpj'] = preg_replace('/[^0-9]/', '', $validated['cpf_cnpj']);
        }
        if (isset($validated['cep'])) {
            $validated['cep'] = preg_replace('/[^0-9]/', '', $validated['cep']);
        }

        $client->update($validated);

        return redirect()->route('clients.index')
                        ->with('success', 'Cliente atualizado com sucesso!');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
                        ->with('success', 'Cliente removido com sucesso!');
    }
} 