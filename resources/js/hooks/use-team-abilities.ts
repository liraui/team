import type { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import type { TeamAbilities } from '../types';

export function useTeamAbilities(): TeamAbilities | undefined {
    const { auth } = usePage<SharedData>().props;

    return auth.user.team_abilities;
}
