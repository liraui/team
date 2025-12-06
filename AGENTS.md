# AGENTS.md

## Laravel/PHP Code Style Guide

### Naming Conventions

This document outlines the recommended naming conventions for Laravel classes and methods. Adhering to these conventions ensures consistency, clarity, and optimal compatibility with development tools.

---

### 1. Controller Classes

Controller class names should be descriptive, end with the `Controller` suffix, and follow `PascalCase`. The name should reflect the primary resource or function the controller manages.

**Good Examples:**
- `UserController`
- `AuthController`
- `ProfileController`

**Bad Examples:**
- `Users` (missing Controller suffix)
- `HandleAuth` (not descriptive)
- `AuthManager` (wrong suffix)

---

### 2. Controller Methods

Method names should be action-oriented, use `camelCase`, and be as descriptive as possible. Follow RESTful conventions where applicable.

**Common Patterns:**
- **Display forms:** `showLoginForm`, `showRegistrationForm`, `showResetPasswordForm`
- **Process actions:** `login`, `register`, `logout`, `resetPassword`
- **Resource operations:** `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`

**Good Examples:**
- `login` (process authentication)
- `showLoginForm` (display login form)
- `updateProfile` (update user profile)
- `changePassword` (change user password)

**Bad Examples:**
- `submitLogin` (redundant prefix)
- `doAuthentication` (vague)
- `handleUserUpdate` (verbose)

---

### 3. Action Classes

Action classes encapsulate single business logic operations and should be named using the `VerbNounAction` pattern in `PascalCase`.

**Pattern:** `{Verb}{Noun}Action`

**Good Examples:**
- `AuthenticateUserAction`
- `SendEmailAction`
- `ProcessPaymentAction`
- `RegisterUserAction`

**Bad Examples:**
- `UserAuth` (missing Action suffix, unclear verb)
- `EmailSender` (wrong suffix)
- `PaymentProcessor` (wrong suffix)

---

### 4. Request Classes

Form request classes handle validation and should be named using descriptive patterns in `PascalCase`.

**Patterns:**
- `{Action}Request` for simple actions: `LoginRequest`, `RegisterRequest`
- `{Verb}{Noun}Request` for complex operations: `UpdateProfileRequest`, `ChangePasswordRequest`

**Good Examples:**
- `LoginRequest`
- `UpdateProfileRequest`
- `SendPasswordResetLinkRequest`
- `ShowRecoveryCodesRequest`

**Bad Examples:**
- `UserLoginFormRequest` (too verbose)
- `ProfileUpdate` (missing Request suffix)
- `ForgotPassword` (use `SendPasswordResetLink` for clarity)

---

### 5. Response Classes

Response classes handle HTTP responses and should be named using the `{Verb}{Noun}Response` pattern in `PascalCase`.

**Pattern:** `{Verb}{Noun}Response`

**Good Examples:**
- `AuthenticateUserResponse`
- `SendEmailResponse`
- `UpdateProfileResponse`
- `ChangePasswordResponse`

**Bad Examples:**
- `UserAuthResponse` (noun-first, unclear action)
- `EmailSendResponse` (awkward grammar)
- `UserProfileUpdatedResponse` (too verbose)

---

### 6. Contract Classes (Interfaces)

Contract classes define interfaces and should use clear, descriptive names in `PascalCase`. The pattern depends on the contract's purpose:

**Action Contracts (VerbNoun pattern):**
Define what an action does. Use present tense third-person singular.

- `AuthenticatesUser` (handles user authentication)
- `ChangesUserPassword` (changes user password)
- `SendsUserEmailVerification` (sends verification email)
- `RegistersUser` (registers a new user)
- `DeletesUser` (deletes user account)

**Response Contracts (NounVerb pattern):**
Define responses to events. Use past tense or state.

- `UserAuthenticated` (user was authenticated)
- `UserRegistered` (user was registered)
- `UserLoggedOut` (user logged out)
- `PasswordChanged` (password was changed)
- `EmailVerified` (email was verified)
- `SessionInvalidated` (session was invalidated)

**Service Contracts:**
Define services and utilities.

- `Otp` (OTP service)
- `OtpStore` (OTP storage)

**Good Examples:**
- `AuthenticatesUser` (action)
- `UserAuthenticated` (response)
- `ChangesUserPassword` (action)
- `PasswordChanged` (response)

**Bad Examples:**
- `UserAuth` (unclear, missing context)
- `PasswordChange` (use `ChangesUserPassword` for action or `PasswordChanged` for response)
- `Authenticate` (too generic, add context)

---

### 7. Service Providers

Service provider classes should end with `ServiceProvider` and follow `PascalCase`. The name should reflect the module or functionality.

**Good Examples:**
- `AuthServiceProvider`
- `RouteServiceProvider`
- `AppServiceProvider`

**Bad Examples:**
- `AuthProvider` (missing ServiceProvider suffix)
- `RoutesProvider` (inconsistent naming)

---

### General Principles

1. **Be Explicit:** Names should clearly convey purpose without needing additional context
2. **Be Consistent:** Follow established patterns throughout the codebase
3. **Avoid Redundancy:** Don't repeat information already clear from namespace or context
4. **Use Standard Terms:** Stick to common Laravel/PHP terminology
5. **Keep It Concise:** Descriptive doesn't mean verbose

---

## Laravel Wayfinder Routing Guide

### Overview

Laravel Wayfinder automatically generates type-safe route helpers for your frontend based on route attributes in your Laravel controllers. This eliminates manual route management and provides IntelliSense support in TypeScript.

**Key Benefits:**
- **Type-Safe Routes:** Auto-generated TypeScript definitions prevent routing errors
- **IntelliSense Support:** IDE autocomplete for all routes and parameters
- **Seamless Integration:** Works with Inertia.js `<Form>` component and router methods
- **No Manual URL Management:** Routes are automatically synced from backend to frontend
- **Automatic State Management:** Form state is managed via `name` attributes with render props pattern

**Important:** Wayfinder generates route helpers (like `submitLogin.url()`) and provides a `<Form>` component that automatically manages form state via the `name` attribute. The render props pattern `{({ processing, errors }) => (...)}` gives you access to form state without manual state management.

### Controller-Side Setup

#### 1. Route Attributes

Use Spatie Route Attributes to define routes directly in your controllers. Wayfinder reads these attributes to generate frontend route helpers.

**Pattern:**
```php
use Spatie\RouteAttributes\Attributes\{Get, Post, Put, Delete, Patch};

#[{Method}(
    uri: '{route-path}',
    name: '{route.name}',
    middleware: ['{middleware}']
)]
public function {methodName}(): Response
{
    // Controller logic
}
```

**Example:**
```php
<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;

class ProfileController extends Controller
{
    #[Get(
        uri: '/profile/settings',
        name: 'profile.settings',
        middleware: ['web', 'auth', 'verified']
    )]
    public function showProfileSettings(): InertiaResponse
    {
        return Inertia::render('profile/settings');
    }

    #[Post(
        uri: '/profile/update',
        name: 'profile.update',
        middleware: ['web', 'auth', 'verified', 'throttle:6,1']
    )]
    public function submitUpdateProfile(UpdateProfileRequest $request): Response
    {
        // Update logic
        return back()->with('status', 'profile-updated');
    }
}
```

