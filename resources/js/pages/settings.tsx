import { TeamNavigation } from '@/components/navigation/team-navigation';
import { DashboardShell } from '@/layouts/dashboard-shell';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import { DeleteTeamForm } from '../components/forms/delete-team-form';
import { TeamMemberManager } from '../components/forms/team-member-manager';
import { TeamRolesForm } from '../components/forms/team-roles-form';
import { UpdateTeamNameForm } from '../components/forms/update-team-name-form';
import type { Team } from '../types';

interface Props {
    team: Team;
    availablePermissions: string[];
}

export default function TeamSettings({ team, availablePermissions }: Props) {
    const variant = usePage<SharedData>().props.dashboard.shell || 'default';

    return (
        <div>
            {variant === 'default' && <TeamNavigation />}
            <div className="flex w-full flex-col gap-8 px-8 py-8">
                <UpdateTeamNameForm team={team} />
                <TeamRolesForm team={team} availablePermissions={availablePermissions} />
                <TeamMemberManager team={team} />
                {/* <LeaveTeamForm team={team} /> */}
                <DeleteTeamForm team={team} />
            </div>
        </div>
    );
}

TeamSettings.layout = (page: React.ReactNode) => (
    <DashboardShell
        breadcrumbs={[
            { label: page.props.team.name, href: '#' },
            { label: 'Settings', href: '#' },
        ]}
    >
        {page}
    </DashboardShell>
);
