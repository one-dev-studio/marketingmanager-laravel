import { Button } from '@/components/ui/button';
import { route } from 'ziggy-js';

export function GoogleSignInButton() {
    return (
        <Button type="button" variant="outline" className="w-full" asChild>
            <a href={route('auth.google.redirect')}>Continue with Google</a>
        </Button>
    );
}
