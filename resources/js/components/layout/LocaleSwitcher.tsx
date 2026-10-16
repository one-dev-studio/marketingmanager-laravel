import { useMenuDisclosure } from '@/hooks/useMenuDisclosure';
import type { AppShellContext } from '@/types/appShell';
import { Link } from '@inertiajs/react';
import type { ReactElement } from 'react';

export function LocaleSwitcher({ shell }: { shell: AppShellContext }): ReactElement {
    const { open, toggle, close, buttonProps, menuId } = useMenuDisclosure();

    return (
        <div className="relative">
            <button
                type="button"
                onClick={toggle}
                className="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500"
                aria-label="Change language"
                {...buttonProps}
            >
                {shell.currentLocale.toUpperCase()}
            </button>
            {open ? (
                <div
                    id={menuId}
                    role="menu"
                    className="absolute right-0 z-50 mt-2 w-56 rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5"
                >
                    {shell.locales.map((locale) => (
                        <Link
                            key={locale.code}
                            href={locale.href}
                            className={`block px-4 py-2 text-sm ${
                                locale.isCurrent
                                    ? 'bg-gray-100 font-semibold text-gray-900'
                                    : 'text-gray-700 hover:bg-gray-50'
                            }`}
                            role="menuitem"
                            onClick={close}
                        >
                            {locale.name}
                        </Link>
                    ))}
                </div>
            ) : null}
        </div>
    );
}
