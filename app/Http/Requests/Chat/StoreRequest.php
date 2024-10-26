<?php

namespace App\Http\Requests\Chat;

use App\Facade\Chat as ChatFacade;
use App\Models\Chat;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string',
            'users' => 'required|array',
            'users.*' => 'required|exists:users,id',
        ];
    }

    public function store(): Chat
    {
        return ChatFacade::store($this->validated());
    }
}
