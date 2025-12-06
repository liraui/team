<?php

namespace LiraUi\Team\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LiraUi\Team\Models\Team;

class UpdateCurrentTeamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $team = Team::find($this->team_id);

        return $team && $this->user()->belongsToTeam($team);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'team_id' => ['required', 'exists:teams,id'],
        ];
    }
}
