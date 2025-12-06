import { leave } from '@/actions/LiraUi/Team/Http/Controllers/TeamController';
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
import { Spinner } from '@/components/ui/spinner';
import { Form } from '@inertiajs/react';
import { ArrowLeftToLineIcon } from 'lucide-react';
import { useState } from 'react';
import type { Team } from '../../types';

interface Props {
    team: Team;
}

export function LeaveTeamButton({ team }: Props) {
    const [open, setOpen] = useState(false);

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>
                <Button variant="destructive">
                    <ArrowLeftToLineIcon /> Leave team
                </Button>
            </DialogTrigger>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Leaving team</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to leave the team. All of your resources and data will be inaccessible to you until you are invited back
                        to the team.
                    </DialogDescription>
                </DialogHeader>
                <Form
                    {...leave.form({ team: team.id })}
                    options={{ preserveScroll: true }}
                    onSuccess={() => setOpen(false)}
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
                                    {processing && <Spinner className="mr-2" />}
                                    Leave team
                                </Button>
                            </DialogFooter>
                        </>
                    )}
                </Form>
            </DialogContent>
        </Dialog>
    );
}
