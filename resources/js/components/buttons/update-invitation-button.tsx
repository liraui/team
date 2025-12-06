import { updateRole } from '@/actions/LiraUi/Team/Http/Controllers/TeamInvitationController';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import { Form } from '@inertiajs/react';
import { useState } from 'react';
import { Role, Team, TeamInvitation } from '../../types';

interface Props {
    team: Team;
    invitation: TeamInvitation;
    roles: Role[];
}

export function UpdateInvitationButton({ team, invitation, roles }: Props) {
    const [open, setOpen] = useState(false);
    const [roleId, setRoleId] = useState<string>(invitation.role_id?.toString() || '');

    const handleOpen = () => {
        setRoleId(invitation.role_id?.toString() || '');
        setOpen(true);
    };

    return (
        <>
            <DropdownMenuItem
                onSelect={(e) => {
                    e.preventDefault();
                    handleOpen();
                }}
            >
                Update invite
            </DropdownMenuItem>
            <Dialog open={open} onOpenChange={setOpen}>
                <DialogContent className="sm:max-w-[425px]">
                    <DialogHeader>
                        <DialogTitle>Update invite</DialogTitle>
                        <DialogDescription>Make modification to the invitation before the user accepts it.</DialogDescription>
                    </DialogHeader>
                    <Form
                        {...updateRole.form({ team: team.id, invitation: invitation.id })}
                        options={{ preserveScroll: true }}
                        onSuccess={() => setOpen(false)}
                        className="flex flex-col gap-y-6"
                    >
                        {({ processing, errors }: { processing: boolean; errors: any }) => (
                            <>
                                <div className="flex flex-col gap-y-4">
                                    <div className="flex flex-col gap-y-2">
                                        <Label htmlFor="role_id">Role</Label>
                                        <Select value={roleId} onValueChange={setRoleId} name="role_id">
                                            <SelectTrigger
                                                className="w-full"
                                                aria-invalid={!!errors.role_id}
                                                aria-describedby={errors.role_id ? 'role-error' : undefined}
                                            >
                                                <SelectValue placeholder="Select a role" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                {roles.map((r) => (
                                                    <SelectItem key={r.id} value={r.id.toString()}>
                                                        {r.name}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                        <input type="hidden" name="role_id" value={roleId} />
                                        {errors.role_id && (
                                            <p className="text-destructive text-sm" role="alert">
                                                {errors.role_id}
                                            </p>
                                        )}
                                    </div>
                                </div>
                                <DialogFooter>
                                    <DialogClose asChild>
                                        <Button variant="outline" type="button" disabled={processing}>
                                            Cancel
                                        </Button>
                                    </DialogClose>
                                    <Button type="submit" disabled={processing}>
                                        {processing && <Spinner className="mr-2" />} Update invitation
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
