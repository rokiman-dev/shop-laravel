<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
    'user_id',
    'tipe_order',
    'tanggal_order',
    'total_order',
    'status_order',
    'alamat_kirim',
    'midtrans_order_id', // ⬅️ Tambahkan ini
];

    // Relasi ke User (bisa customer/mitra, tergantung role di tabel users)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke item pesanan
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    // Jika ada relasi ke pembayaran
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id');
    }
}