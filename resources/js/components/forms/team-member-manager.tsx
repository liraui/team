import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import type { User } from '@/types';
import { MailCheckIcon, MailQuestionMarkIcon, MoreVerticalIcon, UserRoundPlusIcon, UserStarIcon } from 'lucide-react';
import { useState } from 'react';
import { useTeamAbilities } from '../../hooks/use-team-abilities';
import type { Team, TeamAbilities } from '../../types';
import { TeamInvitation } from '../../types';
import { DeleteInvitationButton } from '../buttons/delete-invitation-button';
import { InviteMemberButton } from '../buttons/invite-member-button';
import { RemoveTeamMemberButton } from '../buttons/remove-team-member-button';
import { UpdateInvitationButton } from '../buttons/update-invitation-button';
import { UpdateTeamMemberRoleButton } from '../buttons/update-team-member-role-button';

interface Props {
    team: Team;
}

interface MemberDropdownProps {
    team: Team;
    user: User;
    abilities: TeamAbilities;
}

interface InvitationDropdownProps {
    team: Team;
    invitation: TeamInvitation;
}

function MemberDropdown({ team, user, abilities }: MemberDropdownProps) {
    const [open, setOpen] = useState(false);

    return (
        <DropdownMenu open={open} onOpenChange={setOpen}>
            <DropdownMenuTrigger asChild>
                <Button variant="ghost" size="icon">
                    <MoreVerticalIcon className="h-4 w-4" />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
                {abilities?.canUpdateTeamMember && (
                    <UpdateTeamMemberRoleButton team={team} user={user} roles={team.roles || []} onClose={() => setOpen(false)} />
                )}
                {abilities?.canRemoveTeamMember && <RemoveTeamMemberButton team={team} user={user} onClose={() => setOpen(false)} />}
            </DropdownMenuContent>
        </DropdownMenu>
    );
}

function InvitationDropdown({ team, invitation }: InvitationDropdownProps) {
    const [open, setOpen] = useState(false);

    return (
        <DropdownMenu open={open} onOpenChange={setOpen}>
            <DropdownMenuTrigger asChild>
                <Button variant="ghost" size="icon">
                    <MoreVerticalIcon className="h-4 w-4" />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
                <UpdateInvitationButton team={team} invitation={invitation} roles={team.roles || []} onClose={() => setOpen(false)} />
                <DeleteInvitationButton team={team} invitation={invitation} onClose={() => setOpen(false)} />
            </DropdownMenuContent>
        </DropdownMenu>
    );
}

export function TeamMemberManager({ team }: Props) {
    const abilities = useTeamAbilities();

    return (
        <div className="flex flex-col gap-8 md:flex-row">
            <div className="flex w-full flex-col gap-2 md:w-1/2">
                <h1 className="text-xl leading-6 font-semibold md:text-2xl">Members</h1>
                <p className="text-muted-foreground leading-5">List of users that belong to the team.</p>
            </div>
            <div className="flex w-full flex-col gap-8 md:w-1/2">
                <div className="flex flex-col gap-4">
                    <h4 className="text-sm md:text-base">
                        Below is a list of all team members including their name and email address. You can manage their roles and permissions as
                        needed.
                    </h4>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead className="w-16"></TableHead>
                                <TableHead className="text-muted-foreground">Name</TableHead>
                                <TableHead className="text-muted-foreground"></TableHead>
                                <TableHead className="w-16"></TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {team.owner && (
                                <TableRow key={team.owner.id}>
                                    <TableCell>
                                        <img src={team.owner.avatar} width={62} alt="Avatar" className="rounded-md" />
                                    </TableCell>
                                    <TableCell className="font-medium">
                                        <div className="flex flex-col">
                                            <span>{team.owner.name}</span>
                                            <span className="text-muted-foreground flex items-center gap-1 text-xs">
                                                <UserStarIcon size={14} /> {team.owner.email}
                                            </span>
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        <Badge>Owner</Badge>
                                    </TableCell>
                                    <TableCell></TableCell>
                                </TableRow>
                            )}
                            {team.users?.map((user: User) => (
                                <TableRow key={user.id}>
                                    <TableCell>
                                        <img src={user.avatar} width={62} alt="Avatar" className="rounded-md" />
                                    </TableCell>
                                    <TableCell className="font-medium">
                                        <div className="flex flex-col">
                                            <span>{user.name}</span>
                                            <span className="text-muted-foreground flex items-center gap-1 text-xs">
                                                <MailCheckIcon size={14} /> {user.email}
                                            </span>
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        <Badge variant="outline">{user.role.name}</Badge>
                                    </TableCell>
                                    <TableCell className="text-right">
                                        {(abilities?.canUpdateTeamMember || abilities?.canRemoveTeamMember) && (
                                            <MemberDropdown team={team} user={user} abilities={abilities} />
                                        )}
                                    </TableCell>
                                </TableRow>
                            ))}
                            {team.team_invitations?.map((invitation: TeamInvitation) => (
                                <TableRow key={invitation.id}>
                                    <TableCell>
                                        <div className="bg-primary text-primary-foreground flex h-12 w-12 items-center justify-center rounded-md text-2xl font-semibold">
                                            <UserRoundPlusIcon />
                                        </div>
                                    </TableCell>
                                    <TableCell className="font-medium">
                                        <div className="flex flex-col">
                                            <span className="italic">Pending</span>
                                            <span className="text-muted-foreground flex items-center gap-1 text-xs">
                                                <MailQuestionMarkIcon size={14} /> {invitation.email}
                                            </span>
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        <Badge variant="outline">{invitation.role.name}</Badge>
                                    </TableCell>
                                    <TableCell className="text-right">
                                        {abilities?.canAddTeamMembers && <InvitationDropdown team={team} invitation={invitation} />}
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                </div>
                {abilities?.canAddTeamMembers && (
                    <div className="flex flex-col gap-4">
                        <h4 className="text-sm md:text-base">
                            Invite additional members to join your team and collaborate on projects together. Simply click the button below to send
                            invitations.
                        </h4>
                        <div>
                            <InviteMemberButton team={team} roles={team.roles || []} />
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}
