import { updateName } from '@/actions/LiraUi/Team/Http/Controllers/TeamController';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { Form } from '@inertiajs/react';
import { useTeamAbilities } from '../../hooks/use-team-abilities';
import type { Team } from '../../types';

interface Props {
    team: Team;
}

export function UpdateTeamNameForm({ team }: Props) {
    const abilities = useTeamAbilities();

    return (
        <div className="flex flex-col gap-8 md:flex-row">
            <div className="flex w-full flex-col gap-2 md:w-1/2">
                <h1 className="text-2xl leading-6 font-semibold">Team information</h1>
                <p className="text-muted-foreground leading-5">The team's name and owner information.</p>
            </div>
            <div className="flex w-full flex-col gap-4 md:w-1/2">
                <div className="flex flex-col gap-2">
                    <div className="bg-primary text-primary-foreground flex h-16 w-16 items-center justify-center rounded-md text-2xl font-semibold">
                        {team.name.substring(0, 2)}
                    </div>
                    <div>
                        <h4 className="text-sm leading-4 font-medium">Team owner</h4>
                        <p className="text-muted-foreground text-sm leading-5 font-medium">{team.owner.name}</p>
                    </div>
                </div>
                <div>
                    <Card className="border-0 bg-transparent py-0 shadow-none">
                        <CardContent className="px-0">
                            <Form {...updateName.form({ team: team.id })} options={{ preserveScroll: true }} className="flex flex-col gap-y-6">
                                {({ processing, errors }: { processing: boolean; errors: any }) => (
                                    <>
                                        <div className="flex flex-col gap-y-4">
                                            <div className="flex w-full flex-col gap-y-2">
                                                <Label htmlFor="name">Name</Label>
                                                <Input
                                                    id="name"
                                                    type="text"
                                                    name="name"
                                                    disabled={!abilities?.canUpdateTeam || processing}
                                                    defaultValue={team.name}
                                                    aria-invalid={!!errors.name}
                                                    aria-describedby={errors.name ? 'name-error' : undefined}
                                                />
                                                {errors.name && (
                                                    <span id="name-error" className="text-destructive text-sm" role="alert">
                                                        {errors.name}
                                                    </span>
                                                )}
                                            </div>
                                        </div>
                                        {abilities?.canUpdateTeam && (
                                            <div className="flex w-full items-center gap-2 self-start sm:w-auto sm:self-end">
                                                <Button type="submit" disabled={processing}>
                                                    {processing && <Spinner />} Update team
                                                </Button>
                                            </div>
                                        )}
                                    </>
                                )}
                            </Form>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    );
}
