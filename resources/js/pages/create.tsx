import { create } from '@/actions/LiraUi/Team/Http/Controllers/TeamController';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { DashboardShell } from '@/layouts/dashboard-shell';
import { Form } from '@inertiajs/react';

export default function CreateTeam() {
    return (
        <div className="mx-auto flex min-h-[calc(100vh-200px)] w-sm items-center justify-center">
            <div className="outline-border/50 from-border/70 to-border/70 relative w-full overflow-hidden rounded-2xl bg-linear-to-br via-transparent via-50% p-px outline outline-offset-4">
                <Card className="bg-primary-foreground w-full rounded-2xl border-0 shadow-none">
                    <CardHeader className="gap-3">
                        <CardTitle className="text-2xl">Create team</CardTitle>
                        <CardDescription>Teams allow you to collaborate with others on projects and manage permissions effectively.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Form {...create.form()} options={{ preserveScroll: true }} disableWhileProcessing className="flex flex-col gap-y-6">
                            {({ processing, errors }: { processing: boolean; errors: any }) => (
                                <>
                                    <div className="flex w-full flex-col gap-y-2">
                                        <Label htmlFor="name">Name</Label>
                                        <Input
                                            id="name"
                                            name="name"
                                            placeholder="e.g. Marketing"
                                            autoFocus
                                            aria-invalid={!!errors.name}
                                            aria-describedby={errors.name ? 'name-error' : undefined}
                                        />
                                        {errors.name && (
                                            <span id="name-error" className="text-destructive text-sm" role="alert">
                                                {errors.name}
                                            </span>
                                        )}
                                    </div>
                                    <Button type="submit" className="w-full" disabled={processing}>
                                        {processing && <Spinner />} Create
                                    </Button>
                                </>
                            )}
                        </Form>
                    </CardContent>
                    <CardFooter className="mx-auto flex justify-between">
                        <p className="text-sm">Team owner will have full control and can invite members to collaborate.</p>
                    </CardFooter>
                </Card>
            </div>
        </div>
    );
}

CreateTeam.layout = (page: React.ReactNode) => (
    <DashboardShell
        breadcrumbs={[
            { label: 'Teams', href: '#' },
            { label: 'Create', href: '#' },
        ]}
    >
        {page}
    </DashboardShell>
);
