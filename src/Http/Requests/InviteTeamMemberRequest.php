<?php

namespace LiraUi\Team\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InviteTeamMemberRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('team_invitations')->where(function ($query) {
                    $query->where('team_id', $this->user()->current_team_id);
                }),
                function ($attribute, $value, $fail) {
                    if ($this->user()->currentTeam->users->contains('email', $value)) {
                        $fail('This user is already a member of the team.');
                    }
                },
            ],
            'role_id' => [
                'required',
                'integer',
                Rule::exists('roles', 'id')->where(function ($query) {
                    $query->where('team_id', $this->user()->current_team_id);
                }),
            ],
        ];
    }
}
