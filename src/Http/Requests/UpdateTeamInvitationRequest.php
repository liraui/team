<?php

namespace LiraUi\Team\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeamInvitationRequest extends FormRequest
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
            'role_id' => [
                'required',
                'integer',
                Rule::exists('roles', 'id')->where(function ($query) {
                    /** @var \Illuminate\Database\Eloquent\Builder $query */
                    /** @var \LiraUi\Team\Models\TeamInvitation $invitation */
                    $invitation = $this->route('invitation');

                    $query->where('team_id', $invitation->team_id);
                }),
            ],
        ];
    }
}
