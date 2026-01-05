<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class Nguoidung extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'nguoidung';

    protected $fillable = [
        'name',
        'email',
        'username',
        'role',
        'phone',
        'password',
        'diachi',

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function baiViet(): HasMany
    {
        return $this->hasMany(BaiViet::class, 'nguoidung_id', 'id');
    }

    public function binhLuanBaiViet(): HasMany
    {
        return $this->hasMany(BinhLuanBaiViet::class, 'nguoidung_id', 'id');
    }

    public function donHang(): HasMany
    {
        return $this->hasMany(DonHang::class, 'nguoidung_id', 'id');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token, $this->email));
    }
}

class CustomResetPasswordNotification extends ResetPassword
{
    protected $emailFrom;

    public function __construct($token, $emailFrom)
    {
        parent::__construct($token);
        $this->emailFrom = $emailFrom;
    }
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from($this->emailFrom)
            ->subject('Khôi phục mật khẩu')
            ->line('Bạn vừa yêu cầu ' . config('app.name') . ' khôi phục mật khẩu của mình.')
            ->line('Liên kết đặt lại mật khẩu này sẽ hết hạn sau 60 phút.')
            ->line('Xin vui lòng nhấn vào nút "Khôi phục mật khẩu" bên dưới để tiến hành cấp mật khẩu mới.')
            ->action('Khôi phục mật khẩu', url(config('app.url') . route('password.reset', $this->token, false)))
            ->line('Nếu bạn không yêu cầu đặt lại mật khẩu, xin vui lòng không làm gì thêm và báo lại cho quản trị hệ thống về vấn đề này.');
    }
}
