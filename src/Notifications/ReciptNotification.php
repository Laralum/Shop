<?php

namespace Laralum\Shop\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Laralum\Notifications\Models\Settings;

class ReciptNotification extends Notification
{
    use Queueable;

    public $subject;
    public $message;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return Settings::first()->mail_enabled ? ['mail', 'database'] : ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject(__('laralum_shop::notifications.order_complete_title'))
                    ->greeting(__('laralum_shop::notifications.order_complete_title'))
                    ->line(__('laralum_shop::notifications.order_complete_desc', ['id' => $this->order->id]))
                    ->action(__('laralum_shop::notifications.view_order'), route('laralum_public::shop.order', ['order' => $this->order->id]));
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase ($notifiable)
    {
        return [
            'subject' => __('laralum_shop::notifications.order_complete_title'),
            'message' => __('laralum_shop::notifications.order_complete_desc', ['id' => $this->order->id]),
        ];
    }
}
