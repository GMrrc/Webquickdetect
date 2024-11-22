<?php
namespace App\Http\Controllers\FormRequest;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest as BaseEmailVerificationRequest;
use Symfony\Component\HttpFoundation\Response;

class EmailVerificationRequest extends BaseEmailVerificationRequest
{
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        Auth::login(User::findOr($this->route('id'), fn() => abort(Response::HTTP_UNAUTHORIZED)));
    }
}
