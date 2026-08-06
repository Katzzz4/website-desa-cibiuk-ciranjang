<?php

namespace App\Http\Controllers;

use App\Models\Agenda;

class AgendaController extends Controller
{
    public function index()
    {
        $mendatang = Agenda::where('tanggal_mulai', '>=', now()->startOfDay())
            ->orderBy('tanggal_mulai')
            ->get();

        $lampau = Agenda::where('tanggal_mulai', '<', now()->startOfDay())
            ->orderByDesc('tanggal_mulai')
            ->paginate(8);

        return view('agenda.index', compact('mendatang', 'lampau'));
    }
}