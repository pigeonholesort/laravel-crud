<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomPasswordReset;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts() // 複数形
    {
        // 記事を新しい順で取得する
        return $this->hasMany('App\Post')->latest();
    }

    /**
     * パスワードリセット通知の送信
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomPasswordReset($token));
    }

    /**
     * 現在のユーザー、または引数で渡されたIDが管理者かどうかを返す
     *
     * @param  number  $id  User ID
     * @return boolean
     */
    public function isAdmin($id = null) {
        $id = ($id) ? $id : $this->id;
        return $id == config('admin_id');
    }
}
