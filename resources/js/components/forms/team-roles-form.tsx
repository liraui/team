import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Empty, EmptyDescription, EmptyHeader, EmptyMedia } from '@/components/ui/empty';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { MoreVertical, SlidersVerticalIcon } from 'lucide-react';
import { useTeamAbilities } from '../../hooks/use-team-abilities';
import type { Team } from '../../types';
import { CreateRoleButton } from '../buttons/create-role-button';
import { DeleteRoleButton } from '../buttons/delete-role-button';
import { UpdateRoleButton } from '../buttons/update-role-button';

interface Props {
    team: Team;
    availablePermissions: string[];
}

export function TeamRolesForm({ team, availablePermissions }: Props) {
    const abilities = useTeamAbilities();

    return (
        <div className="flex flex-col gap-8 md:flex-row">
            <div className="flex w-full flex-col gap-2 md:w-1/2">
                <h1 className="text-xl leading-6 font-semibold md:text-2xl">Roles</h1>
                <p className="text-muted-foreground leading-5">Update or remove permissions from the assigned role.</p>
            </div>
            <div className="flex w-full flex-col gap-8 md:w-1/2">
                <div className="flex flex-col gap-4">
                    <h4 className="text-sm md:text-base">
                        If necessary, you may update or revoke specific permissions from the assigned role. This ensures that users have only the
                        access they need. Review the current permissions below and make adjustments as appropriate.
                    </h4>
                    {team.roles?.length === 0 && (
                        <Empty className="bg-background/50 relative mx-auto flex h-full w-full items-center justify-center">
                            <EmptyHeader>
                                <EmptyMedia variant="icon">
                                    <SlidersVerticalIcon />
                                </EmptyMedia>
                                <EmptyDescription>Get started by creating roles to manage team members effectively.</EmptyDescription>
                            </EmptyHeader>
                        </Empty>
                    )}
                    {team.roles?.length > 0 && (
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead className="text-muted-foreground">Name</TableHead>
                                    <TableHead></TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {team.roles?.map((role) => (
                                    <TableRow key={role.id}>
                                        <TableCell className="font-medium">
                                            <span>{role.name}</span>
                                            <span className="text-muted-foreground flex items-center gap-1">
                                                <span>{role.permissions.length} Permissions</span>
                                            </span>
                                        </TableCell>
                                        <TableCell className="text-right">
                                            {abilities?.canUpdateTeam && (
                                                <DropdownMenu>
                                                    <DropdownMenuTrigger asChild>
                                                        <Button variant="ghost" size="icon">
                                                            <MoreVertical className="h-4 w-4" />
                                                            <span className="sr-only">Open menu</span>
                                                        </Button>
                                                    </DropdownMenuTrigger>
                                                    <DropdownMenuContent align="end">
                                                        <UpdateRoleButton team={team} role={role} availablePermissions={availablePermissions} />
                                                        <DeleteRoleButton team={team} role={role} />
                                                    </DropdownMenuContent>
                                                </DropdownMenu>
                                            )}
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>
                    )}
                </div>
                {abilities?.canUpdateTeam && (
                    <div className="flex flex-col gap-4">
                        <h4 className="text-sm md:text-base">
                            You can add new roles to your team to better manage permissions and access levels for different members. Default roles
                            include Admin, Editor, and Viewer, but you can customize roles to fit your team's needs.
                        </h4>
                        <div>
                            <CreateRoleButton team={team} availablePermissions={availablePermissions} />
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}
