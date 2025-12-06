import { updateRole } from '@/actions/LiraUi/Team/Http/Controllers/TeamRoleController';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { Form } from '@inertiajs/react';
import { useState } from 'react';
import type { Team } from '../../types';
import { Role } from '../../types';

interface Props {
    team: Team;
    role: Role;
    availablePermissions: string[];
}

export function UpdateRoleButton({ team, role, availablePermissions }: Props) {
    const [open, setOpen] = useState(false);
    const [permissions, setPermissions] = useState<string[]>(role.permissions.map((p) => p.name));

    const togglePermission = (permission: string) => {
        if (permissions.includes(permission)) {
            setPermissions(permissions.filter((p) => p !== permission));
        } else {
            setPermissions([...permissions, permission]);
        }
    };

    const handleOpen = () => {
        setPermissions(role.permissions.map((p) => p.name));
        setOpen(true);
    };

    return (
        <>
            <DropdownMenuItem
                onSelect={(e: React.SyntheticEvent) => {
                    e.preventDefault();
                    handleOpen();
                }}
                asChild
            >
                <span>Edit role</span>
            </DropdownMenuItem>

            <Dialog open={open} onOpenChange={setOpen}>
                <DialogContent className="sm:max-w-[425px]">
                    <DialogHeader>
                        <DialogTitle>Edit role</DialogTitle>
                        <DialogDescription>Modifying role can affect team members assigned to this role.</DialogDescription>
                    </DialogHeader>
                    <Form
                        {...updateRole.form({ team: team.id, role: role.id })}
                        options={{ preserveScroll: true }}
                        onSuccess={() => setOpen(false)}
                        className="flex flex-col gap-y-6"
                    >
                        {({ processing, errors }: { processing: boolean; errors: any }) => (
                            <>
                                {permissions.map((permission) => (
                                    <input key={permission} type="hidden" name="permissions[]" value={permission} />
                                ))}
                                <div className="flex flex-col gap-y-4">
                                    <div className="flex flex-col gap-y-2">
                                        <Label htmlFor="name">Role Name</Label>
                                        <Input
                                            id="name"
                                            name="name"
                                            defaultValue={role.name}
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
                                        <Button variant="outline" type="button" disabled={processing}>
                                            Cancel
                                        </Button>
                                    </DialogClose>
                                    <Button type="submit" disabled={processing}>
                                        {processing && <Spinner />} Update role
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
