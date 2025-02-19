<?php

namespace App\Services;

class OFXParser
{
    protected $content;
    protected $transactions = [];

    public function __construct($content)
    {
        $this->content = $content;
        $this->parse();
    }

    protected function parse()
    {
        // Limpa o cabeçalho SGML/XML
        $content = preg_replace('/<\?ofx[^>]*>/', '', $this->content);
        
        // Converte tags OFX para XML
        $content = preg_replace('/<([^>]*)>([^<]*)/','<\1>\2</\1>', $content);
        
        // Carrega como XML
        $xml = simplexml_load_string($content);

        // Busca as transações
        if (isset($xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKTRANLIST)) {
            foreach ($xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKTRANLIST->STMTTRN as $transaction) {
                $this->transactions[] = [
                    'data' => $this->parseDate((string)$transaction->DTPOSTED),
                    'valor' => $this->parseAmount((string)$transaction->TRNAMT),
                    'tipo' => ((string)$transaction->TRNAMT > 0) ? 'receita' : 'despesa',
                    'descricao' => (string)$transaction->MEMO,
                    'documento' => (string)$transaction->CHECKNUM,
                ];
            }
        }
    }

    protected function parseDate($date)
    {
        // Converte data do formato OFX (YYYYMMDD) para Y-m-d
        return date('Y-m-d', strtotime(substr($date, 0, 8)));
    }

    protected function parseAmount($amount)
    {
        // Converte valor para formato decimal
        return abs((float)$amount);
    }

    public function getTransactions()
    {
        return $this->transactions;
    }
} 