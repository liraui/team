<x-mail::message>
# Team Invitation

You have been invited to join the team **{{ $invitation->team->name }}**.

<x-mail::button :url="URL::signedRoute('team-invitations.accept', ['invitation' => $invitation])">
Accept Invitation
</x-mail::button>

If you did not expect to receive an invitation to this team, you may discard this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
