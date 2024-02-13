import QuoteSection from "@/Components/QuoteSection";
import DefaultLayout from "@/Layouts/DefaultLayout";
import { Head, Link } from "@inertiajs/react";
import { useState } from "react";

export default function Report({ auth, users }) {
    const [usersState, setUsers] = useState(users);

    const handleFavoriteRemoved = (userId, quoteId) => {
        setUsers(
            usersState.map((user) => {
                if (user.id === userId) {
                    const updatedFavorites = user.favorite_quotes.filter(
                        (quote) => quote.id !== quoteId
                    );
                    return { ...user, favorite_quotes: updatedFavorites };
                }
                return user;
            })
        );
    };
    return (
        <DefaultLayout
            auth={auth}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Report of Favorite Quotes
                </h2>
            }
        >
            <Head title="Report of Favorite Quotes" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="grid grid-cols-2 gap-4">
                        {usersState.map((user) => (
                            <div key={user.id} className="overflow-hidden">
                                <div className="shadow-sm sm:rounded-lg bg-white p-6 text-gray-900 flex flex-col">
                                    <h2
                                        className={`${
                                            user.favorite_quotes.length > 0
                                                ? "mb-4"
                                                : ""
                                        } text-lg font-medium text-gray-900 text-center`}
                                    >
                                        <Link
                                            href={`${route(
                                                "login"
                                            )}?username=${encodeURIComponent(
                                                user.username
                                            )}`}
                                            className="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm"
                                        >
                                            {user.username}
                                        </Link>
                                    </h2>
                                    <div className="flex-1">
                                        {user.favorite_quotes.map((quote) => (
                                            <div
                                                key={quote.id}
                                                className="mb-4"
                                            >
                                                <hr className="my-2" />
                                                <QuoteSection
                                                    auth={
                                                        user.id === auth.user.id
                                                            ? auth
                                                            : null
                                                    }
                                                    quote={quote}
                                                    isFavorite={true}
                                                    onFavoriteRemoved={() =>
                                                        handleFavoriteRemoved(
                                                            user.id,
                                                            quote.id
                                                        )
                                                    }
                                                />
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </DefaultLayout>
    );
}
