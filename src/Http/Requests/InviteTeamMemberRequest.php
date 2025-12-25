<?php

namespace LiraUi\Team\Http\Requests;

use Illuminate\Database\Eloquent\Builder;
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
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('team_invitations')->where(function ($query) {
                    /** @var \Illuminate\Database\Eloquent\Builder $query */
                    /** @var \App\Models\User $user */
                    $user = $this->user();

                    $query->where('team_id', $user->current_team_id);
                }),
                function ($attribute, $value, callable $fail) {
                    /** @var \App\Models\User $user */
                    $user = $this->user();

                    if ($user->currentTeam &&
                        $user->currentTeam->users->contains('email', $value)
                    ) {
                        $fail('This user is already a member of the team.');
                    }
                },
            ],
            'role_id' => [
                'required',
                'integer',
                Rule::exists('roles', 'id')->where(function ($query) {
                    /** @var \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query */
                    /** @var \App\Models\User $user */
                    $user = $this->user();

                    $query->where('team_id', $user->current_team_id);
                }),
            ],
        ];
    }
}
