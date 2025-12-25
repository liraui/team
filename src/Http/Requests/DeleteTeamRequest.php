<?php

namespace LiraUi\Team\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class DeleteTeamRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            /** @var \LiraUi\Team\Models\Team $team */
            $team = $this->route('team');

            if ($team->personal_team) {
                $validator->errors()->add('team', 'You cannot delete your personal team.');
            }
        });
    }
}
