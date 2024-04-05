<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Mail\Message;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'costumer_id',
        'date',
        'total_price',
        'notes',
        'nif',
        'address',
        'payment_type',
        'payment_ref',
        'receipt_url',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function sendMail(Order $order, $emailPath)
    {
        $recipientEmail = $order->customer->user->email;
        $recipientName = $order->customer->user->name;

        $content = storage_path($emailPath);
        $contentFile = file_get_contents($content);
        $htmlContent = $contentFile;

        if ($order->status == 'pending') {
            Mail::send([], [], function (Message $message) use ($recipientEmail, $recipientName, $htmlContent) {
                $message->to($recipientEmail, $recipientName)
                    ->subject('Encomenda Pendente')
                    ->html($htmlContent, 'text/html');
            });
        } else if ($order->status == 'closed') {
            $attachmentName = 'order_' . $order->id . '.pdf';
            $attachmentPath = storage_path('app/pdf_receipts/' . $attachmentName);

            Mail::send([], [], function (Message $message) use ($recipientEmail, $recipientName, $attachmentPath, $attachmentName,  $htmlContent) {
                $message->to($recipientEmail, $recipientName)
                    ->subject('Encomenda Finalizada - Recibo PDF')
                    ->attach($attachmentPath, ['as' => $attachmentName])
                    ->html($htmlContent, 'text/html');
            });
        } else if ($order->status == 'canceled') {
            Mail::send([], [], function (Message $message) use ($recipientEmail, $recipientName, $htmlContent) {
                $message->to($recipientEmail, $recipientName)
                    ->subject('Encomenda Cancelada')
                    ->html($htmlContent, 'text/html');
            });
        }
        return true;
    }
}
