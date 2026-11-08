<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Ticket $ticket): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return false;
    }

    // DÜZELTİLDİ: Sadece is_admin yetkisi olanlar silebilir
    public function delete(User $user, Ticket $ticket)
    {
        if ($user->is_admin) {
            return true;
        }

        return false;
    }

    public function restore(User $user, Ticket $ticket): bool
    {
        return false;
    }

    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return false;
    }
}