#### 2. Naming Conventions for Route Methods

**GET Routes (Display/Show):**
- Prefix with `show` for pages/forms: `showLogin`, `showProfileSettings`
- Use descriptive nouns: `showDashboard`, `showVerifyEmail`

**POST Routes (Submit/Process):**
- Prefix with `submit` for form submissions: `submitLogin`, `submitUpdateProfile`
- Use action verbs for clarity: `submitEnableTwoFactor`, `submitResetPassword`

**DELETE Routes:**
- Prefix with `delete`: `deleteAccount`, `deleteBrowserSession`

**PUT/PATCH Routes:**
- Prefix with `update`: `updateProfile`, `updatePassword`

#### 3. Route Parameters

For routes with parameters, use standard Laravel syntax:

```php
#[Get(
    uri: '/posts/{post}',
    name: 'posts.show',
    middleware: ['web']
)]
public function showPost(Post $post): InertiaResponse
{
    return Inertia::render('posts/show', ['post' => $post]);
}

#[Put(
    uri: '/posts/{post}/update',
    name: 'posts.update',
    middleware: ['web', 'auth']
)]
public function updatePost(Post $post, UpdatePostRequest $request): Response
{
    // Update logic
}
```

---

### Frontend Integration

#### 1. Vite Configuration

Wayfinder is configured in `vite.config.js`:

```javascript
import { wayfinder } from '@laravel/vite-plugin-wayfinder';

export default defineConfig({
    plugins: [
        wayfinder({
            formVariants: true, // Enable form helper methods
        }),
    ],
    resolve: {
        alias: {
            '@auth': resolve(__dirname, 'vendor/liraui/auth/resources/js'),
        },
    },
});
```

#### 2. Generated Route Helpers

Wayfinder generates route helpers in `resources/js/actions/` organized by namespace and controller:

**File Structure:**
```
resources/js/actions/
├── App/
│   └── Http/
│       └── Controllers/
│           ├── DashboardController.ts
│           ├── HomeController.ts
│           └── index.ts
└── LiraUi/
    └── Auth/
        └── Http/
            └── Controllers/
                ├── AuthController.ts
                ├── ProfileController.ts
                └── index.ts
```

#### 3. Using Route Helpers in React Components

**Basic Usage (Navigation/Links):**

```tsx
import { showDashboard } from '@/actions/App/Http/Controllers/DashboardController';
import { showLogin, showRegister } from '@/actions/LiraUi/Auth/Http/Controllers/AuthController';
import { Link } from '@inertiajs/react';

export function Navigation() {
    return (
        <nav>
            <Link href={showDashboard.url()}>Dashboard</Link>
            <Link href={showLogin.url()}>Login</Link>
            <Link href={showRegister.url()}>Register</Link>
        </nav>
    );
}
```

**With Inertia Link (Recommended):**

Wayfinder route helpers can be passed directly to Inertia's `Link` component:

```tsx
import { Link } from '@inertiajs/react';
import { show } from '@/actions/App/Http/Controllers/PostController';

// Pass Wayfinder route object directly
<Link href={show(1)}>Show me the first post</Link>

// Or use .url() for just the URL string
<Link href={show.url(1)}>Show me the first post</Link>
```

**Form Submissions with Wayfinder:**

Wayfinder's `<Form>` component automatically manages form state via the `name` attribute on input fields. Use the render props pattern to access `processing` and `errors` states.

```tsx
import { submitLogin } from '@/actions/LiraUi/Auth/Http/Controllers/AuthController';
import { Form } from '@inertiajs/react';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';

export function LoginForm() {
    return (
        <Form {...submitLogin.form()} options={{ preserveScroll: true }}>
            {({ processing, errors }) => (
                <>
                    <Input
                        type="email"
                        name="email"
                        placeholder="Email"
                    />
                    {errors.email && <span className="text-destructive">{errors.email}</span>}
                    
                    <Input
                        type="password"
                        name="password"
                        placeholder="Password"
                    />
                    {errors.password && <span className="text-destructive">{errors.password}</span>}
                    
                    <Button type="submit" disabled={processing}>
                        {processing ? 'Logging in...' : 'Login'}
                    </Button>
                </>
            )}
        </Form>
    );
}
```

**Using Form Helpers (with formVariants: true):**

For very simple forms without validation handling, you can use the `.form()` helper directly. However, for forms with validation (which is most cases), prefer using Inertia's `<Form>` component with render props as shown above.

```tsx
import { submitUpdateProfile } from '@/actions/LiraUi/Auth/Http/Controllers/ProfileController';

// Simple form without validation handling
export function SimpleProfileForm() {
    return (
        <form {...submitUpdateProfile.form()}>
            {/* Form fields */}
            <button type="submit">Update Profile</button>
        </form>
    );
}
```

**Programmatic Navigation:**

```tsx
import { router } from '@inertiajs/react';
import { showDashboard, showPost } from '@/actions/App/Http/Controllers/DashboardController';

// Visit route - Wayfinder returns { url, method }
router.visit(showDashboard.url());

// GET request
router.get(showPost.url(1));

// Using Wayfinder object directly with router
const route = showDashboard();
router.visit(route.url);
```

**Wayfinder with Inertia Forms:**

Use Wayfinder's `<Form>` component with render props for automatic state management:

```tsx
import { Form } from '@inertiajs/react';
import { store } from '@/actions/App/Http/Controllers/PostController';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';

export function CreatePost() {
    return (
        <Form {...store.form()}>
            {({ processing, errors }) => (
                <>
                    <Input
                        name="title"
                        placeholder="Post title"
                    />
                    {errors.title && <span className="text-destructive">{errors.title}</span>}
                    
                    <Textarea
                        name="content"
                        placeholder="Post content"
                    />
                    {errors.content && <span className="text-destructive">{errors.content}</span>}
                    
                    <Button type="submit" disabled={processing}>
                        {processing ? 'Creating...' : 'Create Post'}
                    </Button>
                </>
            )}
        </Form>
    );
}
```

**Note:** Only use `useForm` when you need complex state manipulation before submission. For standard forms, the `<Form>` component with render props is preferred.

#### 4. Route Parameters in Frontend

**Single Parameter:**

```tsx
import { showPost, updatePost } from '@/actions/App/Http/Controllers/PostController';

// Generated helpers accept parameters
const postId = 123;

// Navigation
<Link href={showPost.url({ post: postId })}>View Post</Link>

// Form submission
router.put(updatePost.url({ post: postId }), formData);
```

**Multiple Parameters:**

```tsx
import { showComment } from '@/actions/App/Http/Controllers/CommentController';

// Route: /posts/{post}/comments/{comment}
<Link href={showComment.url({ post: 1, comment: 5 })}>
    View Comment
</Link>
```

