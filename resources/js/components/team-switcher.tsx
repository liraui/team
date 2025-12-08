import { showCreateForm, showTeam, switchTeam } from '@/actions/LiraUi/Team/Http/Controllers/TeamController';
import { removeTeamMember } from '@/actions/LiraUi/Team/Http/Controllers/TeamMemberController';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { cn, isSameUrl } from '@/lib/utils';
import type { SharedData } from '@/types';
import { Link, usePage, Form } from '@inertiajs/react';
import { PlusIcon, SettingsIcon, DoorOpenIcon, Loader2 as Spinner } from 'lucide-react';
import { useState } from 'react';
import { useTeamAbilities } from '../hooks/use-team-abilities';
import { Team } from '../types';

export function TeamSwitcher() {
    const [open, setOpen] = useState(false);
    const [leaveDialogOpen, setLeaveDialogOpen] = useState(false);

    const page = usePage<SharedData>();

    const { auth } = page.props;
    const { user } = auth;

    const abilities = useTeamAbilities();

    const currentTeam = user.current_team as Team;

    const activeTeamId = currentTeam.id;
    const teams = user.all_teams as Team[];

    const activeTeam = teams.find((team: Team) => team.id === activeTeamId);
    const otherTeams = teams.filter((team: Team) => team.id !== activeTeamId);

    const getInitials = (name: string) => name.substring(0, 2).toUpperCase();

    return (
        <TooltipProvider delayDuration={0}>
            <DropdownMenu open={open} onOpenChange={setOpen}>
                <DropdownMenuTrigger asChild>
                    <button className="bg-primary text-primary-foreground hover:bg-primary/90 flex h-8 w-8 items-center justify-center rounded-lg text-sm font-semibold transition-colors">
                        {activeTeam ? getInitials(activeTeam.name) : ''}
                    </button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="start" side="bottom" sideOffset={-36} alignOffset={-6} className="w-auto min-w-0 p-1.5">
                    <div className="flex flex-col items-center gap-2">
                        <Tooltip>
                            <TooltipTrigger asChild>
                                <Link
                                    href={switchTeam.url()}
                                    method="put"
                                    data={{ team_id: activeTeam?.id }}
                                    preserveState={false}
                                    onSuccess={() => setOpen(false)}
                                    className="bg-primary text-primary-foreground ring-primary ring-offset-background flex h-8 w-8 items-center justify-center rounded-lg text-sm font-semibold ring-2 ring-offset-2"
                                >
                                    {activeTeam ? getInitials(activeTeam.name) : ''}
                                </Link>
                            </TooltipTrigger>
                            <TooltipContent side="right" sideOffset={8}>
                                {activeTeam?.name}
                            </TooltipContent>
                        </Tooltip>
                        {otherTeams.map((team) => (
                            <Tooltip key={team.id}>
                                <TooltipTrigger asChild>
                                    <Link
                                        href={switchTeam.url()}
                                        method="put"
                                        data={{ team_id: team.id }}
                                        preserveState={false}
                                        onSuccess={() => setOpen(false)}
                                        className="bg-muted text-muted-foreground hover:bg-accent hover:text-accent-foreground flex h-8 w-8 items-center justify-center rounded-lg text-sm font-semibold transition-all"
                                    >
                                        {getInitials(team.name)}
                                    </Link>
                                </TooltipTrigger>
                                <TooltipContent side="right" sideOffset={8}>
                                    {team.name}
                                </TooltipContent>
                            </Tooltip>
                        ))}
                        <div className="bg-border my-0.5 h-px w-full" />
                        <Tooltip>
                            <TooltipTrigger asChild>
                                <Link
                                    href={showCreateForm.url()}
                                    onClick={() => setOpen(false)}
                                    className="border-border text-muted-foreground hover:border-primary hover:text-primary flex h-8 w-8 items-center justify-center rounded-lg border-2 border-dashed transition-colors"
                                >
                                    <PlusIcon className="h-5 w-5" />
                                </Link>
                            </TooltipTrigger>
                            <TooltipContent side="right" sideOffset={8}>
                                Create team
                            </TooltipContent>
                        </Tooltip>
                        {abilities?.canViewTeam && (
                            <Tooltip>
                                <TooltipTrigger asChild>
                                    <Link
                                        href={showTeam.url({ team: currentTeam.id })}
                                        onClick={() => setOpen(false)}
                                        className={cn(
                                            'text-muted-foreground hover:bg-accent hover:text-accent-foreground flex h-8 w-8 items-center justify-center rounded-lg transition-colors',
                                            {
                                                'text-accent-foreground bg-accent font-medium': isSameUrl(
                                                    page.url,
                                                    showTeam.url({ team: currentTeam.id }),
                                                ),
                                            },
                                        )}
                                    >
                                        <SettingsIcon className="h-5 w-5" />
                                    </Link>
                                </TooltipTrigger>
                                <TooltipContent side="right" sideOffset={8}>
                                    Settings
                                </TooltipContent>
                            </Tooltip>
                        )}
                        {abilities?.canLeaveTeam && !currentTeam.personal_team && (
                            <Tooltip>
                                <TooltipTrigger asChild>
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        onClick={() => setLeaveDialogOpen(true)}
                                        className="text-muted-foreground hover:bg-destructive hover:text-destructive-foreground h-8 w-8 rounded-lg"
                                    >
                                        <DoorOpenIcon className="h-5 w-5" />
                                    </Button>
                                </TooltipTrigger>
                                <TooltipContent side="right" sideOffset={8}>
                                    Leave team
                                </TooltipContent>
                            </Tooltip>
                        )}
                    </div>
                </DropdownMenuContent>
            </DropdownMenu>
            <Dialog open={leaveDialogOpen} onOpenChange={setLeaveDialogOpen}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Leaving team</DialogTitle>
                        <DialogDescription>
                            Are you sure you want to leave the team. All of your resources and data will be inaccessible to you until you are invited back
                            to the team.
                        </DialogDescription>
                    </DialogHeader>
                    <Form
                        {...removeTeamMember.form({ team: currentTeam.id, user: user.id })}
                        options={{ preserveScroll: true }}
                        onSuccess={() => setLeaveDialogOpen(false)}
                        className="flex flex-col gap-y-6"
                    >
                        {({ processing }: { processing: boolean }) => (
                            <>
                                <DialogFooter>
                                    <DialogClose asChild>
                                        <Button variant="outline" type="button">
                                            Cancel
                                        </Button>
                                    </DialogClose>
                                    <Button variant="destructive" type="submit" disabled={processing}>
                                        {processing && <Spinner className="mr-2 h-4 w-4 animate-spin" />}
                                        Leave team
                                    </Button>
                                </DialogFooter>
                            </>
                        )}
                    </Form>
                </DialogContent>
            </Dialog>
        </TooltipProvider>
    );
}
