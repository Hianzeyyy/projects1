<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\Medicine;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationService
{
    /**
     * Create a reservation and lock inventory.
     */
    public function createReservation(array $data): Reservation
    {
        return DB::transaction(function () use ($data) {
            $reservation = Reservation::create([
                'customer_id' => $data['customer_id'],
                'status' => 'pending',
                'reserved_at' => now(),
                'expires_at' => Carbon::now()->addHours(24), // 24h expiry
            ]);

            foreach ($data['items'] as $item) {
                ReservationItem::create([
                    'reservation_id' => $reservation->id,
                    'medicine_id' => $item['medicine_id'],
                    'quantity' => $item['quantity'],
                ]);
                // Lock inventory
                $medicine = Medicine::find($item['medicine_id']);
                $medicine->decrement('stock', $item['quantity']);
            }

            return $reservation;
        });
    }

    /**
     * Update reservation status and unlock inventory if expired.
     */
    public function expireReservations()
    {
        $expired = Reservation::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->get();
        foreach ($expired as $reservation) {
            foreach ($reservation->items as $item) {
                $medicine = Medicine::find($item->medicine_id);
                $medicine->increment('stock', $item->quantity);
            }
            $reservation->status = 'expired';
            $reservation->save();
        }
    }
}
