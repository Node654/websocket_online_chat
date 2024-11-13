<?php

namespace App\Http\Requests\Message;

use App\Facade\Message as MessageFacade;
use App\Models\Message;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

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
            'chat_id' => 'required|integer|exists:chats,id',
            'body' => 'required|string',
        ];
    }

    public function store(): Message|JsonResponse
    {
        return MessageFacade::store($this->validated());
    }
}
