import type { ReactElement, ReactNode } from 'react';
import { useEffect } from 'react';

interface ShellMobileSidebarProps {
    open: boolean;
    onClose: () => void;
    children: ReactNode;
    /** Accessible name for the navigation region */
    navLabel: string;
}

/**
 * Slide-over sidebar for viewports below `md`, shared by customer and agency shells.
 */
export function ShellMobileSidebar({
    open,
    onClose,
    children,
    navLabel,
}: ShellMobileSidebarProps): ReactElement | null {
    useEffect(() => {
        if (!open) {
            return undefined;
        }

        const onKeyDown = (event: KeyboardEvent): void => {
            if (event.key === 'Escape') {
                onClose();
            }
        };

        document.addEventListener('keydown', onKeyDown);

        return () => {
            document.removeEventListener('keydown', onKeyDown);
        };
    }, [open, onClose]);

    if (!open) {
        return null;
    }

    return (
        <>
            <button
                type="button"
                className="fixed inset-0 z-40 bg-black/50 md:hidden"
                onClick={onClose}
                aria-label="Close navigation menu"
            />
            <aside
                className="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-gray-200 bg-white shadow-xl md:hidden"
                aria-label={navLabel}
            >
                {children}
            </aside>
        </>
    );
}