#### 5. Query Parameters

Add query strings to any route:

```tsx
import { showDashboard } from '@/actions/App/Http/Controllers/DashboardController';

// With query option
<Link href={showDashboard.url({ query: { tab: 'settings', sort: 'asc' } })}>
    Dashboard Settings
</Link>
// Generates: /dashboard?tab=settings&sort=asc

// Merge with existing query params
<Link href={showDashboard.url({ mergeQuery: { filter: 'active' } })}>
    Active Items
</Link>
```

#### 6. Method Variants

Each route helper provides method-specific variants:

```tsx
import { showDashboard } from '@/actions/App/Http/Controllers/DashboardController';

// Default (uses primary method)
showDashboard.url() // GET

// Explicit method
showDashboard.get() // Returns { url: '/dashboard', method: 'get' }
showDashboard.head() // Returns { url: '/dashboard', method: 'head' }

// Form variant (when formVariants: true)
showDashboard.form() // Returns { action: '/dashboard', method: 'get' }
showDashboard.form.get()
```

#### 7. Type Safety

All route helpers are fully typed:

```tsx
import type { RouteDefinition, RouteQueryOptions } from '@/wayfinder';

// Type-safe route definitions
const route: RouteDefinition<'get'> = showDashboard();

// Query options are typed
const options: RouteQueryOptions = {
    query: { tab: 'settings' },
    mergeQuery: { filter: 'active' },
};
```

---

### Package-Specific Routes (liraui/auth)

For routes defined in vendor packages like `liraui/auth`, routes are namespaced under their package:

**Generated Path:**
```
resources/js/actions/LiraUi/Auth/Http/Controllers/
```

**Usage:**
```tsx
import { 
    showLogin, 
    submitLogin, 
    submitLogout 
} from '@/actions/LiraUi/Auth/Http/Controllers/AuthController';

import { 
    showProfileSettings,
    submitUpdateProfile 
} from '@/actions/LiraUi/Auth/Http/Controllers/ProfileController';
```

**Note:** Package routes maintain their full namespace to avoid conflicts with application routes.

---

### Best Practices

1. **Always Use Route Helpers:** Never hardcode URLs in your React components
   ```tsx
   // ❌ Bad
   <Link href="/dashboard">Dashboard</Link>
   
   // ✅ Good
   import { showDashboard } from '@/actions/App/Http/Controllers/DashboardController';
   <Link href={showDashboard.url()}>Dashboard</Link>
   ```

2. **Import Only What You Need:** Be specific with imports
   ```tsx
   // ✅ Good
   import { showLogin, submitLogin } from '@/actions/LiraUi/Auth/Http/Controllers/AuthController';
   
   // ❌ Less optimal (imports everything)
   import * as AuthController from '@/actions/LiraUi/Auth/Http/Controllers/AuthController';
   ```

3. **Use Type Definitions:** Leverage TypeScript for safety
   ```tsx
   import type { RouteDefinition } from '@/wayfinder';
   
   const handleNavigation = (route: RouteDefinition<'get'>) => {
       router.visit(route.url);
   };
   ```

4. **Consistent Naming:** Match controller method names between backend and frontend usage
   - Backend: `showProfileSettings()` → Frontend: `showProfileSettings.url()`
   - Backend: `submitLogin()` → Frontend: `submitLogin.url()`

5. **Regenerate Routes:** Run `npm run dev` or `npm run build` after adding/modifying routes to regenerate helpers

6. **Prefer `<Form>` Component for Forms:** Use Wayfinder's `<Form>` component with render props for automatic state management
   ```tsx
   import { submitUpdateProfile } from '@/actions/LiraUi/Auth/Http/Controllers/ProfileController';
   import { Form } from '@inertiajs/react';
   
   // ✅ Good - Automatic state management via name attributes
   <Form {...submitUpdateProfile.form()}>
       {({ processing, errors }) => (
           <>
               <input name="name" />
               {errors.name && <span>{errors.name}</span>}
               <button type="submit" disabled={processing}>Save</button>
           </>
       )}
   </Form>
   
   // ⚠️ Only use useForm for complex state manipulation
   const form = useForm({ name: '' });
   form.submit(submitUpdateProfile());
   ```

---

### Common Patterns

**Navigation Menu:**
```tsx
import { showDashboard } from '@/actions/App/Http/Controllers/DashboardController';
import { showProfileSettings } from '@/actions/LiraUi/Auth/Http/Controllers/ProfileController';
import { Link } from '@inertiajs/react';

const menuItems = [
    { label: 'Dashboard', href: showDashboard.url() },
    { label: 'Profile', href: showProfileSettings.url() },
];

export function NavigationMenu() {
    return (
        <nav>
            {menuItems.map(item => (
                <Link key={item.label} href={item.href}>
                    {item.label}
                </Link>
            ))}
        </nav>
    );
}
```

**Logout Action:**
```tsx
import { submitLogout } from '@/actions/LiraUi/Auth/Http/Controllers/AuthController';
import { router } from '@inertiajs/react';

export function LogoutButton() {
    const handleLogout = () => {
        router.post(submitLogout.url());
    };
    
    return <button onClick={handleLogout}>Logout</button>;
}
```

**Conditional Redirects:**
```tsx
import { showLogin } from '@/actions/LiraUi/Auth/Http/Controllers/AuthController';
import { showDashboard } from '@/actions/App/Http/Controllers/DashboardController';
import { router } from '@inertiajs/react';

const redirectUser = (isAuthenticated: boolean) => {
    const destination = isAuthenticated 
        ? showDashboard.url() 
        : showLogin.url();
    
    router.visit(destination);
};
```

---

### Troubleshooting

**Routes Not Generated:**
- Ensure route attributes are properly formatted in controllers
- Verify `spatie/laravel-route-attributes` is configured in `config/route-attributes.php`
- Run `npm run dev` to regenerate routes
- Check that controllers are in the registered route directory

**Type Errors:**
- Ensure `@laravel/vite-plugin-wayfinder` is up to date
- Regenerate routes with `npm run build`
- Check TypeScript configuration in `tsconfig.json`

**Missing Routes from Packages:**
- Verify package routes are registered in service provider
- Check alias configuration in `vite.config.js`
- Ensure package controllers use route attributes

---

## React + Inertia.js Frontend Code Style Guide

### Naming Conventions

This section outlines naming conventions for React components, pages, layouts, and hooks when using Inertia.js with Laravel.

---

### 8. Page Components

Page components are rendered by Inertia and correspond to backend routes. They should be named using `PascalCase` and located in a directory structure that mirrors the application's logical organization.

**Location:** `resources/js/pages/`

**Naming Pattern:** `{Feature}/{PageName}.tsx`

**Good Examples:**
- `auth/login.tsx` - Login page
- `auth/register.tsx` - Registration page
- `profile/settings.tsx` - Profile settings page
- `dashboard/index.tsx` - Dashboard home page
- `posts/show.tsx` - Show single post
- `posts/edit.tsx` - Edit post page

