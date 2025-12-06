import { remove } from '@/actions/LiraUi/Team/Http/Controllers/TeamMemberController';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { Spinner } from '@/components/ui/spinner';
import { Form, usePage } from '@inertiajs/react';
import { useState } from 'react';
import { Team, User } from '../../types';

interface Props {
    team: Team;
    user: User;
}

export function RemoveTeamMemberButton({ team, user }: Props) {
    const [open, setOpen] = useState(false);
    const { auth } = usePage<any>().props;

    const isCurrentUser = auth.user.id === user.id;

    return (
        <>
            <DropdownMenuItem
                className="text-destructive focus:text-destructive"
                onSelect={(e) => {
                    e.preventDefault();
                    setOpen(true);
                }}
            >
                {isCurrentUser ? 'Leave team' : 'Remove member'}
            </DropdownMenuItem>

            <Dialog open={open} onOpenChange={setOpen}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>{isCurrentUser ? 'Leaving team' : 'Removing team member'}</DialogTitle>
                        <DialogDescription>
                            {isCurrentUser
                                ? 'Are you sure you want to leave the team. All of your resources and data will be inaccessible to you until you are invited back to the team.'
                                : 'Are you sure you want to remove this team member? They will no longer have access to the team resources.'}
                        </DialogDescription>
                    </DialogHeader>
                    <Form
                        {...remove.form({ team: team.id, user: user.id })}
                        options={{ preserveScroll: true }}
                        onSuccess={() => setOpen(false)}
                        className="flex flex-col gap-y-6"
                    >
                        {({ processing }: { processing: boolean }) => (
                            <>
                                <DialogFooter>
                                    <DialogClose asChild>
                                        <Button variant="outline" type="button" disabled={processing}>
                                            Cancel
                                        </Button>
                                    </DialogClose>
                                    <Button variant="destructive" type="submit" disabled={processing}>
                                        {processing && <Spinner className="mr-2" />} {isCurrentUser ? 'Leave team' : 'Remove member'}
                                    </Button>
                                </DialogFooter>
                            </>
                        )}
                    </Form>
                </DialogContent>
            </Dialog>
        </>
    );
}
