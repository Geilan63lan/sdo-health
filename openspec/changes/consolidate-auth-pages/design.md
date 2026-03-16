## Architecture

```
┌─────────────────────────────────────────────────────────────┐
│           CONSOLIDATED AUTH ARCHITECTURE                    │
└─────────────────────────────────────────────────────────────┘

    FORTIFY (Authentication Layer)        FILAMENT (Admin Interface)
    ──────────────────────────────        ─────────────────────────
    • /login                              • /admin (dashboard)
    • /register                           • Admin resources
    • /forgot-password                    • User management
    • /reset-password                     • Reports, etc.
    • /email/verify
    • /two-factor-challenge
    
    Shared Session (web guard)
    ↓
    After login → redirect to /admin
    ↓
    Filament checks canAccessPanel()
    ↓
    Access granted if user has admin role
```

## Authentication Flow

### 1. User Registration
```
User visits /register (Fortify)
    ↓
User fills registration form
    ↓
CreateNewUser action:
  - Creates user in database
  - Sets role column to 'health_coordinator'
  - Assigns Spatie 'health_coordinator' role
    ↓
User redirected to /login
```

### 2. User Login
```
User visits /login OR /admin/login
    ↓
Fortify authentication (web guard)
    ↓
User authenticated successfully
    ↓
Redirected to /admin (dashboard)
    ↓
Filament checks canAccessPanel()
    ↓
Access granted (user has Spatie role)
    ↓
User sees admin dashboard
```

### 3. Password Reset
```
User visits /forgot-password (Fortify)
    ↓
User enters email
    ↓
Fortify sends reset email
    ↓
User clicks link in email
    ↓
User visits /reset-password (Fortify)
    ↓
User resets password
    ↓
Redirected to /login
```

### 4. 2FA Challenge
```
User logs in with 2FA enabled
    ↓
Fortify redirects to /two-factor-challenge
    ↓
User enters 2FA code
    ↓
User authenticated
    ↓
Redirected to /admin (dashboard)
```

### 5. Email Verification
```
User registers
    ↓
Fortify sends verification email
    ↓
User clicks verification link
    ↓
User email verified
    ↓
User can now log in
```

## Key Components

### 1. CreateNewUser Action (`app/Actions/Fortify/CreateNewUser.php`)
```php
public function create(array $input): User
{
    // Validate input
    Validator::make($input, [
        ...$this->profileRules(),
        'password' => $this->passwordRules(),
    ])->validate();

    // Create user with default role
    $user = User::create([
        'name' => $input['name'],
        'email' => $input['email'],
        'password' => $input['password'],
        'role' => 'health_coordinator', // Database enum
    ]);

    // Assign Spatie role
    $role = Role::where('name', 'health_coordinator')->first();
    if (! $role) {
        $role = Role::create(['name' => 'health_coordinator']);
    }
    $user->assignRole($role);

    return $user;
}
```

### 2. User Model (`app/Models/User.php`)
```php
public function canAccessPanel(Panel $panel): bool
{
    if ($panel->getId() === 'admin') {
        return $this->hasRole('sdo_admin') 
            || $this->hasRole('health_coordinator') 
            || $this->hasRole('principal');
    }
    return true;
}
```

### 3. Fortify Configuration (`config/fortify.php`)
```php
'home' => '/admin', // Redirect after login
'features' => [
    Features::registration(),
    Features::resetPasswords(),
    Features::emailVerification(),
    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]),
],
```

### 4. Filament Admin Panel (`app/Providers/Filament/AdminPanelProvider.php`)
```php
->path('admin')
->login() // Enables Filament login page
->authMiddleware([
    Authenticate::class,
])
```

## Database Schema

### Users Table
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    role ENUM('sdo_admin', 'principal', 'health_coordinator') NOT NULL DEFAULT 'health_coordinator',
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255),
    -- ... other fields
);
```

### Spatie Permission Tables
- `roles` - Contains role names (sdo_admin, principal, health_coordinator, user)
- `permissions` - Contains permission names
- `model_has_roles` - Maps users to roles
- `role_has_permissions` - Maps roles to permissions

## Security Considerations

1. **Password Hashing**: Laravel's default bcrypt hashing
2. **Session Security**: Web guard with secure session management
3. **CSRF Protection**: All forms include CSRF tokens
4. **Rate Limiting**: Fortify limits login attempts (5 per minute)
5. **2FA**: Optional two-factor authentication for extra security
6. **Email Verification**: Ensures users own their email addresses

## UI/UX Considerations

1. **Consistent Design**: Fortify views use Flux UI components (same as Filament)
2. **Clear Navigation**: Login page has links to register, password reset
3. **Mobile Responsive**: Flux UI components are responsive
4. **Accessibility**: Standard form labels and inputs for screen readers
