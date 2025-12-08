import { deleteTeam } from '@/actions/LiraUi/Team/Http/Controllers/TeamController';
import { Button } from '@/components/ui/button';
import { PasswordConfirmationDialog } from '@auth/components/dialogs/password-confirmation-dialog';
import { Trash2Icon } from 'lucide-react';
import { useState } from 'react';
import type { Team } from '../../types';

interface Props {
    team: Team;
}

export function DeleteTeamButton({ team }: Props) {
    const [showPasswordDialog, setShowPasswordDialog] = useState(false);

    return (
        <>
            <Button type="button" variant="destructive" onClick={() => setShowPasswordDialog(true)} className="mr-auto">
                <Trash2Icon /> Delete team
            </Button>
            <PasswordConfirmationDialog
                show={showPasswordDialog}
                onOpenChange={(open: boolean) => {
                    setShowPasswordDialog(open);
                }}
                title="Delete team"
                description="This action cannot be undone. Please enter your password to confirm you want to permanently delete your team."
                form={deleteTeam.form({ team: team.id })}
                success={() => {
                    setShowPasswordDialog(false);
                }}
                confirmButtonText="Delete team"
                confirmButtonVariant="destructive"
            />
        </>
    );
}
