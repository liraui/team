import { useTeamAbilities } from '../../hooks/use-team-abilities';
import type { Team } from '../../types';
import { LeaveTeamButton } from '../buttons/leave-team-button';

interface Props {
    team: Team;
}

export function LeaveTeamForm({ team }: Props) {
    const abilities = useTeamAbilities();

    if (!abilities?.canLeaveTeam) {
        return null;
    }

    return (
        <div className="flex flex-col gap-8 md:flex-row">
            <div className="flex w-full flex-col gap-2 md:w-1/2">
                <h1 className="text-xl leading-6 font-semibold md:text-2xl">Leave</h1>
                <p className="text-muted-foreground leading-5">Leaving this team will remove your access to its resources.</p>
            </div>
            <div className="flex w-full flex-col gap-2 md:w-1/2">
                <div className="flex flex-col gap-4">
                    <h4 className="text-sm md:text-base">
                        If you leave this team, you will lose access to all of its resources and data. You can only rejoin if you are invited back by
                        a team administrator.
                    </h4>
                    <div>
                        <LeaveTeamButton team={team} />
                    </div>
                </div>
            </div>
        </div>
    );
}
