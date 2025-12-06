import { useTeamAbilities } from '../../hooks/use-team-abilities';
import type { Team } from '../../types';
import { DeleteTeamButton } from '../buttons/delete-team-button';

interface Props {
    team: Team;
}

export function DeleteTeamForm({ team }: Props) {
    const abilities = useTeamAbilities();

    if (!abilities?.canDeleteTeam) {
        return null;
    }

    return (
        <div className="flex flex-col gap-8 md:flex-row">
            <div className="flex w-full flex-col gap-2 md:w-1/2">
                <h1 className="text-xl leading-6 font-semibold md:text-2xl">Delete team</h1>
                <p className="text-muted-foreground leading-5">Permanently delete this team.</p>
            </div>
            <div className="flex w-full flex-col gap-2 md:w-1/2">
                <div className="flex flex-col gap-4">
                    <h4 className="text-sm md:text-base">
                        Once your team is deleted, all of its resources and data will be permanently deleted. Before deleting your team, please
                        download any data or information that you wish to retain.
                    </h4>
                    <DeleteTeamButton team={team} />
                </div>
            </div>
        </div>
    );
}