**Bad Examples:**
- `LoginPage.tsx` (redundant Page suffix)
- `auth/LoginForm.tsx` (use components/ for forms)
- `ProfileSettingsPage.tsx` (redundant suffix)
- `DashboardHome.tsx` (use index.tsx for default pages)

**File Structure Example:**
```
resources/js/pages/
├── auth/
│   ├── login.tsx
│   ├── register.tsx
│   ├── forgot-password.tsx
│   ├── reset-password.tsx
│   └── verify-email.tsx
├── profile/
│   └── settings.tsx
├── dashboard/
│   └── index.tsx
└── welcome.tsx
```

**Page Component Pattern:**
```tsx
// resources/js/pages/auth/login.tsx
import { Head } from '@inertiajs/react';
import { AuthLayout } from '@/layouts/auth-layout';
import { LoginForm } from '@/components/auth/login-form';

export default function Login() {
    return (
        <AuthLayout>
            <Head title="Login" />
            <LoginForm />
        </AuthLayout>
    );
}
```

---

### 9. Layout Components

Layout components wrap page content and provide consistent structure. They should be named with a `Layout` suffix using `PascalCase` and use kebab-case for filenames.

**Location:** `resources/js/layouts/`

**Naming Pattern:** `{name}-layout.tsx` → Export `{Name}Layout`

**Good Examples:**
- `auth-layout.tsx` → `AuthLayout`
- `app-layout.tsx` → `AppLayout`
- `guest-layout.tsx` → `GuestLayout`
- `dashboard-layout.tsx` → `DashboardLayout`

**Bad Examples:**
- `AuthenticationLayout.tsx` (too verbose)
- `Layout.tsx` (too generic)
- `auth.tsx` (missing layout context)
- `AuthLayoutComponent.tsx` (redundant suffix)

**Layout Component Pattern:**
```tsx
// resources/js/layouts/auth-layout.tsx
import { PropsWithChildren } from 'react';
import { Link } from '@inertiajs/react';

export function AuthLayout({ children }: PropsWithChildren) {
    return (
        <div className="min-h-screen bg-gray-100">
            <nav>
                <Link href="/">Home</Link>
            </nav>
            <main>{children}</main>
        </div>
    );
}
```

---

### 10. Component Files

Reusable components should use kebab-case for filenames and PascalCase for exports. Organize by feature or domain.

**Location:** `resources/js/components/`

**Naming Pattern:** `{feature}/{component-name}.tsx` → Export `{ComponentName}`

**Good Examples:**
- `auth/login-form.tsx` → `LoginForm`
- `auth/register-form.tsx` → `RegisterForm`
- `ui/button.tsx` → `Button`
- `ui/input.tsx` → `Input`
- `profile/update-profile-form.tsx` → `UpdateProfileForm`
- `navigation/nav-link.tsx` → `NavLink`

**Bad Examples:**
- `LoginFormComponent.tsx` (redundant suffix)
- `login-form-component.tsx` (redundant suffix)
- `Auth_LoginForm.tsx` (wrong separator)
- `authLoginForm.tsx` (wrong case for file)

**File Structure Example:**
```
resources/js/components/
├── auth/
│   ├── login-form.tsx
│   ├── register-form.tsx
│   ├── two-factor-challenge.tsx
│   └── password-input.tsx
├── ui/
│   ├── button.tsx
│   ├── input.tsx
│   ├── card.tsx
│   └── dialog.tsx
└── navigation/
    ├── nav-link.tsx
    └── user-menu.tsx
```

**Component Pattern:**
```tsx
// resources/js/components/auth/login-form.tsx
import { submitLogin } from '@/actions/LiraUi/Auth/Http/Controllers/AuthController';
import { Form } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

export function LoginForm() {
    return (
        <Form {...submitLogin.form()} options={{ preserveScroll: true }}>
            {({ processing, errors }) => (
                <>
                    <Input 
                        type="email" 
                        name="email"
                        placeholder="Email"
                    />
                    {errors.email && <span className="text-destructive">{errors.email}</span>}
                    
                    <Input 
                        type="password" 
                        name="password"
                        placeholder="Password"
                    />
                    {errors.password && <span className="text-destructive">{errors.password}</span>}
                    
                    <Button type="submit" disabled={processing}>
                        {processing ? 'Logging in...' : 'Login'}
                    </Button>
                </>
            )}
        </Form>
    );
}
```

---

### 11. Custom Hooks

Custom React hooks should be prefixed with `use` and follow camelCase for filenames and exports.

**Location:** `resources/js/hooks/`

**Naming Pattern:** `use-{functionality}.ts` → Export `use{Functionality}`

**Good Examples:**
- `use-auth.ts` → `useAuth`
- `use-two-factor.ts` → `useTwoFactor`
- `use-route.ts` → `useRoute`
- `use-toast.ts` → `useToast`
- `use-page-props.ts` → `usePageProps`

**Bad Examples:**
- `authHook.ts` (missing use prefix)
- `useAuthentication.ts` (too verbose)
- `use_auth.ts` (wrong separator)
- `UseAuth.ts` (wrong case for file)

**Hook Pattern:**
```tsx
// resources/js/hooks/use-auth.ts
import { usePage } from '@inertiajs/react';
import { PageProps } from '@/types';

export function useAuth() {
    const { auth } = usePage<PageProps>().props;
    
    return {
        user: auth.user,
        isAuthenticated: !!auth.user,
        can: (permission: string) => {
            return auth.user?.permissions?.includes(permission) ?? false;
        },
    };
}
```

---

### 12. Type Definitions

Type definitions should use PascalCase and be organized by domain or feature.

**Location:** `resources/js/types/`

**Naming Pattern:** `{feature}.ts` or `index.ts`

**Good Examples:**
- `index.ts` → Global types
- `auth.ts` → Authentication types
- `user.ts` → User-related types
- `inertia.ts` → Inertia-specific types

**Type Definition Patterns:**
```tsx
// resources/js/types/index.ts
export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
}

export interface PageProps {
    auth: {
        user: User;
    };
    flash: {
        success?: string;
        error?: string;
    };
}

// resources/js/types/auth.ts
export interface LoginFormData {
    email: string;
    password: string;
    remember: boolean;
}

export interface RegisterFormData {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
}
```

---

### 13. Utility Functions

Utility functions should use kebab-case for filenames and camelCase for exports.

**Location:** `resources/js/lib/` or `resources/js/utils/`

**Naming Pattern:** `{utility-name}.ts`

**Good Examples:**
- `cn.ts` → `cn` (className utility)
- `format-date.ts` → `formatDate`
- `validate-email.ts` → `validateEmail`
- `route-helpers.ts` → Various helper functions

**Bad Examples:**
- `utils.ts` (too generic, group by purpose)
- `ClassNameUtil.ts` (wrong case)
- `format_date.ts` (wrong separator for TS files)

**Utility Pattern:**
```tsx
// resources/js/lib/cn.ts
import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

// resources/js/lib/format-date.ts
export function formatDate(date: string | Date): string {
    return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    }).format(new Date(date));
}
```

