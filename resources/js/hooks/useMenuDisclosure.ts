import { useCallback, useEffect, useId, useState } from 'react';

interface MenuDisclosure {
    open: boolean;
    toggle: () => void;
    close: () => void;
    buttonProps: {
        'aria-expanded': boolean;
        'aria-haspopup': 'menu';
        'aria-controls': string;
    };
    menuId: string;
}

/**
 * Shared open/close state for header menus with Escape-to-dismiss.
 */
export function useMenuDisclosure(): MenuDisclosure {
    const menuId = useId();
    const [open, setOpen] = useState(false);

    const close = useCallback(() => {
        setOpen(false);
    }, []);

    const toggle = useCallback(() => {
        setOpen((value) => !value);
    }, []);

    useEffect(() => {
        if (!open) {
            return undefined;
        }

        const onKeyDown = (event: KeyboardEvent): void => {
            if (event.key === 'Escape') {
                close();
            }
        };

        document.addEventListener('keydown', onKeyDown);

        return () => {
            document.removeEventListener('keydown', onKeyDown);
        };
    }, [open, close]);

    return {
        open,
        toggle,
        close,
        menuId,
        buttonProps: {
            'aria-expanded': open,
            'aria-haspopup': 'menu',
            'aria-controls': menuId,
        },
    };
}
