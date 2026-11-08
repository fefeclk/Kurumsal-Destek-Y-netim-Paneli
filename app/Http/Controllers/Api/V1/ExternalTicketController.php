<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Services\TicketService;

class ExternalTicketController extends Controller
{
    public function __construct(protected TicketService $service) {}

    // GET /api/v1/external/tickets -> Hocanın veri çekeceği uç nokta (200 OK)
    public function index()
    {
        $tickets =$this->service->tumTicketlariGetir();

        return TicketResource::collection($tickets)->additional([
            'meta_analiz' => [
                'proje_adi' => 'Kurumsal Akıllı Destek ve Karar Otomasyonu',
                'toplam_kayit' => Ticket::count(),
                'kritik_vaka_sayisi' => Ticket::where('onem_seviyesi', 'Kritik')->count(),
            ]
        ]);
    }

    // GET /api/v1/external/tickets/{id} -> Tekil kayıt (200 OK)
    public function show(Ticket $ticket)
    {
        return new TicketResource($ticket);
    }

    // POST /api/v1/external/tickets -> Yeni kayıt ekleme (201 Created)
    public function store(StoreTicketRequest $request)
    {
        $ticket = $this->service->yeniTicketOlustur($request->validated());
        return (new TicketResource($ticket))->response()->setStatusCode(201);
    }
}