---

### 14. Context Providers

Context providers should be named with a `Provider` suffix and follow PascalCase.

**Location:** `resources/js/contexts/` or within feature directories

**Naming Pattern:** `{feature}-context.tsx` → Export `{Feature}Provider`

**Good Examples:**
- `auth-context.tsx` → `AuthProvider`, `useAuthContext`
- `theme-context.tsx` → `ThemeProvider`, `useThemeContext`
- `toast-context.tsx` → `ToastProvider`, `useToastContext`

**Context Pattern:**
```tsx
// resources/js/contexts/auth-context.tsx
import { createContext, useContext, PropsWithChildren } from 'react';
import { User } from '@/types';

interface AuthContextValue {
    user: User | null;
    isAuthenticated: boolean;
}

const AuthContext = createContext<AuthContextValue | undefined>(undefined);

export function AuthProvider({ children, user }: PropsWithChildren<{ user: User | null }>) {
    const value = {
        user,
        isAuthenticated: !!user,
    };

    return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

export function useAuthContext() {
    const context = useContext(AuthContext);
    if (!context) {
        throw new Error('useAuthContext must be used within AuthProvider');
    }
    return context;
}
```

---

## Frontend Code Quality Best Practices

### General Guidelines

#### Code Comments

**Do NOT add comments in frontend code.** Code should be self-documenting through:
- Clear, descriptive variable and function names
- Proper TypeScript interfaces and types
- Well-structured component organization
- Meaningful function and component names

```tsx
// ❌ Bad - Using comments to explain code
export function LoginForm() {
    // Set initial state for email and password
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    
    // Handle form submission
    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        // Submit the form data
        post('/login');
    };
}

// ✅ Good - Self-documenting code without comments
export function LoginForm() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    
    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        post('/login');
    };
}
```

**Exception:** JSDoc comments are allowed for complex prop interfaces to provide IDE hints.

```tsx
// ✅ Acceptable - JSDoc for prop documentation
interface ProfileCardProps {
    /** The user object to display */
    user: User;
    /** Whether the card should be editable */
    editable?: boolean;
    /** Callback when save button is clicked */
    onSave?: (user: User) => void;
}
```

#### Type Organization

**All interfaces and types should be exported from the `types/` directory.**

- **Component-specific props interfaces** → `types/{feature}.ts`
- **Shared types** → `types/index.ts`
- **Domain-specific types** → `types/{domain}.ts`

```tsx
// ❌ Bad - Type defined in component file
// components/auth/login-form.tsx
interface LoginFormProps {
    redirectUrl?: string;
}

export function LoginForm({ redirectUrl }: LoginFormProps) {
    // ...
}

// ✅ Good - Type exported from types directory
// types/auth.ts
export interface LoginFormProps {
    redirectUrl?: string;
}

// components/auth/login-form.tsx
import type { LoginFormProps } from '@/types/auth';

export function LoginForm({ redirectUrl }: LoginFormProps) {
    // ...
}
```

**Benefits:**
- Centralized type definitions
- Easy to find and reuse types
- Prevents type duplication
- Better IDE autocomplete
- Easier maintenance

---

### React Component Guidelines

#### 1. Component Structure

Components should follow a consistent internal structure for maintainability:

```tsx
import { useState, useEffect, useCallback } from 'react';
import { useForm, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { formatDate } from '@/lib/utils';
import type { LoginFormProps } from '@/types/auth';

export function LoginForm({ redirectUrl = '/dashboard', showRememberMe = true }: LoginFormProps) {
    const { flash } = usePage().props;
    const [showPassword, setShowPassword] = useState(false);
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    useEffect(() => {
        if (flash?.message) {
            console.log(flash.message);
        }
    }, [flash]);

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        post('/login');
    };

    const togglePasswordVisibility = useCallback(() => {
        setShowPassword(prev => !prev);
    }, []);

    return (
        <form onSubmit={handleSubmit}>
            <Input type="email" name="email" />
            <Input type={showPassword ? 'text' : 'password'} name="password" />
            <Button type="submit">Login</Button>
        </form>
    );
}
```

**Component Structure Order:**
1. Imports (React, third-party, local components, types)
2. Component declaration with typed props
3. Hooks (context, state, refs, memoization, effects, custom)
4. Event handlers and helper functions
5. Render (return statement)

**Order of Hooks:**
1. Context hooks (`useContext`, `usePage`)
2. State hooks (`useState`)
3. Ref hooks (`useRef`)
4. Derived state/memoization (`useMemo`, `useCallback`)
5. Effects (`useEffect`, `useLayoutEffect`)
6. Custom hooks

---

#### 2. Props and TypeScript

**All prop interfaces must be defined in the `types/` directory:**

```tsx
// types/auth.ts
export interface ProfileCardProps {
    /** The user object to display */
    user: User;
    /** Whether the card should be editable */
    editable?: boolean;
    /** Callback when save button is clicked */
    onSave?: (user: User) => void;
    /** Additional CSS classes */
    className?: string;
}

// components/profile/profile-card.tsx
import type { ProfileCardProps } from '@/types/auth';

export function ProfileCard({ user, editable = false, onSave, className }: ProfileCardProps) {
    return (
        <div className={className}>
            <h2>{user.name}</h2>
            {editable && <button onClick={() => onSave?.(user)}>Save</button>}
        </div>
    );
}

// ❌ Bad - Type defined in component file
export function ProfileCard(props: any) {
    // Loses type safety
}

// ❌ Bad - Inline types in component
export function ProfileCard({ user }: { user: { name: string; email: string } }) {
    // Hard to maintain and reuse
}
```

**Use PropsWithChildren for wrapper components:**

```tsx
import { PropsWithChildren } from 'react';
import type { CardProps } from '@/types/ui';

export function Card({ title, variant = 'default', children }: PropsWithChildren<CardProps>) {
    return (
        <div className={`card card-${variant}`}>
            <h2>{title}</h2>
            {children}
        </div>
    );
}
```

---

#### 3. State Management

**Keep State Local When Possible:**

```tsx
// ✅ Good - State stays in the component that needs it
export function LoginForm() {
    const [showPassword, setShowPassword] = useState(false);
    // Use state locally
}

// ❌ Bad - Unnecessary lifting of state
export function ParentComponent() {
    const [showPassword, setShowPassword] = useState(false);
    return <LoginForm showPassword={showPassword} setShowPassword={setShowPassword} />;
}
```

**Use Inertia Form Helper for Forms:**

```tsx
import { useForm } from '@inertiajs/react';
import { submitUpdateProfile } from '@/actions/LiraUi/Auth/Http/Controllers/ProfileController';

export function UpdateProfileForm() {
    const { data, setData, put, processing, errors, reset } = useForm({
        name: '',
        email: '',
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        put(submitUpdateProfile.url(), {
            onSuccess: () => reset(),
            onError: (errors) => {
                console.error('Validation errors:', errors);
            },
        });
    };

    return (
        <form onSubmit={handleSubmit}>
            <Input
                value={data.name}
                onChange={e => setData('name', e.target.value)}
                error={errors.name}
            />
            <Button type="submit" disabled={processing}>
                Save
            </Button>
        </form>
    );
}
```

