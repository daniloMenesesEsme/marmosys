<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('nome')->paginate(10);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|max:20',
            'cpf_cnpj' => 'nullable|max:20',
            'endereco' => 'nullable'
        ]);

        Client::create($validated);

        return redirect()->route('clients.index')
                        ->with('success', 'Cliente cadastrado com sucesso!');
    }

    public function edit(Client $client)
    {
        return view('clients.form', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'nome' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|max:20',
            'cpf_cnpj' => 'nullable|max:20',
            'endereco' => 'nullable',
            'ativo' => 'boolean'
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')
                        ->with('success', 'Cliente atualizado com sucesso!');
    }

    public function destroy(Client $client)
    {
        $client->ativo = false;
        $client->save();

        return redirect()->route('clients.index')
                        ->with('success', 'Cliente desativado com sucesso!');
    }
} 