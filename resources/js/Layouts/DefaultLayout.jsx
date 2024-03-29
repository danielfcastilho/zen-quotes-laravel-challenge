import { useState } from "react";
import ApplicationLogo from "@/Components/ApplicationLogo";
import Dropdown from "@/Components/Dropdown";
import NavLink from "@/Components/NavLink";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink";
import { Link } from "@inertiajs/react";
import { ApiProvider } from "@/Contexts/ApiContext";

export default function DefaultLayout({
    auth,
    header,
    children,
    forceReloadUrl = false,
}) {
    const [showingNavigationDropdown, setShowingNavigationDropdown] =
        useState(false);

    return (
        <ApiProvider>
            <div className="min-h-screen bg-gray-100">
                <nav className="bg-white border-b border-gray-100">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between h-16">
                            <div className="flex">
                                <div className="shrink-0 flex items-center">
                                    <Link href="/">
                                        <ApplicationLogo className="block h-9 w-auto fill-current text-gray-800" />
                                    </Link>
                                </div>

                                <div className="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                    <NavLink
                                        href={route("today")}
                                        active={route().current("today")}
                                    >
                                        Quote of the Day
                                    </NavLink>
                                    {auth.user ? (
                                        <>
                                            <NavLink
                                                href={route("secure-quotes")}
                                                active={route().current(
                                                    "secure-quotes"
                                                )}
                                            >
                                                Ten Secure Quotes
                                            </NavLink>
                                            <NavLink
                                                href={route("favorite-quotes")}
                                                active={route().current(
                                                    "favorite-quotes"
                                                )}
                                            >
                                                Favorite Quotes
                                            </NavLink>
                                            <NavLink
                                                href={route(
                                                    "report-favorite-quotes"
                                                )}
                                                active={route().current(
                                                    "report-favorite-quotes"
                                                )}
                                            >
                                                Report of Favorite Quotes
                                            </NavLink>
                                        </>
                                    ) : (
                                        <NavLink
                                            href={route("quotes")}
                                            active={route().current("quotes")}
                                        >
                                            Five Random Quotes
                                        </NavLink>
                                    )}
                                    <NavLink
                                        href={route("api-test")}
                                        active={route().current("api-test")}
                                    >
                                        Online API Test
                                    </NavLink>
                                </div>
                            </div>

                            <div className="hidden sm:flex sm:items-center sm:ms-6">
                                {auth.user ? (
                                    <div className="ms-3 relative">
                                        <Dropdown>
                                            <Dropdown.Trigger>
                                                <span className="inline-flex rounded-md">
                                                    <button
                                                        type="button"
                                                        className="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                                    >
                                                        {auth.user.username}

                                                        <svg
                                                            className="ms-2 -me-0.5 h-4 w-4"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20"
                                                            fill="currentColor"
                                                        >
                                                            <path
                                                                fillRule="evenodd"
                                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                clipRule="evenodd"
                                                            />
                                                        </svg>
                                                    </button>
                                                </span>
                                            </Dropdown.Trigger>

                                            <Dropdown.Content>
                                                <Dropdown.Link
                                                    href={route("profile.edit")}
                                                >
                                                    Profile
                                                </Dropdown.Link>
                                                <Dropdown.Link
                                                    href={route("login")}
                                                    method="get"
                                                    as="button"
                                                >
                                                    Switch Account
                                                </Dropdown.Link>
                                                <Dropdown.Link
                                                    href={route("logout")}
                                                    method="post"
                                                    as="button"
                                                >
                                                    Log Out
                                                </Dropdown.Link>
                                            </Dropdown.Content>
                                        </Dropdown>
                                    </div>
                                ) : (
                                    <div className="px-4">
                                        <div className="text-sm font-small text-base text-gray-800">
                                            <Link
                                                href={route("login")}
                                                className="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm"
                                            >
                                                Log in
                                            </Link>

                                            <Link
                                                href={route("register")}
                                                className="ms-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm"
                                            >
                                                Register
                                            </Link>
                                        </div>
                                    </div>
                                )}
                            </div>

                            <div className="-me-2 flex items-center sm:hidden">
                                <button
                                    onClick={() =>
                                        setShowingNavigationDropdown(
                                            (previousState) => !previousState
                                        )
                                    }
                                    className="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                                >
                                    <svg
                                        className="h-6 w-6"
                                        stroke="currentColor"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            className={
                                                !showingNavigationDropdown
                                                    ? "inline-flex"
                                                    : "hidden"
                                            }
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M4 6h16M4 12h16M4 18h16"
                                        />
                                        <path
                                            className={
                                                showingNavigationDropdown
                                                    ? "inline-flex"
                                                    : "hidden"
                                            }
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div
                        className={
                            (showingNavigationDropdown ? "block" : "hidden") +
                            " sm:hidden"
                        }
                    >
                        <div className="pt-2 pb-3 space-y-1">
                            <ResponsiveNavLink
                                href={route("today")}
                                active={route().current("today")}
                            >
                                Quote of the Day
                            </ResponsiveNavLink>
                            {auth.user ? (
                                <>
                                    <ResponsiveNavLink
                                        href={route("secure-quotes")}
                                        active={route().current(
                                            "secure-quotes"
                                        )}
                                    >
                                        Ten Secure Quotes
                                    </ResponsiveNavLink>
                                    <ResponsiveNavLink
                                        href={route("favorite-quotes")}
                                        active={route().current(
                                            "favorite-quotes"
                                        )}
                                    >
                                        Favorite Quotes
                                    </ResponsiveNavLink>
                                    <ResponsiveNavLink
                                        href={route("report-favorite-quotes")}
                                        active={route().current(
                                            "report-favorite-quotes"
                                        )}
                                    >
                                        Report of Favorite Quotes
                                    </ResponsiveNavLink>
                                </>
                            ) : (
                                <ResponsiveNavLink
                                    href={route("quotes")}
                                    active={route().current("quotes")}
                                >
                                    Five Random Quotes
                                </ResponsiveNavLink>
                            )}
                            <ResponsiveNavLink
                                href={route("api-test")}
                                active={route().current("api-test")}
                            >
                                Online API Test
                            </ResponsiveNavLink>
                        </div>

                        {auth.user ? (
                            <div className="pt-4 pb-1 border-t border-gray-200">
                                <div className="px-4">
                                    <div className="font-medium text-base text-gray-800">
                                        {auth.user.username}
                                    </div>
                                </div>
                                <div className="mt-3 space-y-1">
                                    <ResponsiveNavLink
                                        href={route("profile.edit")}
                                    >
                                        Profile
                                    </ResponsiveNavLink>
                                    <ResponsiveNavLink
                                        method="get"
                                        href={route("login")}
                                        as="button"
                                    >
                                        Switch Account
                                    </ResponsiveNavLink>
                                    <ResponsiveNavLink
                                        method="post"
                                        href={route("logout")}
                                        as="button"
                                    >
                                        Log Out
                                    </ResponsiveNavLink>
                                </div>
                            </div>
                        ) : (
                            <div className="pb-1 border-t border-gray-200">
                                <div className="mt-1 space-y-1">
                                    <ResponsiveNavLink href={route("login")}>
                                        Log in
                                    </ResponsiveNavLink>
                                    <ResponsiveNavLink
                                        method="get"
                                        href={route("register")}
                                        as="button"
                                    >
                                        Register
                                    </ResponsiveNavLink>
                                </div>
                            </div>
                        )}
                    </div>
                </nav>

                {header && (
                    <header className="bg-white shadow">
                        <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                            <div>{header}</div>
                            {forceReloadUrl && (
                                <Link
                                    href={forceReloadUrl}
                                    className="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    Force Reload
                                </Link>
                            )}
                        </div>
                    </header>
                )}

                <main>{children}</main>
            </div>
            <footer className="fixed inset-x-0 bottom-0 bg-white border-t border-gray-100">
                <div className="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
                    Inspirational quotes provided by{" "}
                    <a href="https://zenquotes.io/" target="_blank">
                        ZenQuotes API
                    </a>
                </div>
            </footer>
        </ApiProvider>
    );
}
