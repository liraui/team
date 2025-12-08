import { cancelInvite } from '@/actions/LiraUi/Team/Http/Controllers/TeamInvitationController';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { Spinner } from '@/components/ui/spinner';
import { Form } from '@inertiajs/react';
import { useState } from 'react';
import { Team, TeamInvitation } from '../../types';

interface Props {
    team: Team;
    invitation: TeamInvitation;
}

export function DeleteInvitationButton({ team, invitation }: Props) {
    const [open, setOpen] = useState(false);

    return (
        <>
            <DropdownMenuItem
                className="text-destructive focus:text-destructive"
                onSelect={(e) => {
                    e.preventDefault();
                    setOpen(true);
                }}
            >
                Cancel invite
            </DropdownMenuItem>
            <Dialog open={open} onOpenChange={setOpen}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Cancel invite</DialogTitle>
                        <DialogDescription>
                            Are you sure you want to cancel this invitation? The invited user will no longer be able to accept it.
                        </DialogDescription>
                    </DialogHeader>
                    <Form
                        {...cancelInvite.form({ team: team.id, invitation: invitation.id })}
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
                                        {processing && <Spinner className="mr-2" />} Cancel invite
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