**Prefer useCallback for Event Handlers:**

```tsx
import { useCallback } from 'react';

export function SearchInput() {
    const [query, setQuery] = useState('');

    // ✅ Good - Memoized handler prevents unnecessary re-renders
    const handleSearch = useCallback((value: string) => {
        setQuery(value);
        // Perform search
    }, []);

    // ❌ Bad - Creates new function on every render
    const handleSearch = (value: string) => {
        setQuery(value);
    };

    return <Input onChange={e => handleSearch(e.target.value)} />;
}
```

---

#### 4. Conditional Rendering

**Use Clear Conditional Patterns:**

```tsx
// ✅ Good - Simple boolean condition
{isAuthenticated && <UserMenu />}

// ✅ Good - Ternary for either/or
{isAuthenticated ? <UserMenu /> : <LoginButton />}

// ✅ Good - Early return for complex conditions
export function Dashboard() {
    if (!user) {
        return <LoginPrompt />;
    }

    if (!user.verified) {
        return <VerificationPrompt />;
    }

    return <DashboardContent user={user} />;
}

// ❌ Bad - Nested ternaries
{isAuthenticated ? (
    hasPermission ? <AdminPanel /> : <UserPanel />
) : (
    isGuest ? <GuestPanel /> : <LoginPrompt />
)}

// ✅ Better - Extract to variable or helper
const PanelComponent = getPanelComponent(isAuthenticated, hasPermission, isGuest);
return <PanelComponent />;
```

---

#### 5. Error Handling

**Display Validation Errors:**

```tsx
import { useForm } from '@inertiajs/react';

export function LoginForm() {
    const { data, setData, post, errors } = useForm({
        email: '',
        password: '',
    });

    return (
        <form>
            <div>
                <Input
                    type="email"
                    value={data.email}
                    onChange={e => setData('email', e.target.value)}
                />
                {errors.email && (
                    <p className="text-sm text-destructive">{errors.email}</p>
                )}
            </div>
            
            <div>
                <Input
                    type="password"
                    value={data.password}
                    onChange={e => setData('password', e.target.value)}
                />
                {errors.password && (
                    <p className="text-sm text-destructive">{errors.password}</p>
                )}
            </div>
        </form>
    );
}
```

**Handle Flash Messages:**

```tsx
import { usePage } from '@inertiajs/react';

export function FlashMessage() {
    const { flash } = usePage<{ flash: { success?: string; error?: string } }>().props;

    if (!flash.success && !flash.error) {
        return null;
    }

    return (
        <div>
            {flash.success && (
                <div className="alert alert-success">{flash.success}</div>
            )}
            {flash.error && (
                <div className="alert alert-error">{flash.error}</div>
            )}
        </div>
    );
}
```

---

#### 6. Form Validation with Wayfinder

**Laravel Validation Integration:**

Wayfinder seamlessly integrates with Laravel's validation system. When using Inertia.js and the `useForm` hook, validation errors from your Laravel Request classes are automatically available in the frontend.

**Backend Validation (Laravel Request):**

```php
<?php

namespace LiraUi\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user()->id],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already in use.',
        ];
    }
}
```

**Frontend Form with Validation:**

```tsx
import { FormEvent } from 'react';
import { useForm } from '@inertiajs/react';
import { submitUpdateProfile } from '@/actions/LiraUi/Auth/Http/Controllers/ProfileController';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';

export function UpdateProfileForm() {
    const { data, setData, put, processing, errors, reset, isDirty, clearErrors } = useForm({
        name: '',
        email: '',
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        put(submitUpdateProfile.url(), {
            onSuccess: () => {
                reset();
                // Success handling
            },
            onError: (errors) => {
                // Errors are automatically set in the errors object
                console.error('Validation failed:', errors);
            },
        });
    };

    return (
        <form onSubmit={handleSubmit}>
            <div className="space-y-4">
                <div>
                    <Label htmlFor="name">Name</Label>
                    <Input
                        id="name"
                        type="text"
                        value={data.name}
                        onChange={e => setData('name', e.target.value)}
                        onFocus={() => clearErrors('name')}
                        aria-invalid={!!errors.name}
                        aria-describedby={errors.name ? "name-error" : undefined}
                    />
                    {errors.name && (
                        <p id="name-error" className="mt-1 text-sm text-destructive" role="alert">
                            {errors.name}
                        </p>
                    )}
                </div>

                <div>
                    <Label htmlFor="email">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        value={data.email}
                        onChange={e => setData('email', e.target.value)}
                        onFocus={() => clearErrors('email')}
                        aria-invalid={!!errors.email}
                        aria-describedby={errors.email ? "email-error" : undefined}
                    />
                    {errors.email && (
                        <p id="email-error" className="mt-1 text-sm text-destructive" role="alert">
                            {errors.email}
                        </p>
                    )}
                </div>

                <Button type="submit" disabled={processing || !isDirty}>
                    {processing ? 'Saving...' : 'Save Changes'}
                </Button>
            </div>
        </form>
    );
}
```

**useForm Hook Properties:**

The Inertia `useForm` hook provides several useful properties and methods for validation:

```tsx
const {
    data,           // Form data object
    setData,        // Update form data (single field or multiple)
    errors,         // Validation errors from backend
    hasErrors,      // Boolean - true if any errors exist
    processing,     // Boolean - true while request is in progress
    progress,       // Upload progress (for file uploads)
    wasSuccessful,  // Boolean - true if last submission succeeded
    recentlySuccessful, // Boolean - true for 2 seconds after success
    isDirty,        // Boolean - true if form data has changed
    clearErrors,    // Clear specific or all errors
    reset,          // Reset form to initial values
    setError,       // Manually set validation errors
    transform,      // Transform data before submission
} = useForm({ /* initial data */ });
```

**Accessing Individual Errors:**

```tsx
// Simple field error
{errors.email && <span>{errors.email}</span>}

// Nested field errors
{errors['user.profile.bio'] && <span>{errors['user.profile.bio']}</span>}

// Array field errors
{errors['items.0.name'] && <span>{errors['items.0.name']}</span>}

// Check if field has error
const hasEmailError = !!errors.email;
```

**Clearing Errors:**

```tsx
// Clear specific field error
<Input
    value={data.email}
    onChange={e => setData('email', e.target.value)}
    onFocus={() => clearErrors('email')} // Clear on focus
/>

// Clear multiple errors
clearErrors(['email', 'password']);

// Clear all errors
clearErrors();
```

**Manual Error Setting:**

```tsx
// Set single error
setError('email', 'This email is already registered');

// Set multiple errors
setError({
    email: 'Invalid email',
    password: 'Password too weak',
});
```

**Client-Side Validation (Complementary):**

While backend validation is primary, client-side validation improves UX:

