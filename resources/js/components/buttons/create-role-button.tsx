import { createRole } from '@/actions/LiraUi/Team/Http/Controllers/TeamRoleController';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
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
import { Spinner } from '@/components/ui/spinner';
import { Form } from '@inertiajs/react';
import { SlidersVerticalIcon } from 'lucide-react';
import { useState } from 'react';
import type { Team } from '../../types';

interface CreateRoleButtonProps {
    team: Team;
    availablePermissions: string[];
}

export function CreateRoleButton({ team, availablePermissions }: CreateRoleButtonProps) {
    const [open, setOpen] = useState(false);
    const [permissions, setPermissions] = useState<string[]>([]);

    const togglePermission = (permission: string) => {
        if (permissions.includes(permission)) {
            setPermissions(permissions.filter((p) => p !== permission));
        } else {
            setPermissions([...permissions, permission]);
        }
    };

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>
                <Button variant={'outline'} type="button">
                    <SlidersVerticalIcon /> Create role
                </Button>
            </DialogTrigger>
            <DialogContent className="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>Create role</DialogTitle>
                    <DialogDescription>Role will be assigned to team members and define their permissions within the team.</DialogDescription>
                </DialogHeader>
                <Form
                    {...createRole.form({ team: team.id })}
                    options={{ preserveScroll: true }}
                    onSuccess={() => {
                        setOpen(false);
                        setPermissions([]);
                    }}
                    className="flex flex-col gap-y-6"
                >
                    {({ processing, errors }: { processing: boolean; errors: any }) => (
                        <>
                            {permissions.map((permission) => (
                                <input key={permission} type="hidden" name="permissions[]" value={permission} />
                            ))}
                            <div className="flex flex-col gap-y-4">
                                <div className="flex flex-col gap-y-2">
                                    <Label htmlFor="name">Name</Label>
                                    <Input
                                        id="name"
                                        name="name"
                                        placeholder="e.g. Manager"
                                        aria-invalid={!!errors.name}
                                        aria-describedby={errors.name ? 'name-error' : undefined}
                                    />
                                    {errors.name && (
                                        <p id="name-error" className="text-destructive text-sm" role="alert">
                                            {errors.name}
                                        </p>
                                    )}
                                </div>
                                <div className="flex flex-col gap-y-2">
                                    <Label>Permissions</Label>
                                    <div className="grid max-h-[200px] grid-cols-2 gap-2 overflow-y-auto p-1">
                                        {availablePermissions.map((permission) => (
                                            <div key={permission} className="flex items-center space-x-2">
                                                <Checkbox
                                                    id={`permission-${permission}`}
                                                    checked={permissions.includes(permission)}
                                                    onCheckedChange={() => togglePermission(permission)}
                                                />
                                                <Label htmlFor={`permission-${permission}`} className="cursor-pointer text-sm font-normal">
                                                    {permission}
                                                </Label>
                                            </div>
                                        ))}
                                    </div>
                                    {errors.permissions && (
                                        <p className="text-destructive text-sm" role="alert">
                                            {errors.permissions}
                                        </p>
                                    )}
                                </div>
                            </div>
                            <DialogFooter>
                                <DialogClose asChild>
                                    <Button type="button" variant="outline">
                                        Cancel
                                    </Button>
                                </DialogClose>
                                <Button type="submit" disabled={processing}>
                                    {processing && <Spinner />} Create role
                                </Button>
                            </DialogFooter>
                        </>
                    )}
                </Form>
            </DialogContent>
        </Dialog>
    );
}
