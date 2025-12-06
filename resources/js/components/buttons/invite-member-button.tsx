import { invite } from '@/actions/LiraUi/Team/Http/Controllers/TeamInvitationController';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import { Form } from '@inertiajs/react';
import { SendIcon } from 'lucide-react';
import { useState } from 'react';
import { Role, Team } from '../../types';

interface Props {
    team: Team;
    roles: Role[];
}

export function InviteMemberButton({ team, roles }: Props) {
    const [open, setOpen] = useState(false);
    const [roleId, setRoleId] = useState<string>('');

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>
                <Button variant="outline">
                    <SendIcon /> Invite member
                </Button>
            </DialogTrigger>
            <DialogContent className="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>Invite member</DialogTitle>
                    <DialogDescription>Enter the email address and role of the person you would like to collaborate with.</DialogDescription>
                </DialogHeader>
                <Form
                    {...invite.form({ team: team.id })}
                    options={{ preserveScroll: true }}
                    onSuccess={() => {
                        setOpen(false);
                        setRoleId('');
                    }}
                    className="flex flex-col gap-y-6"
                >
                    {({ processing, errors }: { processing: boolean; errors: any }) => (
                        <>
                            <div className="flex flex-col gap-y-4">
                                <div className="flex flex-col gap-y-2">
                                    <Label htmlFor="email">Email</Label>
                                    <Input
                                        id="email"
                                        type="email"
                                        name="email"
                                        placeholder="email@example.com"
                                        aria-invalid={!!errors.email}
                                        aria-describedby={errors.email ? 'email-error' : undefined}
                                    />
                                    {errors.email && (
                                        <p id="email-error" className="text-destructive text-sm" role="alert">
                                            {errors.email}
                                        </p>
                                    )}
                                </div>
                                <div className="flex flex-col gap-y-2">
                                    <Label htmlFor="role_id">Role</Label>
                                    <Select value={roleId} onValueChange={setRoleId} name="role_id" required>
                                        <SelectTrigger
                                            className="w-full"
                                            aria-invalid={!!errors.role_id}
                                            aria-describedby={errors.role_id ? 'role-error' : undefined}
                                        >
                                            <SelectValue placeholder="Select a role" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {roles.map((role) => (
                                                <SelectItem key={role.id} value={role.id.toString()}>
                                                    {role.name}
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
                            <DialogFooter className="flex gap-2">
                                <DialogClose asChild>
                                    <Button variant="outline" type="button" disabled={processing}>
                                        Cancel
                                    </Button>
                                </DialogClose>
                                <Button type="submit" disabled={processing}>
                                    {processing && <Spinner />} Invite member
                                </Button>
                            </DialogFooter>
                        </>
                    )}
                </Form>
            </DialogContent>
        </Dialog>
    );
}