```tsx
import { FormEvent, useState } from 'react';
import { useForm } from '@inertiajs/react';

export function LoginForm() {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
    });

    const [clientErrors, setClientErrors] = useState<Record<string, string>>({});

    const validateEmail = (email: string): boolean => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email) {
            setClientErrors(prev => ({ ...prev, email: 'Email is required' }));
            return false;
        }
        if (!emailRegex.test(email)) {
            setClientErrors(prev => ({ ...prev, email: 'Please enter a valid email' }));
            return false;
        }
        setClientErrors(prev => {
            const { email, ...rest } = prev;
            return rest;
        });
        return true;
    };

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        
        // Client-side validation
        const isEmailValid = validateEmail(data.email);
        if (!isEmailValid) return;

        // Submit to backend (backend validation is authoritative)
        post(submitLogin.url());
    };

    return (
        <form onSubmit={handleSubmit}>
            <Input
                type="email"
                value={data.email}
                onChange={e => {
                    setData('email', e.target.value);
                    validateEmail(e.target.value);
                }}
                onBlur={() => validateEmail(data.email)}
            />
            {/* Show client-side errors immediately, backend errors on submission */}
            {(clientErrors.email || errors.email) && (
                <p className="text-sm text-destructive">
                    {clientErrors.email || errors.email}
                </p>
            )}
        </form>
    );
}
```

**Validation with File Uploads:**

```tsx
import { useForm } from '@inertiajs/react';

export function AvatarUploadForm() {
    const { data, setData, post, processing, errors, progress } = useForm({
        avatar: null as File | null,
    });

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (file) {
            // Client-side file validation
            const maxSize = 2 * 1024 * 1024; // 2MB
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

            if (file.size > maxSize) {
                setError('avatar', 'File size must be less than 2MB');
                return;
            }

            if (!allowedTypes.includes(file.type)) {
                setError('avatar', 'File must be a JPEG, PNG, or GIF image');
                return;
            }

            setData('avatar', file);
            clearErrors('avatar');
        }
    };

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        post(uploadAvatar.url(), {
            forceFormData: true, // Force multipart/form-data
        });
    };

    return (
        <form onSubmit={handleSubmit}>
            <input
                type="file"
                accept="image/*"
                onChange={handleFileChange}
            />
            {errors.avatar && <p className="text-destructive">{errors.avatar}</p>}
            {progress && (
                <div className="progress-bar">
                    <div style={{ width: `${progress.percentage}%` }} />
                </div>
            )}
            <Button type="submit" disabled={processing}>
                Upload
            </Button>
        </form>
    );
}
```

**Form State Indicators:**

```tsx
export function FormWithStateIndicators() {
    const { data, setData, put, processing, wasSuccessful, recentlySuccessful, isDirty } = useForm({
        name: 'John Doe',
    });

    return (
        <form>
            <Input
                value={data.name}
                onChange={e => setData('name', e.target.value)}
            />
            
            {/* Show unsaved changes indicator */}
            {isDirty && (
                <p className="text-warning">You have unsaved changes</p>
            )}

            {/* Show success message temporarily */}
            {recentlySuccessful && (
                <p className="text-success">Saved successfully!</p>
            )}

            {/* Disable submit when processing or no changes */}
            <Button type="submit" disabled={processing || !isDirty}>
                {processing ? 'Saving...' : 'Save'}
            </Button>
        </form>
    );
}
```

**Validation Best Practices:**

1. **Backend is Authoritative:** Always validate on the backend; client-side validation is for UX only
2. **Clear Errors on Input:** Clear errors when user starts typing to improve UX
3. **Accessible Error Messages:** Use `aria-invalid` and `aria-describedby` for screen readers
4. **Disable Submit When Invalid:** Disable submit button when processing or form is invalid
5. **Show Success Feedback:** Use `recentlySuccessful` to show temporary success messages
6. **Transform Data Before Submit:** Use `transform()` to modify data before sending to backend

```tsx
const { transform, post } = useForm({ /* ... */ });

transform((data) => ({
    ...data,
    // Transform data before submission
    email: data.email.toLowerCase().trim(),
}));
```

---

#### 7. Performance Optimization

**Avoid Inline Function Definitions in Props:**

```tsx
// ❌ Bad - Creates new function every render
<Button onClick={() => handleClick(id)}>Click</Button>

// ✅ Good - Use useCallback
const handleButtonClick = useCallback(() => {
    handleClick(id);
}, [id, handleClick]);

<Button onClick={handleButtonClick}>Click</Button>

// ✅ Also Good - For simple cases where re-render is not expensive
<Button onClick={() => console.log('clicked')}>Click</Button>
```

**Memoize Expensive Computations:**

```tsx
import { useMemo } from 'react';

export function UserList({ users }: { users: User[] }) {
    // ✅ Good - Memoized computation
    const sortedUsers = useMemo(() => {
        return users.sort((a, b) => a.name.localeCompare(b.name));
    }, [users]);

    // ❌ Bad - Recomputes on every render
    const sortedUsers = users.sort((a, b) => a.name.localeCompare(b.name));

    return (
        <ul>
            {sortedUsers.map(user => (
                <li key={user.id}>{user.name}</li>
            ))}
        </ul>
    );
}
```

**Use Proper Keys in Lists:**

```tsx
// ✅ Good - Stable, unique key
{users.map(user => (
    <UserCard key={user.id} user={user} />
))}

// ❌ Bad - Index as key (causes issues with reordering)
{users.map((user, index) => (
    <UserCard key={index} user={user} />
))}

// ❌ Bad - Non-unique key
{users.map(user => (
    <UserCard key={user.name} user={user} />
))}
```

---

#### 8. Accessibility

**Use Semantic HTML:**

```tsx
// ✅ Good - Semantic elements
export function Navigation() {
    return (
        <nav>
            <ul>
                <li><a href="/home">Home</a></li>
                <li><a href="/about">About</a></li>
            </ul>
        </nav>
    );
}

// ❌ Bad - Generic divs
export function Navigation() {
    return (
        <div>
            <div>
                <div onClick={() => navigate('/home')}>Home</div>
                <div onClick={() => navigate('/about')}>About</div>
            </div>
        </div>
    );
}
```

**Add ARIA Labels:**

```tsx
// ✅ Good - Accessible button
<button
    type="button"
    onClick={handleLogout}
    aria-label="Log out of your account"
>
    <LogOutIcon />
</button>

// ✅ Good - Form with labels
<form>
    <label htmlFor="email">Email address</label>
    <input
        id="email"
        type="email"
        aria-required="true"
        aria-invalid={!!errors.email}
        aria-describedby={errors.email ? "email-error" : undefined}
    />
    {errors.email && (
        <span id="email-error" role="alert">
            {errors.email}
        </span>
    )}
</form>
```

---

#### 9. Styling Best Practices

**Use Tailwind Consistently:**

