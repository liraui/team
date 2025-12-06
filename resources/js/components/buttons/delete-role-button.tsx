import { deleteMethod } from '@/actions/LiraUi/Team/Http/Controllers/TeamRoleController';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { Spinner } from '@/components/ui/spinner';
import { Form } from '@inertiajs/react';
import { useState } from 'react';
import type { Team } from '../../types';
import { Role } from '../../types';

interface Props {
    team: Team;
    role: Role;
}

export function DeleteRoleButton({ team, role }: Props) {
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
                Delete
            </DropdownMenuItem>

            <Dialog open={open} onOpenChange={setOpen}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Delete role</DialogTitle>
                        <DialogDescription>This action cannot be undone. Are you sure you want to delete the role.</DialogDescription>
                    </DialogHeader>
                    <Form
                        {...deleteMethod.form({ team: team.id, role: role.id })}
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
                                        {processing && <Spinner />} Delete role
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
