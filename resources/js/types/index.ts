import type { User } from '@/types';

export interface Team {
    id: number;
    name: string;
    personal_team: boolean;
    avatar: string;
    created_at: string;
    updated_at: string;
    owner: User;
    users?: User[];
    roles: Role[];
    team_invitations?: TeamInvitation[];
}
export interface Permission {
    id: number;
    name: string;
    guard_name: string;
    created_at: string;
    updated_at: string;
}

export interface TeamAbilities {
    canViewTeam: boolean;
    canAddTeamMembers: boolean;
    canDeleteTeam: boolean;
    canUpdateTeam: boolean;
    canUpdateTeamMember: boolean;
    canRemoveTeamMember: boolean;
    canLeaveTeam: boolean;
}

export interface Role {
    id: number;
    name: string;
    guard_name: string;
    team_id: number;
    created_at: string;
    updated_at: string;
    permissions: Permission[];
}

export interface TeamInvitation {
    id: number;
    team_id: number;
    email: string;
    role_id: number;
    role: {
        id: number;
        name: string;
    };
    created_at: string;
    updated_at: string;
}