```tsx
// ✅ Good - Tailwind utility classes
<div className="flex items-center justify-between p-4 bg-white rounded-lg shadow-md">
    <h2 className="text-xl font-semibold">Title</h2>
</div>

// ❌ Bad - Mixed inline styles and classes
<div className="flex" style={{ padding: '16px', backgroundColor: 'white' }}>
    <h2 style={{ fontSize: '20px' }}>Title</h2>
</div>
```

**Use cn() Utility for Conditional Classes:**

```tsx
import { cn } from '@/lib/cn';

export function Button({ variant = 'default', className, ...props }: ButtonProps) {
    return (
        <button
            className={cn(
                'px-4 py-2 rounded font-medium',
                variant === 'primary' && 'bg-blue-500 text-white',
                variant === 'secondary' && 'bg-gray-500 text-white',
                variant === 'outline' && 'border border-gray-300',
                className
            )}
            {...props}
        />
    );
}
```

---

#### 10. Code Organization Patterns

**Extract Complex Logic to Custom Hooks:**

```tsx
// hooks/use-two-factor.ts
export function useTwoFactor() {
    const [showRecoveryCodes, setShowRecoveryCodes] = useState(false);
    const [qrCode, setQrCode] = useState<string | null>(null);

    const enable = useCallback(async () => {
        const response = await router.post(enableTwoFactor.url());
        // Handle response
    }, []);

    const disable = useCallback(async () => {
        await router.delete(disableTwoFactor.url());
    }, []);

    return {
        showRecoveryCodes,
        setShowRecoveryCodes,
        qrCode,
        enable,
        disable,
    };
}

// components/forms/two-factor-form.tsx
export function TwoFactorForm() {
    const { qrCode, enable, disable } = useTwoFactor();
    
    return (
        <div>
            {/* Use the hook's state and methods */}
        </div>
    );
}
```

**Component Composition Over Props Drilling:**

```tsx
// ✅ Good - Composition pattern
export function Card({ children }: PropsWithChildren) {
    return <div className="card">{children}</div>;
}

export function CardHeader({ children }: PropsWithChildren) {
    return <div className="card-header">{children}</div>;
}

export function CardContent({ children }: PropsWithChildren) {
    return <div className="card-content">{children}</div>;
}

// Usage
<Card>
    <CardHeader>
        <h2>Profile</h2>
    </CardHeader>
    <CardContent>
        <ProfileForm />
    </CardContent>
</Card>

// ❌ Bad - Too many props
<Card
    title="Profile"
    content={<ProfileForm />}
    footer={<SaveButton />}
    showHeader={true}
    headerClassName="custom-header"
/>
```

---

#### 11. Inertia.js Best Practices

**Use Inertia Links for Navigation:**

```tsx
import { Link } from '@inertiajs/react';
import { showDashboard } from '@/actions/App/Http/Controllers/DashboardController';

// ✅ Good - Inertia Link with type-safe route
<Link href={showDashboard.url()}>Dashboard</Link>

// ✅ Good - With preserved state
<Link href={showDashboard.url()} preserveState>Dashboard</Link>

// ❌ Bad - Regular anchor tag (causes full page reload)
<a href="/dashboard">Dashboard</a>
```

**Handle Inertia Page Props Correctly:**

```tsx
import { usePage } from '@inertiajs/react';
import type { PageProps } from '@/types';

export function UserMenu() {
    // ✅ Good - Typed page props
    const { auth } = usePage<PageProps>().props;

    return (
        <div>
            <p>Welcome, {auth.user.name}</p>
        </div>
    );
}
```

**Use Inertia Router Methods:**

```tsx
import { router } from '@inertiajs/react';
import { submitLogin } from '@/actions/LiraUi/Auth/Http/Controllers/AuthController';

// ✅ Good - With callbacks
router.post(submitLogin.url(), data, {
    onSuccess: () => {
        console.log('Login successful');
    },
    onError: (errors) => {
        console.error('Login failed:', errors);
    },
    onFinish: () => {
        console.log('Request completed');
    },
});

// ✅ Good - Preserve scroll position
router.visit(showProfile.url(), {
    preserveScroll: true,
});

// ✅ Good - Replace history instead of push
router.visit(showDashboard.url(), {
    replace: true,
});
```

---

### Security Considerations

**Sanitize User Input:**

```tsx
// ✅ Good - React automatically escapes values
<div>{user.name}</div>

// ⚠️ Caution - Only use dangerouslySetInnerHTML with sanitized content
import DOMPurify from 'dompurify';

<div dangerouslySetInnerHTML={{ __html: DOMPurify.sanitize(content) }} />

// ❌ Bad - Never trust raw HTML from user input
<div dangerouslySetInnerHTML={{ __html: userContent }} />
```

**Validate on Backend:**

```tsx
// Frontend validation is for UX, not security
export function LoginForm() {
    const { data, setData, post, errors } = useForm({
        email: '',
        password: '',
    });

    // ✅ Frontend validation for immediate feedback
    const isEmailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email);

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        // Backend will perform real validation
        post(submitLogin.url());
    };

    return (
        <form onSubmit={handleSubmit}>
            <Input value={data.email} onChange={e => setData('email', e.target.value)} />
            {!isEmailValid && <span>Please enter a valid email</span>}
            {/* Backend validation errors from Laravel */}
            {errors.email && <span>{errors.email}</span>}
        </form>
    );
}
```

---

### Frontend General Principles

1. **Consistency in Naming:** Always use kebab-case for files, PascalCase for components/types
2. **Descriptive Names:** Names should clearly indicate component purpose and domain
3. **Avoid Redundancy:** Don't repeat information already clear from directory structure
4. **Colocation:** Group related components, types, and utilities by feature
5. **Single Responsibility:** Each component should have one clear purpose
6. **Export Clarity:** Export component/function names that match their purpose
7. **Type Safety:** Use TypeScript strictly, avoid `any` type
8. **Performance:** Memoize when needed, but don't over-optimize
9. **Accessibility:** Follow WCAG guidelines, use semantic HTML
10. **Security:** Never trust client-side validation alone

---

### File Organization Best Practices

**Feature-Based Structure:**
```
resources/js/
├── pages/              # Inertia page components
│   ├── auth/
│   ├── profile/
│   └── dashboard/
├── layouts/            # Layout components
│   ├── app-layout.tsx
│   └── auth-layout.tsx
├── components/         # Reusable components
│   ├── auth/
│   ├── ui/
│   └── navigation/
├── hooks/              # Custom React hooks
│   ├── use-auth.ts
│   └── use-route.ts
├── lib/                # Utility functions
│   ├── cn.ts
│   └── utils.ts
├── types/              # TypeScript types
│   ├── index.ts
│   └── auth.ts
└── contexts/           # React contexts
    └── auth-context.tsx
```

**Import Aliases:**
Use path aliases for clean imports:
```tsx
// ✅ Good
import { Button } from '@/components/ui/button';
import { useAuth } from '@/hooks/use-auth';
import { formatDate } from '@/lib/utils';

// ❌ Bad
import { Button } from '../../../components/ui/button';
import { useAuth } from '../../hooks/use-auth';
```

---

